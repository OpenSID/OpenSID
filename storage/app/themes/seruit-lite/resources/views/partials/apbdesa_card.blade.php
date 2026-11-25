@php
    defined('BASEPATH') OR exit('No direct script access allowed');

    $sebutan_desa = setting('sebutan_desa');
    $prefix_apb = strtoupper('apb' . substr($sebutan_desa, 0, 3));

    $laporan = \Illuminate\Support\Str::of($subdatas['laporan'])
        ->when($sebutan_desa != 'desa', function (\Illuminate\Support\Stringable $string) {
            return $string->replace('Des', ucfirst(substr(setting('sebutan_desa'), 0, 1)));
        });

    $total_anggaran = 0;
    $total_realisasi = 0;
    $items = [];

    foreach ($subdatas as $subdata_key => $subdata) {
        if (is_array($subdata) && $subdata['judul'] != null && $subdata_key != 'laporan') {
            if ($subdata['realisasi'] != 0 || $subdata['anggaran'] != 0) {
                $items[] = $subdata;
                $total_anggaran += (float)$subdata['anggaran'];
                $total_realisasi += (float)$subdata['realisasi'];
            }
        }
    }

    $persen_total = $total_anggaran > 0 ? round(($total_realisasi / $total_anggaran) * 100, 2) : 0;
@endphp

<div x-data="{ open: false }" class="bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden h-full flex flex-col">
    <div class="p-4 text-white bg-gradient-to-r {{ $gradient_class }}">
        <h3 class="font-bold text-sm uppercase tracking-wider">{{ $prefix_apb . ' ' . $subdatas['tahun'] }}</h3>
        <p class="text-lg font-extrabold leading-tight">{{ e($laporan) }}</p>
    </div>
    
    <div class="p-5 flex-grow">
        <div class="mb-4">
            <div class="flex justify-between text-xs font-bold text-gray-500 dark:text-gray-400 mb-1 uppercase">
                <span>Realisasi</span>
                <span>{{ number_format($persen_total, 2, ',', '.') }}%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 h-3 rounded-none overflow-hidden">
                <div class="h-full bg-teal-500" style="width: {{ $persen_total > 100 ? 100 : $persen_total }}%"></div>
            </div>
        </div>

        <div class="space-y-3">
            <div>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold">Total Anggaran</p>
                <p class="text-base font-bold dark:text-white">{{ rupiah($total_anggaran, true) }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold">Total Realisasi</p>
                <p class="text-base font-bold text-teal-600 dark:text-teal-400">{{ rupiah($total_realisasi, true) }}</p>
            </div>
        </div>

        @if (count($items) > 0)
            <button @click="open = !open" class="mt-6 w-full py-2 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center text-xs font-bold text-gray-400 hover:text-teal-500 transition-colors">
                <span>RINCIAN DATA</span>
                <i class="fas fa-chevron-down transform transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="open" x-cloak x-transition class="mt-4 space-y-4">
                @foreach ($items as $item)
                    @php
                        $persen_item = $item['anggaran'] > 0 ? round(($item['realisasi'] / $item['anggaran']) * 100, 1) : 0;
                    @endphp
                    <div class="border-l-2 border-teal-500 pl-3 py-1">
                        <p class="text-xs font-bold text-gray-700 dark:text-gray-200 leading-tight">{{ e($item['judul']) }}</p>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-[10px] text-gray-500">{{ rupiah($item['realisasi'], true) }}</span>
                            <span class="text-[10px] font-bold text-teal-600">{{ $persen_item }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>