@php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $gradient_lite = 'from-green-500 to-teal-500';
@endphp

<div 
    x-data="{ showBackToTop: false }"
    @scroll.window="showBackToTop = (window.pageYOffset > 400)"
>
    <button
        x-show="showBackToTop"
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="p-3 rounded-none text-white shadow-2xl transition-all duration-300 hover:scale-110 flex items-center justify-center border"
        :class="darkMode ? 'bg-gray-800 border-teal-500/50 text-teal-500' : 'bg-gradient-to-br {{ $gradient_lite }} border-white/20 text-white'"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        x-cloak
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
        </svg>
    </button>
</div>