@php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $gradient_lite = 'from-green-500 to-teal-500';
@endphp

<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 border-t shadow-lg transition-colors duration-300"
     :class="darkMode ? 'bg-gray-900 border-gray-800' : 'bg-gradient-to-r {{ $gradient_lite }} border-white/10'">
    <div class="flex items-center h-16">
        <a href="{{ site_url() }}" class="flex-1 flex flex-col items-center justify-center text-white/80 hover:text-white transition-colors">
            <i class="fas fa-home text-lg"></i>
            <span class="text-[9px] font-bold uppercase mt-1">Beranda</span>
        </a>
        <a href="{{ site_url('peta') }}" class="flex-1 flex flex-col items-center justify-center text-white/80 hover:text-white transition-colors">
            <i class="fas fa-map-marked-alt text-lg"></i>
            <span class="text-[9px] font-bold uppercase mt-1">Peta</span>
        </a>
        <div class="flex-1 flex justify-center">
            <button @click="navOpen = !navOpen" 
                    class="w-14 h-14 -mt-6 rounded-full shadow-2xl border-4 flex items-center justify-center transform transition hover:scale-110"
                    :class="darkMode ? 'bg-gray-800 border-gray-900 text-teal-500' : 'bg-gradient-to-br from-green-600 to-teal-600 border-white/20 text-white'">
                <i class="fas fa-th-large text-xl"></i>
            </button>
        </div>
        <a href="{{ site_url('peraturan-desa') }}" class="flex-1 flex flex-col items-center justify-center text-white/80 hover:text-white transition-colors">
            <i class="fas fa-gavel text-lg"></i>
            <span class="text-[9px] font-bold uppercase mt-1">Hukum</span>
        </a>
        <a href="{{ site_url('galeri') }}" class="flex-1 flex flex-col items-center justify-center text-white/80 hover:text-white transition-colors">
            <i class="fas fa-images text-lg"></i>
            <span class="text-[9px] font-bold uppercase mt-1">Galeri</span>
        </a>
    </div>
</nav>