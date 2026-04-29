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
    background: linear-gradient(135deg, #00a65a 0%, #008d4c 100%);
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
    background: linear-gradient(135deg, #00a65a 0%, #008d4c 100%);
    color: white;
    border: 1px solid #00a65a;
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

.modal-header.bg-success {
    background: linear-gradient(135deg, #00a65a 0%, #008d4c 100%);
}

.attendance-badge {
    font-size: 12px;
    padding: 6px 10px;
    border-radius: 50%;
    font-weight: bold;
    min-width: 25px;
    text-align: center;
}

.category-highlight {
    background: linear-gradient(45deg, #f39c12, #e67e22);
    color: white;
}

.paud-highlight {
    background: linear-gradient(45deg, #3c8dbc, #2c5282);
    color: white;
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
        <small>Bulanan Anak 2-6 Tahun</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Bulanan Anak 2-6 Tahun</li>
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
                        <i class="fa fa-graduation-cap text-primary"></i> Data Rekapitulasi Layanan PAUD Anak 2-6 Tahun
                    </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#paudKriteriaModal">
                            <i class="fa fa-info-circle"></i> Panduan PAUD
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row mepet">
                        <div class="col-md-12">
                            @include('admin.stunting.filter', ['urlFilter' => ci_route('stunting.rekapitulasi_bulanan_balita')])
                        </div>
                    </div>
                    <hr class="batas">

                    @if(count($dataFilter) > 0)
                        <!-- Ringkasan Statistik -->
                        <div class="row" style="margin-bottom: 15px;">
                            @php
                                $totalAnak = count($dataFilter);
                                $kategori1 = $dataFilter->where('kategori_usia', 1)->count();
                                $kategori2 = $dataFilter->where('kategori_usia', 2)->count();
                                $laki = $dataFilter->filter(function($item) {
                                    return $item->kia && $item->kia->anak && $item->kia->anak->sex == 1;
                                })->count();
                                $perempuan = $totalAnak - $laki;
                            @endphp
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3>{{ $totalAnak }}</h3>
                                        <p>Total Anak PAUD</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>{{ $kategori1 }}</h3>
                                        <p>Anak 2-3 Tahun</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-child"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3>{{ $kategori2 }}</h3>
                                        <p>Anak 3-6 Tahun</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-graduation-cap"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h3>{{ $laki }}/{{ $perempuan }}</h3>
                                        <p>Laki-laki / Perempuan</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-venus-mars"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="table-responsive">
                    <table id="tabeldata" class="table table-bordered table-hover table-striped">
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
                                <th colspan="2" class="text-center category-highlight" style="vertical-align: middle;">
                                    <i class="fa fa-users"></i> Kategori Usia PAUD
                                </th>
                                <th colspan="{{ $akhirBulan - $awalBulan + 1 }}" class="text-center paud-highlight" style="vertical-align: middle;">
                                    <i class="fa fa-graduation-cap"></i> Status Kehadiran Layanan PAUD per Bulan
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center" style="vertical-align: middle; background-color: #ffeaa7;">
                                    <i class="fa fa-baby"></i><br>2 - &lt; 3 Tahun<br><small>(Parenting)</small>
                                </th>
                                <th class="text-center" style="vertical-align: middle; background-color: #fdcb6e;">
                                    <i class="fa fa-graduation-cap"></i><br>3 - 6 Tahun<br><small>(Kelas PAUD)</small>
                                </th>
                                @for ($i = $awalBulan; $i <= $akhirBulan; $i++)
                                    <th class="text-center" style="vertical-align: middle; background-color: #ddd6fe; min-width: 80px;">
                                        <i class="fa fa-calendar"></i><br>{{ getBulan($i) }}
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        @forelse ($dataFilter as $item)
                            <tr>
                                <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item->kia->no_kia }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item->kia->anak->nama }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ App\Enums\JenisKelaminEnum::valueOf($item->kia->anak->sex) }}</td>
                                <td class="text-center" style="vertical-align: middle;">
                                    @if($item->kategori_usia == 1)
                                        <span class="label label-success">v</span>
                                    @else
                                        <span class="label label-default">-</span>
                                    @endif
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    @if($item->kategori_usia == 2)
                                        <span class="label label-success">v</span>
                                    @else
                                        <span class="label label-default">-</span>
                                    @endif
                                </td>
                                @for ($i = $awalBulan; $i <= $akhirBulan; $i++)
                                    @php
                                        $bulanNama = getBulan($i);
                                        $statusBulan = $item->{strtolower($bulanNama)};
                                    @endphp
                                    <td class="text-center" style="vertical-align: middle;">
                                        @if($statusBulan == 1)
                                            <span class="label label-warning">-</span>
                                        @elseif($statusBulan == 2)
                                            <span class="label label-success">v</span>
                                        @else
                                            <span class="label label-danger">x</span>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $akhirBulan - $awalBulan + 7 }}" class="text-center" style="padding: 50px;">
                                    <div class="alert alert-info" style="margin: 0; border: none; background: transparent;">
                                        <i class="fa fa-graduation-cap fa-3x text-info" style="margin-bottom: 15px;"></i>
                                        <h4 class="text-info">Data Belum Tersedia</h4>
                                        <p class="text-muted">
                                            Belum ada data kehadiran PAUD untuk periode yang dipilih.<br>
                                            Silakan input data kehadiran atau sesuaikan filter pencarian.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>

            @if(count($dataFilter) > 0)
            <!-- Summary Statistics -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bar-chart"></i> Ringkasan Kehadiran PAUD</h3>
                </div>
                <div class="box-body">
                    @php
                        $totalAnak = count($dataFilter);
                        $kategori1 = $dataFilter->where('kategori_usia', 1)->count();
                        $kategori2 = $dataFilter->where('kategori_usia', 2)->count();
                        
                        // Calculate monthly attendance statistics
                        $monthlyStats = [];
                        for ($i = $awalBulan; $i <= $akhirBulan; $i++) {
                            $bulanNama = strtolower(getBulan($i));
                            $hadir = $dataFilter->where($bulanNama, 2)->count();
                            $tidakHadir = $dataFilter->where($bulanNama, 3)->count();
                            $belum = $dataFilter->where($bulanNama, 1)->count();
                            
                            $monthlyStats[getBulan($i)] = [
                                'hadir' => $hadir,
                                'tidak_hadir' => $tidakHadir,
                                'belum' => $belum,
                                'persentase' => $totalAnak > 0 ? number_format(($hadir / $totalAnak) * 100, 1) : 0
                            ];
                        }
                    @endphp
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-blue"><i class="fa fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Anak</span>
                                    <span class="info-box-number">{{ $totalAnak }}</span>
                                    <div class="progress">
                                        <div class="progress-bar bg-blue" style="width: 100%"></div>
                                    </div>
                                    <span class="progress-description">Kategori 1: {{ $kategori1 }} | Kategori 2: {{ $kategori2 }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <h4><i class="fa fa-calendar"></i> Statistik Kehadiran per Bulan</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="bg-light-blue">
                                            <th class="text-center">Bulan</th>
                                            <th class="text-center">Hadir</th>
                                            <th class="text-center">Tidak Hadir</th>
                                            <th class="text-center">Belum</th>
                                            <th class="text-center">Persentase Hadir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($monthlyStats as $bulan => $stats)
                                        <tr>
                                            <td class="text-center"><strong>{{ $bulan }}</strong></td>
                                            <td class="text-center">
                                                <span class="badge bg-green">{{ $stats['hadir'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-red">{{ $stats['tidak_hadir'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-yellow">{{ $stats['belum'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $persen = floatval($stats['persentase']);
                                                    $progressClass = $persen >= 80 ? 'progress-bar-success' : ($persen >= 60 ? 'progress-bar-warning' : 'progress-bar-danger');
                                                @endphp
                                                <div class="progress progress-sm" style="margin: 0;">
                                                    <div class="progress-bar {{ $progressClass }}" style="width: {{ $persen }}%">
                                                        {{ $stats['persentase'] }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Penjelasan Layanan PAUD -->
    <div class="modal fade" id="paudKriteriaModal" tabindex="-1" role="dialog" aria-labelledby="paudKriteriaModalLabel">
        <div class="modal-dialog modal-xl" role="document" style="width: 95%; max-width: 1200px;">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-white" id="paudKriteriaModalLabel">
                        <i class="fa fa-graduation-cap"></i> Panduan Komprehensif Layanan PAUD & Pengembangan Anak Usia Dini
                    </h4>
                </div>
                <div class="modal-body">
                    <!-- Header Overview -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout callout-success">
                                <h4><i class="fa fa-info-circle"></i> Tentang Program PAUD (Pendidikan Anak Usia Dini)</h4>
                                <p><strong>Program PAUD</strong> merupakan bagian integral dari pencegahan stunting yang berfokus pada stimulasi tumbuh kembang anak sejak usia 2-6 tahun. Program ini menggabungkan layanan parenting untuk orang tua dan pendidikan formal untuk anak dalam mendukung <strong>1000 Hari Pertama Kehidupan Plus</strong>.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#kategori-usia" aria-controls="kategori-usia" role="tab" data-toggle="tab">
                                <i class="fa fa-users"></i> Kategori Usia
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#layanan-paud" aria-controls="layanan-paud" role="tab" data-toggle="tab">
                                <i class="fa fa-graduation-cap"></i> Layanan PAUD
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#tracking-kehadiran" aria-controls="tracking-kehadiran" role="tab" data-toggle="tab">
                                <i class="fa fa-calendar-check-o"></i> Tracking Kehadiran
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#manfaat-paud" aria-controls="manfaat-paud" role="tab" data-toggle="tab">
                                <i class="fa fa-star"></i> Manfaat PAUD
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" style="margin-top: 15px;">
                        <!-- Kategori Usia Tab -->
                        <div role="tabpanel" class="tab-pane active" id="kategori-usia">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box bg-yellow">
                                        <span class="info-box-icon"><i class="fa fa-child"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">KATEGORI 1</span>
                                            <span class="info-box-number">2 - < 3 Tahun</span>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: 60%"></div>
                                            </div>
                                            <span class="progress-description">
                                                Program Parenting untuk Orang Tua
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="box box-solid box-warning">
                                        <div class="box-header with-border">
                                            <i class="fa fa-users"></i>
                                            <h3 class="box-title">Program Parenting (2-3 Tahun)</h3>
                                        </div>
                                        <div class="box-body">
                                            <h5><i class="fa fa-target text-orange"></i> Sasaran Utama:</h5>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-green"></i> Orang tua/pengasuh anak</li>
                                                <li><i class="fa fa-check text-green"></i> Keluarga dengan anak usia 2-3 tahun</li>
                                                <li><i class="fa fa-check text-green"></i> Kader posyandu dan pendamping</li>
                                            </ul>
                                            
                                            <h5><i class="fa fa-book text-blue"></i> Materi Pembelajaran:</h5>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-arrow-right text-blue"></i> Teknik komunikasi dengan balita</li>
                                                <li><i class="fa fa-arrow-right text-blue"></i> Stimulasi motorik halus dan kasar</li>
                                                <li><i class="fa fa-arrow-right text-blue"></i> Pola asuh yang tepat</li>
                                                <li><i class="fa fa-arrow-right text-blue"></i> Deteksi dini keterlambatan tumbuh kembang</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="info-box bg-green">
                                        <span class="info-box-icon"><i class="fa fa-graduation-cap"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">KATEGORI 2</span>
                                            <span class="info-box-number">3 - 6 Tahun</span>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: 90%"></div>
                                            </div>
                                            <span class="progress-description">
                                                Kelas PAUD Formal
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="box box-solid box-success">
                                        <div class="box-header with-border">
                                            <i class="fa fa-child"></i>
                                            <h3 class="box-title">Kelas PAUD (3-6 Tahun)</h3>
                                        </div>
                                        <div class="box-body">
                                            <h5><i class="fa fa-target text-green"></i> Sasaran Utama:</h5>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-green"></i> Anak usia 3-6 tahun</li>
                                                <li><i class="fa fa-check text-green"></i> Persiapan memasuki pendidikan formal</li>
                                                <li><i class="fa fa-check text-green"></i> Pengembangan sosial emosional</li>
                                            </ul>
                                            
                                            <h5><i class="fa fa-puzzle-piece text-purple"></i> Aktivitas Pembelajaran:</h5>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-arrow-right text-purple"></i> Bermain sambil belajar</li>
                                                <li><i class="fa fa-arrow-right text-purple"></i> Pengembangan kreativitas</li>
                                                <li><i class="fa fa-arrow-right text-purple"></i> Sosialisasi dengan teman sebaya</li>
                                                <li><i class="fa fa-arrow-right text-purple"></i> Persiapan kemandirian</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Layanan PAUD Tab -->
                        <div role="tabpanel" class="tab-pane" id="layanan-paud">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box box-solid box-primary">
                                        <div class="box-header with-border">
                                            <i class="fa fa-calendar"></i>
                                            <h3 class="box-title">Jadwal & Frekuensi</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr class="bg-light-blue">
                                                            <th>Kategori</th>
                                                            <th>Frekuensi</th>
                                                            <th>Durasi</th>
                                                            <th>Target Bulanan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><span class="label label-warning">2-3 Tahun</span></td>
                                                            <td>1x per bulan</td>
                                                            <td>2-3 jam</td>
                                                            <td>Min. 80% kehadiran</td>
                                                        </tr>
                                                        <tr>
                                                            <td><span class="label label-success">3-6 Tahun</span></td>
                                                            <td>2x per bulan</td>
                                                            <td>3-4 jam</td>
                                                            <td>Min. 85% kehadiran</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="box box-solid box-info">
                                        <div class="box-header with-border">
                                            <i class="fa fa-users"></i>
                                            <h3 class="box-title">Tim Pelaksana</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="progress-group">
                                                <span class="progress-text">Guru PAUD Bersertifikat</span>
                                                <span class="float-right"><i class="fa fa-graduation-cap text-blue"></i></span>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar progress-bar-blue" style="width: 100%"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="progress-group">
                                                <span class="progress-text">Kader Posyandu Terlatih</span>
                                                <span class="float-right"><i class="fa fa-users text-green"></i></span>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar progress-bar-green" style="width: 100%"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="progress-group">
                                                <span class="progress-text">Tenaga Kesehatan</span>
                                                <span class="float-right"><i class="fa fa-stethoscope text-red"></i></span>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar progress-bar-red" style="width: 100%"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="callout callout-info callout-sm">
                                                <h5><i class="fa fa-lightbulb-o"></i> Koordinasi Tim</h5>
                                                <p>Koordinasi antar tim untuk memastikan layanan PAUD terintegrasi dengan program kesehatan dan gizi.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tracking Kehadiran Tab -->
                        <div role="tabpanel" class="tab-pane" id="tracking-kehadiran">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="box box-solid box-warning">
                                        <div class="box-header with-border">
                                            <i class="fa fa-calendar-check-o"></i>
                                            <h3 class="box-title">Sistem Pencatatan Kehadiran</h3>
                                        </div>
                                        <div class="box-body">
                                            <h5><i class="fa fa-list-alt text-blue"></i> Status Kehadiran:</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="bg-light-blue">
                                                            <th class="text-center">Status</th>
                                                            <th class="text-center">Kode</th>
                                                            <th>Keterangan</th>
                                                            <th>Tindak Lanjut</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center">
                                                                <span class="label label-success attendance-badge">v</span>
                                                            </td>
                                                            <td class="text-center"><strong>HADIR</strong></td>
                                                            <td>Anak/orang tua mengikuti kegiatan</td>
                                                            <td>Lanjutkan monitoring</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">
                                                                <span class="label label-danger attendance-badge">x</span>
                                                            </td>
                                                            <td class="text-center"><strong>TIDAK HADIR</strong></td>
                                                            <td>Tidak mengikuti tanpa keterangan</td>
                                                            <td>Follow up kunjungan rumah</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">
                                                                <span class="label label-warning attendance-badge">-</span>
                                                            </td>
                                                            <td class="text-center"><strong>BELUM BERLAKU</strong></td>
                                                            <td>Belum masuk periode/kategori</td>
                                                            <td>Tunggu sesuai jadwal</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                            <div class="alert alert-warning">
                                                <h5><i class="fa fa-exclamation-triangle"></i> Perhatian Khusus</h5>
                                                <ul>
                                                    <li>Anak dengan kehadiran < 60% perlu pendampingan intensif</li>
                                                    <li>Koordinasi dengan orang tua untuk identifikasi hambatan</li>
                                                    <li>Evaluasi berkala untuk perbaikan program</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="info-box bg-purple">
                                        <span class="info-box-icon"><i class="fa fa-line-chart"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Target Kehadiran</span>
                                            <span class="info-box-number">85%</span>
                                        </div>
                                    </div>
                                    
                                    <div class="box box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Indikator Keberhasilan</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="progress-group">
                                                <span class="progress-text">Sangat Baik (≥85%)</span>
                                                <span class="float-right"><span class="label label-success">Optimal</span></span>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar progress-bar-success" style="width: 100%"></div>
                                                </div>
                                            </div>
                                            <div class="progress-group">
                                                <span class="progress-text">Baik (70-84%)</span>
                                                <span class="float-right"><span class="label label-primary">Cukup</span></span>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar progress-bar-primary" style="width: 80%"></div>
                                                </div>
                                            </div>
                                            <div class="progress-group">
                                                <span class="progress-text">Perlu Perbaikan (<70%)</span>
                                                <span class="float-right"><span class="label label-danger">Kurang</span></span>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar progress-bar-danger" style="width: 50%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Manfaat PAUD Tab -->
                        <div role="tabpanel" class="tab-pane" id="manfaat-paud">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="small-box bg-blue">
                                        <div class="inner">
                                            <h3><i class="fa fa-cogs"></i></h3>
                                            <p>Perkembangan Kognitif</p>
                                        </div>
                                    </div>
                                    <div class="box box-solid">
                                        <div class="box-body">
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-green"></i> Meningkatkan daya ingat</li>
                                                <li><i class="fa fa-check text-green"></i> Kemampuan problem solving</li>
                                                <li><i class="fa fa-check text-green"></i> Kreativitas dan imajinasi</li>
                                                <li><i class="fa fa-check text-green"></i> Kesiapan sekolah</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <h3><i class="fa fa-heart"></i></h3>
                                            <p>Sosial Emosional</p>
                                        </div>
                                    </div>
                                    <div class="box box-solid">
                                        <div class="box-body">
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-green"></i> Keterampilan bersosialisasi</li>
                                                <li><i class="fa fa-check text-green"></i> Kontrol emosi yang baik</li>
                                                <li><i class="fa fa-check text-green"></i> Empati dan kepedulian</li>
                                                <li><i class="fa fa-check text-green"></i> Kepercayaan diri</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <h3><i class="fa fa-child"></i></h3>
                                            <p>Fisik Motorik</p>
                                        </div>
                                    </div>
                                    <div class="box box-solid">
                                        <div class="box-body">
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-green"></i> Koordinasi motorik halus</li>
                                                <li><i class="fa fa-check text-green"></i> Keseimbangan dan kelincahan</li>
                                                <li><i class="fa fa-check text-green"></i> Kebiasaan hidup sehat</li>
                                                <li><i class="fa fa-check text-green"></i> Pertumbuhan optimal</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box box-solid box-success">
                                        <div class="box-header with-border">
                                            <i class="fa fa-line-chart"></i>
                                            <h3 class="box-title">Dampak Jangka Panjang Program PAUD</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5><i class="fa fa-graduation-cap text-blue"></i> Pendidikan:</h5>
                                                    <ul>
                                                        <li>Prestasi akademik yang lebih baik</li>
                                                        <li>Tingkat putus sekolah rendah</li>
                                                        <li>Motivasi belajar tinggi</li>
                                                        <li>Adaptasi sekolah yang baik</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5><i class="fa fa-users text-green"></i> Sosial:</h5>
                                                    <ul>
                                                        <li>Keterampilan komunikasi efektif</li>
                                                        <li>Leadership dan teamwork</li>
                                                        <li>Resolusi konflik yang konstruktif</li>
                                                        <li>Partisipasi sosial aktif</li>
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
                            <div class="callout callout-success">
                                <h4><i class="fa fa-trophy"></i> Visi Program PAUD dalam Pencegahan Stunting</h4>
                                <p>Program PAUD merupakan investasi jangka panjang dalam membangun generasi yang berkualitas. Melalui layanan holistik yang menggabungkan aspek pendidikan, kesehatan, gizi, dan pengasuhan, program ini berkontribusi signifikan dalam menurunkan angka stunting dan meningkatkan kualitas sumber daya manusia Indonesia.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <small class="text-muted">
                                <i class="fa fa-info-circle"></i> 
                                Informasi berdasarkan Standar PAUD dan Pedoman Pencegahan Stunting Nasional
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
