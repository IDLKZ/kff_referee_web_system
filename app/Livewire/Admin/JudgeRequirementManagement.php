<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\JudgeRequirement;
use App\Models\JudgeType;
use App\Models\MatchModel;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Judge Requirements')]
class JudgeRequirementManagement extends Component
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

    // Form fields
    public ?int $editingId = null;
    public ?int $match_id = null;
    public ?int $judge_type_id = null;
    public int $qty = 1;
    public bool $is_required = true;
    public bool $isEditing = false;

    // Searchable select state
    public string $matchSearch = '';
    public string $judgeTypeSearch = '';
    public string $selectedMatchName = '';
    public string $selectedJudgeTypeName = '';

    // Delete target
    public ?int $deletingId = null;
    public string $deletingInfo = '';

    protected function rules(): array
    {
        return [
            'match_id' => ['required', 'integer', 'exists:matches,id'],
            'judge_type_id' => ['required', 'integer', 'exists:judge_types,id'],
            'qty' => ['required', 'integer', 'min:1', 'max:100'],
            'is_required' => ['required', 'boolean'],
        ];
    }

    protected function messages(): array
    {
        return [
            'match_id.required' => __('crud.select_match_required'),
            'judge_type_id.required' => __('crud.select_judge_type_required'),
            'qty.required' => __('crud.qty_required'),
            'qty.min' => __('crud.qty_min'),
            'qty.max' => __('crud.qty_max'),
        ];
    }

    public function getMatchOptions()
    {
        if (strlen($this->matchSearch) < 2) {
            return collect();
        }

        return MatchModel::where('is_active', true)
            ->where(function ($q) {
                $q->where('id', 'like', "%{$this->matchSearch}%")
                  ->orWhereHas('tournament', function ($tq) {
                      $tq->where('title_ru', 'like', "%{$this->matchSearch}%")
                         ->orWhere('title_kk', 'like', "%{$this->matchSearch}%")
                         ->orWhere('title_en', 'like', "%{$this->matchSearch}%");
                  })
                  ->orWhereHas('ownerClub', function ($cq) {
                      $cq->where('title_ru', 'like', "%{$this->matchSearch}%")
                         ->orWhere('title_kk', 'like', "%{$this->matchSearch}%")
                         ->orWhere('title_en', 'like', "%{$this->matchSearch}%");
                  })
                  ->orWhereHas('guestClub', function ($cq) {
                      $cq->where('title_ru', 'like', "%{$this->matchSearch}%")
                         ->orWhere('title_kk', 'like', "%{$this->matchSearch}%")
                         ->orWhere('title_en', 'like', "%{$this->matchSearch}%");
                  });
            })
            ->limit(10)
            ->get();
    }

    public function getJudgeTypeOptions()
    {
        if (strlen($this->judgeTypeSearch) < 2) {
            return collect();
        }

        return JudgeType::where('is_active', true)
            ->where(function ($q) {
                $q->where('title_ru', 'like', "%{$this->judgeTypeSearch}%")
                  ->orWhere('title_kk', 'like', "%{$this->judgeTypeSearch}%")
                  ->orWhere('title_en', 'like', "%{$this->judgeTypeSearch}%")
                  ->orWhere('value', 'like', "%{$this->judgeTypeSearch}%");
            })
            ->limit(10)
            ->get();
    }

    public function selectMatch(int $id): void
    {
        $match = MatchModel::with(['tournament', 'ownerClub', 'guestClub'])->findOrFail($id);
        $this->match_id = $match->id;
        $this->selectedMatchName = $match->tournament->title_ru . ': ' .
                                   $match->ownerClub->title_ru . ' vs ' .
                                   $match->guestClub->title_ru;
        $this->matchSearch = '';
    }

    public function selectJudgeType(int $id): void
    {
        $judgeType = JudgeType::findOrFail($id);
        $this->judge_type_id = $judgeType->id;
        $this->selectedJudgeTypeName = $judgeType->title_ru;
        $this->judgeTypeSearch = '';
    }

    public function clearMatch(): void
    {
        $this->match_id = null;
        $this->selectedMatchName = '';
        $this->matchSearch = '';
    }

    public function clearJudgeType(): void
    {
        $this->judge_type_id = null;
        $this->selectedJudgeTypeName = '';
        $this->judgeTypeSearch = '';
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::JUDGE_REQUIREMENTS_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::JUDGE_REQUIREMENTS_UPDATE);
        $item = JudgeRequirement::with(['match.tournament', 'match.ownerClub', 'match.guestClub', 'judgeType'])->findOrFail($id);

        $this->editingId = $item->id;
        $this->match_id = $item->match_id;
        $this->judge_type_id = $item->judge_type_id;
        $this->qty = $item->qty;
        $this->is_required = $item->is_required;

        $this->selectedMatchName = $item->match->tournament->title_ru . ': ' .
                                   $item->match->ownerClub->title_ru . ' vs ' .
                                   $item->match->guestClub->title_ru;
        $this->selectedJudgeTypeName = $item->judgeType->title_ru;

        $this->matchSearch = '';
        $this->judgeTypeSearch = '';
        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::JUDGE_REQUIREMENTS_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::JUDGE_REQUIREMENTS_CREATE);
        }

        $this->validate();

        // Check for duplicate combination
        $exists = JudgeRequirement::where('match_id', $this->match_id)
            ->where('judge_type_id', $this->judge_type_id)
            ->when($this->editingId, fn($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($exists) {
            $this->addError('judge_type_id', __('crud.judge_requirement_exists'));
            return;
        }

        $data = [
            'match_id' => $this->match_id,
            'judge_type_id' => $this->judge_type_id,
            'qty' => $this->qty,
            'is_required' => $this->is_required,
        ];

        if ($this->isEditing) {
            JudgeRequirement::findOrFail($this->editingId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            JudgeRequirement::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::JUDGE_REQUIREMENTS_DELETE);
        $item = JudgeRequirement::with(['match.tournament', 'match.ownerClub', 'match.guestClub', 'judgeType'])->findOrFail($id);
        $this->deletingId = $item->id;
        $this->deletingInfo = $item->judgeType->title_ru . ' — ' .
                              $item->match->tournament->title_ru . ': ' .
                              $item->match->ownerClub->title_ru . ' vs ' .
                              $item->match->guestClub->title_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::JUDGE_REQUIREMENTS_DELETE);
        JudgeRequirement::findOrFail($this->deletingId)->delete();

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
        $this->isEditing = false;
        $this->match_id = null;
        $this->judge_type_id = null;
        $this->qty = 1;
        $this->is_required = true;
        $this->matchSearch = '';
        $this->judgeTypeSearch = '';
        $this->selectedMatchName = '';
        $this->selectedJudgeTypeName = '';
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
        $query = JudgeRequirement::query()
            ->with(['match.tournament', 'match.ownerClub', 'match.guestClub', 'judgeType'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('match.tournament', function ($tq) {
                        $tq->where('title_ru', 'like', "%{$this->search}%")
                           ->orWhere('title_kk', 'like', "%{$this->search}%")
                           ->orWhere('title_en', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('match.ownerClub', function ($cq) {
                        $cq->where('title_ru', 'like', "%{$this->search}%")
                           ->orWhere('title_kk', 'like', "%{$this->search}%")
                           ->orWhere('title_en', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('match.guestClub', function ($cq) {
                        $cq->where('title_ru', 'like', "%{$this->search}%")
                           ->orWhere('title_kk', 'like', "%{$this->search}%")
                           ->orWhere('title_en', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('judgeType', function ($jq) {
                        $jq->where('title_ru', 'like', "%{$this->search}%")
                           ->orWhere('title_kk', 'like', "%{$this->search}%")
                           ->orWhere('title_en', 'like', "%{$this->search}%");
                    });
                });
            });

        // Sorting
        if ($this->sortField === 'match') {
            $query->join('matches', 'judge_requirements.match_id', '=', 'matches.id')
                  ->join('tournaments', 'matches.tournament_id', '=', 'tournaments.id')
                  ->orderBy('tournaments.title_ru', $this->sortDirection)
                  ->select('judge_requirements.*');
        } elseif ($this->sortField === 'judge_type') {
            $query->join('judge_types', 'judge_requirements.judge_type_id', '=', 'judge_types.id')
                  ->orderBy('judge_types.title_ru', $this->sortDirection)
                  ->select('judge_requirements.*');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $items = $query->paginate(10);

        return view('livewire.admin.judge-requirement-management', [
            'items' => $items,
            'matchOptions' => $this->getMatchOptions(),
            'judgeTypeOptions' => $this->getJudgeTypeOptions(),
        ]);
    }
}
