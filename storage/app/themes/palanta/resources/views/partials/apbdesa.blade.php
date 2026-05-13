<div class="apbdstyle">
    <div class="heading-module c-flex">
        <div class="heading-module-inner c-flex">
            <i class="fa fa-edit"></i>
            <h1>APBD {{ ucwords($sebutan_desa) }}</h1>
        </div>
    </div>

    <div class="row-custom mlr-min5">
    @foreach($data_widget as $subdata_name => $subdatas)
        <div class="apbd-room">
            <h1>{{
                    \Illuminate\Support\Str::of($subdatas['laporan'])
                        ->when(setting('sebutan_desa') != 'desa', function (\Illuminate\Support\Stringable $string) {
                            return $string->replace('Des', \Illuminate\Support\Str::of(setting('sebutan_desa'))->substr(0,1)->ucfirst());
                        })
            }}</h1>
            <div class="box-def-inner">
                @foreach ($subdatas as $key => $subdata)                    
                    @if (is_array($subdata) && $subdata['judul'] != null && $key != 'laporan' && ($subdata['realisasi'] != 0 ||
            $subdata['anggaran'] != 0))
                    <div class="apbd-row">
                        <h3>{{
                            \Illuminate\Support\Str::of($subdata['judul'])
                                ->title()
                                ->whenEndsWith('Desa', function (\Illuminate\Support\Stringable $string) {
                                    if (! in_array($string, ['Dana Desa'])) {
                                        return $string->replace('Desa', setting('sebutan_desa'));
                                    }
                                })
                                ->title()
                        }}</h3>
                        <div class="c-flex">
                            <p>Anggaran</p>
                            <p style="margin:0 5px;">|</p>
                            <p>Realisasi</p>
                        </div>
                        <table class="table-apbd" style="width:100%;">
                            <tr>
                                <td style="font-size:90%;text-align:left;">Rp. {{ number_format($subdata['anggaran']); }}
                                </td>
                                <td style="font-size:90%;text-align:right;">Rp. {{ number_format($subdata['realisasi']); }}
                                </td>
                            </tr>
                        </table>
                        <div class="progress progress-md">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                style="width: {{ $subdata['persen'] }}%">{{ $subdata['persen'] }}%</div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>