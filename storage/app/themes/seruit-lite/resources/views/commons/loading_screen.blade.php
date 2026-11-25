@php
    defined('BASEPATH') OR exit('No direct script access allowed');
@endphp

<div
    x-show="isLoading"
    x-transition:leave="transition ease-in duration-500"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-white dark:bg-gray-900"
    x-cloak
>
    <div class="relative flex flex-col items-center">
        <div class="w-20 h-20 mb-6 relative">
            <div class="absolute inset-0 rounded-full border-4 border-gray-100 dark:border-gray-800"></div>
            <div class="absolute inset-0 rounded-full border-4 border-t-teal-500 animate-spin"></div>
            <img class="absolute inset-0 m-auto h-10 w-10 object-contain" src="{{ gambar_desa($desa['logo']) }}" alt="Logo">
        </div>
        
        <div class="text-center">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white uppercase tracking-tight">
                {{ ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) }}
            </h2>
            <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase tracking-[0.2em] mt-1">
                Memuat Halaman...
            </p>
        </div>
    </div>

    <div class="absolute bottom-10 text-center">
        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest">
            Seruit Lite <span class="mx-1 text-gray-300">|</span> Versi {{ $themeVersion ?? '3.0.0' }}
        </p>
    </div>
</div>