@extends('admin.layouts.index')

@section('title')
    <h1>
        Hasil Acak Data Penduduk dan Keluarga
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Database</li>
    <li class="active">Hasil Acak Data Penduduk dan Keluarga</li>
@endsection

@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            <b>Hasil Acak Data Penduduk</b>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabel-penduduk">
                    <thead>
                        <tr>
                            <th class="padat">NO</th>
                            <th>NIK</th>
                            <th>NIK Acak</th>
                            <th>Nama</th>
                            <th>Nama Acak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penduduk as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data['nik'] }}</td>
                                <td>{{ $data['nik_acak'] }}</td>
                                <td>{{ $data['nama'] }}</td>
                                <td>{{ $data['nama_acak'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="box box-info">
        <div class="box-header with-border">
            <b>Hasil Acak Data Keluarga</b>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabel-keluarga">
                    <thead>
                        <tr>
                            <th class="padat">NO</th>
                            <th>KK</th>
                            <th>KK Acak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($keluarga as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data['no_kk'] }}</td>
                                <td>{{ $data['no_kk_acak'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
