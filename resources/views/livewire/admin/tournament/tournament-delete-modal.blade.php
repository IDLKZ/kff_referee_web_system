<div>
    {{-- Header --}}
    <div class="px-6 py-4" style="border-bottom: 1px solid var(--border-color);">
        <h3 class="text-lg font-semibold" style="color: var(--text-primary);">
            {{ __('crud.confirm_delete') }}
        </h3>
    </div>

    {{-- Body --}}
    <div class="px-6 py-5">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center w-12 h-12 rounded-full mb-4"
                 style="background: var(--color-danger-light);">
                <svg class="w-6 h-6" style="color: var(--color-danger);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <p style="color: var(--text-secondary);">
                {{ __('crud.confirm_delete_text') }}
            </p>
            <p class="mt-2 font-semibold" style="color: var(--text-primary);">
                {{ $tournamentName }}
            </p>
        </div>
    </div>

    {{-- Footer --}}
    <div class="flex items-center justify-end gap-3 px-6 py-4"
         style="border-top: 1px solid var(--border-color);">
        <button wire:click="$dispatch('closeModal')" class="btn-secondary">
            {{ __('crud.cancel') }}
        </button>
        <button wire:click="delete" class="btn-danger" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="delete">{{ __('crud.yes_delete') }}</span>
            <span wire:loading wire:target="delete">{{ __('ui.loading') }}</span>
        </button>
    </div>
</div>
