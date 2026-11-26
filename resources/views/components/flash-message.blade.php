@props(['inline' => false])

@php
    $wrapperClass = $inline
        ? 'mb-6 space-y-2'
        : 'fixed top-20 right-4 z-50 max-w-sm w-full space-y-2 pointer-events-none';
@endphp

@if(session('success') || session('status') || session('error') || $errors->any())
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" class="{{ $wrapperClass }}">
        @if(session('success') || session('status'))
            <div class="pointer-events-auto flex items-start justify-between gap-3 bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-xl shadow-md text-sm">
                <span>{{ session('success') ?? session('status') }}</span>
                <button type="button" @click="show = false" class="text-emerald-700 font-bold leading-none">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="pointer-events-auto flex items-start justify-between gap-3 bg-red-50 border border-red-200 text-red-900 px-4 py-3 rounded-xl shadow-md text-sm">
                <span>{{ session('error') }}</span>
                <button type="button" @click="show = false" class="text-red-700 font-bold leading-none">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="pointer-events-auto flex items-start justify-between gap-3 bg-red-50 border border-red-200 text-red-900 px-4 py-3 rounded-xl shadow-md text-sm">
                <div>
                    <p class="font-bold mb-1">Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" @click="show = false" class="text-red-700 font-bold leading-none">&times;</button>
            </div>
        @endif
    </div>
@endif
