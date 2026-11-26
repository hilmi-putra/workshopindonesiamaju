<div x-data="{
        open: false,
        submitting: false,
        title: 'Konfirmasi Tindakan',
        message: 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
        itemName: '',
        confirmText: 'Ya, Lanjutkan',
        cancelText: 'Batal',
        variant: 'danger',
        targetForm: null,
        openModal(detail) {
            this.targetForm = detail.form || null;
            this.title = detail.title || 'Konfirmasi Tindakan';
            this.message = detail.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
            this.itemName = detail.itemName || '';
            this.confirmText = detail.confirmText || 'Ya, Lanjutkan';
            this.cancelText = detail.cancelText || 'Batal';
            this.variant = detail.variant || 'danger';
            this.submitting = false;
            this.open = true;
            this.$nextTick(() => this.$refs.confirmBtn?.focus());
        },
        closeModal() {
            if (this.submitting) return;
            this.open = false;
            this.targetForm = null;
        },
        confirmAction() {
            if (this.submitting) return;
            this.submitting = true;
            if (this.targetForm) {
                this.targetForm.dataset.confirmed = 'true';
                this.targetForm.submit();
            } else {
                this.open = false;
                this.submitting = false;
            }
        }
    }"
    x-on:open-confirm-modal.window="openModal($event.detail)"
    x-on:keydown.escape.window="if (open) closeModal()"
    x-show="open"
    x-cloak
    role="dialog"
    aria-modal="true"
    aria-labelledby="confirm-modal-title"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4">

    {{-- Backdrop --}}
    <div x-show="open"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeModal()"
         class="fixed inset-0 bg-[#141738]/55 backdrop-blur-xs"></div>

    {{-- Modal Panel --}}
    <div x-show="open"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-3"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-3"
         @click.stop
         class="relative z-10 w-full max-w-md bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">

        {{-- Top Accent Strip --}}
        <div class="h-1.5 w-full"
             :class="{
                 'bg-red-600': variant === 'danger',
                 'bg-amber-500': variant === 'warning',
                 'bg-[#2B3288]': variant === 'primary'
             }"></div>

        <button type="button"
                @click="closeModal()"
                :disabled="submitting"
                aria-label="Tutup modal"
                class="absolute top-4 right-4 size-8 rounded-full text-gray-400 hover:text-gray-700 hover:bg-gray-100 flex items-center justify-center transition-colors cursor-pointer disabled:opacity-40">
            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="p-6 sm:p-7 text-center">
            {{-- Icon Badge --}}
            <div class="mx-auto mb-5 size-16 rounded-2xl flex items-center justify-center ring-8"
                 :class="{
                     'bg-red-50 text-red-600 ring-red-50/70 border border-red-100': variant === 'danger',
                     'bg-amber-50 text-amber-600 ring-amber-50/70 border border-amber-100': variant === 'warning',
                     'bg-[#2B3288]/10 text-[#2B3288] ring-[#2B3288]/5 border border-[#2B3288]/15': variant === 'primary'
                 }">
                {{-- Danger Icon (Trash) --}}
                <svg x-show="variant === 'danger'" class="size-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                </svg>
                {{-- Warning Icon --}}
                <svg x-show="variant === 'warning'" class="size-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                </svg>
                {{-- Primary Icon (Shield Check / Info) --}}
                <svg x-show="variant === 'primary'" class="size-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                </svg>
            </div>

            <h3 id="confirm-modal-title" class="text-xl font-bold text-[#171512] mb-2" x-text="title"></h3>
            <p class="text-sm text-gray-500 leading-relaxed" x-text="message"></p>

            <div x-show="itemName" class="mt-3.5 px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-200/80 text-xs font-bold text-[#171512] break-words" x-text="itemName"></div>

            <div class="mt-6 flex flex-col-reverse sm:flex-row gap-2.5">
                <button type="button"
                        @click="closeModal()"
                        :disabled="submitting"
                        class="flex-1 h-11 px-5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-sm font-semibold text-gray-700 transition-colors cursor-pointer disabled:opacity-50"
                        x-text="cancelText"></button>
                <button type="button"
                        x-ref="confirmBtn"
                        @click="confirmAction()"
                        :disabled="submitting"
                        class="flex-1 h-11 px-5 rounded-xl text-sm font-bold text-white shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60"
                        :class="{
                            'bg-red-600 hover:bg-red-700 shadow-red-600/20': variant === 'danger',
                            'bg-amber-600 hover:bg-amber-700 shadow-amber-600/20': variant === 'warning',
                            'bg-[#2B3288] hover:bg-[#20266E] shadow-[#2B3288]/25': variant === 'primary'
                        }">
                    <svg x-show="submitting" class="size-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span x-text="submitting ? 'Memproses...' : confirmText"></span>
                </button>
            </div>
        </div>
    </div>
</div>

@once
    <script>
        if (!window.__wimConfirmModalBound) {
            window.__wimConfirmModalBound = true;
            document.addEventListener('submit', function (event) {
                const form = event.target;
                if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-confirm')) {
                    return;
                }
                if (form.dataset.confirmed === 'true') {
                    delete form.dataset.confirmed;
                    return;
                }
                event.preventDefault();
                event.stopPropagation();
                window.dispatchEvent(new CustomEvent('open-confirm-modal', {
                    detail: {
                        form: form,
                        title: form.dataset.confirmTitle || 'Konfirmasi Tindakan',
                        message: form.dataset.confirmMessage || 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
                        itemName: form.dataset.confirmItem || '',
                        confirmText: form.dataset.confirmButton || 'Ya, Lanjutkan',
                        cancelText: form.dataset.confirmCancel || 'Batal',
                        variant: form.dataset.confirmVariant || 'danger',
                    }
                }));
            }, true);
        }
    </script>
@endonce
