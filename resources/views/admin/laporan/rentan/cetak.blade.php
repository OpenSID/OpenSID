<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Cetak Laporan Kelompok Rentan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="{{ asset('css/report.css') }}" rel="stylesheet" type="text/css">
</head>

<body>
    <div id="container">
        <!-- Print Body -->
        <div id="body">
            <table width="100%">
                <tbody>
                    <tr align="center">
                        <td width="100%">
                            <h3>PEMERINTAH KABUPATEN/KOTA {{ strtoupper($desa['nama_kabupaten']) }}</h3>
                        </td>
                    </tr>
                    <tr align="center">
                        <td width="100%">
                            <h4>DATA PILAH KEPENDUDUKAN MENURUT UMUR DAN FAKTOR KERENTANAN (LAMPIRAN A - 9)</h4>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table>
                <tbody>
                    <tr>
                        <td>{{ ucwords(setting('sebutan_desa')) }}/Kelurahan</td>
                        <td width="3%">:</td>
                        <td width="38.5%">{{ $desa['nama_desa'] }}</h4>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>{{ ucwords(setting('sebutan_kecamatan')) }}</td>
                        <td width="3%">:</td>
                        <td width="38.5%">{{ $desa['nama_kecamatan'] }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Laporan Bulan</td>
                        <td width="3%">:</td>
                        <td>{{ getBulan(date('m')) }}</td>
                        <td width="40%"></td>
                    </tr>
                    @if ($dusun)
                        <tr>
                            <td>Dusun</td>
                            <td width="3%">:</td>
                            <td>
                                {{ $dusun }}
                            </td>
                            <td width="40%"></td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <br>
            <table class="border thick">
                <thead>
                    @if ($_SESSION['dusun'] != '')
                        <tr>
                            <h3>DATA PILAH {{ strtoupper(setting('sebutan_dusun')) }} {{ $_SESSION['dusun'] }}</h3>
                        </tr>
                    @endif
                    <tr class="border thick">
                    <tr>
                        <th rowspan="2" align="center">{{ ucwords(setting('sebutan_dusun')) }}</th>
                        <th rowspan="2" align="center">RW</th>
                        <th rowspan="2" align="center">RT</th>
                        <th colspan="2" align="center">KK</th>
                        <th colspan="6" align="center">Kondisi dan Kelompok Umur</th>
                        <th colspan="7" align="center">Cacat</th>
                        <th colspan="2" align="center">Sakit Menahun</th>
                        <th rowspan="2" align="center">Hamil</th>
                    </tr>
                    <tr>
                        <th align="center">L</th>
                        <th align="center">P</th>
                        <th align="center">Dibawah 1 Tahun</th>
                        <th align="center">1-5 Tahun</th>
                        <th align="center">6-12 Tahun</th>
                        <th align="center">13-15 Tahun</th>
                        <th align="center">16-18 Tahun</th>
                        <th align="center">Diatas 60 Tahun</th>
                        <th align="center">Cacat Fisik</th>
                        <th align="center">Cacat Netra/ Buta</th>
                        <th align="center">Cacat Rungu/ Wicara</th>
                        <th align="center">Cacat Mental/ Jiwa</th>
                        <th align="center">Cacat Fisik dan Mental</th>
                        <th align="center">Cacat Lainnya</th>
                        <th align="center">Tidak Cacat</th>
                        <th align="center">L</th>
                        <th align="center">P</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $bayi = 0;
                        $balita = 0;
                        $sd = 0;
                        $smp = 0;
                        $sma = 0;
                        $lansia = 0;
                        $cacat = 0;
                        $sakit_L = 0;
                        $sakit_P = 0;
                        $hamil = 0;
                        $jenis_cacat = App\Enums\CacatEnum::all();
                        $totalCacat = [];
                    @endphp
                    @foreach ($wilayah as $namaDusun => $dusunObj)
                        @foreach ($dusunObj as $namaRw => $rwObj)
                            @foreach ($rwObj as $rt)
                                @php
                                    $totalBarisCacat = 0;
                                    $totalPenduduk = 0;
                                    $totalPendudukPria = 0;
                                    $totalPendudukWanita = 0;
                                    if ($main['jenisKelamin'][$rt->id]) {
                                        if ($main['jenisKelamin'][$rt->id][App\Enums\JenisKelaminEnum::LAKI_LAKI]) {
                                            $totalPendudukPria += $main['jenisKelamin'][$rt->id][App\Enums\JenisKelaminEnum::LAKI_LAKI]['total'];
                                        }
                                        if ($main['jenisKelamin'][$rt->id][App\Enums\JenisKelaminEnum::PEREMPUAN]) {
                                            $totalPendudukWanita += $main['jenisKelamin'][$rt->id][App\Enums\JenisKelaminEnum::PEREMPUAN]['total'];
                                        }
                                    }
                                    $totalPenduduk = $totalPendudukPria + $totalPendudukWanita;
                                @endphp
                                @if (!$totalPenduduk)
                                    @continue
                                @endif
                                <tr>
                                    <td align="right">{{ $namaDusun }}</td>
                                    <td align="right">{{ $namaRw }}</td>
                                    <td align="right">{{ $rt->rt }}</td>
                                    <td align="right">{{ $totalPendudukPria }}
                                    </td>
                                    <td align="right">{{ $totalPendudukWanita }}
                                    </td>
                                    <td align="right">{{ $main['bayi'][$rt->id]['total'] ?? 0 }}
                                    </td>
                                    <td align="right">{{ $main['balita'][$rt->id]['total'] ?? 0 }}
                                    </td>
                                    <td align="right">{{ $main['sd'][$rt->id]['total'] ?? 0 }}
                                    </td>
                                    <td align="right">{{ $main['smp'][$rt->id]['total'] ?? 0 }}
                                    </td>
                                    <td align="right">{{ $main['sma'][$rt->id]['total'] ?? 0 }}
                                    </td>
                                    <td align="right">{{ $main['lansia'][$rt->id]['total'] ?? 0 }}
                                    </td>
                                    @foreach ($jenis_cacat as $kode_cacat => $value)
                                        @php
                                            $cacat = $main['cacat'][$rt->id][$kode_cacat]['total'] ?? 0;

                                            if ($kode_cacat == App\Enums\CacatEnum::TIDAK_CACAT) {
                                                $cacat = $totalPenduduk - $totalBarisCacat;
                                            } else {
                                                $totalBarisCacat += $cacat;
                                            }
                                            $totalCacat[$kode_cacat] += $cacat;
                                        @endphp
                                        <td align="right">{{ $cacat }}
                                        </td>
                                    @endforeach
                                    <td align="right">
                                        {{ $main['sakit'][$rt->id][App\Enums\JenisKelaminEnum::LAKI_LAKI]['total'] ?? 0 }}
                                    </td>
                                    <td align="right">
                                        {{ $main['sakit'][$rt->id][App\Enums\JenisKelaminEnum::PEREMPUAN]['total'] ?? 0 }}
                                    </td>
                                    <td align="right">{{ $main['hamil'][$rt->id]['total'] ?? 0 }}
                                    </td>
                                    @php
                                        $bayi += $main['bayi'][$rt->id]['total'] ?? 0;
                                        $balita += $main['balita'][$rt->id]['total'] ?? 0;
                                        $sd += $main['sd'][$rt->id]['total'] ?? 0;
                                        $smp += $main['smp'][$rt->id]['total'] ?? 0;
                                        $sma += $main['sma'][$rt->id]['total'] ?? 0;
                                        $lansia += $main['lansia'][$rt->id]['total'] ?? 0;
                                        $sakit_L += $main['sakit'][App\Enums\JenisKelaminEnum::LAKI_LAKI][$rt->id]['total'] ?? 0;
                                        $sakit_P += $main['sakit'][App\Enums\JenisKelaminEnum::PEREMPUAN][$rt->id]['total'] ?? 0;
                                        $hamil += $main['hamil'][$rt->id]['total'] ?? 0;
                                    @endphp

                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot class="bg-gray disabled color-palette">
                    <tr>
                        <th colspan="5">
                            <div align="right">Total</div>
                        </th>
                        <th>
                            <div align="right">{{ $bayi }}</div>
                        </th>
                        <th>
                            <div align="right">{{ $balita }}</div>
                        </th>
                        <th>
                            <div align="right">{{ $sd }}</div>
                        </th>
                        <th>
                            <div align="right">{{ $smp }}</div>
                        </th>
                        <th>
                            <div align="right">{{ $sma }}</div>
                        </th>
                        <th>
                            <div align="right">{{ $lansia }}</div>
                        </th>
                        @foreach ($totalCacat as $cacat)
                            <th>
                                <div align="right">{{ $cacat }}</div>
                            </th>
                        @endforeach
                        <th>
                            <div align="right">{{ $sakit_L }}</div>
                        </th>
                        <th>
                            <div align="right">{{ $sakit_P }}</div>
                        </th>
                        <th>
                            <div align="right">{{ $hamil }}</div>
                        </th>
                    </tr>
                </tfoot>
            </table>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
        </div>
    </div>
    <label>Tanggal cetak : &nbsp; </label>{{ tgl_indo(date('Y m d')) }}
    </div>

</body>

</html>
