@props([
    'name' => 'modal',
    'title' => '',
    'maxWidth' => 'max-w-lg',
])

<div x-data="{ open: false }"
     x-on:open-modal.window="if ($event.detail === '{{ $name }}') open = true"
     x-on:close-modal.window="if (!$event.detail || $event.detail === '{{ $name }}') open = false"
     x-on:keydown.escape.window="open = false"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    <div @click="open = false" class="fixed inset-0 bg-black/50 backdrop-blur-xs"></div>

    <div @click.stop class="relative w-full {{ $maxWidth }} bg-white rounded-2xl border border-[#C9CEDF] shadow-2xl overflow-hidden z-10">
        @if($title)
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-lg text-[#171512]">{{ $title }}</h3>
                <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-700 text-xl leading-none cursor-pointer">&times;</button>
            </div>
        @endif

        <div class="p-6 max-h-[85vh] overflow-y-auto">
            {{ $slot }}
        </div>
    </div>
</div>
