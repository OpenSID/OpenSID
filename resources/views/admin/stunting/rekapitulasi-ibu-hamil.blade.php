@include('admin.layouts.components.asset_datatables')

@push('styles')
<style>
.equation-box {
    background: #f8f9fa;
    border: 2px dashed #3c8dbc;
    border-radius: 10px;
    padding: 15px;
    margin: 10px 0;
}

.modal-xl {
    width: 95% !important;
    max-width: 1200px !important;
}

.table-responsive {
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.table thead th {
    background: linear-gradient(135deg, #3c8dbc 0%, #367fa9 100%);
    color: white !important;
    font-weight: 600;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
    border: none !important;
}

.table tbody tr:hover {
    background-color: #f5f5f5;
    transition: background-color 0.3s ease;
}

.progress-group {
    margin-bottom: 15px;
}

.info-box {
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    border-radius: 8px;
    transition: transform 0.2s ease;
}

.info-box:hover {
    transform: translateY(-2px);
}

.small-box {
    border-radius: 8px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.small-box:hover {
    transform: translateY(-2px);
}

.nav-tabs > li > a {
    border-radius: 8px 8px 0 0;
    font-weight: 600;
}

.nav-tabs > li.active > a,
.nav-tabs > li.active > a:hover,
.nav-tabs > li.active > a:focus {
    background: linear-gradient(135deg, #3c8dbc 0%, #367fa9 100%);
    color: white;
    border: 1px solid #3c8dbc;
}

.callout {
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.label {
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 4px;
}

.box {
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.modal-header.bg-primary {
    background: linear-gradient(135deg, #3c8dbc 0%, #367fa9 100%);
}

@media print {
    .modal-footer, .modal-header .close {
        display: none !important;
    }
}
</style>
@endpush

@extends('admin.layouts.index')

@section('title')
    <h1>
        Stunting
        <small>Bulanan Ibu Hamil</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Bulanan Ibu Hamil</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    @include('admin.stunting.widget')

    <div class="row">
        @include('admin.stunting.navigasi')

        <div class="col-md-9 col-lg-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-table text-blue"></i> Data Rekapitulasi Konvergensi Stunting Ibu Hamil
                    </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#kriteriaModal">
                            <i class="fa fa-info-circle"></i> Panduan Lengkap
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row mepet">
                        <div class="col-md-12">
                            @include('admin.stunting.filter', ['urlFilter' => ci_route('stunting.rekapitulasi_ibu_hamil')])
                        </div>
                    </div>
                    <hr class="batas">

                    @if ($dataFilter)
                        <!-- Ringkasan Statistik -->
                        <div class="row" style="margin-bottom: 15px;">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3>{{ count($dataFilter) }}</h3>
                                        <p>Total Ibu Hamil</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-female"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>{{ $tingkatKonvergensiDesa['persen'] }}</h3>
                                        <p>Tingkat Konvergensi</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3>{{ $tingkatKonvergensiDesa['jumlah_diterima'] }}</h3>
                                        <p>Indikator Terpenuhi</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-check-circle"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h3>{{ $tingkatKonvergensiDesa['jumlah_seharusnya'] }}</h3>
                                        <p>Target Indikator</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-target"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="table-responsive">
                    <table id="table-datas" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th rowspan="3" class="text-center" style="vertical-align: middle; min-width: 50px;">
                                    <i class="fa fa-list-ol"></i><br>No
                                </th>
                                <th rowspan="3" class="text-center" style="vertical-align: middle; min-width: 100px;">
                                    <i class="fa fa-id-card"></i><br>NO KIA
                                </th>
                                <th rowspan="3" class="text-center" style="vertical-align: middle; min-width: 150px;">
                                    <i class="fa fa-female"></i><br>Nama Ibu
                                </th>
                                <th rowspan="3" class="text-center" style="vertical-align: middle; min-width: 100px;">
                                    <i class="fa fa-heartbeat"></i><br>Status Kehamilan
                                </th>
                                <th colspan="2" class="text-center" style="vertical-align: middle; background-color: #e8f5e8;">
                                    <i class="fa fa-calendar"></i> Usia Kehamilan dan Persalinan
                                </th>
                                <th colspan="8" class="text-center" style="vertical-align: middle; background-color: #e8f4f8;">
                                    <i class="fa fa-check-square"></i> Status Penerimaan Indikator
                                </th>
                                <th colspan="3" class="text-center" style="vertical-align: middle; background-color: #fff3cd;">
                                    <i class="fa fa-bar-chart"></i> Tingkat Konvergensi Indikator
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center" style="vertical-align: middle; background-color: #e8f5e8;">
                                    <i class="fa fa-clock-o"></i><br>Usia (Bulan)
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #e8f5e8;">
                                    <i class="fa fa-calendar-check-o"></i><br>Tanggal Melahirkan
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #e8f4f8;">
                                    <i class="fa fa-stethoscope"></i><br>Periksa Kehamilan
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #e8f4f8;">
                                    <i class="fa fa-medkit"></i><br>Pil Fe
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #e8f4f8;">
                                    <i class="fa fa-user-md"></i><br>Periksa Nifas
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #e8f4f8;">
                                    <i class="fa fa-graduation-cap"></i><br>Konseling Gizi
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #e8f4f8;">
                                    <i class="fa fa-home"></i><br>Kunjungan Rumah
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #e8f4f8;">
                                    <i class="fa fa-tint"></i><br>Air Bersih
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #e8f4f8;">
                                    <i class="fa fa-building"></i><br>Jamban
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #e8f4f8;">
                                    <i class="fa fa-shield"></i><br>Jaminan Kesehatan
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #fff3cd;">
                                    <i class="fa fa-check"></i><br>Diterima
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #fff3cd;">
                                    <i class="fa fa-target"></i><br>Seharusnya
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #fff3cd;">
                                    <i class="fa fa-percent"></i><br>%
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$dataFilter)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle; padding: 50px;" colspan="17">
                                        <div class="alert alert-info" style="margin: 0; border: none; background: transparent;">
                                            <i class="fa fa-info-circle fa-3x text-info" style="margin-bottom: 15px;"></i>
                                            <h4 class="text-info">Data Tidak Ditemukan</h4>
                                            <p class="text-muted">
                                                Belum ada data rekapitulasi ibu hamil untuk filter yang dipilih.<br>
                                                Silakan sesuaikan filter atau hubungi administrator untuk informasi lebih lanjut.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                @foreach ($dataFilter as $item)
                                    {{-- {{ die(json_encode($item[1]['no_kia'])) }} --}}
                                    <tr>
                                        <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['user']['no_kia'] }}</td>
                                        <td style="vertical-align: middle;">{{ $item['user']['nama_ibu'] }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            @php
                                                $status = $item['user']['status_kehamilan'] == 1 ? 'NORMAL' : ($item['user']['status_kehamilan'] == 2 ? 'RISTI' : ($item['user']['status_kehamilan'] == 3 ? 'KEK' : '-'));
                                                $badgeClass = $item['user']['status_kehamilan'] == 1 ? 'success' : ($item['user']['status_kehamilan'] == 2 ? 'warning' : ($item['user']['status_kehamilan'] == 3 ? 'danger' : 'default'));
                                            @endphp
                                            <span class="label label-{{ $badgeClass }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['user']['usia_kehamilan'] ?? '-' }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['user']['tanggal_melahirkan'] == '-' ? $item['user']['tanggal_melahirkan'] : tgl_indo($item['user']['tanggal_melahirkan']) }}
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="label label-{{ $item['indikator']['periksa_kehamilan'] == 'Y' ? 'success' : ($item['indikator']['periksa_kehamilan'] == 'T' ? 'danger' : 'default') }}">
                                                {{ $item['indikator']['periksa_kehamilan'] }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="label label-{{ $item['indikator']['pil_fe'] == 'Y' ? 'success' : ($item['indikator']['pil_fe'] == 'T' ? 'danger' : 'default') }}">
                                                {{ $item['indikator']['pil_fe'] }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="label label-{{ $item['indikator']['pemeriksaan_nifas'] == 'Y' ? 'success' : ($item['indikator']['pemeriksaan_nifas'] == 'T' ? 'danger' : 'default') }}">
                                                {{ $item['indikator']['pemeriksaan_nifas'] }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="label label-{{ $item['indikator']['konseling_gizi'] == 'Y' ? 'success' : ($item['indikator']['konseling_gizi'] == 'T' ? 'danger' : 'default') }}">
                                                {{ $item['indikator']['konseling_gizi'] }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="label label-{{ $item['indikator']['kunjungan_rumah'] == 'Y' ? 'success' : ($item['indikator']['kunjungan_rumah'] == 'T' ? 'danger' : 'default') }}">
                                                {{ $item['indikator']['kunjungan_rumah'] }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="label label-{{ $item['indikator']['akses_air_bersih'] == 'Y' ? 'success' : ($item['indikator']['akses_air_bersih'] == 'T' ? 'danger' : 'default') }}">
                                                {{ $item['indikator']['akses_air_bersih'] }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="label label-{{ $item['indikator']['kepemilikan_jamban'] == 'Y' ? 'success' : ($item['indikator']['kepemilikan_jamban'] == 'T' ? 'danger' : 'default') }}">
                                                {{ $item['indikator']['kepemilikan_jamban'] }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="label label-{{ $item['indikator']['jaminan_kesehatan'] == 'Y' ? 'success' : ($item['indikator']['jaminan_kesehatan'] == 'T' ? 'danger' : 'default') }}">
                                                {{ $item['indikator']['jaminan_kesehatan'] }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['konvergensi_indikator']['jumlah_diterima_lengkap'] }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['konvergensi_indikator']['jumlah_seharusnya'] }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            @php
                                                $persen = (float) str_replace('%', '', $item['konvergensi_indikator']['persen']);
                                                $badgeClass = $persen >= 80 ? 'success' : ($persen >= 60 ? 'warning' : 'danger');
                                            @endphp
                                            <span class="label label-{{ $badgeClass }}">
                                                {{ $item['konvergensi_indikator']['persen'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        @if ($dataFilter)
                            <tfoot>
                                <tr style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                    <th colspan="4" rowspan="3" class="text-center" style="vertical-align: middle; font-weight: bold;">
                                        <i class="fa fa-bar-chart text-primary"></i><br>Tingkat Capaian Konvergensi
                                    </th>
                                    <th colspan="2" class="text-center" style="vertical-align: middle; background-color: #d4edda;">
                                        <i class="fa fa-check-circle text-success"></i> Jumlah Diterima
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['periksa_kehamilan']['Y'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['pil_fe']['Y'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['pemeriksaan_nifas']['Y'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['konseling_gizi']['Y'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['kunjungan_rumah']['Y'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['akses_air_bersih']['Y'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['kepemilikan_jamban']['Y'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['jaminan_kesehatan']['Y'] }}</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">
                                        {{ $tingkatKonvergensiDesa['jumlah_diterima'] }}</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">
                                        {{ $tingkatKonvergensiDesa['jumlah_seharusnya'] }}</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">
                                        {{ $tingkatKonvergensiDesa['persen'] }}</th>
                                </tr>
                                <tr style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                    <th colspan="2" class="text-center" style="vertical-align: middle; background-color: #fff3cd;">
                                        <i class="fa fa-target text-warning"></i> Jumlah Seharusnya
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['periksa_kehamilan']['jumlah_seharusnya'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['pil_fe']['jumlah_seharusnya'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['pemeriksaan_nifas']['jumlah_seharusnya'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['konseling_gizi']['jumlah_seharusnya'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['kunjungan_rumah']['jumlah_seharusnya'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['akses_air_bersih']['jumlah_seharusnya'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['kepemilikan_jamban']['jumlah_seharusnya'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['jaminan_kesehatan']['jumlah_seharusnya'] }}</th>
                                </tr>
                                <tr style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                    <th colspan="2" class="text-center" style="vertical-align: middle; background-color: #f8d7da;">
                                        <i class="fa fa-percent text-danger"></i> Persentase (%)
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['periksa_kehamilan']['persen'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['pil_fe']['persen'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['pemeriksaan_nifas']['persen'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['konseling_gizi']['persen'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['kunjungan_rumah']['persen'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['akses_air_bersih']['persen'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['kepemilikan_jamban']['persen'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['jaminan_kesehatan']['persen'] }}</th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Kriteria Modal -->
    <div class="modal fade" id="kriteriaModal" tabindex="-1" role="dialog" aria-labelledby="kriteriaModalLabel">
        <div class="modal-dialog modal-xl" role="document" style="width: 95%; max-width: 1200px;">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-white" id="kriteriaModalLabel">
                        <i class="fa fa-female"></i> Panduan Komprehensif Indikator Stunting Ibu Hamil
                    </h4>
                </div>
                <div class="modal-body">
                    <!-- Header Overview -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout callout-info">
                                <h4><i class="fa fa-info-circle"></i> Tentang Program Konvergensi Stunting</h4>
                                <p><strong>Program Konvergensi Stunting</strong> bertujuan memastikan ibu hamil mendapat pelayanan kesehatan optimal selama kehamilan hingga nifas, serta dukungan lingkungan yang kondusif untuk mencegah stunting pada anak yang akan dilahirkan melalui pendekatan <strong>1000 Hari Pertama Kehidupan (HPK)</strong>.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#status-kehamilan" aria-controls="status-kehamilan" role="tab" data-toggle="tab">
                                <i class="fa fa-heartbeat"></i> Status Kehamilan
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#pelayanan-kesehatan" aria-controls="pelayanan-kesehatan" role="tab" data-toggle="tab">
                                <i class="fa fa-stethoscope"></i> Pelayanan Kesehatan
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#layanan-pendukung" aria-controls="layanan-pendukung" role="tab" data-toggle="tab">
                                <i class="fa fa-support"></i> Layanan Pendukung
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#konvergensi" aria-controls="konvergensi" role="tab" data-toggle="tab">
                                <i class="fa fa-bar-chart"></i> Tingkat Konvergensi
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" style="margin-top: 15px;">
                        <!-- Status Kehamilan Tab -->
                        <div role="tabpanel" class="tab-pane active" id="status-kehamilan">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-box bg-green">
                                        <span class="info-box-icon"><i class="fa fa-heart"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">NORMAL</span>
                                            <span class="info-box-number">Kondisi Optimal</span>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: 100%"></div>
                                            </div>
                                            <span class="progress-description">
                                                Kehamilan dengan kondisi baik tanpa komplikasi
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box bg-orange">
                                        <span class="info-box-icon"><i class="fa fa-warning"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">RISTI</span>
                                            <span class="info-box-number">Risiko Tinggi</span>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: 70%"></div>
                                            </div>
                                            <span class="progress-description">
                                                Kehamilan dengan Risiko Tinggi - Perlu Perhatian Khusus
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box bg-red">
                                        <span class="info-box-icon"><i class="fa fa-exclamation-triangle"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">KEK</span>
                                            <span class="info-box-number">Kekurangan Energi</span>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: 40%"></div>
                                            </div>
                                            <span class="progress-description">
                                                Kekurangan Energi Kronis (LILA < 23.5 cm)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <h4><i class="fa fa-stethoscope"></i> Faktor Risiko KEK pada Ibu Hamil</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul>
                                            <li>Lingkar Lengan Atas (LILA) < 23,5 cm</li>
                                            <li>Berat badan sebelum hamil < 45 kg</li>
                                            <li>Tinggi badan < 145 cm</li>
                                            <li>Penambahan berat badan tidak sesuai standar</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul>
                                            <li>Riwayat melahirkan bayi BBLR</li>
                                            <li>Jarak kehamilan terlalu dekat</li>
                                            <li>Usia hamil < 20 tahun atau > 35 tahun</li>
                                            <li>Status ekonomi rendah</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pelayanan Kesehatan Tab -->
                        <div role="tabpanel" class="tab-pane" id="pelayanan-kesehatan">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box box-solid box-success">
                                        <div class="box-header with-border">
                                            <i class="fa fa-calendar-check-o"></i>
                                            <h3 class="box-title">Pemeriksaan Kehamilan (K1-K4)</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr class="bg-light-blue">
                                                            <th>Kunjungan</th>
                                                            <th>Usia Kehamilan</th>
                                                            <th>Pemeriksaan Utama</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><span class="label label-info">K1</span></td>
                                                            <td>0-12 minggu</td>
                                                            <td>Anamnesis, pemeriksaan fisik, HB</td>
                                                        </tr>
                                                        <tr>
                                                            <td><span class="label label-info">K2</span></td>
                                                            <td>13-27 minggu</td>
                                                            <td>Monitoring janin, TT1</td>
                                                        </tr>
                                                        <tr>
                                                            <td><span class="label label-info">K3</span></td>
                                                            <td>28-35 minggu</td>
                                                            <td>Persiapan persalinan, TT2</td>
                                                        </tr>
                                                        <tr>
                                                            <td><span class="label label-info">K4</span></td>
                                                            <td>36-40 minggu</td>
                                                            <td>Persiapan mental, rencana KB</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="box box-solid box-warning">
                                        <div class="box-header with-border">
                                            <i class="fa fa-medkit"></i>
                                            <h3 class="box-title">Suplementasi & Nifas</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="progress-group">
                                                <span class="progress-text">Tablet Fe (Zat Besi)</span>
                                                <span class="float-right"><b>90</b>/90 tablet</span>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar progress-bar-green" style="width: 100%"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="progress-group">
                                                <span class="progress-text">Pemeriksaan Nifas</span>
                                                <span class="float-right"><b>3</b>/3 kali</span>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar progress-bar-blue" style="width: 100%"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="progress-group">
                                                <span class="progress-text">Kelas Ibu Hamil</span>
                                                <span class="float-right"><b>3</b>/3 kali</span>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar progress-bar-purple" style="width: 100%"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="callout callout-warning callout-sm">
                                                <h5><i class="fa fa-lightbulb-o"></i> Tips Konsumsi Fe</h5>
                                                <p>Minum tablet Fe dengan air putih atau jus jeruk, hindari teh/kopi. Konsumsi malam hari untuk mengurangi mual.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Layanan Pendukung Tab -->
                        <div role="tabpanel" class="tab-pane" id="layanan-pendukung">
                            <div class="row">
                                <div class="col-lg-3 col-xs-6">
                                    <div class="small-box bg-blue">
                                        <div class="inner">
                                            <h3>2x</h3>
                                            <p>Kunjungan Rumah</p>
                                        </div>
                                    </div>
                                    <div class="box box-solid">
                                        <div class="box-body">
                                            <p><strong>Tujuan:</strong></p>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-green"></i> Monitoring kesehatan ibu</li>
                                                <li><i class="fa fa-check text-green"></i> Edukasi keluarga</li>
                                                <li><i class="fa fa-check text-green"></i> Deteksi dini komplikasi</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="small-box bg-aqua">
                                        <div class="inner">
                                            <h3><i class="fa fa-tint"></i></h3>
                                            <p>Air Bersih</p>
                                        </div>
                                    </div>  
                                    <div class="box box-solid">
                                        <div class="box-body">
                                            <p><strong>Kriteria:</strong></p>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-green"></i> Tidak berbau, berasa, berwarna</li>
                                                <li><i class="fa fa-check text-green"></i> Bebas dari bakteri E.coli</li>
                                                <li><i class="fa fa-check text-green"></i> Mudah diakses keluarga</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="small-box bg-purple">
                                        <div class="inner">
                                            <h3><i class="fa fa-building"></i></h3>
                                            <p>Jamban Sehat</p>
                                        </div>
                                    </div>
                                    <div class="box box-solid">
                                        <div class="box-body">
                                            <p><strong>Syarat:</strong></p>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-green"></i> Tertutup rapat</li>
                                                <li><i class="fa fa-check text-green"></i> Jarak > 10m dari sumber air</li>
                                                <li><i class="fa fa-check text-green"></i> Mudah dibersihkan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <h3><i class="fa fa-shield"></i></h3>
                                            <p>Jaminan Kesehatan</p>
                                        </div>
                                    </div>
                                    <div class="box box-solid">
                                        <div class="box-body">
                                            <p><strong>Jenis:</strong></p>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-green"></i> BPJS Kesehatan</li>
                                                <li><i class="fa fa-check text-green"></i> Asuransi Swasta</li>
                                                <li><i class="fa fa-check text-green"></i> Jampersal</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tingkat Konvergensi Tab -->
                        <div role="tabpanel" class="tab-pane" id="konvergensi">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="box box-solid box-primary">
                                        <div class="box-header with-border">
                                            <i class="fa fa-calculator"></i>
                                            <h3 class="box-title">Cara Perhitungan Konvergensi</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="equation-box">
                                                <h4 class="text-center">
                                                    <span class="label label-info">Persentase Konvergensi</span> = 
                                                    <span class="label label-success">Jumlah Indikator Diterima</span> ÷ 
                                                    <span class="label label-warning">Jumlah Indikator Seharusnya</span> × 100%
                                                </h4>
                                            </div>
                                            
                                            <div class="row" style="margin-top: 20px;">
                                                <div class="col-md-6">
                                                    <h5><i class="fa fa-check-circle text-green"></i> Indikator yang Dinilai (8 Total):</h5>
                                                    <ol>
                                                        <li>Pemeriksaan Kehamilan ≥ 4 kali</li>
                                                        <li>Konsumsi Pil Fe ≥ 90 tablet</li>
                                                        <li>Pemeriksaan Nifas ≥ 3 kali</li>
                                                        <li>Konseling Gizi ≥ 3 kali</li>
                                                        <li>Kunjungan Rumah ≥ 2 kali</li>
                                                        <li>Akses Air Bersih</li>
                                                        <li>Kepemilikan Jamban Sehat</li>
                                                        <li>Jaminan Kesehatan</li>
                                                    </ol>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5><i class="fa fa-bar-chart text-blue"></i> Kategori Capaian:</h5>
                                                    <div class="progress-group">
                                                        <span class="progress-text">Tinggi (≥80%)</span>
                                                        <span class="float-right"><span class="label label-success">Sangat Baik</span></span>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar progress-bar-success" style="width: 100%"></div>
                                                        </div>
                                                    </div>
                                                    <div class="progress-group">
                                                        <span class="progress-text">Sedang (60-79%)</span>
                                                        <span class="float-right"><span class="label label-warning">Perlu Perbaikan</span></span>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar progress-bar-warning" style="width: 70%"></div>
                                                        </div>
                                                    </div>
                                                    <div class="progress-group">
                                                        <span class="progress-text">Rendah (<60%)</span>
                                                        <span class="float-right"><span class="label label-danger">Perlu Perhatian Khusus</span></span>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar progress-bar-danger" style="width: 40%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="info-box bg-yellow">
                                        <span class="info-box-icon"><i class="fa fa-question-circle"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Keterangan Status</span>
                                            <span class="info-box-number">Y / T / -</span>
                                        </div>
                                    </div>
                                    
                                    <div class="box box-solid">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-xs-12" style="margin-bottom: 10px;">
                                                    <span class="label label-success" style="width: 30px; display: inline-block; text-align: center;">Y</span>
                                                    <span style="margin-left: 10px;">Ya - Memenuhi Syarat</span>
                                                </div>
                                                <div class="col-xs-12" style="margin-bottom: 10px;">
                                                    <span class="label label-danger" style="width: 30px; display: inline-block; text-align: center;">T</span>
                                                    <span style="margin-left: 10px;">Tidak - Tidak Memenuhi Syarat</span>
                                                </div>
                                                <div class="col-xs-12">
                                                    <span class="label label-default" style="width: 30px; display: inline-block; text-align: center;">-</span>
                                                    <span style="margin-left: 10px;">Data Tidak Tersedia</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="callout callout-info">
                                        <h5><i class="fa fa-lightbulb-o"></i> Tips Meningkatkan Konvergensi</h5>
                                        <ul class="list-unstyled">
                                            <li>• Sosialisasi rutin kepada ibu hamil</li>
                                            <li>• Koordinasi lintas sektor</li>
                                            <li>• Monitoring berkala</li>
                                            <li>• Pendampingan intensif</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer dengan informasi tambahan -->
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <div class="callout callout-success">
                                <h4><i class="fa fa-target"></i> Tujuan Program 1000 HPK</h4>
                                <p>Program ini merupakan bagian dari upaya pencegahan stunting yang berfokus pada <strong>1000 Hari Pertama Kehidupan</strong> mulai dari masa kehamilan hingga anak berusia 2 tahun. Melalui konvergensi berbagai indikator, diharapkan dapat menurunkan angka stunting dan meningkatkan kualitas generasi penerus bangsa.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <small class="text-muted">
                                <i class="fa fa-info-circle"></i> 
                                Informasi ini berdasarkan Pedoman Konvergensi Stunting Kementerian Kesehatan RI
                            </small>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
