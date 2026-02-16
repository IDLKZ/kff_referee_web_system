@php $locale = app()->getLocale(); @endphp
@section('page-title', __('ui.protocol_detail'))

<div>
    {{-- Flash Messages --}}
    @if($successMessage)
        <div class="mb-4 p-4 rounded-lg flex items-center gap-3" style="background: var(--color-success-light); border: 1px solid var(--color-success);">
            <svg class="w-5 h-5 flex-shrink-0" style="color: var(--color-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <p style="color: var(--color-success);">{{ $successMessage }}</p>
            <button wire:click="clearMessages" class="p-1 rounded-md hover:opacity-70" style="color: var(--color-success);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    @if($errorMessage)
        <div class="mb-4 p-4 rounded-lg flex items-center gap-3" style="background: var(--color-danger-light); border: 1px solid var(--color-danger);">
            <svg class="w-5 h-5 flex-shrink-0" style="color: var(--color-danger);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p style="color: var(--color-danger);">{{ $errorMessage }}</p>
            <button wire:click="clearMessages" class="p-1 rounded-md hover:opacity-70" style="color: var(--color-danger);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="card p-6 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('referee.referee-protocol-management') }}"
               class="p-2 rounded-lg hover:bg-opacity-80 transition-colors"
               style="background: var(--color-primary-light);">
                <svg class="w-5 h-5" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold" style="color: var(--text-primary);">
                    {{ __('ui.protocol_detail') }}
                </h1>
                @if($match)
                    <p class="text-sm mt-1" style="color: var(--text-muted);">
                        {{ $match->ownerClub->{'short_name_' . $locale} ?? '—' }}
                        {{ __('crud.vs') }}
                        {{ $match->guestClub->{'short_name_' . $locale} ?? '—' }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    @if($match)
        {{-- Match Info Card --}}
        <div class="card p-6 mb-6">
            <div class="flex flex-wrap items-center gap-2 mb-4">
                @if($match->tournament)
                    <span class="badge badge-info">{{ $match->tournament->{'title_' . $locale} }}</span>
                @endif
                @if($match->season)
                    <span class="badge badge-purple">{{ $match->season->{'title_' . $locale} }}</span>
                @endif
                @if($match->round)
                    <span class="badge badge-secondary">{{ __('crud.round') }}: {{ $match->round }}</span>
                @endif
                @if($match->start_at)
                    <span class="badge badge-secondary">{{ $match->start_at->format('d.m.Y H:i') }}</span>
                @endif
                @if($match->operation)
                    <span class="badge badge-warning">{{ $match->operation->{'title_' . $locale} }}</span>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-base font-semibold mb-2" style="color: var(--text-primary);">
                        {{ $match->ownerClub->{'short_name_' . $locale} ?? '—' }}
                    </h3>
                    <div class="flex flex-wrap gap-3 text-sm" style="color: var(--text-secondary);">
                        @if($match->city)
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $match->city->{'title_' . $locale} }}
                            </span>
                        @endif
                        @if($match->stadium)
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                </svg>
                                {{ $match->stadium->{'title_' . $locale} }}
                            </span>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-base font-semibold mb-2" style="color: var(--text-primary);">
                        {{ __('crud.vs') }}
                    </h3>
                    <h3 class="text-base font-semibold mb-2" style="color: var(--text-primary);">
                        {{ $match->guestClub->{'short_name_' . $locale} ?? '—' }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- Report Status --}}
        @if($matchReport)
            <div class="card p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                    {{ __('ui.report_status') }}
                </h2>
                <div class="flex flex-wrap gap-3">
                    @if($matchReport->is_finished)
                        <span class="badge badge-success">{{ __('crud.report_submitted') }}</span>
                    @else
                        <span class="badge badge-warning">{{ __('crud.report_not_submitted') }}</span>
                    @endif

                    @if($matchReport->is_accepted === true)
                        <span class="badge badge-success">{{ __('crud.report_accepted') }}</span>
                    @elseif($matchReport->is_accepted === false)
                        <span class="badge badge-danger">{{ __('crud.report_rejected') }}</span>
                    @else
                        <span class="badge badge-secondary">{{ __('crud.report_pending_review') }}</span>
                    @endif
                </div>

                @if($matchReport->final_comment)
                    <p class="text-sm mt-3" style="color: var(--text-muted);">
                        <strong>{{ __('crud.final_comment') }}:</strong> {{ $matchReport->final_comment }}
                    </p>
                @endif
            </div>
        @endif

        {{-- Protocol Requirements Section --}}
        @if($protocolRequirements->isNotEmpty())
            <div class="card p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold" style="color: var(--text-primary);">
                        {{ __('ui.protocol_requirements') }}
                    </h2>

                    {{-- Create Report Button --}}
                    @if(!$matchReport)
                        <button wire:click="createReport"
                                class="btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('ui.create_report') }}
                        </button>
                    @endif
                </div>

                {{-- Requirements Grid --}}
                <div class="space-y-4">
                    @foreach($protocolRequirements as $requirement)
                        @php
                            $document = $reportDocuments[$requirement->id] ?? null;
                            $isUploadable = $isEditable && $matchReport;
                        @endphp

                        <div class="p-4 rounded-lg" style="background: var(--bg-body); border: 1px solid var(--border-color);">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="font-semibold" style="color: var(--text-primary);">
                                            {{ $requirement->{'title_' . $locale} }}
                                        </h3>
                                        @if($requirement->is_required)
                                            <span class="badge badge-danger">{{ __('crud.required_document') }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __('crud.optional_document') }}</span>
                                        @endif
                                    </div>

                                    @if($requirement->{'info_' . $locale})
                                        <p class="text-sm mb-2" style="color: var(--text-muted);">
                                            {{ $requirement->{'info_' . $locale} }}
                                        </p>
                                    @endif

                                    @if($requirement->extensions && !empty(json_decode($requirement->extensions)))
                                        <div class="flex flex-wrap gap-1 mb-2">
                                            @foreach(json_decode($requirement->extensions) as $ext)
                                                <span class="text-xs px-2 py-0.5 rounded" style="background: var(--bg-hover); color: var(--text-muted);">
                                                    {{ $ext }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- Document Display or Upload Button --}}
                                    @if($document)
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('files.download', ['id' => $document->file_id]) }}"
                                               target="_blank"
                                               class="link flex items-center gap-2 text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                {{ $document->file->filename }}
                                            </a>

                                            @if($document->comment)
                                                <span class="text-sm" style="color: var(--text-muted);">
                                                    {{ $document->comment }}
                                                </span>
                                            @endif

                                            {{-- Delete button for editable, unreviewed documents --}}
                                            @if($isUploadable && $document->is_accepted === null)
                                                <button wire:click="deleteDocument({{ $document->id }})"
                                                        class="p-1 rounded hover:bg-opacity-70 transition-colors"
                                                        style="color: var(--color-danger);">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    @elseif($isUploadable)
                                        <button wire:click="openUploadModal({{ $requirement->id }})"
                                                class="btn-secondary text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m0-6v6m0 0L8 8m0-6l-4-4m12 0l-4-4m0 0L8 8m0-6v6m0 0L8 8"/>
                                            </svg>
                                            {{ __('ui.upload_document') }}
                                        </button>
                                    @else
                                        <span class="text-sm" style="color: var(--text-muted);">
                                            {{ __('ui.no_document') }}
                                        </span>
                                    @endif

                                    {{-- Document Status Badge --}}
                                    @if($document && $document->is_accepted !== null)
                                        <div class="mt-2">
                                            @if($document->is_accepted)
                                                <span class="badge badge-success">{{ __('crud.report_accepted') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ __('crud.report_rejected') }}</span>
                                                @if($document->final_comment)
                                                    <p class="text-xs mt-1" style="color: var(--text-muted);">
                                                        {{ $document->final_comment }}
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Submit for Review Button --}}
                @if($isEditable && $canSubmit)
                    <div class="mt-6 pt-4 flex justify-end" style="border-top: 1px solid var(--border-color);">
                        <button wire:click="submitForReview"
                                class="btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            {{ __('ui.submit_for_review') }}
                        </button>
                    </div>
                @endif
            </div>
        @endif

        {{-- No Requirements Message --}}
        @if($protocolRequirements->isEmpty())
            <div class="card p-12 text-center">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                     style="background: var(--bg-hover);">
                    <svg class="w-8 h-8" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="font-medium mb-1" style="color: var(--text-secondary);">
                    {{ __('ui.no_protocol_requirements') }}
                </p>
                <p class="text-sm" style="color: var(--text-muted);">
                    {{ __('ui.no_protocol_requirements_hint') }}
                </p>
            </div>
        @endif
    @endif

    {{-- Upload Document Modal --}}
    <x-modal wire:model="showUploadModal" maxWidth="md">
        <x-slot name="title">{{ __('ui.upload_document') }}</x-slot>

        <div class="space-y-4">
            <div>
                <label class="form-label">{{ __('crud.file') }}</label>
                <input type="file"
                       wire:model="uploadedDocument"
                       class="form-input"
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.jpg,.jpeg,.png,.gif,.webp,.svg">
                @error('uploadedDocument')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('crud.comment') }}</label>
                <textarea wire:model="uploadComment"
                          class="form-input"
                          rows="3"
                          placeholder="{{ __('ui.upload_comment_placeholder') }}"></textarea>
            </div>
        </div>

        <x-slot name="footer">
            <button type="button" wire:click="closeUploadModal" class="btn-secondary">{{ __('crud.cancel') }}</button>
            <button type="button" wire:click="uploadDocument" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ __('crud.upload') }}</span>
                <span wire:loading>Загрузка...</span>
            </button>
        </x-slot>
    </x-modal>
</div>
