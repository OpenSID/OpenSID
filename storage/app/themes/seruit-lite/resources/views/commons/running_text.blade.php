@php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $gradient_lite = 'from-green-500 to-teal-500';
@endphp

@if ($teks_berjalan)
    <div class="flex items-center rounded-none shadow-md text-white overflow-hidden" 
         :class="darkMode ? 'bg-gray-800 border border-gray-700' : 'bg-gradient-to-r {{ $gradient_lite }}'">
        
        <div class="flex-shrink-0 px-4 py-2 font-bold text-[10px] uppercase tracking-wider bg-black/20 flex items-center border-r border-white/10">
            <i class="fas fa-bullhorn mr-2"></i>
            <span>Info Terkini</span>
        </div>

        <div class="flex-1 relative overflow-hidden h-10 flex items-center">
            <marquee onmouseover="this.stop();" onmouseout="this.start();" scrollamount="4">
                <div class="flex items-center divide-x divide-white/10">
                    @foreach ($teks_berjalan as $marquee)
                        <span class="px-4 text-xs font-semibold uppercase tracking-tight">
                            {{ $marquee['teks'] }}
                            @if (trim($marquee['tautan']) && $marquee['judul_tautan'])
                                <a href="{{ $marquee['tautan'] }}" class="ml-2 text-yellow-300 hover:text-white underline decoration-dotted" target="_blank" rel="noopener noreferrer">
                                    [{{ $marquee['judul_tautan'] }}]
                                </a>
                            @endif
                        </span>
                    @endforeach
                </div>
            </marquee>
        </div>
    </div>
@endif