@extends('admin.layouts.index')
@section('title')
    <h1>
        Laporan Kelompok Rentan
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Laporan Kelompok Rentan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-12">
            <form id="mainform" name="mainform" action="{{ ci_route('laporan.bulan') }}" method="post" class="form-horizontal">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <a href="{{ ci_route('laporan_rentan.cetak.cetak') }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank"><i class="fa fa-print "></i> Cetak</a>
                        <a href="{{ ci_route('laporan_rentan.cetak.unduh') }}" class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank"><i class="fa  fa-download"></i> Unduh</a>
                    </div>
                    <div class="box-header  with-border">
                        <h4 class="text-center"><strong>PEMERINTAH KABUPATEN/KOTA
                                {{ strtoupper($desa['nama_kabupaten']) }}</strong></h4>
                        <h5 class="text-center"><strong>DATA PILAH KEPENDUDUKAN MENURUT UMUR DAN FAKTOR KERENTANAN (LAMPIRAN
                                A - 9)</strong></h5>
                    </div>
                    <div class="box-header  with-border">
                        <div class="form-group">
                            <label class="col-sm-2 col-md-1 control-label" for="kelurahan">{{ ucwords(setting('sebutan_desa')) }}/Kel</label>
                            <div class="col-sm-4 col-md-2">
                                <input type="text" class="form-control input-sm" value="{{ $desa['nama_desa'] }}" disabled /></input>
                            </div>
                            <label class="col-sm-2 col-md-1 control-label" for="kecamatan">{{ ucwords(setting('sebutan_kecamatan')) }}</label>
                            <div class="col-sm-4 col-md-2">
                                <input type="text" class="form-control input-sm" value="{{ $desa['nama_kecamatan'] }}" disabled /></input>
                            </div>
                            <label class="col-sm-2 col-md-1 control-label" for="laporan">Lap. Bulan</label>
                            <div class="col-sm-4 col-md-2">
                                <input type="text" class="form-control input-sm" value="{{ getBulan(date('m')) }}" disabled /></input>
                            </div>
                            <label class="col-sm-2 col-md-1 control-label" for="filter">{{ ucwords(setting('sebutan_dusun')) }}</label>
                            <div class="col-sm-4 col-md-2">
                                <select class="form-control input-sm select2" name="dusun" onchange="formAction('mainform','{{ ci_route('laporan_rentan.dusun') }}')">
                                    <option value="">Pilih {{ ucwords(setting('sebutan_dusun')) }}</option>
                                    @foreach ($wilayah as $keyDusun => $dusun)
                                        <option value="{{ $keyDusun }}" @selected($keyDusun == $dusunTerpilih)>
                                            {{ $keyDusun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        @if ($dusunTerpilih != '')
                            <h4>DATA PILAH {{ strtoupper(setting('sebutan_dusun')) }} {{ $dusunTerpilih }}</h4>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover nowrap">
                                <thead class="bg-gray">
                                    <tr>
                                        <th rowspan="2" class="text-center">{{ ucwords(setting('sebutan_dusun')) }}</th>
                                        <th rowspan="2" class="text-center">RW</th>
                                        <th rowspan="2" class="text-center">RT</th>
                                        <th colspan="2" class="text-center">KK</th>
                                        <th colspan="6" class="text-center">Kondisi dan Kelompok Umur</th>
                                        <th colspan="7" class="text-center">Cacat</th>
                                        <th colspan="2" class="text-center">Sakit Menahun</th>
                                        <th rowspan="2" class="text-center">Hamil</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">L</th>
                                        <th class="text-center">P</th>
                                        <th class="text-center">Dibawah 1 Tahun</th>
                                        <th class="text-center">1-5 Tahun</th>
                                        <th class="text-center">6-12 Tahun</th>
                                        <th class="text-center">13-15 Tahun</th>
                                        <th class="text-center">16-18 Tahun</th>
                                        <th class="text-center">Diatas 60 Tahun</th>
                                        <th class="text-center">Cacat Fisik</th>
                                        <th class="text-center">Cacat Netra/ Buta</th>
                                        <th class="text-center">Cacat Rungu/ Wicara</th>
                                        <th class="text-center">Cacat Mental/ Jiwa</th>
                                        <th class="text-center">Cacat Fisik dan Mental</th>
                                        <th class="text-center">Cacat Lainnya</th>
                                        <th class="text-center">Tidak Cacat</th>
                                        <th class="text-center">L</th>
                                        <th class="text-center">P</th>
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
                                                    <td class="text-right">{{ $namaDusun }}</td>
                                                    <td class="text-right">{{ $namaRw }}</td>
                                                    <td class="text-right">{{ $rt->rt }}</td>
                                                    <td class="text-right"><a href="{{ ci_route("penduduk.lap_statistik.{$rt->id}.1") }}?dusun={{ $namaDusun }}&rw={{ $namaRw }}&rt={{ $rt->rt }}">{{ $totalPendudukPria }}</a>
                                                    </td>
                                                    <td class="text-right"><a href="{{ ci_route("penduduk.lap_statistik.{$rt->id}.2") }}?dusun={{ $namaDusun }}&rw={{ $namaRw }}&rt={{ $rt->rt }}">{{ $totalPendudukWanita }}</a>
                                                    </td>
                                                    <td class="text-right"><a href="{{ ci_route("penduduk.lap_statistik.{$rt->id}.3") }}?dusun={{ $namaDusun }}&rw={{ $namaRw }}&rt={{ $rt->rt }}">{{ $main['bayi'][$rt->id]['total'] ?? 0 }}</a>
                                                    </td>
                                                    <td class="text-right"><a href="{{ ci_route("penduduk.lap_statistik.{$rt->id}.4") }}?dusun={{ $namaDusun }}&rw={{ $namaRw }}&rt={{ $rt->rt }}">{{ $main['balita'][$rt->id]['total'] ?? 0 }}</a>
                                                    </td>
                                                    <td class="text-right"><a href="{{ ci_route("penduduk.lap_statistik.{$rt->id}.5") }}?dusun={{ $namaDusun }}&rw={{ $namaRw }}&rt={{ $rt->rt }}">{{ $main['sd'][$rt->id]['total'] ?? 0 }}</a>
                                                    </td>
                                                    <td class="text-right"><a href="{{ ci_route("penduduk.lap_statistik.{$rt->id}.6") }}?dusun={{ $namaDusun }}&rw={{ $namaRw }}&rt={{ $rt->rt }}">{{ $main['smp'][$rt->id]['total'] ?? 0 }}</a>
                                                    </td>
                                                    <td class="text-right"><a href="{{ ci_route("penduduk.lap_statistik.{$rt->id}.7") }}?dusun={{ $namaDusun }}&rw={{ $namaRw }}&rt={{ $rt->rt }}">{{ $main['sma'][$rt->id]['total'] ?? 0 }}</a>
                                                    </td>
                                                    <td class="text-right"><a href="{{ ci_route("penduduk.lap_statistik.{$rt->id}.8") }}?dusun={{ $namaDusun }}&rw={{ $namaRw }}&rt={{ $rt->rt }}">{{ $main['lansia'][$rt->id]['total'] ?? 0 }}</a>
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
                                                        <td class="text-right"><a href="{{ ci_route("penduduk.lap_statistik.{$rt->id}.9{$kode_cacat}") }}?dusun={{ $namaDusun }}&rw={{ $namaRw }}&rt={{ $rt->rt }}">{{ $cacat }}</a>
                                                        </td>
                                                    @endforeach
                                                    <td class="text-right"><a href="{{ ci_route("penduduk.lap_statistik.{$rt->id}.10") }}?dusun={{ $namaDusun }}&rw={{ $namaRw }}&rt={{ $rt->rt }}">{{ $main['sakit'][$rt->id][App\Enums\JenisKelaminEnum::LAKI_LAKI]['total'] ?? 0 }}</a>
                                                    </td>
                                                    <td class="text-right"><a href="{{ ci_route("penduduk.lap_statistik.{$rt->id}.11") }}?dusun={{ $namaDusun }}&rw={{ $namaRw }}&rt={{ $rt->rt }}">{{ $main['sakit'][$rt->id][App\Enums\JenisKelaminEnum::PEREMPUAN]['total'] ?? 0 }}</a>
                                                    </td>
                                                    <td class="text-right"><a href="{{ ci_route("penduduk.lap_statistik.{$rt->id}.12") }}?dusun={{ $namaDusun }}&rw={{ $namaRw }}&rt={{ $rt->rt }}">{{ $main['hamil'][$rt->id]['total'] ?? 0 }}</a>
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
                                        <th colspan="5" class="text-right">Total</th>
                                        <th class="text-right">{{ $bayi }}</th>
                                        <th class="text-right">{{ $balita }}</th>
                                        <th class="text-right">{{ $sd }}</th>
                                        <th class="text-right">{{ $smp }}</th>
                                        <th class="text-right">{{ $sma }}</th>
                                        <th class="text-right">{{ $lansia }}</th>
                                        @foreach ($totalCacat as $cacat)
                                            <th class="total text-right">{{ $cacat }}</th>
                                        @endforeach
                                        <th class="text-right">{{ $sakit_L }}</th>
                                        <th class="text-right">{{ $sakit_P }}</th>
                                        <th class="text-right">{{ $hamil }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
