<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\Club;
use App\Models\ClubStadium;
use App\Models\Stadium;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Club Stadiums')]
class ClubStadiumsManagement extends Component
{
    use WithPagination;

    // Search & Filters
    public string $search = '';
    public string $sortField = 'club_id';
    public string $sortDirection = 'asc';
    public ?int $filterClub = null;
    public ?int $filterStadium = null;

    // Modal state
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;
    public bool $isEditing = false;

    // Form fields
    public ?string $editingId = null;
    public ?int $club_id = null;
    public ?int $stadium_id = null;

    // Delete target
    public ?int $deletingClubId = null;
    public ?int $deletingStadiumId = null;
    public string $deletingName = '';

    // For managing multiple stadiums for a club
    public ?int $managingClubId = null;
    public array $selectedStadiums = [];

    protected function rules(): array
    {
        return [
            'club_id' => ['required', 'exists:clubs,id'],
            'stadium_id' => ['required', 'exists:stadiums,id'],
        ];
    }

    protected function messages(): array
    {
        return [
            'club_id.required' => __('validation.required', ['attribute' => __('crud.club')]),
            'stadium_id.required' => __('validation.required', ['attribute' => __('crud.stadium')]),
            'club_id.exists' => __('validation.exists', ['attribute' => __('crud.club')]),
            'stadium_id.exists' => __('validation.exists', ['attribute' => __('crud.stadium')]),
        ];
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::CLUB_STADIUMS_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $clubId, int $stadiumId): void
    {
        $this->checkPermission(PermissionConstants::CLUB_STADIUMS_UPDATE);

        $clubStadium = ClubStadium::where('club_id', $clubId)
            ->where('stadium_id', $stadiumId)
            ->firstOrFail();

        $this->editingId = $clubId . '-' . $stadiumId;
        $this->club_id = $clubStadium->club_id;
        $this->stadium_id = $clubStadium->stadium_id;
        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::CLUB_STADIUMS_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::CLUB_STADIUMS_CREATE);
        }

        $this->validate();

        // Check for duplicates
        $exists = ClubStadium::where('club_id', $this->club_id)
            ->where('stadium_id', $this->stadium_id)
            ->exists();

        if ($exists && !$this->isEditing) {
            toastr()->error(__('crud.club_stadium_exists'));
            return;
        }

        if ($this->isEditing) {
            // For pivot table, we delete old and create new
            list($oldClubId, $oldStadiumId) = explode('-', $this->editingId);
            ClubStadium::where('club_id', $oldClubId)
                ->where('stadium_id', $oldStadiumId)
                ->delete();

            ClubStadium::create([
                'club_id' => $this->club_id,
                'stadium_id' => $this->stadium_id,
            ]);

            toastr()->success(__('crud.updated_success'));
        } else {
            ClubStadium::create([
                'club_id' => $this->club_id,
                'stadium_id' => $this->stadium_id,
            ]);

            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $clubId, int $stadiumId): void
    {
        $this->checkPermission(PermissionConstants::CLUB_STADIUMS_DELETE);

        $clubStadium = ClubStadium::where('club_id', $clubId)
            ->where('stadium_id', $stadiumId)
            ->firstOrFail();

        $this->deletingClubId = $clubId;
        $this->deletingStadiumId = $stadiumId;
        $this->deletingName = $clubStadium->club->short_name_ru . ' - ' . $clubStadium->stadium->title_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::CLUB_STADIUMS_DELETE);

        ClubStadium::where('club_id', $this->deletingClubId)
            ->where('stadium_id', $this->deletingStadiumId)
            ->delete();

        toastr()->success(__('crud.deleted_success'));
        $this->showDeleteModal = false;
        $this->deletingClubId = null;
        $this->deletingStadiumId = null;
    }

    public function openManageStadiumsModal(int $clubId): void
    {
        $this->checkPermission(PermissionConstants::CLUB_STADIUMS_CREATE);

        $this->managingClubId = $clubId;
        $club = Club::findOrFail($clubId);

        // Get currently assigned stadiums
        $this->selectedStadiums = $club->stadiums->pluck('id')->toArray();

        $this->showFormModal = true;
    }

    public function saveClubStadiums(): void
    {
        $this->checkPermission(PermissionConstants::CLUB_STADIUMS_CREATE);

        if (!$this->managingClubId) {
            return;
        }

        $club = Club::findOrFail($this->managingClubId);

        // Sync stadiums (detach all, then attach selected)
        $club->stadiums()->sync($this->selectedStadiums);

        toastr()->success(__('crud.updated_success'));
        $this->showFormModal = false;
        $this->resetForm();
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
        $this->sortField = 'club_id';
        $this->sortDirection = 'asc';
        $this->filterClub = null;
        $this->filterStadium = null;
    }

    public function getClubOptions(): array
    {
        return Club::orderBy('short_name_ru')
            ->pluck('short_name_ru', 'id')
            ->toArray();
    }

    public function getStadiumOptions(): array
    {
        return Stadium::orderBy('title_ru')
            ->pluck('title_ru', 'id')
            ->toArray();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->club_id = null;
        $this->stadium_id = null;
        $this->managingClubId = null;
        $this->selectedStadiums = [];
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
        $query = ClubStadium::with(['club', 'stadium'])
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->whereHas('club', function ($q) use ($searchTerm) {
                        $q->where('short_name_ru', 'like', $searchTerm)
                          ->orWhere('full_name_ru', 'like', $searchTerm);
                    })
                    ->orWhereHas('stadium', function ($q) use ($searchTerm) {
                        $q->where('title_ru', 'like', $searchTerm);
                    });
            })
            ->when($this->filterClub, function ($query) {
                $query->where('club_id', $this->filterClub);
            })
            ->when($this->filterStadium, function ($query) {
                $query->where('stadium_id', $this->filterStadium);
            });

        // Sorting by club name or stadium name
        if ($this->sortField === 'club_id') {
            $query->join('clubs', 'club_stadiums.club_id', '=', 'clubs.id')
                  ->orderBy('clubs.short_name_ru', $this->sortDirection)
                  ->select('club_stadiums.*');
        } elseif ($this->sortField === 'stadium_id') {
            $query->join('stadiums', 'club_stadiums.stadium_id', '=', 'stadiums.id')
                  ->orderBy('stadiums.title_ru', $this->sortDirection)
                  ->select('club_stadiums.*');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $clubStadiums = $query->paginate(10);

        return view('livewire.admin.club-stadiums-management', [
            'clubStadiums' => $clubStadiums,
        ]);
    }
}
