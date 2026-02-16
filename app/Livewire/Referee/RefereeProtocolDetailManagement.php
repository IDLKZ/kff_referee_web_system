<?php

namespace App\Livewire\Referee;

use App\Constants\OperationConstants;
use App\Constants\RoleConstants;
use App\Models\MatchJudge;
use App\Models\MatchModel;
use App\Models\MatchOperationLog;
use App\Models\MatchProtocolRequirement;
use App\Models\MatchReport;
use App\Models\MatchReportDocument;
use App\Models\Operation;
use App\Services\File\FileService;
use App\Services\File\DTO\FileValidationOptions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('referee.layout')]
#[Title('Referee Protocol Detail')]
class RefereeProtocolDetailManagement extends Component
{
    use WithFileUploads;

    public int $matchId;
    public ?MatchModel $match = null;
    public ?MatchReport $matchReport = null;

    // Document upload modal
    public bool $showUploadModal = false;
    public ?int $uploadRequirementId = null;
    public ?string $uploadComment = '';
    public $uploadedDocument = null;

    // Requirements and documents
    public $protocolRequirements = [];
    public $reportDocuments = [];

    // Type IDs for the judge
    public $judgeTypeIds = [];

    // Access control
    public bool $hasAccess = false;
    public bool $isEditable = false;
    public bool $canSubmit = false;

    // Flash messages
    public string $successMessage = '';
    public string $errorMessage = '';

    // Operations
    protected ?Operation $waitingForProtocolOperation = null;
    protected ?Operation $protocolReprocessingOperation = null;
    protected ?Operation $protocolReviewOperation = null;

    public function mount(int $matchId): void
    {
        abort_unless(
            auth()->user()->role->value === RoleConstants::SOCCER_REFEREE,
            403
        );

        $this->matchId = $matchId;
        $this->loadOperations();
        $this->checkAccess();
        $this->loadData();
    }

    protected function loadOperations(): void
    {
        $this->waitingForProtocolOperation = Operation::where('value', OperationConstants::WAITING_FOR_PROTOCOL)->first();
        $this->protocolReprocessingOperation = Operation::where('value', OperationConstants::PROTOCOL_REPROCESSING)->first();
        $this->protocolReviewOperation = Operation::where('value', OperationConstants::PROTOCOL_REVIEW)->first();
    }

    protected function getActualMatchIds(): array
    {
        $userId = auth()->id();

        // Step 1: Get match judges where final_status=1, judge_response=1, is_actual=true
        $matchJudges = MatchJudge::where('judge_id', $userId)
            ->where('final_status', 1)
            ->where('judge_response', 1)
            ->where('is_actual', true)
            ->get(['match_id', 'type_id']);

        if ($matchJudges->isEmpty()) {
            return [];
        }

        $matchIds = $matchJudges->pluck('match_id')->unique()->toArray();
        $this->judgeTypeIds = $matchJudges->pluck('type_id')->unique()->toArray();

        // Step 2: Get matches with WAITING_FOR_PROTOCOL operation
        $operationIds = [];
        if ($this->waitingForProtocolOperation) {
            $operationIds[] = $this->waitingForProtocolOperation->id;
        }
        if ($this->protocolReprocessingOperation) {
            $operationIds[] = $this->protocolReprocessingOperation->id;
        }

        if (empty($operationIds)) {
            return [];
        }

        return MatchModel::whereIn('id', $matchIds)
            ->whereIn('current_operation_id', $operationIds)
            ->pluck('id')
            ->toArray();
    }

    protected function checkAccess(): void
    {
        $actualMatchIds = $this->getActualMatchIds();

        // Check if matchId is in actualMatchIds
        $this->hasAccess = in_array($this->matchId, $actualMatchIds, true);

        if (!$this->hasAccess) {
            abort(403);
        }
    }

