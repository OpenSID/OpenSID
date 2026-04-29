@include('admin.layouts.components.asset_datatables')

@push('styles')
<style>
.modal-xl {
    width: 95% !important;
    max-width: 1200px !important;
}

.table-responsive {
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.table thead th {
    background: linear-gradient(135deg, #dd4b39 0%, #c23321 100%);
    color: white !important;
    font-weight: 600;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
    border: none !important;
}

.table tbody tr:hover {
    background-color: #f5f5f5;
    transition: background-color 0.3s ease;
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
    background: linear-gradient(135deg, #dd4b39 0%, #c23321 100%);
    color: white;
    border: 1px solid #dd4b39;
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

.modal-header.bg-red {
    background: linear-gradient(135deg, #dd4b39 0%, #c23321 100%);
}

.age-category-0-6 {
    background: linear-gradient(45deg, #e3f2fd, #bbdefb);
    color: #1565c0;
}

.age-category-6-12 {
    background: linear-gradient(45deg, #e8f5e8, #c8e6c9);
    color: #2e7d32;
}

.age-category-12-18 {
    background: linear-gradient(45deg, #fff3e0, #ffcc02);
    color: #ef6c00;
}

.age-category-18-23 {
    background: linear-gradient(45deg, #ffebee, #ffcdd2);
    color: #c62828;
}

.indicator-health {
    background: linear-gradient(45deg, #f3e5f5, #e1bee7);
    color: #7b1fa2;
}

.indicator-environment {
    background: linear-gradient(45deg, #e0f2f1, #b2dfdb);
    color: #00695c;
}

.indicator-admin {
    background: linear-gradient(45deg, #fce4ec, #f8bbd9);
    color: #ad1457;
}

.progress-sm {
    height: 10px;
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
        <small>Bulanan Anak 0-2 Tahun</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Bulanan Anak 0-2 Tahun</li>
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
                        <i class="fa fa-child text-info"></i> Data Rekapitulasi Konvergensi Stunting Anak 0-2 Tahun
                    </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#kriteriaModal">
                            <i class="fa fa-info-circle"></i> Panduan Kategori Usia
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row mepet">
                        <div class="col-sm-12">
                            @include('admin.stunting.filter', ['urlFilter' => ci_route('stunting.rekapitulasi_bulanan_anak')])
                        </div>
                    </div>
                    <hr class="batas">
                    
                    @if ($dataFilter)
                    <!-- Ringkasan Statistik -->
                    <div class="row" style="margin-bottom: 15px;">
                        @php
                            $totalAnak = count($dataFilter);
                            $kategori0_6 = collect($dataFilter)->filter(function($item) {
                                return $item['umur_dan_gizi']['umur_bulan'] < 6;
                            })->count();
                            $kategori6_12 = collect($dataFilter)->filter(function($item) {
                                return $item['umur_dan_gizi']['umur_bulan'] >= 6 && $item['umur_dan_gizi']['umur_bulan'] <= 12;
                            })->count();
                            $kategori12_18 = collect($dataFilter)->filter(function($item) {
                                return $item['umur_dan_gizi']['umur_bulan'] > 12 && $item['umur_dan_gizi']['umur_bulan'] < 18;
                            })->count();
                            $kategori18_23 = collect($dataFilter)->filter(function($item) {
                                return $item['umur_dan_gizi']['umur_bulan'] >= 18 && $item['umur_dan_gizi']['umur_bulan'] <= 23;
                            })->count();
                            $statusNormal = collect($dataFilter)->filter(function($item) {
                                return stripos($item['umur_dan_gizi']['status_gizi'], 'normal') !== false;
                            })->count();
                            $statusStunting = collect($dataFilter)->filter(function($item) {
                                return stripos($item['umur_dan_gizi']['status_gizi'], 'stunting') !== false;
                            })->count();
                        @endphp
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>{{ $totalAnak }}</h3>
                                    <p>Total Anak (0-2 Tahun)</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-child"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>{{ $statusNormal }}</h3>
                                    <p>Status Gizi Normal</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-heart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3>{{ $statusStunting }}</h3>
                                    <p>Risiko/Stunting</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-warning"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="small-box bg-blue">
                                <div class="inner">
                                    <h3>{{ $tingkatKonvergensiDesa['persen'] ?? '0%' }}</h3>
                                    <p>Tingkat Konvergensi</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-bar-chart"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Distribusi Kategori Usia -->
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-md-3">
                            <div class="info-box age-category-0-6">
                                <span class="info-box-icon"><i class="fa fa-baby"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">0 - < 6 Bulan</span>
                                    <span class="info-box-number">{{ $kategori0_6 }}</span>
                                    <div class="progress">
                                        <div class="progress-bar bg-blue" style="width: {{ $totalAnak > 0 ? ($kategori0_6/$totalAnak)*100 : 0 }}%"></div>
                                    </div>
                                    <span class="progress-description">{{ $totalAnak > 0 ? number_format(($kategori0_6/$totalAnak)*100, 1) : 0 }}% dari total</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box age-category-6-12">
                                <span class="info-box-icon"><i class="fa fa-child"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">6 - 12 Bulan</span>
                                    <span class="info-box-number">{{ $kategori6_12 }}</span>
                                    <div class="progress">
                                        <div class="progress-bar bg-green" style="width: {{ $totalAnak > 0 ? ($kategori6_12/$totalAnak)*100 : 0 }}%"></div>
                                    </div>
                                    <span class="progress-description">{{ $totalAnak > 0 ? number_format(($kategori6_12/$totalAnak)*100, 1) : 0 }}% dari total</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box age-category-12-18">
                                <span class="info-box-icon"><i class="fa fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">12 - < 18 Bulan</span>
                                    <span class="info-box-number">{{ $kategori12_18 }}</span>
                                    <div class="progress">
                                        <div class="progress-bar bg-yellow" style="width: {{ $totalAnak > 0 ? ($kategori12_18/$totalAnak)*100 : 0 }}%"></div>
                                    </div>
                                    <span class="progress-description">{{ $totalAnak > 0 ? number_format(($kategori12_18/$totalAnak)*100, 1) : 0 }}% dari total</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box age-category-18-23">
                                <span class="info-box-icon"><i class="fa fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">18 - 23 Bulan</span>
                                    <span class="info-box-number">{{ $kategori18_23 }}</span>
                                    <div class="progress">
                                        <div class="progress-bar bg-red" style="width: {{ $totalAnak > 0 ? ($kategori18_23/$totalAnak)*100 : 0 }}%"></div>
                                    </div>
                                    <span class="progress-description">{{ $totalAnak > 0 ? number_format(($kategori18_23/$totalAnak)*100, 1) : 0 }}% dari total</span>
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
                                    <i class="fa fa-child"></i><br>Nama Anak
                                </th>
                                <th rowspan="3" class="text-center" style="vertical-align: middle; min-width: 100px;">
                                    <i class="fa fa-venus-mars"></i><br>Jenis Kelamin
                                </th>
                                <th colspan="2" class="text-center age-category-0-6" style="vertical-align: middle;">
                                    <i class="fa fa-birthday-cake"></i> Profil Anak
                                </th>
                                <th colspan="10" class="text-center indicator-health" style="vertical-align: middle;">
                                    <i class="fa fa-stethoscope"></i> Indikator Layanan Kesehatan & Pengasuhan
                                </th>
                                <th colspan="3" class="text-center indicator-admin" style="vertical-align: middle;">
                                    <i class="fa fa-bar-chart"></i> Tingkat Konvergensi Indikator
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center" style="vertical-align: middle; background-color: #e3f2fd;">
                                    <i class="fa fa-clock-o"></i><br>Umur & Kategori
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #e8f5e8;">
                                    <i class="fa fa-heart"></i><br>Status Gizi
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #f3e5f5;">
                                    <i class="fa fa-shield"></i><br>Imunisasi
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #f3e5f5;">
                                    <i class="fa fa-balance-scale"></i><br>Berat Badan
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #f3e5f5;">
                                    <i class="fa fa-arrows-v"></i><br>Tinggi Badan
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #f3e5f5;">
                                    <i class="fa fa-graduation-cap"></i><br>Konseling Gizi
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #e0f2f1;">
                                    <i class="fa fa-home"></i><br>Kunjungan Rumah
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #e0f2f1;">
                                    <i class="fa fa-tint"></i><br>Air Bersih
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #e0f2f1;">
                                    <i class="fa fa-building"></i><br>Jamban
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #fce4ec;">
                                    <i class="fa fa-certificate"></i><br>Akta Lahir
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #fce4ec;">
                                    <i class="fa fa-shield"></i><br>Jaminan Kesehatan
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #fce4ec;">
                                    <i class="fa fa-users"></i><br>Pengasuhan (PAUD)
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
                                    <td class="text-center" style="vertical-align: middle; padding: 50px;" colspan="20">
                                        <div class="alert alert-info" style="margin: 0; border: none; background: transparent;">
                                            <i class="fa fa-child fa-3x text-info" style="margin-bottom: 15px;"></i>
                                            <h4 class="text-info">Data Belum Tersedia</h4>
                                            <p class="text-muted">
                                                Belum ada data rekapitulasi anak 0-2 tahun untuk filter yang dipilih.<br>
                                                Silakan sesuaikan filter atau hubungi administrator untuk informasi lebih lanjut.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                @foreach ($dataFilter as $item)
                                    @php
                                        // Determine age category for display
                                        $umur = $item['umur_dan_gizi']['umur_bulan'];
                                        $usiaLabel = '0 - < 6 Bulan';
                                        
                                        if ($umur < 6) {
                                            $usiaLabel = '0 - < 6 Bulan';
                                        } elseif ($umur <= 12) {
                                            $usiaLabel = '6 - 12 Bulan';
                                        } elseif ($umur > 12 && $umur < 18) {
                                            $usiaLabel = '> 12 - < 18 Bulan';
                                        } else {
                                            $usiaLabel = '> 18 - 23 Bulan';
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['user']['no_kia'] }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['user']['nama'] }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ \App\Enums\JenisKelaminEnum::valueToUpper($item['user']['jenis_kelamin']) ?? 'TIDAK DIKETAHUI' }}
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <strong>{{ $item['umur_dan_gizi']['umur_bulan'] }}</strong>
                                            <br><small class="text-muted">{{ $usiaLabel }}</small>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            @php
                                                $statusGizi = strtolower($item['umur_dan_gizi']['status_gizi']);
                                                $badgeClass = 'default';
                                                if (stripos($statusGizi, 'normal') !== false) {
                                                    $badgeClass = 'success';
                                                } elseif (stripos($statusGizi, 'stunting') !== false) {
                                                    $badgeClass = 'danger';
                                                } elseif (stripos($statusGizi, 'kurang') !== false || stripos($statusGizi, 'buruk') !== false) {
                                                    $badgeClass = 'warning';
                                                }
                                            @endphp
                                            <span class="label label-{{ $badgeClass }}">
                                                {{ $item['umur_dan_gizi']['status_gizi'] }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="label label-{{ $item['indikator']['imunisasi'] == 'Y' ? 'success' : ($item['indikator']['imunisasi'] == 'T' ? 'danger' : 'default') }}">
                                                {{ $item['indikator']['imunisasi'] }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="label label-{{ $item['indikator']['pengukuran_berat_badan'] == 'Y' ? 'success' : ($item['indikator']['pengukuran_berat_badan'] == 'T' ? 'danger' : 'default') }}">
                                                {{ $item['indikator']['pengukuran_berat_badan'] }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="label label-{{ $item['indikator']['pengukuran_tinggi_badan'] == 'Y' ? 'success' : ($item['indikator']['pengukuran_tinggi_badan'] == 'T' ? 'danger' : 'default') }}">
                                                {{ $item['indikator']['pengukuran_tinggi_badan'] }}
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
                                            <span class="label label-{{ $item['indikator']['air_bersih'] == 'Y' ? 'success' : ($item['indikator']['air_bersih'] == 'T' ? 'danger' : 'default') }}">
                                                {{ $item['indikator']['air_bersih'] }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="label label-{{ $item['indikator']['jamban_sehat'] == 'Y' ? 'success' : ($item['indikator']['jamban_sehat'] == 'T' ? 'danger' : 'default') }}">
                                                {{ $item['indikator']['jamban_sehat'] }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="label label-{{ $item['indikator']['akta_lahir'] == 'Y' ? 'success' : ($item['indikator']['akta_lahir'] == 'T' ? 'danger' : 'default') }}">
                                                {{ $item['indikator']['akta_lahir'] }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="label label-{{ $item['indikator']['jaminan_kesehatan'] == 'Y' ? 'success' : ($item['indikator']['jaminan_kesehatan'] == 'T' ? 'danger' : 'default') }}">
                                                {{ $item['indikator']['jaminan_kesehatan'] }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="label label-{{ $item['indikator']['pengasuhan_paud'] == 'Y' ? 'success' : ($item['indikator']['pengasuhan_paud'] == 'T' ? 'danger' : 'default') }}">
                                                {{ $item['indikator']['pengasuhan_paud'] }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="badge bg-green">{{ $item['tingkat_konvergensi_indikator']['jumlah_diterima_lengkap'] }}</span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <span class="badge bg-blue">{{ $item['tingkat_konvergensi_indikator']['jumlah_seharusnya'] }}</span>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            @php
                                                $persen = floatval($item['tingkat_konvergensi_indikator']['persen']);
                                                $progressClass = $persen >= 80 ? 'progress-bar-success' : ($persen >= 60 ? 'progress-bar-warning' : 'progress-bar-danger');
                                            @endphp
                                            <div class="progress progress-sm" style="margin: 0;">
                                                <div class="progress-bar {{ $progressClass }}" style="width: {{ $persen }}%">
                                                    {{ $item['tingkat_konvergensi_indikator']['persen'] }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                        @if ($dataFilter)
                            <tfoot>
                                <tr>
                                    <th colspan="3" rowspan="3" class="text-center" style="vertical-align: middle;">
                                        Tingkat Capaian Konvergensi</th>
                                    <th colspan="3" class="text-center" style="vertical-align: middle;">Jumlah Diterima
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-green">{{ $capaianKonvergensi['imunisasi']['jumlah_diterima'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-green">{{ $capaianKonvergensi['pengukuran_berat_badan']['jumlah_diterima'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-green">{{ $capaianKonvergensi['pengukuran_tinggi_badan']['jumlah_diterima'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-green">{{ $capaianKonvergensi['konseling_gizi']['jumlah_diterima'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-green">{{ $capaianKonvergensi['kunjungan_rumah']['jumlah_diterima'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-green">{{ $capaianKonvergensi['air_bersih']['jumlah_diterima'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-green">{{ $capaianKonvergensi['jamban_sehat']['jumlah_diterima'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-green">{{ $capaianKonvergensi['akta_lahir']['jumlah_diterima'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-green">{{ $capaianKonvergensi['jaminan_kesehatan']['jumlah_diterima'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-green">{{ $capaianKonvergensi['pengasuhan_paud']['jumlah_diterima'] }}</span></th>
                                    <th rowspan="3" class="text-center bg-light-blue" style="vertical-align: middle;">
                                        <span class="badge bg-green">{{ $tingkatKonvergensiDesa['jumlah_diterima'] }}</span>
                                    </th>
                                    <th rowspan="3" class="text-center bg-light-blue" style="vertical-align: middle;">
                                        <span class="badge bg-blue">{{ $tingkatKonvergensiDesa['jumlah_seharusnya'] }}</span>
                                    </th>
                                    <th rowspan="3" class="text-center bg-light-blue" style="vertical-align: middle;">
                                        @php
                                            $desaPersen = floatval($tingkatKonvergensiDesa['persen']);
                                            $desaProgressClass = $desaPersen >= 80 ? 'progress-bar-success' : ($desaPersen >= 60 ? 'progress-bar-warning' : 'progress-bar-danger');
                                        @endphp
                                        <div class="progress progress-sm" style="margin: 0;">
                                            <div class="progress-bar {{ $desaProgressClass }}" style="width: {{ $desaPersen }}%">
                                                {{ $tingkatKonvergensiDesa['persen'] }}%
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-center" style="vertical-align: middle;">Jumlah
                                        Seharusnya</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-blue">{{ $capaianKonvergensi['imunisasi']['jumlah_seharusnya'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-blue">{{ $capaianKonvergensi['pengukuran_berat_badan']['jumlah_seharusnya'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-blue">{{ $capaianKonvergensi['pengukuran_tinggi_badan']['jumlah_seharusnya'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-blue">{{ $capaianKonvergensi['konseling_gizi']['jumlah_seharusnya'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-blue">{{ $capaianKonvergensi['kunjungan_rumah']['jumlah_seharusnya'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-blue">{{ $capaianKonvergensi['air_bersih']['jumlah_seharusnya'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-blue">{{ $capaianKonvergensi['jamban_sehat']['jumlah_seharusnya'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-blue">{{ $capaianKonvergensi['akta_lahir']['jumlah_seharusnya'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-blue">{{ $capaianKonvergensi['jaminan_kesehatan']['jumlah_seharusnya'] }}</span></th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-blue">{{ $capaianKonvergensi['pengasuhan_paud']['jumlah_seharusnya'] }}</span></th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-center" style="vertical-align: middle;">%</th>
                                    <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi['imunisasi']['persen'] }}%</th>
                                    <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi['pengukuran_berat_badan']['persen'] }}%</th>
                                    <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi['pengukuran_tinggi_badan']['persen'] }}%</th>
                                    <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi['konseling_gizi']['persen'] }}%</th>
                                    <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi['kunjungan_rumah']['persen'] }}%</th>
                                    <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi['air_bersih']['persen'] }}%</th>
                                    <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi['jamban_sehat']['persen'] }}%</th>
                                    <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi['akta_lahir']['persen'] }}%</th>
                                    <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi['jaminan_kesehatan']['persen'] }}%</th>
                                    <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi['pengasuhan_paud']['persen'] }}%</th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Penjelasan Kriteria -->
    <div class="modal fade" id="kriteriaModal" tabindex="-1" role="dialog" aria-labelledby="kriteriaModalLabel">
        <div class="modal-dialog modal-xl" role="document" style="width: 95%; max-width: 1200px;">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-white" id="kriteriaModalLabel">
                        <i class="fa fa-child"></i> Panduan Komprehensif Konvergensi Stunting Anak 0-2 Tahun
                    </h4>
                </div>
                <div class="modal-body">
                    <!-- Header Overview -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout callout-danger">
                                <h4><i class="fa fa-info-circle"></i> Tentang Program Konvergensi Stunting Anak 0-2 Tahun</h4>
                                <p><strong>Program Konvergensi Stunting Anak 0-2 Tahun</strong> merupakan bagian kritis dari <strong>1000 Hari Pertama Kehidupan (HPK)</strong> yang berfokus pada periode emas tumbuh kembang anak. Program ini mengintegrasikan layanan kesehatan, gizi, pengasuhan, dan lingkungan untuk mencegah stunting sejak usia dini.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#kategori-0-6" aria-controls="kategori-0-6" role="tab" data-toggle="tab">
                                <i class="fa fa-baby"></i> 0-6 Bulan
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#kategori-6-12" aria-controls="kategori-6-12" role="tab" data-toggle="tab">
                                <i class="fa fa-child"></i> 6-12 Bulan
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#kategori-12-18" aria-controls="kategori-12-18" role="tab" data-toggle="tab">
                                <i class="fa fa-user"></i> 12-18 Bulan
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#kategori-18-23" aria-controls="kategori-18-23" role="tab" data-toggle="tab">
                                <i class="fa fa-users"></i> 18-23 Bulan
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#status-gizi" aria-controls="status-gizi" role="tab" data-toggle="tab">
                                <i class="fa fa-heart"></i> Status Gizi
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" style="margin-top: 15px;">
                        <!-- Kategori 0-6 Bulan -->
                        <div role="tabpanel" class="tab-pane active" id="kategori-0-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="info-box age-category-0-6">
                                        <span class="info-box-icon"><i class="fa fa-child"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">PERIODE KRITIS</span>
                                            <span class="info-box-number">0 - < 6 Bulan</span>
                                            <div class="progress">
                                                <div class="progress-bar bg-blue" style="width: 100%"></div>
                                            </div>
                                            <span class="progress-description">
                                                Periode ASI Eksklusif & Bonding Attachment
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box box-solid box-primary">
                                        <div class="box-header with-border">
                                            <i class="fa fa-check-circle"></i>
                                            <h3 class="box-title">Indikator yang Berlaku</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr class="bg-light-blue">
                                                            <th>Indikator</th>
                                                            <th>Target Minimum</th>
                                                            <th>Kategori</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><i class="fa fa-home text-blue"></i> Kunjungan Rumah</td>
                                                            <td>2 kali</td>
                                                            <td><span class="label label-info">Layanan</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><i class="fa fa-tint text-aqua"></i> Air Bersih</td>
                                                            <td>1 kali verifikasi</td>
                                                            <td><span class="label label-success">Lingkungan</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><i class="fa fa-building text-gray"></i> Jamban Sehat</td>
                                                            <td>1 kali verifikasi</td>
                                                            <td><span class="label label-success">Lingkungan</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><i class="fa fa-shield text-green"></i> Jaminan Kesehatan</td>
                                                            <td>Kepemilikan</td>
                                                            <td><span class="label label-warning">Administrasi</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><i class="fa fa-certificate text-orange"></i> Akta Lahir</td>
                                                            <td>Kepemilikan</td>
                                                            <td><span class="label label-warning">Administrasi</span></td>
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
                                            <i class="fa fa-times-circle"></i>
                                            <h3 class="box-title">Indikator Tidak Berlaku</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="alert alert-warning">
                                                <h5><i class="fa fa-info-circle"></i> Mengapa Tidak Berlaku?</h5>
                                                <p>Pada usia 0-6 bulan, beberapa indikator belum relevan karena kondisi biologis dan kebutuhan anak yang spesifik.</p>
                                            </div>
                                            
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-times text-muted"></i> <del>Imunisasi Dasar</del> - <em>Belum waktunya</em></li>
                                                <li><i class="fa fa-times text-muted"></i> <del>Pengukuran Berat Badan</del> - <em>Belum rutin</em></li>
                                                <li><i class="fa fa-times text-muted"></i> <del>Pengukuran Tinggi Badan</del> - <em>Belum rutin</em></li>
                                                <li><i class="fa fa-times text-muted"></i> <del>Konseling Gizi</del> - <em>Fokus ASI Eksklusif</em></li>
                                                <li><i class="fa fa-times text-muted"></i> <del>Pengasuhan PAUD</del> - <em>Belum waktunya</em></li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="callout callout-info">
                                        <h5><i class="fa fa-heart"></i> Fokus Utama Usia 0-6 Bulan</h5>
                                        <ul>
                                            <li><strong>ASI Eksklusif</strong> - Tidak ada makanan/minuman lain</li>
                                            <li><strong>Bonding</strong> - Ikatan emosional ibu-anak</li>
                                            <li><strong>Lingkungan Sehat</strong> - Sanitasi dan kebersihan</li>
                                            <li><strong>Akses Layanan</strong> - Kesehatan dan administrasi</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kategori 6-12 Bulan -->
                        <div role="tabpanel" class="tab-pane" id="kategori-6-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="info-box age-category-6-12">
                                        <span class="info-box-icon"><i class="fa fa-child"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">TRANSISI MAKANAN</span>
                                            <span class="info-box-number">6 - 12 Bulan</span>
                                            <div class="progress">
                                                <div class="progress-bar bg-green" style="width: 100%"></div>
                                            </div>
                                            <span class="progress-description">
                                                Periode Pengenalan MPASI & Imunisasi Aktif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="box box-solid box-success">
                                        <div class="box-header with-border">
                                            <i class="fa fa-list-alt"></i>
                                            <h3 class="box-title">Daftar Lengkap Indikator (6-12 Bulan)</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5><i class="fa fa-stethoscope text-purple"></i> Kesehatan & Gizi:</h5>
                                                    <ul class="list-unstyled">
                                                        <li><i class="fa fa-shield text-green"></i> <strong>Imunisasi Dasar:</strong> > 0 kali<br><small class="text-muted">+ Campak jika umur > 9 bulan</small></li>
                                                        <li><i class="fa fa-balance-scale text-blue"></i> <strong>Penimbangan:</strong> Min. 5 kali/tahun<br><small class="text-muted">Pantau pertumbuhan rutin</small></li>
                                                        <li><i class="fa fa-graduation-cap text-orange"></i> <strong>Konseling Gizi:</strong> Min. 5 kali/tahun<br><small class="text-muted">Edukasi MPASI & gizi</small></li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5><i class="fa fa-home text-green"></i> Layanan & Lingkungan:</h5>
                                                    <ul class="list-unstyled">
                                                        <li><i class="fa fa-home text-blue"></i> <strong>Kunjungan Rumah:</strong> Min. 2 kali<br><small class="text-muted">Monitoring tumbuh kembang</small></li>
                                                        <li><i class="fa fa-users text-purple"></i> <strong>Pengasuhan PAUD:</strong> Min. 5 kali/tahun<br><small class="text-muted">Stimulasi dini</small></li>
                                                        <li><i class="fa fa-check text-green"></i> <strong>Indikator Lainnya:</strong> Min. 1 kali<br><small class="text-muted">Air bersih, jamban, jaminan kesehatan, akta lahir</small></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <h3>6-9</h3>
                                            <p>Imunisasi Dasar</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-shield"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="box box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Milestone Penting</h3>
                                        </div>
                                        <div class="box-body">
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-cutlery text-orange"></i> <strong>6 bulan:</strong> Mulai MPASI</li>
                                                <li><i class="fa fa-shield text-green"></i> <strong>9 bulan:</strong> Imunisasi Campak</li>
                                                <li><i class="fa fa-smile-o text-blue"></i> <strong>8-10 bulan:</strong> Duduk tanpa bantuan</li>
                                                <li><i class="fa fa-hand-paper-o text-purple"></i> <strong>10-12 bulan:</strong> Finger foods</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kategori 12-18 Bulan -->
                        <div role="tabpanel" class="tab-pane" id="kategori-12-18">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="info-box age-category-12-18">
                                        <span class="info-box-icon"><i class="fa fa-user"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">PEMBELAJARAN AKTIF</span>
                                            <span class="info-box-number">12 - < 18 Bulan</span>
                                            <div class="progress">
                                                <div class="progress-bar bg-yellow" style="width: 100%"></div>
                                            </div>
                                            <span class="progress-description">
                                                Periode Eksplorasi & Pembentukan Kebiasaan
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box box-solid box-warning">
                                        <div class="box-header with-border">
                                            <i class="fa fa-line-chart"></i>
                                            <h3 class="box-title">Peningkatan Frekuensi Layanan</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="progress-group">
                                                <span class="progress-text">Penimbangan (8x/tahun)</span>
                                                <span class="float-right"><b>67%</b> peningkatan</span>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar progress-bar-yellow" style="width: 67%"></div>
                                                </div>
                                            </div>
                                            <div class="progress-group">
                                                <span class="progress-text">Konseling Gizi (8x/tahun)</span>
                                                <span class="float-right"><b>67%</b> peningkatan</span>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar progress-bar-orange" style="width: 67%"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="alert alert-warning">
                                                <h5><i class="fa fa-exclamation-triangle"></i> Mengapa Ditingkatkan?</h5>
                                                <p>Usia 12-18 bulan adalah masa kritis untuk pembentukan pola makan dan deteksi dini masalah pertumbuhan.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="box box-solid box-info">
                                        <div class="box-header with-border">
                                            <i class="fa fa-child"></i>
                                            <h3 class="box-title">Fokus Perkembangan</h3>
                                        </div>
                                        <div class="box-body">
                                            <h5><i class="fa fa-brain text-purple"></i> Kognitif & Motorik:</h5>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-walking text-green"></i> Mulai berjalan mandiri</li>
                                                <li><i class="fa fa-comments text-blue"></i> Mengucapkan 2-3 kata</li>
                                                <li><i class="fa fa-hand-grab-o text-orange"></i> Koordinasi tangan-mata membaik</li>
                                            </ul>
                                            
                                            <h5><i class="fa fa-heart text-red"></i> Sosial & Emosional:</h5>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-smile-o text-green"></i> Meniru perilaku orang dewasa</li>
                                                <li><i class="fa fa-users text-blue"></i> Bermain bersama anak lain</li>
                                                <li><i class="fa fa-thumbs-up text-orange"></i> Menunjukkan kemandirian</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kategori 18-23 Bulan -->
                        <div role="tabpanel" class="tab-pane" id="kategori-18-23">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="info-box age-category-18-23">
                                        <span class="info-box-icon"><i class="fa fa-users"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">PERSIAPAN MANDIRI</span>
                                            <span class="info-box-number">18 - 23 Bulan</span>
                                            <div class="progress">
                                                <div class="progress-bar bg-red" style="width: 100%"></div>
                                            </div>
                                            <span class="progress-description">
                                                Periode Intensif Sebelum Usia 2 Tahun
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="box box-solid box-danger">
                                        <div class="box-header with-border">
                                            <i class="fa fa-rocket"></i>
                                            <h3 class="box-title">Intensifikasi Layanan (Target Tertinggi)</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="alert alert-danger">
                                                <h4><i class="fa fa-exclamation-triangle"></i> Periode Kritis Terakhir!</h4>
                                                <p>Usia 18-23 bulan adalah kesempatan terakhir dalam 1000 HPK untuk mencegah stunting. Semua indikator harus optimal.</p>
                                            </div>
                                            
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="bg-red">
                                                            <th>Indikator</th>
                                                            <th>Target (18-23 bulan)</th>
                                                            <th>Vs Kategori Sebelumnya</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><i class="fa fa-balance-scale"></i> Penimbangan</td>
                                                            <td><strong>15 kali/tahun</strong></td>
                                                            <td><span class="label label-danger">+88% dari 8x</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><i class="fa fa-graduation-cap"></i> Konseling Gizi</td>
                                                            <td><strong>15 kali/tahun</strong></td>
                                                            <td><span class="label label-danger">+88% dari 8x</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><i class="fa fa-shield"></i> Imunisasi</td>
                                                            <td><strong>Dasar + Campak</strong></td>
                                                            <td><span class="label label-success">Lengkap</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><i class="fa fa-users"></i> PAUD</td>
                                                            <td><strong>5 kali/tahun</strong></td>
                                                            <td><span class="label label-info">Konsisten</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <h3>1000</h3>
                                            <p>HPK Berakhir</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="callout callout-danger">
                                        <h4><i class="fa fa-target"></i> Target Akhir HPK</h4>
                                        <ul>
                                            <li><strong>Gizi Optimal:</strong> Status gizi normal</li>
                                            <li><strong>Imunitas Kuat:</strong> Imunisasi lengkap</li>
                                            <li><strong>Stimulasi Cukup:</strong> PAUD aktif</li>
                                            <li><strong>Lingkungan Sehat:</strong> Sanitasi baik</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Gizi Tab -->
                        <div role="tabpanel" class="tab-pane" id="status-gizi">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <h3><i class="fa fa-heart"></i></h3>
                                            <p>Status Gizi Normal</p>
                                        </div>
                                    </div>
                                    <div class="box box-solid">
                                        <div class="box-body">
                                            <h5><i class="fa fa-check-circle text-green"></i> Indikator Normal:</h5>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-arrow-up text-green"></i> BB/U: -2 SD s/d +1 SD</li>
                                                <li><i class="fa fa-arrow-up text-green"></i> TB/U: -2 SD s/d +3 SD</li>
                                                <li><i class="fa fa-arrow-up text-green"></i> BB/TB: -2 SD s/d +1 SD</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <h3><i class="fa fa-warning"></i></h3>
                                            <p>Gizi Kurang/Buruk</p>
                                        </div>
                                    </div>
                                    <div class="box box-solid">
                                        <div class="box-body">
                                            <h5><i class="fa fa-exclamation-triangle text-orange"></i> Indikator Kurang:</h5>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-arrow-down text-orange"></i> BB/U: < -2 SD</li>
                                                <li><i class="fa fa-arrow-down text-orange"></i> Wasting: BB/TB < -2 SD</li>
                                                <li><i class="fa fa-clock-o text-red"></i> Perlu intervensi segera</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <h3><i class="fa fa-exclamation-triangle"></i></h3>
                                            <p>Stunting</p>
                                        </div>
                                    </div>
                                    <div class="box box-solid">
                                        <div class="box-body">
                                            <h5><i class="fa fa-warning text-red"></i> Indikator Stunting:</h5>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-arrows-v text-red"></i> TB/U: < -2 SD</li>
                                                <li><i class="fa fa-exclamation text-red"></i> Pendek untuk usianya</li>
                                                <li><i class="fa fa-ambulance text-red"></i> Intervensi intensif</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box box-solid box-primary">
                                        <div class="box-header with-border">
                                            <i class="fa fa-line-chart"></i>
                                            <h3 class="box-title">Grafik Pertumbuhan & Interpretasi</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="alert alert-info">
                                                <h4><i class="fa fa-info-circle"></i> Cara Membaca Status Gizi</h4>
                                                <p>Status gizi anak dinilai berdasarkan 3 indikator antropometri utama yang dibandingkan dengan standar WHO:</p>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h5><i class="fa fa-balance-scale text-blue"></i> Berat Badan/Usia (BB/U)</h5>
                                                    <ul>
                                                        <li><span class="label label-success">Normal:</span> -2 SD sampai +1 SD</li>
                                                        <li><span class="label label-warning">Kurang:</span> -3 SD sampai < -2 SD</li>
                                                        <li><span class="label label-danger">Buruk:</span> < -3 SD</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-4">
                                                    <h5><i class="fa fa-arrows-v text-green"></i> Tinggi Badan/Usia (TB/U)</h5>
                                                    <ul>
                                                        <li><span class="label label-success">Normal:</span> -2 SD sampai +3 SD</li>
                                                        <li><span class="label label-warning">Pendek:</span> -3 SD sampai < -2 SD</li>
                                                        <li><span class="label label-danger">Sangat Pendek:</span> < -3 SD</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-4">
                                                    <h5><i class="fa fa-exchange text-orange"></i> Berat/Tinggi Badan (BB/TB)</h5>
                                                    <ul>
                                                        <li><span class="label label-success">Normal:</span> -2 SD sampai +1 SD</li>
                                                        <li><span class="label label-warning">Kurus:</span> -3 SD sampai < -2 SD</li>
                                                        <li><span class="label label-danger">Sangat Kurus:</span> < -3 SD</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer dengan informasi tambahan -->
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <div class="callout callout-danger">
                                <h4><i class="fa fa-clock-o"></i> Pentingnya 1000 Hari Pertama Kehidupan</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>1000 HPK dimulai dari:</strong></p>
                                        <ul>
                                            <li>270 hari masa kehamilan</li>
                                            <li>730 hari masa anak (0-2 tahun)</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Dampak jika terlewat:</strong></p>
                                        <ul>
                                            <li>Stunting permanen</li>
                                            <li>Perkembangan kognitif terhambat</li>
                                            <li>Produktivitas ekonomi menurun</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <small class="text-muted">
                                <i class="fa fa-info-circle"></i> 
                                Berdasarkan Pedoman Konvergensi Stunting dan Standar Antropometri WHO
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
