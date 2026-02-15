<?php

namespace App\Livewire\Kff;

use App\Constants\OperationConstants;
use App\Constants\RoleConstants;
use App\Models\MatchModel;
use App\Models\MatchReport;
use App\Models\MatchReportDocument;
use App\Models\Operation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('kff.layout')]
#[Title('Match Protocol Report Detail')]
class MatchProtocolDetailReport extends Component
{
    public int $reportId;
    public ?MatchReport $report = null;
    public ?MatchModel $match = null;
    public ?Operation $currentOperation = null;

    public bool $showCompleteModal = false;
    public bool $showReprocessModal = false;
    public ?string $finalComment = null;

    // Review data for documents
    public array $reviewData = [];

    public function mount(int $reportId): void
    {
        abort_unless(
            auth()->user()->role->value === RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            403
        );

        $this->reportId = $reportId;
        $this->loadReport();
    }

    protected function loadReport(): void
    {
        $this->report = MatchReport::with([
            'match.tournament',
            'match.season',
            'match.ownerClub',
            'match.guestClub',
            'match.city',
            'match.stadium',
            'match.operation',
            'match.judge_requirements.judge_type',
            'match.match_judges.user',
            'match.match_judges.judge_type',
            'match_report_documents.match_protocol_requirement',
            'match_report_documents.user',
            'judge',
            'checked_by',
        ])->find($this->reportId);

        if (!$this->report) {
            abort(404);
        }

        $this->match = $this->report->match;
        $this->currentOperation = $this->match->operation;

        // Initialize review data for documents
        foreach ($this->report->match_report_documents as $doc) {
            $this->reviewData[$doc->id] = [
                'is_accepted' => $doc->is_accepted,
                'final_comment' => $doc->final_comment,
            ];
        }
    }

    public function isReviewAvailable(): bool
    {
        return $this->currentOperation &&
               $this->currentOperation->value === OperationConstants::PROTOCOL_REVIEW;
    }

    public function allDocumentsReviewed(): bool
    {
        return $this->report->match_report_documents->every(function ($doc) {
            return $doc->is_accepted !== null;
        });
    }

    public function canComplete(): bool
    {
        return $this->isReviewAvailable() && $this->allDocumentsReviewed();
    }

    public function acceptDocument(int $documentId): void
    {
        if (!$this->isReviewAvailable()) {
            return;
        }

        if (!isset($this->reviewData[$documentId])) {
            $this->reviewData[$documentId] = [];
        }
        $this->reviewData[$documentId]['is_accepted'] = true;
    }

    public function rejectDocument(int $documentId): void
    {
        if (!$this->isReviewAvailable()) {
            return;
        }

        if (!isset($this->reviewData[$documentId])) {
            $this->reviewData[$documentId] = [];
        }
        $this->reviewData[$documentId]['is_accepted'] = false;
    }

    public function updateDocumentReview(int $documentId): void
    {
        if (!$this->isReviewAvailable()) {
            return;
        }

        $document = MatchReportDocument::find($documentId);
        if (!$document || $document->match_report_id !== $this->reportId) {
            return;
        }

        $data = $this->reviewData[$documentId] ?? [];
        $isAccepted = $data['is_accepted'] ?? null;

        // Convert string to boolean if needed
        if (is_string($isAccepted)) {
            $isAccepted = $isAccepted === 'true';
        }

        $document->update([
            'is_accepted' => $isAccepted,
            'final_comment' => $data['final_comment'] ?? null,
            'checked_by_id' => $isAccepted !== null ? auth()->id() : null,
        ]);

        $this->loadReport();
    }

    public function completeReport(): void
    {
        if (!$this->canComplete()) {
            return;
        }

        // Find SUCCESSFULLY_COMPLETED operation
        $successOperation = Operation::where('value', OperationConstants::SUCCESSFULLY_COMPLETED)->first();
        if (!$successOperation) {
            return;
        }

        $this->report->update([
            'is_accepted' => true,
            'final_comment' => $this->finalComment,
            'checked_by_id' => auth()->id(),
        ]);

        // Update match operation
        $this->match->update([
            'current_operation_id' => $successOperation->id,
        ]);

        $this->showCompleteModal = false;
        $this->finalComment = null;

        $this->redirectRoute('kff.protocol-review', navigate: true);
        session()->flash('success', __('crud.protocol_completed_success'));
    }

    public function reprocessReport(): void
    {
        if (!$this->canComplete()) {
            return;
        }

        // Find PROTOCOL_REPROCESSING operation
        $reprocessOperation = Operation::where('value', OperationConstants::PROTOCOL_REPROCESSING)->first();
        if (!$reprocessOperation) {
            return;
        }

        $this->report->update([
            'final_comment' => $this->finalComment,
            'checked_by_id' => auth()->id(),
        ]);

        // Update match operation
        $this->match->update([
            'current_operation_id' => $reprocessOperation->id,
        ]);

        $this->showReprocessModal = false;
        $this->finalComment = null;

        $this->redirectRoute('kff.protocol-review', navigate: true);
        session()->flash('success', __('crud.protocol_reprocessing_success'));
    }

    public function render()
    {
        return view('livewire.kff.match-protocol-detail-report', [
            'report' => $this->report,
            'match' => $this->match,
            'currentOperation' => $this->currentOperation,
            'isReviewAvailable' => $this->isReviewAvailable(),
            'allDocumentsReviewed' => $this->allDocumentsReviewed(),
            'canComplete' => $this->canComplete(),
        ]);
    }
}
