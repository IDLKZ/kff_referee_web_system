<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\City;
use App\Models\Country;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Cities')]
class CityManagement extends Component
{
    use WithPagination;

    public string $search = '';

    // Sorting
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    // Filter
    public ?int $filter_country_id = null;

    // Modal state
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;
    public bool $isEditing = false;

    // Form fields
    public ?int $editingCityId = null;
    public ?int $country_id = null;
    public string $title_ru = '';
    public string $title_kk = '';
    public string $title_en = '';
    public string $value = '';
    public bool $is_active = true;

    // Delete target
    public ?int $deletingCityId = null;
    public string $deletingCityName = '';

    protected function rules(): array
    {
        $uniqueRule = 'unique:cities,value';
        if ($this->editingCityId) {
            $uniqueRule .= ',' . $this->editingCityId;
        }

        return [
            'country_id' => ['nullable', 'exists:countries,id'],
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:280', $uniqueRule],
            'is_active' => ['boolean'],
        ];
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::CITIES_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::CITIES_UPDATE);
        $city = City::findOrFail($id);

        $this->editingCityId = $city->id;
        $this->country_id = $city->country_id;
        $this->title_ru = $city->title_ru;
        $this->title_kk = $city->title_kk ?? '';
        $this->title_en = $city->title_en ?? '';
        $this->value = $city->value;
        $this->is_active = $city->is_active;
        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::CITIES_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::CITIES_CREATE);
        }

        $this->validate();

        $data = [
            'country_id' => $this->country_id ?: null,
            'title_ru' => $this->title_ru,
            'title_kk' => $this->title_kk ?: null,
            'title_en' => $this->title_en ?: null,
            'value' => $this->value,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditing) {
            City::findOrFail($this->editingCityId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            City::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::CITIES_DELETE);
        $city = City::findOrFail($id);
        $this->deletingCityId = $city->id;
        $this->deletingCityName = $city->title_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::CITIES_DELETE);
        City::findOrFail($this->deletingCityId)->delete();

        toastr()->success(__('crud.deleted_success'));

        $this->showDeleteModal = false;
        $this->deletingCityId = null;
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
        $this->filter_country_id = null;
    }

    public function getCountryOptions(): array
    {
        return Country::where('is_active', true)
            ->orderBy('title_ru')
            ->pluck('title_ru', 'id')
            ->toArray();
    }

    private function resetForm(): void
    {
        $this->editingCityId = null;
        $this->country_id = null;
        $this->title_ru = '';
        $this->title_kk = '';
        $this->title_en = '';
        $this->value = '';
        $this->is_active = true;
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
        $query = City::with('country')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title_ru', 'like', "%{$this->search}%")
                      ->orWhere('title_kk', 'like', "%{$this->search}%")
                      ->orWhere('title_en', 'like', "%{$this->search}%")
                      ->orWhere('value', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filter_country_id, function ($query) {
                $query->where('country_id', $this->filter_country_id);
            });

        $query->orderBy($this->sortField, $this->sortDirection);

        $cities = $query->paginate(10);

        return view('livewire.admin.city-management', [
            'cities' => $cities,
        ]);
    }
}
