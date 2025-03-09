<style>
    table.blueTable {
        border: 1px solid #1C6EA4;
        background-color: #EEEEEE;
        width: 100%;
        text-align: left;
        border-collapse: collapse;
    }

    table.blueTable td,
    table.blueTable th {
        border: 1px solid #AAAAAA;
        max-width: 230px;
        word-wrap: break-word;
        padding: 3px 2px;
    }

    table.blueTable tbody td {
        font-size: 13px;
    }

    table.blueTable tr:nth-child(even) {
        background: #D0E4F5;
    }

    table.blueTable thead {
        background: #1C6EA4;
        background: -moz-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
        background: -webkit-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
        background: linear-gradient(to bottom, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
        border-bottom: 2px solid #444444;
    }

    table.blueTable thead th {
        font-size: 15px;
        font-weight: bold;
        color: #FFFFFF;
        text-align: center;
        border-left: 2px solid #D0E4F5;
    }

    table.blueTable thead th:first-child {
        border-left: none;
    }

    .bold {
        font-weight: bold;
    }

    .highlighted {
        background-color: #FFFF00 !important;
    }
</style>
<div class="table-responsive">
    <table class="table blueTable" width='100%'>
        <thead>
            <tr>
                <th colspan="5">Uraian</th>
                <th>Anggaran (Rp)</th>
                <th>Realisasi (Rp)</th>
                <th>Lebih/(Kurang)(Rp)</th>
                <th>Persentase (%)</th>
            </tr>
        </thead>

        <tbody>
            @php
                $silpaRealisasi = 0;
                $silpaAnggaran = 0;
                $silpaSelisih = 0;
                $silpaPersentase = 0;
            @endphp
            @foreach ($laporan as $tingkat1)
                @php
                    $silpaRealisasi += $tingkat1['realisasi'];
                    $silpaAnggaran += $tingkat1['anggaran'];
                    $silpaSelisih += $tingkat1['selisih'];
                    $silpaPersentase = $silpaAnggaran != 0 ? ($silpaRealisasi / $silpaAnggaran) * 100 : 0;
                @endphp
                <tr class='bold'>
                    <td>{{ $tingkat1['kode_rekening'] }}</td>
                    <td colspan='4'>{{ strtoupper($tingkat1['uraian']) }}</td>
                    <td align='right'></td>
                    <td align='right'></td>
                    <td align='right'></td>
                    <td align='right'></td>
                </tr>
                @foreach ($tingkat1['sub_kode_rekening'] as $tingkat2)
                    @if (!empty($tingkat2['anggaran'] || $tingkat2['realisasi']))
                        <tr class='bold'>
                            <td></td>
                            <td>{{ $tingkat2['kode_rekening'] }}</td>
                            <td colspan='3'>
                                {{ Illuminate\Support\Str::of($tingkat2['uraian'])->title()->whenContains(
                                        'Desa',
                                        static function (Illuminate\Support\Stringable $string) {
                                            if ($string != 'Dana Desa') {
                                                return $string->replace('Desa', setting('sebutan_desa'));
                                            }
                                        },
                                        static fn(Illuminate\Support\Stringable $string) => $string->append(' ' . setting('sebutan_desa')),
                                    )->title() }}
                            </td>
                            <td align='right'>{{ rp($tingkat2['anggaran']) }}</td>
                            <td align='right'>{{ rp($tingkat2['realisasi']) }}</td>
                            <td align='right'>{{ rp($tingkat2['selisih']) }}</td>
                            @if (in_array($tingkat1['kode_rekening'], ['6']))
                                <td align='right'></td>
                            @else
                                <td align='right'>{{ $tingkat2['persentase'] }}</td>
                            @endif
                        </tr>
                    @endif

                    @foreach ($tingkat2['sub_kode_rekening'] as $tingkat3)
                        @if (!empty($tingkat3['anggaran'] || $tingkat3['realisasi']))
                            <tr>
                                <td></td>
                                <td></td>
                                <td>{{ $tingkat3['kode_rekening'] }}</td>
                                <td colspan='2'>{{ $tingkat3['uraian'] }}</td>
                                <td align='right'>{{ rp($tingkat3['anggaran']) }}</td>
                                <td align='right'>{{ rp($tingkat3['realisasi']) }}</td>
                                <td align='right'>{{ rp($tingkat3['selisih']) }}</td>
                                @if (in_array($tingkat1['kode_rekening'], ['6']))
                                    <td align='right'></td>
                                @else
                                    <td align='right'>{{ $tingkat3['persentase'] }}</td>
                                @endif
                            </tr>
                        @endif
                    @endforeach
                @endforeach
                @if (in_array($tingkat1['kode_rekening'], ['4', '5']))
                    <tr class='bold highlighted'>
                        <td colspan='5' align="center">JUMLAH {{ strtoupper($tingkat1['uraian']) }}</td>
                        <td align='right'>{{ rp($tingkat1['anggaran']) }}</td>
                        <td align='right'>{{ rp($tingkat1['realisasi']) }}</td>
                        <td align='right'>{{ rp($tingkat1['selisih']) }}</td>
                        <td align='right'>{{ $tingkat1['persentase'] }}</td>
                    </tr>
                @endif
                @if ($tingkat1['kode_rekening'] == '5')
                    <tr class='bold highlighted'>
                        <td colspan='5' align="center">SURPLUS / (DEFISIT)</td>
                        @php
                            $surplus_anggaran = $laporan[0]['anggaran'] - $laporan[1]['anggaran'];
                            $surplus_realisasi = $laporan[0]['realisasi'] - $laporan[1]['realisasi'];
                            $surplus_selisih = $laporan[0]['selisih'] - $laporan[1]['selisih'];
                            $surplus_persentase = $surplus_anggaran != 0 ? ($surplus_realisasi / $surplus_anggaran) * 100 : 0;
                        @endphp
                        <td align='right'>{{ rp($laporan[0]['anggaran'] - $laporan[1]['anggaran']) }}</td>
                        <td align='right'>{{ rp($laporan[0]['realisasi'] - $laporan[1]['realisasi']) }}</td>
                        <td align='right'>{{ rp($laporan[0]['selisih'] - $laporan[1]['selisih']) }}</td>
                        <td align='right'>-</td>
                        {{-- <td align='right'>{{ persen($surplus_persentase) }}</td> --}}
                    </tr>
                @endif
                @if (in_array($tingkat1['kode_rekening'], ['6']))
                    <tr class='bold highlighted'>
                        <td colspan='5' align="center">{{ strtoupper($tingkat1['uraian']) }} NETTO</td>
                        <td align='right'>{{ rp($tingkat1['anggaran']) }}</td>
                        <td align='right'>{{ rp($tingkat1['realisasi']) }}</td>
                        <td align='right'>{{ rp($tingkat1['selisih']) }}</td>
                        <td align='right'></td>
                    </tr>
                    <tr class='bold highlighted'>
                        <td colspan='5' align="center">SILPA/SiLPA TAHUN BERJALAN</td>
                        @php
                            $silpa_anggaran = $surplus_anggaran + $tingkat1['anggaran'];
                            $silpa_realisasi = $surplus_realisasi + $tingkat1['realisasi'];
                            $silpa_selisih = $surplus_selisih + $tingkat1['selisih'];
                            $silpa_persentase = $silpa_anggaran != 0 ? ($silpa_realisasi / $silpa_anggaran) * 100 : 0;
                        @endphp
                        <td align='right'>{{ rp($silpa_anggaran) }}</td>
                        <td align='right'>{{ rp($silpa_realisasi) }}</td>
                        <td align='right'>{{ rp($silpa_selisih) }}</td>
                        <td align='right'></td>
                    </tr>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
