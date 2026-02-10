<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\City;
use App\Models\JudgeCity;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Judge Cities')]
class JudgeCityManagement extends Component
{
    use WithPagination;

    public string $search = '';

    // Sorting
    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    // Modal state
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;
    public bool $isEditing = false;

    // Form fields
    public ?int $editingId = null;
    public ?int $user_id = null;
    public ?int $city_id = null;

    // Searchable select state
    public string $userSearch = '';
    public string $citySearch = '';
    public string $selectedUserName = '';
    public string $selectedCityName = '';

    // Delete target
    public ?int $deletingId = null;
    public string $deletingName = '';

    protected function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
        ];
    }

    protected function messages(): array
    {
        return [
            'user_id.required' => __('crud.select_judge_required'),
            'city_id.required' => __('crud.select_city_required'),
        ];
    }

    public function getUserOptions()
    {
        if (strlen($this->userSearch) < 2) {
            return collect();
        }

        return User::where('is_active', true)
            ->where(function ($q) {
                $q->where('last_name', 'like', "%{$this->userSearch}%")
                  ->orWhere('first_name', 'like', "%{$this->userSearch}%")
                  ->orWhere('email', 'like', "%{$this->userSearch}%")
                  ->orWhere('username', 'like', "%{$this->userSearch}%");
            })
            ->limit(10)
            ->get();
    }

    public function getCityOptions()
    {
        if (strlen($this->citySearch) < 2) {
            return collect();
        }

        return City::where('is_active', true)
            ->where(function ($q) {
                $q->where('title_ru', 'like', "%{$this->citySearch}%")
                  ->orWhere('title_kk', 'like', "%{$this->citySearch}%")
                  ->orWhere('title_en', 'like', "%{$this->citySearch}%");
            })
            ->limit(10)
            ->get();
    }

    public function selectUser(int $id): void
    {
        $user = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->selectedUserName = $user->last_name . ' ' . $user->first_name;
        $this->userSearch = '';
    }

    public function selectCity(int $id): void
    {
        $city = City::findOrFail($id);
        $this->city_id = $city->id;
        $this->selectedCityName = $city->title_ru;
        $this->citySearch = '';
    }

    public function clearUser(): void
    {
        $this->user_id = null;
        $this->selectedUserName = '';
        $this->userSearch = '';
    }

    public function clearCity(): void
    {
        $this->city_id = null;
        $this->selectedCityName = '';
        $this->citySearch = '';
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::JUDGE_CITIES_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::JUDGE_CITIES_UPDATE);
        $item = JudgeCity::with(['user', 'city'])->findOrFail($id);

        $this->editingId = $item->id;
        $this->user_id = $item->user_id;
        $this->city_id = $item->city_id;
        $this->selectedUserName = $item->user->last_name . ' ' . $item->user->first_name;
        $this->selectedCityName = $item->city->title_ru;
        $this->userSearch = '';
        $this->citySearch = '';
        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::JUDGE_CITIES_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::JUDGE_CITIES_CREATE);
        }

        $this->validate();

        // Check for duplicate combination (accounting for soft deletes)
        $exists = JudgeCity::where('user_id', $this->user_id)
            ->where('city_id', $this->city_id)
            ->when($this->editingId, fn($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($exists) {
            $this->addError('city_id', __('crud.judge_city_exists'));
            return;
        }

        $data = [
            'user_id' => $this->user_id,
            'city_id' => $this->city_id,
        ];

        if ($this->isEditing) {
            JudgeCity::findOrFail($this->editingId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            JudgeCity::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::JUDGE_CITIES_DELETE);
        $item = JudgeCity::with(['user', 'city'])->findOrFail($id);
        $this->deletingId = $item->id;
        $this->deletingName = $item->user->last_name . ' ' . $item->user->first_name . ' — ' . $item->city->title_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::JUDGE_CITIES_DELETE);
        JudgeCity::findOrFail($this->deletingId)->delete();

        toastr()->success(__('crud.deleted_success'));

        $this->showDeleteModal = false;
        $this->deletingId = null;
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

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->user_id = null;
        $this->city_id = null;
        $this->userSearch = '';
        $this->citySearch = '';
        $this->selectedUserName = '';
        $this->selectedCityName = '';
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
        $query = JudgeCity::query()
            ->with(['user', 'city'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('user', function ($uq) {
                        $uq->where('last_name', 'like', "%{$this->search}%")
                           ->orWhere('first_name', 'like', "%{$this->search}%")
                           ->orWhere('email', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('city', function ($cq) {
                        $cq->where('title_ru', 'like', "%{$this->search}%")
                           ->orWhere('title_kk', 'like', "%{$this->search}%")
                           ->orWhere('title_en', 'like', "%{$this->search}%");
                    });
                });
            });

        // Sorting
        if ($this->sortField === 'user_name') {
            $query->join('users', 'judge_cities.user_id', '=', 'users.id')
                  ->orderBy('users.last_name', $this->sortDirection)
                  ->select('judge_cities.*');
        } elseif ($this->sortField === 'city_name') {
            $query->join('cities', 'judge_cities.city_id', '=', 'cities.id')
                  ->orderBy('cities.title_ru', $this->sortDirection)
                  ->select('judge_cities.*');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $items = $query->paginate(10);

        return view('livewire.admin.judge-city-management', [
            'items' => $items,
            'userOptions' => $this->getUserOptions(),
            'cityOptions' => $this->getCityOptions(),
        ]);
    }
}
