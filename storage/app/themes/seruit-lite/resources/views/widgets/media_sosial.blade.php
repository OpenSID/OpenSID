@php
    defined('BASEPATH') OR exit('No direct script access allowed');
@endphp

<div class="bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
    <div class="p-4 bg-gradient-to-r from-green-500 to-teal-500 text-white">
        <h3 class="font-bold text-sm uppercase tracking-wider flex items-center">
            <i class="fas fa-share-alt mr-3"></i>{{ $judul_widget }}
        </h3>
    </div>

    <div class="p-4">
        @if (count($sosmed ?? []) > 0)
            <div class="flex flex-wrap gap-3 justify-center">
                @foreach ($sosmed as $data)
                    @if (!empty($data['link']))
                        <a href="{{ e($data['link']) }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-none bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-teal-500 hover:text-white transition-all shadow-sm" title="{{ e($data['nama']) }}">
                            <i class="fab fa-{{ strtolower($data['nama']) }} text-lg"></i>
                        </a>
                    @endif
                @endforeach
            </div>
        @else
            <p class="text-center text-xs text-gray-400 py-4 italic">Belum ada media sosial.</p>
        @endif
    </div>
</div>