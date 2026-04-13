@php
$menu_icons = [
    ['label' => 'Peta Desa', 'icon' => 'fa-map-marker-alt', 'url' => site_url('peta'), 'color' => 'bg-gradient-to-br from-green-500 to-teal-500'],
    ['label' => 'Produk Hukum', 'icon' => 'fa-gavel', 'url' => site_url('peraturan-desa'), 'color' => 'bg-gradient-to-br from-green-500 to-teal-500'],
    ['label' => 'Informasi Publik', 'icon' => 'fa-info-circle', 'url' => site_url('informasi-publik'), 'color' => 'bg-gradient-to-br from-green-500 to-teal-500'],
    ['label' => 'Lapak', 'icon' => 'fa-store', 'url' => site_url('lapak'), 'color' => 'bg-gradient-to-br from-green-500 to-teal-500'],
    ['label' => 'Arsip Berita', 'icon' => 'fa-archive', 'url' => site_url('arsip'), 'color' => 'bg-gradient-to-br from-green-500 to-teal-500'],
    ['label' => 'Album Galeri', 'icon' => 'fa-images', 'url' => site_url('galeri'), 'color' => 'bg-gradient-to-br from-green-500 to-teal-500'],
    ['label' => 'Pengaduan', 'icon' => 'fa-comments', 'url' => site_url('pengaduan'), 'color' => 'bg-gradient-to-br from-green-500 to-teal-500'],
    ['label' => 'Pembangunan', 'icon' => 'fa-cogs', 'url' => site_url('pembangunan'), 'color' => 'bg-gradient-to-br from-green-500 to-teal-500'],
    ['label' => 'Status IDM', 'icon' => 'fa-chart-line', 'url' => site_url('status-idm/2022'), 'color' => 'bg-gradient-to-br from-green-500 to-teal-500'],
];
@endphp

<div class="mt-12 w-full">
    <div class="grid grid-cols-3 md:grid-cols-5 lg:grid-cols-9 gap-3">
        @foreach ($menu_icons as $menu)
        <a href="{{ $menu['url'] }}" class="icon-menu-item {{ $menu['color'] }} dark:bg-none dark:bg-gray-800 transition-transform duration-200 hover:scale-105">
            <i class="fas {{ $menu['icon'] }}"></i>
            <span>{{ $menu['label'] }}</span>
        </a>
        @endforeach
    </div>
</div>