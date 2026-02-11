<div>
    @isset($jsPath)
        <script>{!! file_get_contents($jsPath) !!}</script>
    @endisset
    @isset($cssPath)
        <style>{!! file_get_contents($cssPath) !!}</style>
    @endisset

    <div
            x-data="LivewireUIModal()"
            x-on:close.stop="setShowPropertyTo(false)"
            x-on:keydown.escape.window="show && closeModalOnEscape()"
            x-show="show"
            class="fixed inset-0 z-10 overflow-y-auto"
            style="display: none;"
    >
        {{-- Centering wrapper --}}
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            {{-- Backdrop --}}
            <div
                    x-show="show"
                    x-on:click="closeModalOnClickAway()"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-all transform"
            >
                <div class="absolute inset-0" style="background: rgba(0,0,0,0.5);"></div>
            </div>

            {{-- Dialog --}}
            <div
                    x-show="show && showActiveComponent"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    x-bind:class="modalWidth"
                    class="w-full"
                    style="position: relative; background: var(--bg-card); box-shadow: var(--shadow-lg); color: var(--text-primary); border-radius: 0.75rem; overflow: hidden; max-height: calc(100vh - 2rem); display: flex; flex-direction: column;"
                    id="modal-container"
                    x-trap.noscroll.inert="show && showActiveComponent"
                    aria-modal="true"
            >
                <div style="overflow-y: auto; flex: 1;">
                    @forelse($components as $id => $component)
                        <div x-show.immediate="activeComponent == '{{ $id }}'" x-ref="{{ $id }}" wire:key="{{ $id }}">
                            @livewire($component['name'], $component['arguments'], key($id))
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
