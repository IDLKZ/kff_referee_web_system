<?php

namespace App\Livewire\Admin\Tournament;

use App\Constants\PermissionConstants;
use App\Models\Tournament;
use LivewireUI\Modal\ModalComponent;

class TournamentDeleteModal extends ModalComponent
{
    public int $tournamentId;
    public string $tournamentName = '';

    public static function modalMaxWidth(): string
    {
        return 'sm';
    }

    public function mount(int $tournamentId): void
    {
        $this->checkPermission(PermissionConstants::TOURNAMENTS_DELETE);
        $tournament = Tournament::findOrFail($tournamentId);
        $this->tournamentId = $tournament->id;
        $this->tournamentName = $tournament->title_ru;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::TOURNAMENTS_DELETE);
        Tournament::findOrFail($this->tournamentId)->delete();

        toastr()->success(__('crud.deleted_success'));

        $this->closeModalWithEvents([
            'tournamentDeleted',
        ]);
    }

    private function checkPermission(string $permission): void
    {
        if (!auth()->user()->hasPermission($permission)) {
            abort(403);
        }
    }

    public function render()
    {
        return view('livewire.admin.tournament.tournament-delete-modal');
    }
}
