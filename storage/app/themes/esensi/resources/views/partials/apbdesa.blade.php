@php defined('BASEPATH') OR exit('No direct script access allowed'); @endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 container px-3 lg:px-5">
    @foreach ($data_widget as $subdata_name => $subdatas)
        <div class="shadow bg-white rounded-lg overflow-hidden">
            <h3 class="bg-primary-100 text-white px-5 py-3 text-h5">
                {{ \Illuminate\Support\Str::of($subdatas['laporan'])->when(setting('sebutan_desa') != 'desa', function (\Illuminate\Support\Stringable $string) {
                    return $string->replace('Des', \Illuminate\Support\Str::of(setting('sebutan_desa'))->substr(0, 1)->ucfirst());
                }) }}
            </h3>
            <div class="px-5 py-4 text-xs lg:text-sm space-y-3">
                @foreach ($subdatas as $key => $subdata)
                    @if (is_array($subdata) && $subdata['judul'] != null && $key != 'laporan' && ($subdata['realisasi'] != 0 || $subdata['anggaran'] != 0))
                        <div class="space-y-1">
                            <span class="text-sm font-bold">
                                {{ \Illuminate\Support\Str::of($subdata['judul'])->title()->whenEndsWith('Desa', function (\Illuminate\Support\Stringable $string) {
                                        if (!in_array($string, ['Dana Desa'])) {
                                            return $string->replace('Desa', setting('sebutan_desa'));
                                        }
                                    })->title() }}
                            </span>
                            <div class="text-sm flex justify-between">
                                <span>{{ rupiah24($subdata['realisasi']) }}</span>
                                <span>{{ rupiah24($subdata['anggaran']) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full overflow-hidden">
                                <div class="bg-secondary-100 text-xs font-medium text-white text-center p-0.5 leading-none rounded-l-full" style="width: {{ $subdata['persen'] }}%">{{ $subdata['persen'] }}%</div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
</div>
