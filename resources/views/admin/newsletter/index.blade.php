@extends('layouts.admin')

@section('title', 'Newsletter | Admin Mega Press')
@section('breadcrumb', 'Newsletter')
@section('page_title', 'Manajemen Newsletter')

@section('page_actions')
    <a href="{{ route('admin.newsletter.export') }}" class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold uppercase tracking-wider">
        Ekspor Excel Subscribers
    </a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <div class="lg:col-span-5 space-y-6">
        <form method="POST" action="{{ route('admin.newsletter.broadcast') }}" class="bg-[#FAF8F4] p-6 rounded-2xl border border-gray-100 space-y-4">
            @csrf
            <h2 class="font-bold text-base">Buat Pesan Broadcast</h2>
            <x-input name="subject" label="Subjek Email" required />
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-1">Konten Pesan *</label>
                <textarea name="content" rows="5" required class="w-full rounded-xl border border-[#DDD8C8] bg-white px-3 py-2 text-sm"></textarea>
            </div>
            <x-button type="submit" variant="primary" class="w-full">Catat &amp; Kirim Broadcast</x-button>
        </form>

        <div class="border border-gray-100 rounded-2xl p-5">
            <h3 class="font-bold text-xs uppercase tracking-wider text-gray-400 mb-3">Riwayat Broadcast</h3>
            @forelse($messages as $msg)
                <div class="py-2.5 border-b border-gray-100 last:border-0 text-xs">
                    <p class="font-bold text-[#171512]">{{ $msg->subject }}</p>
                    <p class="text-gray-400">{{ $msg->sent_at?->format('d M Y H:i') }} &middot; {{ $msg->total_sent }} penerima</p>
                </div>
            @empty
                <p class="text-xs text-gray-400">Belum ada broadcast.</p>
            @endforelse
        </div>
    </div>

    <div class="lg:col-span-7 space-y-4">
        <h2 class="font-bold text-base">Daftar Subscriber ({{ $subscribers->total() }})</h2>
        <div class="border border-gray-100 rounded-2xl divide-y divide-gray-100">
            @forelse($subscribers as $sub)
                <div class="p-3.5 flex justify-between items-center text-sm">
                    <div>
                        <p class="font-semibold">{{ $sub->email }}</p>
                        <p class="text-xs text-gray-400">{{ $sub->status }} &middot; {{ optional($sub->subscribed_at ?? $sub->created_at)->format('d M Y') }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.newsletter.subscribers.destroy', $sub) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 font-bold cursor-pointer">Hapus</button>
                    </form>
                </div>
            @empty
                <p class="p-8 text-center text-gray-400">Belum ada subscriber.</p>
            @endforelse
        </div>
        <div>{{ $subscribers->links() }}</div>
    </div>
</div>
@endsection
