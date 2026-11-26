@props(['inline' => false])

@php
    $wrapperClass = $inline
        ? 'mb-6 space-y-3'
        : 'fixed top-20 right-4 z-50 max-w-sm w-full space-y-3 pointer-events-none';
@endphp

@if(session('success') || session('status') || session('error') || $errors->any())
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 6000)"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
         class="{{ $wrapperClass }}">
        @if(session('success') || session('status'))
            <div class="pointer-events-auto flex items-start gap-3.5 bg-white border border-emerald-200 border-l-4 border-l-emerald-500 text-gray-800 px-4 py-3.5 rounded-2xl shadow-lg shadow-emerald-950/5 text-sm">
                <div class="size-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-emerald-900 text-xs uppercase tracking-wider mb-0.5">Berhasil</p>
                    <p class="text-sm text-gray-700 leading-snug">{{ session('success') ?? session('status') }}</p>
                </div>
                <button type="button" @click="show = false" aria-label="Tutup notifikasi" class="p-1 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors cursor-pointer">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="pointer-events-auto flex items-start gap-3.5 bg-white border border-red-200 border-l-4 border-l-red-600 text-gray-800 px-4 py-3.5 rounded-2xl shadow-lg shadow-red-950/5 text-sm">
                <div class="size-8 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-red-900 text-xs uppercase tracking-wider mb-0.5">Gagal</p>
                    <p class="text-sm text-gray-700 leading-snug">{{ session('error') }}</p>
                </div>
                <button type="button" @click="show = false" aria-label="Tutup notifikasi" class="p-1 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors cursor-pointer">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="pointer-events-auto flex items-start gap-3.5 bg-white border border-red-200 border-l-4 border-l-red-600 text-gray-800 px-4 py-3.5 rounded-2xl shadow-lg shadow-red-950/5 text-sm">
                <div class="size-8 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-red-900 text-xs uppercase tracking-wider mb-1">Periksa Kembali Input</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-gray-700">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" @click="show = false" aria-label="Tutup notifikasi" class="p-1 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors cursor-pointer">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif
    </div>
@endif
