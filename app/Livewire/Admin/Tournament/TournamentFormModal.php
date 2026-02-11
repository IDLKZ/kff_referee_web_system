<?php

namespace App\Livewire\Admin\Tournament;

use App\Constants\PermissionConstants;
use App\Models\Country;
use App\Models\Tournament;
use App\Services\File\FileService;
use App\Services\File\DTO\FileValidationOptions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use LivewireUI\Modal\ModalComponent;
use Livewire\WithFileUploads;

class TournamentFormModal extends ModalComponent
{
    use WithFileUploads;

    public ?int $tournamentId = null;
    public bool $isEditing = false;

    // Form fields
    public ?int $country_id = null;
    public $image;
    public ?int $image_id = null;
    public string $title_ru = '';
    public string $title_kk = '';
    public string $title_en = '';
    public string $short_title_ru = '';
    public string $short_title_kk = '';
    public string $short_title_en = '';
    public ?string $description_ru = '';
    public ?string $description_kk = '';
    public ?string $description_en = '';
    public string $value = '';
    public int $level = 1;
    public int $sex = 0;
    public bool $is_active = true;

    // Image preview
    public ?string $temporaryImageUrl = null;
    public ?string $existingImageUrl = null;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    public function mount(?int $tournamentId = null): void
    {
        if ($tournamentId) {
            $this->checkPermission(PermissionConstants::TOURNAMENTS_UPDATE);
            $tournament = Tournament::with('file')->findOrFail($tournamentId);

            $this->tournamentId = $tournament->id;
            $this->isEditing = true;
            $this->country_id = $tournament->country_id;
            $this->image_id = $tournament->file_id;
            $this->title_ru = $tournament->title_ru;
            $this->title_kk = $tournament->title_kk ?? '';
            $this->title_en = $tournament->title_en ?? '';
            $this->short_title_ru = $tournament->short_title_ru ?? '';
            $this->short_title_kk = $tournament->short_title_kk ?? '';
            $this->short_title_en = $tournament->short_title_en ?? '';
            $this->description_ru = $tournament->description_ru ?? '';
            $this->description_kk = $tournament->description_kk ?? '';
            $this->description_en = $tournament->description_en ?? '';
            $this->value = $tournament->value;
            $this->level = $tournament->level;
            $this->sex = $tournament->sex;
            $this->is_active = $tournament->is_active;

            if ($tournament->file && Storage::disk('uploads')->exists($tournament->file->file_path)) {
                $this->existingImageUrl = Storage::disk('uploads')->url($tournament->file->file_path);
            }
        } else {
            $this->checkPermission(PermissionConstants::TOURNAMENTS_CREATE);
        }
    }

    protected function rules(): array
    {
        $uniqueRule = 'unique:tournaments,value';
        if ($this->tournamentId) {
            $uniqueRule .= ',' . $this->tournamentId;
        }

        return [
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'short_title_ru' => ['required', 'string', 'max:255'],
            'short_title_kk' => ['required', 'string', 'max:255'],
            'short_title_en' => ['required', 'string', 'max:255'],
            'description_ru' => ['nullable', 'string'],
            'description_kk' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'value' => ['required', 'string', 'max:255', $uniqueRule],
            'country_id' => ['nullable', 'exists:countries,id'],
            'level' => ['required', 'integer', 'min:1'],
            'sex' => ['required', 'in:0,1,2'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
        ];
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::TOURNAMENTS_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::TOURNAMENTS_CREATE);
        }

        $this->validate();

        // Handle image upload
        if ($this->image instanceof UploadedFile) {
            $fileService = app(FileService::class);
            $fileService->setDisk('uploads');
            $file = $fileService->save(
                $this->image,
                'tournaments',
                FileValidationOptions::images(maxSizeMB: 5)
            );
            $this->image_id = $file->id;
        }

        $data = [
            'file_id' => $this->image_id,
            'country_id' => $this->country_id ?: null,
            'title_ru' => $this->title_ru,
            'title_kk' => $this->title_kk ?: null,
            'title_en' => $this->title_en ?: null,
            'short_title_ru' => $this->short_title_ru ?: '',
            'short_title_kk' => $this->short_title_kk ?: '',
            'short_title_en' => $this->short_title_en ?: '',
            'description_ru' => $this->description_ru ?: null,
            'description_kk' => $this->description_kk ?: null,
            'description_en' => $this->description_en ?: null,
            'value' => $this->value,
            'level' => $this->level,
            'sex' => $this->sex,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditing) {
            Tournament::findOrFail($this->tournamentId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            Tournament::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->closeModalWithEvents([
            'tournamentSaved',
        ]);
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

    public function getCountryOptions(): array
    {
        return Country::where('is_active', true)
            ->orderBy('title_ru')
            ->pluck('title_ru', 'id')
            ->toArray();
    }

    public function getSexOptions(): array
    {
        return [
            0 => __('crud.sex_not_specified'),
            1 => __('crud.sex_male'),
            2 => __('crud.sex_female'),
        ];
    }

    private function checkPermission(string $permission): void
    {
        if (!auth()->user()->hasPermission($permission)) {
            abort(403);
        }
    }

    public function render()
    {
        return view('livewire.admin.tournament.tournament-form-modal');
    }
}
