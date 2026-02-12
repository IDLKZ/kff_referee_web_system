<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Constants\RoleConstants;
use App\Http\Requests\MatchLogists\CreateMatchLogistsRequest;
use App\Http\Requests\MatchLogists\UpdateMatchLogistsRequest;
use App\Models\MatchLogist;
use App\Models\MatchModel;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Match Logists')]
class MatchLogistsManagement extends Component {
    use WithPagination;

    // --- Поиск и фильтры ---
    public string $search = '';
    public ?int $filter_match_id = null;
    public ?int $filter_logist_id = null;
    public ?string $filter_role = null; // filter by role value
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    // --- Состояние модальных окон ---
    public bool $showCreateModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;

    // --- Поля формы создания/редактирования ---
    public ?int $editingId = null;
    public ?int $form_match_id = null;
    public ?int $form_logist_id = null;

    // --- Цель для удаления ---
    public ?int $deletingId = null;
    public string $deletingInfo = '';

    /**
     * Правила валидации
     */
    protected function rules(): array
    {
        return [
            'form_match_id' => ['required', 'exists:matches,id'],
            'form_logist_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if (!$user || !$user->isActive()) {
                        $fail(__('validation.logist_must_be_active'));
                    }
                    if ($user && $user->role->value !== RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN) {
                        $fail(__('validation.logist_must_be_logistician'));
                    }
                },
                Rule::unique('match_logists', 'logist_id')
                    ->where('match_id', $this->form_match_id)
                    ->ignore($this->editingId),
            ],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'form_match_id' => __('crud.match'),
            'form_logist_id' => __('crud.logist'),
        ];
    }

    protected function messages(): array
    {
        return [
            'form_match_id.required' => __('validation.match_id_required'),
            'form_match_id.exists' => __('validation.match_id_exists'),
            'form_logist_id.required' => __('validation.logist_id_required'),
            'form_logist_id.exists' => __('validation.logist_id_exists'),
            'form_logist_id.logist_must_be_active' => __('validation.logist_must_be_active'),
            'form_logist_id.logist_must_be_logistician' => __('validation.logist_must_be_logistician'),
        ];
    }

    // --- Сброс пагинации при изменении фильтров ---
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatedFilterMatchId(): void { $this->resetPage(); }
    public function updatedFilterLogistId(): void { $this->resetPage(); }
    public function updatedFilterRole(): void { $this->resetPage(); }

    // --- Создание ---
    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::MATCH_LOGISTS_CREATE);
        $this->resetForm();
        $this->showCreateModal = true;
    }

    // --- Редактирование ---
    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::MATCH_LOGISTS_UPDATE);
        $matchLogist = MatchLogist::with(['match', 'user'])->findOrFail($id);

        $this->editingId = $matchLogist->id;
        $this->form_match_id = $matchLogist->match_id;
        $this->form_logist_id = $matchLogist->logist_id;
        $this->showCreateModal = true;
    }

    // --- Сохранение (создание/обновление) ---
    public function save(): void
    {
        if ($this->editingId) {
            $this->checkPermission(PermissionConstants::MATCH_LOGISTS_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::MATCH_LOGISTS_CREATE);
        }

        $this->validate();

        $data = [
            'match_id' => $this->form_match_id,
            'logist_id' => $this->form_logist_id,
        ];

        if ($this->editingId) {
            MatchLogist::findOrFail($this->editingId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            MatchLogist::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showCreateModal = false;
        $this->resetForm();
    }

    // --- Удаление ---
    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::MATCH_LOGISTS_DELETE);
        $matchLogist = MatchLogist::with(['match', 'user'])->findOrFail($id);

        $this->deletingId = $matchLogist->id;
        $this->deletingInfo = $matchLogist->user->full_name ?? ($matchLogist->user->first_name ?? '') . ' (' . ($matchLogist->user->last_name ?? '') . ')';
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::MATCH_LOGISTS_DELETE);
        MatchLogist::findOrFail($this->deletingId)->delete();

        toastr()->success(__('crud.deleted_success'));
        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->deletingInfo = '';
    }

    // --- Сортировка ---
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // --- Поиск / Фильтр ---
    public function toggleSearchModal(): void
    {
        $this->showSearchModal = !$this->showSearchModal;
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->filter_match_id = null;
        $this->filter_logist_id = null;
        $this->filter_role = null;
        $this->resetPage();
    }

    // --- Опции для выпадающих списков ---
    public function getRoleFilterOptions(): array
    {
        return [
            '' => __('crud.all'),
            'logistician' => RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
            'finance' => RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
            'accountant' => RoleConstants::FINANCE_DEPARTMENT_ACCOUNTANT,
        ];
    }

    public function getMatchOptions(): array
    {
        $query = MatchModel::query()
            ->with(['ownerClub', 'guestClub', 'stadium', 'city', 'season'])
            ->where('is_active', true);

        // Фильтр по роли логиста - показываем только логистов
        if ($this->filter_role === 'logistician') {
            $query->where('current_operation_id', '>=', function ($q) {
                return $q->whereNotNull('ownerClub');
            });
        }

        return $query->orderBy('start_at', 'desc')
            ->get()
            ->mapWithKeys(function ($match) {
                $homeClub = $match->ownerClub?->short_title_ru ?? '';
                $guestClub = $match->guestClub?->short_title_ru ?? '';
                $stadium = $match->stadium?->title_ru ?? '';
                $city = $match->city?->title_ru ?? '';
                $season = $match->season?->title_ru ?? '';
                $date = $match->start_at?->format('d.m.Y H:i') ?? '';
                $round = $match->round ?? '';

                $label = $match->homeClub . ' vs ' . $guestClub;

                return [
                    $match->id => $label . ' - ' . $date . ' . ' . __('crud.round') . ' ' . $round,
                ];
            })
            ->toArray();
    }

    public function getLogistOptions(): array
    {
        $query = User::query()
            ->where('is_active', true);

        // Фильтр по роли логиста
        if ($this->filter_role) {
            $query->where('role_id', RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN);
        }

        return $query->orderBy('last_name')
            ->get()
            ->mapWithKeys(function ($user) {
                $roleLabel = $user->role?->title_ru ?? __('crud.user_no_role');
                $name = $user->full_name ?? ($user->first_name ?? '') . ' ' . ($user->last_name ?? '');

                return [$user->id => $name . ' (' . $roleLabel . ')'];
            })
            ->toArray();
    }

    // --- Сервисные методы ---
    private function resetForm(): void
    {
        $this->editingId = null;
        $this->form_match_id = null;
        $this->form_logist_id = null;
        $this->resetValidation();
    }

    private function checkPermission(string $permission): void
    {
        if (!auth()->user()->hasPermission($permission)) {
            abort(403);
        }
    }

    // --- Рендер ---
    public function render()
    {
        $query = MatchLogist::with(['match', 'user', 'match.ownerClub', 'match.guestClub'])
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                        $q->whereHas('match', function ($m) use ($searchTerm) {
                            return $m->where('ownerClub.short_title_ru', 'like', $searchTerm)
                                ->orWhere('guestClub.short_title_ru', 'like', $searchTerm);
                        })
                        ->orWhereHas('match', function ($m) use ($searchTerm) {
                            return $m->where('stadium.title_ru', 'like', $searchTerm)
                                ->orWhere('city.title_ru', 'like', $searchTerm)
                                ->orWhere('season.title_ru', 'like', $searchTerm);
                        })
                        ->orWhereHas('user', function ($u) use ($searchTerm) {
                            return $u->where('first_name', 'like', $searchTerm)
                                ->orWhere('last_name', 'like', $searchTerm)
                                ->orWhere('middle_name', 'like', $searchTerm);
                        });
                    });
            })
            ->when($this->filter_match_id, function ($query) {
                $query->where('match_id', $this->filter_match_id);
            })
            ->when($this->filter_logist_id, function ($query) {
                $query->where('logist_id', $this->filter_logist_id);
            })
            ->when($this->filter_role, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('role_id', RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN);
                });
            });

        // Сортировка
        if ($this->sortField === 'match_date') {
            $query->orderBy('matches.start_at', $this->sortDirection);
        } elseif ($this->sortField === 'match_title') {
            $query->join('matches as m', 'match_logists as ml')
                ->orderBy('m.short_title_ru', $this->sortDirection);
        } elseif ($this->sortField === 'logist_name') {
            $query->join('users as u', 'match_logists as ml')
                ->orderBy('u.last_name', $this->sortDirection);
        } else {
            $query->orderBy('match_logists.id', $this->sortDirection);
        }

        $matchLogists = $query->paginate(15);

        return view('livewire.admin.match-logists-management', [
            'matchLogists' => $matchLogists,
        ]);
    }
}
