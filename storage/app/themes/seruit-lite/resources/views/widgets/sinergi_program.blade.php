@php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $sinergi_program = sinergi_program();
@endphp

<div class="bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
    <div class="p-4 bg-gradient-to-r from-green-500 to-teal-500 text-white">
        <h3 class="font-bold text-sm uppercase tracking-wider flex items-center">
            <i class="fas fa-handshake mr-3"></i>{{ $judul_widget }}
        </h3>
    </div>

    <div class="p-4">
        @if (count($sinergi_program) > 0)
            <div class="grid grid-cols-2 gap-3">
                @foreach ($sinergi_program as $program)
                    <a href="{{ e($program['tautan']) }}" target="_blank" rel="noopener noreferrer" class="block p-2 border border-gray-100 dark:border-gray-700 hover:border-teal-500 transition-colors bg-gray-50 dark:bg-gray-900/50">
                        <img src="{{ e($program['gambar_url']) }}" alt="{{ e($program['judul']) }}" class="h-10 w-full object-contain filter grayscale hover:grayscale-0 transition-all" title="{{ e($program['judul']) }}">
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-center text-xs text-gray-400 py-4 italic">Belum ada program.</p>
        @endif
    </div>
</div>