    protected function loadData(): void
    {
        $this->match = MatchModel::with([
            'tournament',
            'season',
            'ownerClub',
            'guestClub',
            'city',
            'stadium',
            'operation',
            'judge_requirements.judge_type',
            'match_judges.user',
            'match_judges.judge_type',
        ])->find($this->matchId);

        if (!$this->match) {
            abort(404);
        }

        $userId = auth()->id();

        // Check if match report exists
        $this->matchReport = MatchReport::where('match_id', $this->matchId)
            ->where('judge_id', $userId)
            ->with('match_report_documents')
            ->first();

        // Determine if editable
        $editableOperations = [];
        if ($this->waitingForProtocolOperation) {
            $editableOperations[] = $this->waitingForProtocolOperation->id;
        }
        if ($this->protocolReprocessingOperation) {
            $editableOperations[] = $this->protocolReprocessingOperation->id;
        }

        $this->isEditable = $this->matchReport !== null &&
            in_array($this->match->current_operation_id, $editableOperations, true);

        // Load protocol requirements
        $this->protocolRequirements = MatchProtocolRequirement::whereIn('match_id', [$this->matchId])
            ->whereIn('judge_type_id', $this->judgeTypeIds)
            ->get();

        // Load report documents if exists
        if ($this->matchReport) {
            $this->reportDocuments = MatchReportDocument::where('match_report_id', $this->matchReport->id)
                ->where('judge_id', $userId)
                ->with(['file', 'match_protocol_requirement'])
                ->get()
                ->keyBy('requirement_id');
        }

        // Determine if can submit
        $this->canSubmit = $this->canSubmitForReview();
    }

    protected function canSubmitForReview(): bool
    {
        if (!$this->isEditable) {
            return false;
        }

        if ($this->protocolReviewOperation === null) {
            return false;
        }

        $userId = auth()->id();

        // Check if there's at least one document with is_accepted == null or true
        $hasValidDocuments = MatchReportDocument::where('match_report_id', $this->matchReport->id)
            ->where('judge_id', $userId)
            ->where(function ($query) {
                $query->whereNull('is_accepted')
                    ->orWhere('is_accepted', true);
            })
            ->exists();

        return $hasValidDocuments;
    }

    public function createReport(): void
    {
        $userId = auth()->id();

        // Check if report already exists
        $existingReport = MatchReport::where('match_id', $this->matchId)
            ->where('judge_id', $userId)
            ->first();

        if ($existingReport) {
            return;
        }

        DB::beginTransaction();
        try {
            $this->matchReport = MatchReport::create([
                'match_id' => $this->matchId,
                'judge_id' => $userId,
                'is_finished' => false,
                'is_accepted' => null,
            ]);

            $this->successMessage = __('ui.report_created_success');
            $this->isEditable = true;
            $this->reportDocuments = [];
            $this->canSubmit = false;

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = __('ui.report_create_failed') . ': ' . $e->getMessage();
        }
    }

    public function openUploadModal(int $requirementId): void
    {
        $this->uploadRequirementId = $requirementId;
        $this->uploadComment = '';
        $this->uploadedDocument = null;
        $this->showUploadModal = true;
    }

    public function closeUploadModal(): void
    {
        $this->showUploadModal = false;
        $this->uploadRequirementId = null;
        $this->uploadComment = '';
        $this->uploadedDocument = null;
    }

