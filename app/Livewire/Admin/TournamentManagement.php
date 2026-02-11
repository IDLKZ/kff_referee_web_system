<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\Country;
use App\Models\Tournament;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Tournaments')]
class TournamentManagement extends Component
{
    use WithPagination;

    // Search & Filter
    public string $search = '';
    public ?int $filter_country_id = null;
    public ?int $filter_sex = null;
    public ?bool $filter_is_active = null;

    // Sorting
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    // Search modal (inline Alpine)
    public bool $showSearchModal = false;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterCountryId(): void
    {
        $this->resetPage();
    }

    public function updatingFilterSex(): void
    {
        $this->resetPage();
    }

    public function updatingFilterIsActive(): void
    {
        $this->resetPage();
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
        $this->filter_sex = null;
        $this->filter_is_active = null;
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

    #[On('tournamentSaved')]
    #[On('tournamentDeleted')]
    public function refreshList(): void
    {
        // Livewire will re-render automatically
    }

    public function render()
    {
        $query = Tournament::with(['country', 'file'])
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
            })
            ->when($this->filter_sex !== null, function ($query) {
                $query->where('sex', $this->filter_sex);
            })
            ->when($this->filter_is_active !== null, function ($query) {
                $query->where('is_active', $this->filter_is_active);
            });

        $query->orderBy($this->sortField, $this->sortDirection);

        $tournaments = $query->paginate(10);

        return view('livewire.admin.tournament-management', [
            'tournaments' => $tournaments,
        ]);
    }
}
