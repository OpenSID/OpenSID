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
                <th colspan='4'>Uraian</th>
                <th>Anggaran (Rp)</th>
                <th>Realisasi (Rp)</th>
                <th>Lebih/(Kurang)(Rp)</th>
                <th>Persentase (%)</th>
            </tr>
        </thead>

        <tbody>

            @foreach ($pendapatan as $l)
                <tr class='bold'>
                    <td colspan='4'>{{ $l['Akun'] . ' ' . $l['Nama_Akun'] }}</td>
                    <td align='right'></td>
                    <td align='right'></td>
                    <td align='right'></td>
                    <td align='right'></td>
                </tr>
                @foreach ($l['sub_pendapatan'] as $s)
                    @if (!empty($s['anggaran'][0]['pagu']) || !empty($s['realisasi'][0]['realisasi'] + $s['realisasi_bunga'][0]['realisasi'] + $s['realisasi_jurnal'][0]['realisasi']))
                        <tr class='bold'>
                            <td>{{ $s['Kelompok'] }}</td>
                            <td colspan='3'>
                                {{ Illuminate\Support\Str::of($s['Nama_Kelompok'])->title()->whenContains(
                                        'Desa',
                                        static function (Illuminate\Support\Stringable $string) {
                                            if ($string != 'Dana Desa') {
                                                return $string->replace('Desa', setting('sebutan_desa'));
                                            }
                                        },
                                        static fn(Illuminate\Support\Stringable $string) => $string->append(' ' . setting('sebutan_desa')),
                                    )->title() }}
                            </td>
                            <td align='right'>{{ rp($s['anggaran'][0]['pagu']) }}</td>
                            <td align='right'>{{ rp($s['realisasi'][0]['realisasi'] + $s['realisasi_bunga'][0]['realisasi'] + $s['realisasi_jurnal'][0]['realisasi']) }}</td>
                            <td align='right'>{{ rp($s['anggaran'][0]['pagu'] - ($s['realisasi'][0]['realisasi'] + $s['realisasi_bunga'][0]['realisasi'] + $s['realisasi_jurnal'][0]['realisasi'])) }}</td>
                            <td align='right'>{{ $s['anggaran'][0]['pagu'] != 0 ? rp((($s['realisasi'][0]['realisasi'] + $s['realisasi_bunga'][0]['realisasi'] + $s['realisasi_jurnal'][0]['realisasi']) / $s['anggaran'][0]['pagu']) * 100) : 0 }}</td>
                        </tr>
                    @endif
                    @foreach ($s['sub_pendapatan2'] as $q)
                        @if (!empty($q['anggaran'][0]['pagu']) || !empty($q['realisasi'][0]['realisasi'] + $q['realisasi_bunga'][0]['realisasi'] + $q['realisasi_jurnal'][0]['realisasi']))
                            <tr>
                                <td></td>
                                <td colspan='2'>{{ $q['Jenis'] }}</td>
                                <td>
                                    {{ Illuminate\Support\Str::of($q['Nama_Jenis'])->title()->whenContains(
                                            'Desa',
                                            static function (Illuminate\Support\Stringable $string) {
                                                if ($string != 'Dana Desa') {
                                                    return $string->replace('Desa', setting('sebutan_desa'));
                                                }
                                            },
                                            static function (Illuminate\Support\Stringable $string) {
                                                if (
                                                    !in_array($string, [
                                                        'Swadaya, Partisipasi dan Gotong Royong',
                                                        'Bagi Hasil Pajak Dan Retribusi',
                                                        'Bantuan Keuangan Provinsi',
                                                        'Bantuan Keuangan Kabupaten/Kota',
                                                        'Penerimaan Dari Hasil Kerjasama Dengan Pihak Ketiga',
                                                        'Koreksi Kesalahan Belanja Tahun-Tahun Sebelumnya',
                                                        'Bunga Bank',
                                                        'Hibah dan Sumbangan dari Pihak Ketiga',
                                                        'Lain-Lain Pendapatan Desa Yang Sah',
                                                        'Lain - Lain Pendapatan Asli Desa Yang Sah',
                                                    ])
                                                ) {
                                                    return $string->append(' ' . setting('sebutan_desa'));
                                                }
                                            },
                                        )->title() }}
                                </td>
                                <td align='right'>{{ rp($q['anggaran'][0]['pagu']) }}</td>
                                <td align='right'>{{ rp($q['realisasi'][0]['realisasi'] + $q['realisasi_bunga'][0]['realisasi'] + $q['realisasi_jurnal'][0]['realisasi']) }}</td>
                                <td align='right'>{{ rp($q['anggaran'][0]['pagu'] - ($q['realisasi'][0]['realisasi'] + $q['realisasi_bunga'][0]['realisasi'] + $q['realisasi_jurnal'][0]['realisasi'])) }}</td>
                                <td align='right'>{{ $q['anggaran'][0]['pagu'] != 0 ? rp((($q['realisasi'][0]['realisasi'] + $q['realisasi_bunga'][0]['realisasi'] + $q['realisasi_jurnal'][0]['realisasi']) / $q['anggaran'][0]['pagu']) * 100) : 0 }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
                <tr class='bold highlighted'>
                    <td colspan='4' align='center'>JUMLAH PENDAPATAN</td>
                    <td align='right'>{{ rp($l['anggaran'][0]['pagu']) }}</td>
                    @php $jumlah_real = ($l['realisasi'][0]['realisasi'] + $l['realisasi_bunga'][0]['realisasi'] + $l['realisasi_jurnal'][0]['realisasi']) @endphp
                    <td align='right'>{{ rp($jumlah_real) }}</td>
                    <td align='right'>{{ rp($l['anggaran'][0]['pagu'] - $jumlah_real) }}</td>
                    <td align='right'>{{ rp($jumlah_real == 0 ? 0 : ($jumlah_real / $l['anggaran'][0]['pagu']) * 100) }} </td>
                </tr>
            @endforeach

            @foreach ($belanja as $b)
                <tr class='bold'>
                    <td colspan='4'>{{ $b['Akun'] . ' ' . $b['Nama_Akun'] }}</td>
                    <td align='right'></td>
                    <td align='right'></td>
                    <td align='right'></td>
                    <td align='right'></td>
                </tr>

                @if ($jenis != 'bidang')
                    <!-- Belanja per kelompok -->
                    @foreach ($b['sub_belanja'] as $b1)
                        @if (!empty($b1['anggaran'][0]['pagu']) || !empty($b1['realisasi'][0]['realisasi'] + $b1['realisasi_spj'][0]['realisasi'] + $b1['realisasi_bunga'][0]['realisasi'] + $b1['realisasi_jurnal'][0]['realisasi']))
                            <tr class='bold'>
                                <td>{{ $b1['Kelompok'] }}</td>
                                <td colspan='3'>{{ $b1['Nama_Kelompok'] }}</td>
                                <td align='right'>{{ rp($b1['anggaran'][0]['pagu']) }}</td>
                                <td align='right'>{{ rp($b1['realisasi'][0]['realisasi'] - $b1['realisasi_um'][0]['realisasi'] + $b1['realisasi_spj'][0]['realisasi'] + $b1['realisasi_bunga'][0]['realisasi'] + $b1['realisasi_jurnal'][0]['realisasi']) }}</td>
                                <td align='right'>{{ rp($b1['anggaran'][0]['pagu'] - ($b1['realisasi'][0]['realisasi'] - $b1['realisasi_um'][0]['realisasi'] + $b1['realisasi_spj'][0]['realisasi'] + $b1['realisasi_bunga'][0]['realisasi'] + $b1['realisasi_jurnal'][0]['realisasi'])) }}</td>
                                <td align='right'>
                                    {{ $b1['anggaran'][0]['pagu'] != 0 ? rp((($b1['realisasi'][0]['realisasi'] - $b1['realisasi_um'][0]['realisasi'] + $b1['realisasi_spj'][0]['realisasi'] + $b1['realisasi_bunga'][0]['realisasi'] + $b1['realisasi_jurnal'][0]['realisasi']) / $b1['anggaran'][0]['pagu']) * 100) : 0 }}
                                </td>
                            </tr>
                        @endif
                        @foreach ($b1['sub_belanja2'] as $b2)
                            @if (!empty($b2['anggaran'][0]['pagu']) || !empty($b2['realisasi'][0]['realisasi'] + $b2['realisasi_spj'][0]['realisasi'] + $b2['realisasi_bunga'][0]['realisasi'] + $b2['realisasi_jurnal'][0]['realisasi']))
                                <tr>
                                    <td></td>
                                    <td colspan='2'>{{ $b2['Jenis'] }}</td>
                                    <td>{{ $b2['Nama_Jenis'] }}</td>
                                    <td align='right'>{{ rp($b2['anggaran'][0]['pagu']) }}</td>
                                    <td align='right'>{{ rp($b2['realisasi'][0]['realisasi'] - $b2['realisasi_um'][0]['realisasi'] + $b2['realisasi_spj'][0]['realisasi'] + $b2['realisasi_bunga'][0]['realisasi'] + $b2['realisasi_jurnal'][0]['realisasi']) }}</td>
                                    <td align='right'>{{ rp($b2['anggaran'][0]['pagu'] - ($b2['realisasi'][0]['realisasi'] - $b2['realisasi_um'][0]['realisasi'] + $b2['realisasi_spj'][0]['realisasi'] + $b2['realisasi_bunga'][0]['realisasi'] + $b2['realisasi_jurnal'][0]['realisasi'])) }}</td>
                                    <td align='right'>
                                        {{ $b2['anggaran'][0]['pagu'] != 0 ? rp((($b2['realisasi'][0]['realisasi'] - $b2['realisasi_um'][0]['realisasi'] + $b2['realisasi_spj'][0]['realisasi'] + $b2['realisasi_bunga'][0]['realisasi'] + $b2['realisasi_jurnal'][0]['realisasi']) / $b2['anggaran'][0]['pagu']) * 100) : 0 }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                @else
                    @foreach ($belanja_bidang as $b1)
                        @if (!empty($b1['anggaran'][0]['pagu']) || !empty($b1['realisasi'][0]['realisasi'] + $b1['realisasi_spj'][0]['realisasi'] + $b1['realisasi_bunga'][0]['realisasi']))
                            <tr class='bold'>
                                <td>{{ str_pad(substr($b1['Kd_Bid'], -1), 2, '0', STR_PAD_LEFT) }}</td>
                                <td colspan='3'>
                                    {{ Illuminate\Support\Str::of(App\Enums\BidangBelanjaEnum::valueOf(substr($b1['Kd_Bid'], -1)))->title()->whenContains(
                                            'Desa',
                                            static function (Illuminate\Support\Stringable $string) {
                                                if ($string != 'Dana Desa') {
                                                    return $string->replace('Desa', setting('sebutan_desa'));
                                                }
                                            },
                                            static fn(Illuminate\Support\Stringable $string) => $string->append(' ' . setting('sebutan_desa')),
                                        )->title() }}
                                </td>
                                <td align='right'>{{ rp($b1['anggaran'][0]['pagu']) }}</td>
                                <td align='right'>{{ rp($b1['realisasi'][0]['realisasi'] - $b1['realisasi_um'][0]['realisasi'] + $b1['realisasi_spj'][0]['realisasi'] + $b1['realisasi_bunga'][0]['realisasi'] + $b1['realisasi_jurnal'][0]['realisasi']) }}</td>
                                <td align='right'>{{ rp($b1['anggaran'][0]['pagu'] - ($b1['realisasi'][0]['realisasi'] - $b1['realisasi_um'][0]['realisasi'] + $b1['realisasi_spj'][0]['realisasi'] + $b1['realisasi_bunga'][0]['realisasi'] + $b1['realisasi_jurnal'][0]['realisasi'])) }}</td>
                                <td align='right'>
                                    {{ $b1['anggaran'][0]['pagu'] != 0 ? rp((($b1['realisasi'][0]['realisasi'] - $b1['realisasi_um'][0]['realisasi'] + $b1['realisasi_spj'][0]['realisasi'] + $b1['realisasi_bunga'][0]['realisasi'] + $b1['realisasi_jurnal'][0]['realisasi']) / $b1['anggaran'][0]['pagu']) * 100) : 0 }}
                                </td>
                            </tr>
                        @endif
                        @foreach ($b1['sub_belanja'] as $b2)
                            @if (!empty($b2['anggaran'][0]['pagu']) || !empty($b2['realisasi'][0]['realisasi'] + $b2['realisasi_spj'][0]['realisasi'] + $b2['realisasi_bunga'][0]['realisasi'] + $b2['realisasi_jurnal'][0]['realisasi']))
                                <tr>
                                    <td></td>
                                    <td colspan='2'>{{ substr($b2['Kd_Keg'], 8) }}</td>
                                    <td>
                                        {{ Illuminate\Support\Str::of($b2['Nama_Kegiatan'])->title()->whenContains(
                                                'Desa',
                                                static function (Illuminate\Support\Stringable $string) {
                                                    if ($string != 'Dana Desa') {
                                                        return $string->replace('Desa', setting('sebutan_desa'));
                                                    }
                                                },
                                                static fn(Illuminate\Support\Stringable $string) => $string->append(' ' . setting('sebutan_desa')),
                                            )->title() }}
                                    </td>
                                    <td align='right'>{{ rp($b2['anggaran'][0]['pagu']) }}</td>
                                    <td align='right'>{{ rp($b2['realisasi'][0]['realisasi'] - $b2['realisasi_um'][0]['realisasi'] + $b2['realisasi_spj'][0]['realisasi'] + $b2['realisasi_bunga'][0]['realisasi'] + $b2['realisasi_jurnal'][0]['realisasi']) }}</td>
                                    <td align='right'>{{ rp($b2['anggaran'][0]['pagu'] - ($b2['realisasi'][0]['realisasi'] - $b2['realisasi_um'][0]['realisasi'] + $b2['realisasi_spj'][0]['realisasi'] + $b2['realisasi_bunga'][0]['realisasi'] + $b2['realisasi_jurnal'][0]['realisasi'])) }}</td>
                                    <td align='right'>
                                        {{ $b2['anggaran'][0]['pagu'] != 0 ? rp((($b2['realisasi'][0]['realisasi'] - $b2['realisasi_um'][0]['realisasi'] + $b2['realisasi_spj'][0]['realisasi'] + $b2['realisasi_bunga'][0]['realisasi'] + $b2['realisasi_jurnal'][0]['realisasi']) / $b2['anggaran'][0]['pagu']) * 100) : 0 }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                @endif

                <tr class='bold highlighted'>
                    <td colspan='4' align='center'>JUMLAH BELANJA</td>
                    <td align='right'>{{ rp($b['anggaran'][0]['pagu']) }}</td>
                    @php $jumlah_belanja = (($b['realisasi'][0]['realisasi'] - $b['realisasi_um'][0]['realisasi']) + $b['realisasi_spj'][0]['realisasi'] + $b['realisasi_bunga'][0]['realisasi'] + $b['realisasi_jurnal'][0]['realisasi']) @endphp
                    <td align='right'>{{ rp($b['realisasi'][0]['realisasi'] - $b['realisasi_um'][0]['realisasi'] + $b['realisasi_spj'][0]['realisasi'] + $b['realisasi_bunga'][0]['realisasi'] + $b['realisasi_jurnal'][0]['realisasi']) }}</td>
                    <td align='right'>{{ rp($b['anggaran'][0]['pagu'] - ($b['realisasi'][0]['realisasi'] - $b['realisasi_um'][0]['realisasi'] + $b['realisasi_spj'][0]['realisasi'] + $b['realisasi_bunga'][0]['realisasi'] + $b['realisasi_jurnal'][0]['realisasi'])) }}</td>
                    <td align='right'>{{ rp($jumlah_belanja == 0 ? 0 : ($jumlah_belanja / $b['anggaran'][0]['pagu']) * 100) }} </td>
                </tr>
            @endforeach

            <tr class='bold highlighted'>
                <td colspan='4' align='center'>SURPLUS / (DEFISIT)</td>
                <td align='right'>{{ rp($l['anggaran'][0]['pagu'] - $b['anggaran'][0]['pagu']) }}</td>
                <td align='right'>{{ rp($jumlah_real - ($b['realisasi'][0]['realisasi'] - $b['realisasi_um'][0]['realisasi'] + $b['realisasi_spj'][0]['realisasi'] + $b['realisasi_bunga'][0]['realisasi'] + $b['realisasi_jurnal'][0]['realisasi'])) }}</td>
                <td align='right'>
                    {{ rp($l['anggaran'][0]['pagu'] - $b['anggaran'][0]['pagu'] - ($jumlah_real - ($b['realisasi'][0]['realisasi'] - $b['realisasi_um'][0]['realisasi'] + $b['realisasi_spj'][0]['realisasi'] + $b['realisasi_bunga'][0]['realisasi'] + $b['realisasi_jurnal'][0]['realisasi']))) }}
                </td>
                <td align='right'>
                    @php
                        $pembagi = $jumlah_real - ($b['realisasi'][0]['realisasi'] - $b['realisasi_um'][0]['realisasi']) + $b['realisasi_spj'][0]['realisasi'] + $b['realisasi_bunga'][0]['realisasi'] + $b['realisasi_jurnal'][0]['realisasi'];
                    @endphp
                    {{ $pembagi > 0 ? rp((($l['anggaran'][0]['pagu'] - $b['anggaran'][0]['pagu']) / $pembagi) * 100) : '-' }}
                </td>
            </tr>
            @foreach ($pembiayaan as $p)
                <tr class='bold'>
                    <td colspan='4'>{{ $p['Akun'] . ' ' . $p['Nama_Akun'] }}</td>
                    <td align='right'></td>
                    <td align='right'></td>
                    <td align='right'></td>
                    <td align='right'></td>
                </tr>

                @foreach ($p['sub_pembiayaan'] as $p1)
                    @if (!empty($p1['anggaran'][0]['pagu']) || !empty($p1['realisasi'][0]['realisasi']))
                        <tr class='bold'>
                            <td>{{ $p1['Kelompok'] }}</td>
                            <td colspan='3'>{{ $p1['Nama_Kelompok'] }}</td>
                            <td align='right'>{{ rp($p1['anggaran'][0]['pagu']) }}</td>
                            <td align='right'>{{ rp($p1['realisasi'][0]['realisasi']) }}</td>
                            <td align='right'>{{ rp($p1['anggaran'][0]['pagu'] - $p1['realisasi'][0]['realisasi']) }}</td>
                            <td align='right'></td>
                        </tr>
                    @endif
                    @foreach ($p1['sub_pembiayaan2'] as $p2)
                        @if (!empty($p2['anggaran'][0]['pagu']) || !empty($p2['realisasi'][0]['realisasi']))
                            <tr>
                                <td></td>
                                <td colspan='2'>{{ $p2['Jenis'] }}</td>
                                <td>{{ $p2['Nama_Jenis'] }}</td>
                                <td align='right'>{{ rp($p2['anggaran'][0]['pagu']) }}</td>
                                <td align='right'>{{ rp($p2['realisasi'][0]['realisasi']) }}</td>
                                <td align='right'>{{ rp($p2['anggaran'][0]['pagu'] - $p2['realisasi'][0]['realisasi']) }}</td>
                                <td align='right'></td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            @endforeach

            @foreach ($pembiayaan_keluar as $pk)
                @foreach ($pk['sub_pembiayaan_keluar'] as $pk1)
                    @if (!empty($pk1['anggaran'][0]['pagu']) || !empty($pk1['realisasi'][0]['realisasi']))
                        <tr class='bold'>
                            <td>{{ $pk1['Kelompok'] }}</td>
                            <td colspan='3'>{{ $pk1['Nama_Kelompok'] }}</td>
                            <td align='right'>{{ rp($pk1['anggaran'][0]['pagu']) }}</td>
                            <td align='right'>{{ rp($pk1['realisasi'][0]['realisasi']) }}</td>
                            <td align='right'>{{ rp($pk1['anggaran'][0]['pagu'] - $pk1['realisasi'][0]['realisasi']) }}</td>
                            <td align='right'></td>
                        </tr>
                    @endif

                    @foreach ($pk1['sub_pembiayaan_keluar2'] as $pk2)
                        @if (!empty($pk2['anggaran'][0]['pagu']) || !empty($pk2['realisasi'][0]['realisasi']))
                            <tr>
                                <td></td>
                                <td colspan='2'>{{ $pk2['Jenis'] }}</td>
                                <td>{{ $pk2['Nama_Jenis'] }}</td>
                                <td align='right'>{{ rp($pk2['anggaran'][0]['pagu']) }}</td>
                                <td align='right'>{{ rp($pk2['realisasi'][0]['realisasi']) }}</td>
                                <td align='right'>{{ rp($pk2['anggaran'][0]['pagu'] - $pk2['realisasi'][0]['realisasi']) }}</td>
                                <td align='right'></td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            @endforeach

            <tr class='bold highlighted'>
                <td colspan='4' align='center'>PEMBIAYAAN NETTO</td>
                <td align='right'>{{ rp($p1['anggaran'][0]['pagu'] - $pk1['anggaran'][0]['pagu']) }}</td>
                <td align='right'>{{ rp($p1['realisasi'][0]['realisasi'] - $pk1['realisasi'][0]['realisasi']) }}</td>
                <td align='right'>{{ rp($p1['anggaran'][0]['pagu'] - $pk1['anggaran'][0]['pagu'] - ($p1['realisasi'][0]['realisasi'] - $pk1['realisasi'][0]['realisasi'])) }}</td>
                <td align='right'></td>
            </tr>

            <tr class='bold highlighted'>
                <td colspan='4' align='center'>SILPA/SiLPA TAHUN BERJALAN</td>
                <td align='right'>{{ rp($l['anggaran'][0]['pagu'] - $b['anggaran'][0]['pagu'] + ($p1['anggaran'][0]['pagu'] - $pk1['anggaran'][0]['pagu'])) }}</td>
                <td align='right'>
                    {{ rp($jumlah_real - ($b['realisasi'][0]['realisasi'] - $b['realisasi_um'][0]['realisasi'] + $b['realisasi_spj'][0]['realisasi'] + $b['realisasi_bunga'][0]['realisasi'] + $b['realisasi_jurnal'][0]['realisasi']) + ($p1['realisasi'][0]['realisasi'] - $pk1['realisasi'][0]['realisasi'])) }}
                </td>
                <td align='right'>
                    {{ rp($l['anggaran'][0]['pagu'] - $b['anggaran'][0]['pagu'] - ($jumlah_real - ($b['realisasi'][0]['realisasi'] - $b['realisasi_um'][0]['realisasi'] + $b['realisasi_spj'][0]['realisasi'] + $b['realisasi_bunga'][0]['realisasi'] + $b['realisasi_jurnal'][0]['realisasi'])) + ($p1['anggaran'][0]['pagu'] - $pk1['anggaran'][0]['pagu'] - ($p1['realisasi'][0]['realisasi'] - $pk1['realisasi'][0]['realisasi']))) }}
                </td>
                <td align='right'></td>
            </tr>

        </tbody>
    </table>
</div>
