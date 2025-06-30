@extends('admin.layouts.index')
@section('title')
    <h1>
        Laporan Kependudukan Bulanan
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Laporan Kependudukan Bulanan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <form id="mainform" name="mainform" action="{{ ci_route('laporan') }}" method="post" class="form-horizontal">
        <div class="row">
            <div class="col-md-12">
                @if ($data_lengkap)
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <a
                                href="{{ ci_route('laporan.dialog.cetak') }}"
                                class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                                title="Cetak Laporan"
                                data-remote="false"
                                data-toggle="modal"
                                data-target="#modalBox"
                                data-title="Cetak Laporan"
                            ><i class="fa fa-print "></i> Cetak</a>
                            <a
                                href="{{ ci_route('laporan.dialog.unduh') }}"
                                title="Unduh Laporan"
                                class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                                title="Unduh Laporan"
                                data-remote="false"
                                data-toggle="modal"
                                data-target="#modalBox"
                                data-title="Unduh Laporan"
                            ><i class="fa fa-download"></i> Unduh</a>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4 class="text-center"><strong>PEMERINTAH KABUPATEN/KOTA {{ strtoupper($desa['nama_kabupaten']) }}</strong></h4>
                                    <h5 class="text-center"><strong>LAPORAN PERKEMBANGAN PENDUDUK (LAMPIRAN A - 9)</strong></h5>
                                    <br />
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="kelurahan">{{ ucwords(setting('sebutan_desa')) }}/Kelurahan</label>
                                        <div class="col-sm-7 col-md-5">
                                            <input type="text" class="form-control input-sm" value="{{ $desa['nama_desa'] }}" disabled /></input>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="kecamatan">{{ ucwords(setting('sebutan_kecamatan')) }}</label>
                                        <div class="col-sm-7 col-md-5">
                                            <input type="text" class="form-control input-sm" value="{{ $desa['nama_kecamatan'] }}" disabled /></input>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="tahun">Tahun</label>
                                        <div class="col-sm-2">
                                            <select class="form-control input-sm required select2" name="tahun" onchange="formAction('mainform','{{ ci_route('laporan.bulan') }}')" width="100%">
                                                <option value="">Pilih tahun</option>
                                                @for ($t = $tahun_lengkap; $t <= date('Y'); $t++)
                                                    <option value={{ $t }} @selected($tahun == $t)>{{ $t }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <label class="col-sm-2 col-md-1 control-label" for="tahun">Bulan</label>
                                        <div class="col-sm-3 col-md-2">
                                            <select class="form-control input-sm select2" name="bulan" onchange="formAction('mainform','{{ ci_route('laporan.bulan') }}')" width="100%">
                                                <option value="">Pilih bulan</option>
                                                @foreach (bulan() as $no_bulan => $nama_bulan)
                                                    <option value={{ $no_bulan }} @selected($bulan == $no_bulan)>{{ $nama_bulan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if ($sesudah_data_lengkap)
                                        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    @include('admin.laporan.tabel_bulanan')
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="box box-info">
                                            <div class="box-header with-border">
                                            </div>
                                            <div class="box-body">
                                                <div class="alert alert-warning">
                                                    Tahun-bulan sebelum tanggal lengkap data pada {{ $tgl_lengkap }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    @include('admin.bumindes.penduduk.data_lengkap', ['judul' => 'Data Penduduk']);
                @endif
            </div>
        </div>
    </form>
@endsection
