@php
    defined('BASEPATH') OR exit('No direct script access allowed');
    if (empty($jam_kerja)) return;
    $gradient_lite = 'from-green-500 to-teal-500';
@endphp

<div class="bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
    <div class="p-4 bg-gradient-to-r {{ $gradient_lite }} text-white">
        <h3 class="font-bold text-sm uppercase tracking-wider flex items-center">
            <i class="fas fa-clock mr-3"></i>{{ $judul_widget }}
        </h3>
    </div>

    <div class="p-4">
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach ($jam_kerja as $jam)
                <div class="py-2 flex items-center justify-between text-xs">
                    <span class="font-bold text-gray-600 dark:text-gray-400 uppercase tracking-tight">{{ e($jam->nama_hari) }}</span>
                    @if ($jam->status)
                        <span class="font-mono font-bold text-gray-800 dark:text-white bg-gray-50 dark:bg-gray-900 px-2 py-1 border border-gray-100 dark:border-gray-700">
                            {{ e($jam->jam_masuk) }} - {{ e($jam->jam_keluar) }}
                        </span>
                    @else
                        <span class="px-2 py-1 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-bold uppercase text-[9px] border border-red-100 dark:border-red-800">
                            Libur
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>