    public function uploadDocument(): void
    {
        // Debug logging
        \Log::info('uploadDocument called', [
            'uploadedDocument' => $this->uploadedDocument,
            'uploadedDocument type' => gettype($this->uploadedDocument),
            'uploadedDocument class' => is_object($this->uploadedDocument) ? get_class($this->uploadedDocument) : 'not an object',
            'matchReport exists' => $this->matchReport !== null,
        ]);

        // Skip validation if no file - show error manually
        if (!$this->uploadedDocument) {
            $this->errorMessage = 'Пожалуйста, выберите файл для загрузки.';
            return;
        }

        // Temporarily skip file validation to see what we're getting
        // $this->validate([
        //     'uploadedDocument' => 'file|max:10240', // max 10MB
        // ]);

        if (!$this->matchReport) {
            $this->errorMessage = __('ui.report_not_found');
            return;
        }

        $requirement = MatchProtocolRequirement::find($this->uploadRequirementId);
        if (!$requirement) {
            $this->errorMessage = __('ui.requirement_not_found');
            return;
        }

        // Validate file extensions if specified
        $extensions = $requirement->extensions ? json_decode($requirement->extensions, true) : null;
        $fileValidationOptions = null;

        if ($extensions && !empty($extensions)) {
            $fileValidationOptions = new FileValidationOptions(
                allowedExtensions: $extensions,
                maxSizeMB: 10
            );
        }

        DB::beginTransaction();
        try {
            $fileService = new FileService();

            $uploadedFile = $this->uploadedDocument;

            // Skip if file is null or invalid
            if (!$uploadedFile || !is_object($uploadedFile)) {
                throw new \Exception("No valid file uploaded");
            }

            // Check if file has isValid method
            if (!method_exists($uploadedFile, 'isValid')) {
                throw new \Exception("Uploaded file is not valid");
            }

            // Validate file is readable and has size
            if (!$uploadedFile->isValid()) {
                throw new \Exception("Uploaded file is not valid");
            }

            try {
                $fileSize = $uploadedFile->getSize();
                if ($fileSize === 0) {
                    throw new \Exception("Uploaded file is empty");
                }
            } catch (\Exception $sizeException) {
                throw new \Exception("Unable to get file size: " . $sizeException->getMessage());
            }

            $file = $fileService->save(
                $uploadedFile,
                'match_reports',
                $fileValidationOptions
            );

            // Create match report document
            MatchReportDocument::create([
                'match_report_id' => $this->matchReport->id,
                'file_id' => $file->id,
                'match_id' => $this->matchId,
                'requirement_id' => $this->uploadRequirementId,
                'judge_id' => auth()->id(),
                'comment' => $this->uploadComment ?: null,
                'is_accepted' => null,
            ]);

            $this->successMessage = __('ui.document_uploaded_success');
            $this->closeUploadModal();
            $this->loadData(); // Reload data

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = __('ui.document_upload_failed') . ': ' . $e->getMessage();
        }
    }

    public function deleteDocument(int $documentId): void
    {
        if (!$this->isEditable) {
            return;
        }

        $document = MatchReportDocument::where('id', $documentId)
            ->where('judge_id', auth()->id())
            ->first();

        if (!$document) {
            $this->errorMessage = __('ui.document_not_found');
            return;
        }

        // Can only delete if not yet reviewed (is_accepted == null)
        if ($document->is_accepted !== null) {
            $this->errorMessage = __('ui.document_cannot_delete');
            return;
        }

        DB::beginTransaction();
        try {
            $fileService = new FileService();
            $fileService->delete($document->file_id);
            $document->delete();

            $this->successMessage = __('ui.document_deleted_success');
            $this->loadData(); // Reload data

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = __('ui.document_delete_failed') . ': ' . $e->getMessage();
        }
    }

    public function submitForReview(): void
    {
        if (!$this->canSubmit) {
            $this->errorMessage = __('ui.cannot_submit_review');
            return;
        }
        $this->protocolReviewOperation = Operation::where('value', OperationConstants::PROTOCOL_REVIEW)->first();
        if (!$this->protocolReviewOperation) {
            $this->errorMessage = __('ui.operation_not_found');
            return;
        }

        DB::beginTransaction();
        try {
            // Create operation log
            MatchOperationLog::create([
                'match_id' => $this->matchId,
                'from_operation_id' => $this->match->current_operation_id,
                'to_operation_id' => $this->protocolReviewOperation->id,
                'performed_by_id' => auth()->id(),
                'comment' => __('ui.protocol_submitted'),
            ]);

            // Update match operation
            $this->match->update([
                'current_operation_id' => $this->protocolReviewOperation->id,
            ]);

            // Update report as finished
            $this->matchReport->update([
                'is_finished' => true,
            ]);

            $this->successMessage = __('ui.protocol_submitted_success');
            $this->isEditable = false;
            $this->canSubmit = false;

            // Reload match data
            $this->match = $this->match->fresh(['operation']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = __('ui.protocol_submit_failed') . ': ' . $e->getMessage();
        }
    }

    public function clearMessages(): void
    {
        $this->successMessage = '';
        $this->errorMessage = '';
    }

    public function render()
    {
        return view('livewire.referee.referee-protocol-detail', [
            'match' => $this->match,
            'matchReport' => $this->matchReport,
            'protocolRequirements' => $this->protocolRequirements,
            'reportDocuments' => $this->reportDocuments,
            'hasAccess' => $this->hasAccess,
            'isEditable' => $this->isEditable,
            'canSubmit' => $this->canSubmit,
        ]);
    }
}
