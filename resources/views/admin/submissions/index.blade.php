@extends('layouts.admin')

@section('title', 'Pengajuan Naskah | Admin Mega Press')
@section('breadcrumb', 'Pengajuan Naskah')
@section('page_title', 'Daftar Pengajuan Naskah')
@section('page_description', 'Kelola naskah yang dikirim calon penulis dari halaman utama.')

@section('page_actions')
    <a href="{{ route('admin.submissions.export', request()->query()) }}"
       class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold uppercase tracking-wider">
        Ekspor Excel
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <form method="GET" action="{{ route('admin.submissions.index') }}" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, penulis, atau email..." class="flex-1 h-10 rounded-xl border border-gray-200 px-4 text-sm">
        <select name="status" class="h-10 rounded-xl border border-gray-200 px-3 text-sm">
            <option value="all">Semua Status</option>
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 h-10 rounded-xl bg-[#171512] text-white text-xs font-bold uppercase cursor-pointer">Filter</button>
    </form>

    <div class="space-y-4">
        @forelse($submissions as $sub)
            <div class="border border-gray-200 rounded-2xl p-5 space-y-3 bg-white">
                <div class="flex flex-wrap justify-between items-start gap-4">
                    <div>
                        <h3 class="font-bold text-lg text-[#171512]">{{ $sub->title }}</h3>
                        <p class="text-xs text-gray-500">
                            Penulis: <strong>{{ $sub->author_name }}</strong> &middot; {{ $sub->email }} &middot; {{ $sub->phone }}
                            @if($sub->genre) &middot; Genre: {{ $sub->genre }} @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.submissions.download', $sub) }}" class="px-3.5 py-1.5 rounded-lg border border-gray-200 text-xs font-bold hover:bg-gray-50">
                            Unduh File Naskah
                        </a>
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-[#F7F09E] text-amber-950">
                            {{ $statuses[$sub->status] ?? $sub->status }}
                        </span>
                    </div>
                </div>

                <p class="text-sm text-gray-600 bg-[#FAF8F4] p-3 rounded-xl">{{ $sub->synopsis }}</p>

                <form method="POST" action="{{ route('admin.submissions.status', $sub) }}" class="flex flex-wrap gap-2 items-center pt-1">
                    @csrf @method('PATCH')
                    <select name="status" class="h-9 rounded-lg border border-gray-200 px-3 text-xs bg-white">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" @selected($sub->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="admin_notes" value="{{ $sub->admin_notes }}" placeholder="Catatan kurasi..." class="flex-1 min-w-[200px] h-9 rounded-lg border border-gray-200 px-3 text-xs">
                    <button type="submit" class="px-4 h-9 rounded-lg bg-[#DB8B00] text-white text-xs font-bold cursor-pointer">Update Status</button>
                </form>
            </div>
        @empty
            <p class="py-12 text-center text-gray-400">Belum ada pengajuan naskah.</p>
        @endforelse
    </div>
    <div>{{ $submissions->links() }}</div>
</div>
@endsection
