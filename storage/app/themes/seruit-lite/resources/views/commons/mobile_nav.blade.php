<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$gradient_class = 'from-green-500 to-teal-500';

$menu_utama = menu_tema() ?? [];
$batas_tampil = 5;
$menu_tampil = array_slice($menu_utama, 0, $batas_tampil);
$menu_overflow = array_slice($menu_utama, $batas_tampil);

if (count($menu_overflow) > 0) {
    $menu_tampil[] = [
        'nama'      => 'Lainnya',
        'link_url'  => '#!',
        'childrens' => $menu_overflow
    ];
}

$_t1 = base64_decode('VGVtYSBTZXJ1aXQgTGl0ZQ==');
$_t2 = base64_decode('VmVyc2kg');
$_t3 = base64_decode('QmVsaSBTZXJ1aXQgUFJP');
$_t4 = base64_decode('aHR0cHM6Ly9vcGVuZGVzYS5pZC90ZW1hLXByby1vcGVuc2lkLw==');
?>

<div
    x-show="navOpen"
    x-cloak
    @keydown.escape.window="navOpen = false"
    class="fixed inset-0 z-[1002] overflow-hidden"
    role="dialog"
    aria-modal="true"
>
    <div 
        x-show="navOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="navOpen = false"
        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
    ></div>

    <div 
        x-show="navOpen"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="relative w-4/5 max-w-xs h-full shadow-2xl flex flex-col text-white"
        :class="darkMode ? 'bg-gray-900' : 'bg-gradient-to-b {{ $gradient_class }}'"
    >
        <div class="p-6 border-b border-white/10 flex flex-col items-center text-center">
            <div class="flex justify-between w-full mb-4">
                <span></span>
                <button @click="navOpen = false" class="text-white/70 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <img src="{{ gambar_desa($desa['logo']) }}" alt="Logo" class="h-16 w-16 object-contain bg-white/20 p-1 rounded-full mb-3">
            <h3 class="font-bold text-base leading-tight">
                {{ ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) }}
            </h3>
            <p class="text-xs text-white/70 mt-1 uppercase tracking-wider">
                Kec. {{ ucwords($desa['nama_kecamatan']) }}
            </p>
        </div>

        <nav class="flex-grow overflow-y-auto p-4 custom-scrollbar">
            <form action="{{ site_url('/') }}" method="get" class="mb-6">
                <input type="hidden" name="{{ get_instance()->security->get_csrf_token_name() }}" value="{{ get_instance()->security->get_csrf_hash() }}">
                <div class="relative">
                    <input type="text" name="cari" class="w-full py-2 pl-3 pr-10 text-sm rounded-none border-white/20 bg-white/10 text-white placeholder-white/50 focus:ring-1 focus:ring-white" placeholder="Cari...">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center justify-center w-10 text-white/70">
                        <i class="fas fa-search text-sm"></i>
                    </button>
                </div>
            </form>

            <div class="grid grid-cols-3 gap-2 mb-6">
                <a href="{{ site_url('siteman') }}" class="flex flex-col items-center justify-center p-2 bg-white/10 hover:bg-white/20 transition text-center">
                    <i class="fas fa-user-shield mb-1 text-yellow-400"></i>
                    <span class="text-[9px] font-bold uppercase leading-none">Admin</span>
                </a>
                @if (setting('layanan_mandiri') == 1)
                <a href="{{ site_url('layanan-mandiri/masuk') }}" class="flex flex-col items-center justify-center p-2 bg-white/10 hover:bg-white/20 transition text-center">
                    <i class="fas fa-id-card mb-1 text-cyan-400"></i>
                    <span class="text-[9px] font-bold uppercase leading-none">Layanan</span>
                </a>
                @endif
                <a href="{{ site_url('kehadiran/masuk') }}" class="flex flex-col items-center justify-center p-2 bg-white/10 hover:bg-white/20 transition text-center">
                    <i class="fas fa-fingerprint mb-1 text-green-400"></i>
                    <span class="text-[9px] font-bold uppercase leading-none">Absensi</span>
                </a>
            </div>

            <ul class="space-y-1">
                @foreach ($menu_tampil as $menu)
                    @include('theme::partials.menu_item_mobile', ['menu' => $menu])
                @endforeach
            </ul>
        </nav>

        <div class="p-5 bg-black/20 text-center border-t border-white/10">
            <p class="text-[10px] uppercase tracking-widest text-white/60 font-bold">{{ $_t1 }}</p>
            <p class="text-[9px] text-white/40 mt-0.5">{{ $_t2 . ($themeVersion ?? '3.0.0') }}</p>
            <div class="mt-3">
                <a href="{{ $_t4 }}" target="_blank" rel="noopener noreferrer" class="inline-block px-4 py-2 bg-yellow-400 text-gray-900 text-[10px] font-extrabold uppercase tracking-tighter shadow-lg hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-shopping-cart mr-1"></i> {{ $_t3 }}
                </a>
            </div>
        </div>
    </div>
</div>