@php
    $groups = [
        [
            'title' => 'OVERVIEW',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'match' => 'admin.dashboard'],
            ],
        ],
        [
            'title' => 'PESANAN & STOK',
            'items' => [
                ['label' => 'Pesanan WA', 'route' => 'admin.orders.index', 'match' => 'admin.orders.*'],
                ['label' => 'Penyesuaian & Riwayat Stok', 'route' => 'admin.stock.index', 'match' => 'admin.stock.*'],
            ],
        ],
        [
            'title' => 'MANAJEMEN BUKU',
            'items' => [
                ['label' => 'Katalog Buku', 'route' => 'admin.books.index', 'match' => 'admin.books.*'],
                ['label' => 'Kategori & Genre', 'route' => 'admin.categories.index', 'match' => 'admin.categories.*'],
                ['label' => 'Penulis', 'route' => 'admin.authors.index', 'match' => 'admin.authors.*'],
                ['label' => 'Penerbit', 'route' => 'admin.publishers.index', 'match' => 'admin.publishers.*'],
            ],
        ],
        [
            'title' => 'BLOG & KONTEN',
            'items' => [
                ['label' => 'Artikel Blog', 'route' => 'admin.blog.posts.index', 'match' => 'admin.blog.posts.*'],
                ['label' => 'Kategori & Tag Blog', 'route' => 'admin.blog.taxonomy.index', 'match' => 'admin.blog.taxonomy.*'],
            ],
        ],
        [
            'title' => 'PENERBITAN & PEMASARAN',
            'items' => [
                ['label' => 'Pengajuan Naskah', 'route' => 'admin.submissions.index', 'match' => 'admin.submissions.*'],
                ['label' => 'Newsletter', 'route' => 'admin.newsletter.index', 'match' => 'admin.newsletter.*'],
                ['label' => 'Ulasan Buku', 'route' => 'admin.reviews.index', 'match' => 'admin.reviews.*'],
            ],
        ],
        [
            'title' => 'PELANGGAN & LAPORAN',
            'items' => [
                ['label' => 'Data Pelanggan', 'route' => 'admin.customers.index', 'match' => 'admin.customers.*'],
                ['label' => 'Laporan Penjualan', 'route' => 'admin.reports.index', 'match' => 'admin.reports.*'],
                ['label' => 'Profil Admin', 'route' => 'admin.profile.show', 'match' => 'admin.profile.*'],
            ],
        ],
    ];
@endphp

@foreach($groups as $group)
    <div>
        <p x-show="sidebarOpen" class="px-3 mb-2 font-bold text-[10px] uppercase tracking-wider text-gray-400">
            {{ $group['title'] }}
        </p>
        <ul class="space-y-1">
            @foreach($group['items'] as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <li>
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 py-2 px-3 text-sm rounded-xl transition-all {{ $active ? 'bg-[#F7F09E] text-amber-950 font-semibold shadow-xs' : 'text-gray-600 hover:bg-white/70 hover:text-[#171512]' }}">
                        <span class="size-2 rounded-full shrink-0 {{ $active ? 'bg-amber-700' : 'bg-gray-300' }}"></span>
                        <span x-show="sidebarOpen" class="truncate">{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endforeach
