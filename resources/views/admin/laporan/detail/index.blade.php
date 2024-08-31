@extends('admin.layouts.index')
@section('title')
    <h1>
        Rincian Kependudukan Bulanan
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('laporan.clear') }}"> Laporan Kependudukan Bulanan</a></li>
    <li class="active">Rincian Kependudukan Bulanan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <form id="mainform" name="mainform" method="post">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="{{ ci_route("laporan.detail_dialog.cetak.{$rincian}.{$tipe}") }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox"
                                    data-title="Cetak Data"
                                ><i class="fa fa-print"></i>Cetak</a>
                                <a href="{{ ci_route("laporan.detail_dialog.unduh.{$rincian}.{$tipe}") }}" class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox"
                                    data-title="Unduh Data"
                                ><i class="fa fa-download"></i>Unduh</a>
                                <a href="{{ ci_route('laporan') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Laporan Bulanan</a>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="text-center"><strong>RINCIAN LAPORAN PERKEMBANGAN {{ $title }}</strong></h5>
                                </br>
                                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered dataTable table-hover">
                                                    <thead class="bg-gray disabled color-palette">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama</th>
                                                            <th>NIK</th>
                                                            <th>Tempat Lahir</th>
                                                            <th>Tanggal Lahir</th>
                                                            <th>Nama Ayah</th>
                                                            <th>Nama Ibu</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($main as $key => $data)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $data->nama }}</td>
                                                                <td>{{ $data->nik }}</td>
                                                                <td>{{ $data->tempatlahir }}</td>
                                                                <td>{{ $data->tanggallahir?->format('Y-m-d') }}</td>
                                                                <td>{{ $data->nama_ayah }}</td>
                                                                <td>{{ $data->nama_ibu }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
@endsection
