<?php
defined('BASEPATH') or exit('No direct script access allowed');

$gradient_class = 'from-green-500 to-teal-500';
?>
<div class="rounded-none shadow-lg overflow-hidden flex flex-col transition-colors duration-300" :class="darkMode ? 'bg-gray-800' : 'bg-gradient-to-br {{ $gradient_class }}'">
    <div class="p-4 border-b" :class="darkMode ? 'bg-gray-700/50 border-gray-700' : 'bg-white/10 border-white/20'">
        <h3 class="font-bold text-lg" :class="darkMode ? 'text-gray-100' : 'text-white'">
            <a href="{{ site_url('galeri') }}" class="hover:text-white transition-colors">
                <i class="fas fa-camera-retro fa-fw mr-3"></i>{{ $judul_widget }}
            </a>
        </h3>
    </div>

    @if (count($w_gal ?? []) > 0)
    <div class="p-4">
        <div class="grid grid-cols-3 gap-2">
            @foreach ($w_gal as $data)
            @if (is_file(LOKASI_GALERI . 'kecil_' . $data['gambar']))
            <a href='{{ site_url("galeri/{$data['slug']}") }}' title="Album: {{ e($data['nama']) }}" class="block relative overflow-hidden group">
                <img src="{{ AmbilGaleri($data['gambar'], 'kecil') }}" alt="{{ e($data['nama']) }}" class="w-full h-24 object-cover transition-transform duration-300 transform group-hover:scale-110">
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-2 text-center">
                    <span class="text-white text-xs font-semibold">{{ e($data['nama']) }}</span>
                </div>
            </a>
            @endif
            @endforeach
        </div>
    </div>
    @else
    <div class="p-4 text-center text-sm" :class="darkMode ? 'text-gray-400' : 'text-white/70'">
        Belum ada album galeri.
    </div>
    @endif
</div>