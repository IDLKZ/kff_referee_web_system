<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\Role;
use App\Models\User;
use App\Services\File\FileService;
use App\Services\File\DTO\FileValidationOptions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Users')]
class UserManagement extends Component
{
    use WithFileUploads;
    use WithPagination;

    // Search & Filter
    public string $search = '';
    public ?int $filter_role_id = null;
    public ?bool $filter_is_active = null;
    public ?bool $filter_is_verified = null;

    // Sorting
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    // Modal state
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;
    public bool $isEditing = false;

    // Form fields
    public ?int $editingUserId = null;
    public ?int $role_id = null;
    public $image;
    public ?int $image_id = null;
    public string $last_name = '';
    public string $first_name = '';
    public ?string $patronymic = '';
    public string $phone = '';
    public string $email = '';
    public string $username = '';
    public int $sex = 0;
    public ?string $iin = '';
    public ?string $birth_date = '';
    public ?string $password = '';
    public bool $is_active = true;
    public bool $is_verified = false;

    // Image URLs for preview
    public ?string $temporaryImageUrl = null;
    public ?string $existingImageUrl = null;

    // Delete target
    public ?int $deletingUserId = null;
    public string $deletingUserInfo = '';

    public function rules(): array
    {
        $rules = [
            'role_id' => ['nullable', 'exists:roles,id'],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'patronymic' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email' . ($this->editingUserId ? ',' . $this->editingUserId : '')],
            'username' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9_@]+$/', 'unique:users,username' . ($this->editingUserId ? ',' . $this->editingUserId : '')],
            'sex' => ['required', 'in:0,1,2'],
            'iin' => ['nullable', 'string', 'regex:/^[0-9]{12}$/'],
            'birth_date' => ['nullable', 'date'],
            'is_active' => ['boolean'],
            'is_verified' => ['boolean'],
        ];

        // Password required only for new users
        if (!$this->isEditing) {
            $rules['password'] = ['required', 'string', 'min:6'];
            $rules['image'] = ['nullable', 'image', 'max:5120']; // 5MB
        } else {
            $rules['password'] = ['nullable', 'string', 'min:6'];
            $rules['image'] = ['nullable', 'image', 'max:5120'];
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check birth date is at least 18 years ago
            if ($this->birth_date) {
                $birthDate = \Carbon\Carbon::parse($this->birth_date);
                $minBirthDate = \Carbon\Carbon::now()->subYears(18);

                if ($birthDate->gt($minBirthDate)) {
                    $validator->errors()->add('birth_date', __('validation.birth_date_minimum_18'));
                }
            }
        });
    }

    public function getValidationMessages(): array
    {
        return [
            'phone.regex' => __('validation.invalid_phone_format'),
            'username.regex' => __('validation.invalid_username_format'),
            'iin.regex' => __('validation.invalid_iin_format'),
        ];
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::USERS_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::USERS_UPDATE);
        $user = User::with('file')->findOrFail($id);

        $this->editingUserId = $user->id;
        $this->role_id = $user->role_id;
        $this->image_id = $user->image_id;
        $this->last_name = $user->last_name;
        $this->first_name = $user->first_name;
        $this->patronymic = $user->patronymic ?? '';
        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->sex = $user->sex;
        $this->iin = $user->iin ?? '';
        $this->birth_date = $user->birth_date ? $user->birth_date->format('Y-m-d') : '';
        $this->is_active = $user->is_active;
        $this->is_verified = $user->is_verified;

        // Resolve existing image URL once
        $this->existingImageUrl = null;
        if ($user->file && \Illuminate\Support\Facades\Storage::disk('uploads')->exists($user->file->file_path)) {
            $this->existingImageUrl = \Illuminate\Support\Facades\Storage::disk('uploads')->url($user->file->file_path);
        }

        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::USERS_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::USERS_CREATE);
        }

        $this->validate();

        // Handle image upload
        if ($this->image instanceof UploadedFile) {
            $fileService = app(FileService::class);
            $fileService->setDisk('uploads');
            $file = $fileService->save(
                $this->image,
                'avatars',
                FileValidationOptions::images(maxSizeMB: 5)
            );
            $this->image_id = $file->id;
        }

        $data = [
            'role_id' => $this->role_id ?: null,
            'image_id' => $this->image_id,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'patronymic' => $this->patronymic ?: null,
            'phone' => $this->phone,
            'email' => $this->email,
            'username' => $this->username,
            'sex' => $this->sex,
            'iin' => $this->iin ?: null,
            'birth_date' => $this->birth_date ?: null,
            'is_active' => $this->is_active,
            'is_verified' => $this->is_verified,
        ];

        // Hash password if provided
        if ($this->password) {
            $data['password_hash'] = Hash::make($this->password);
        }

        if ($this->isEditing) {
            User::findOrFail($this->editingUserId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            User::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::USERS_DELETE);
        $user = User::findOrFail($id);
        $this->deletingUserId = $user->id;
        $this->deletingUserInfo = "{$user->last_name} {$user->first_name} ({$user->username})";
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::USERS_DELETE);
        User::findOrFail($this->deletingUserId)->delete();

        toastr()->success(__('crud.deleted_success'));
        $this->showDeleteModal = false;
        $this->deletingUserId = null;
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleSearchModal(): void
    {
        $this->showSearchModal = !$this->showSearchModal;
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->filter_role_id = null;
        $this->filter_is_active = null;
        $this->filter_is_verified = null;
    }

    public function gotoPage($page): void
    {
        $this->setPage($page);
    }

    public function removeImage(): void
    {
        $this->image = null;
        $this->image_id = null;
        $this->temporaryImageUrl = null;
        $this->existingImageUrl = null;
    }

    public function updatedImage(): void
    {
        if ($this->image instanceof UploadedFile) {
            $this->temporaryImageUrl = $this->image->temporaryUrl();
        }
    }

    public function getRoleOptions(): array
    {
        return Role::orderBy('title_ru')->pluck('title_ru', 'id')->toArray();
    }

    public function getSexOptions(): array
    {
        return [
            0 => __('crud.sex_not_specified'),
            1 => __('crud.sex_male'),
            2 => __('crud.sex_female'),
        ];
    }

    private function resetForm(): void
    {
        $this->editingUserId = null;
        $this->role_id = null;
        $this->image = null;
        $this->image_id = null;
        $this->temporaryImageUrl = null;
        $this->existingImageUrl = null;
        $this->last_name = '';
        $this->first_name = '';
        $this->patronymic = '';
        $this->phone = '';
        $this->email = '';
        $this->username = '';
        $this->sex = 0;
        $this->iin = '';
        $this->birth_date = '';
        $this->password = '';
        $this->is_active = true;
        $this->is_verified = false;
        $this->resetValidation();
    }

    private function checkPermission(string $permission): void
    {
        if (!auth()->user()->hasPermission($permission)) {
            abort(403);
        }
    }

    public function render()
    {
        $query = User::with(['role', 'file'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('last_name', 'like', "%{$this->search}%")
                      ->orWhere('first_name', 'like', "%{$this->search}%")
                      ->orWhere('patronymic', 'like', "%{$this->search}%")
                      ->orWhere('phone', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%")
                      ->orWhere('username', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filter_role_id, function ($query) {
                $query->where('role_id', $this->filter_role_id);
            })
            ->when($this->filter_is_active !== null, function ($query) {
                $query->where('is_active', $this->filter_is_active);
            })
            ->when($this->filter_is_verified !== null, function ($query) {
                $query->where('is_verified', $this->filter_is_verified);
            });

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $users = $query->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users,
        ]);
    }
}
