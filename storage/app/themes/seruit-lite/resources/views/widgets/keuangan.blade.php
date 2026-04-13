@php
    defined('BASEPATH') OR exit('No direct script access allowed');
@endphp

<div class="bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
    <div class="p-4 bg-gradient-to-r from-green-500 to-teal-500 text-white">
        <h3 class="font-bold text-sm uppercase tracking-wider flex items-center">
            <i class="fas fa-chart-pie mr-3"></i>{{ $judul_widget }}
        </h3>
    </div>

    <div class="p-4 space-y-6">
        @if (empty($widget_keuangan['data_widget']))
            <p class="text-center text-xs text-gray-400 py-4 italic">Data tidak tersedia.</p>
        @else
            @foreach ($widget_keuangan['data_widget'] as $laporan)
                <div class="space-y-3">
                    <h4 class="text-[11px] font-extrabold text-gray-800 dark:text-gray-200 uppercase border-b border-gray-100 dark:border-gray-700 pb-1">{{ $laporan['laporan'] }}</h4>
                    @foreach ($laporan as $key => $item)
                        @if (is_array($item) && !empty($item['judul']))
                            @php
                                $persen = ($item['anggaran'] > 0) ? round(($item['realisasi'] / $item['anggaran']) * 100, 1) : 0;
                            @endphp
                            <div class="space-y-1">
                                <div class="flex justify-between text-[10px] font-bold text-gray-500 uppercase">
                                    <span class="truncate w-3/4">{{ e($item['judul']) }}</span>
                                    <span>{{ $persen }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-gray-700 h-2 shadow-inner">
                                    <div class="h-full bg-teal-500" style="width: {{ $persen > 100 ? 100 : $persen }}%"></div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>
</div>