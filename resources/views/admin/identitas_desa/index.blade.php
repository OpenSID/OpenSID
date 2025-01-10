@extends('admin.layouts.index')

@push('css')
    <style>
        .table {
            font-size: 12px;
        }

        .bg-identitas {
            width: 100%;
            height: 300px;
            background: url("{{ gambar_desa($main['path_kantor_desa'], true) }}");
            background-repeat: no-repeat;
            background-position: center center;
        }

        .img-identitas {
            margin: 30px auto;
            width: 100px;
            padding: 3px;
        }

        .text-identitas {
            text-align: center;
            font-weight: bold;
            color: #fff;
            text-shadow: 2px 2px 2px #0c83c5;
        }
    </style>
@endpush

@section('title')
    <h1>
        {{ SebutanDesa('Identitas [Desa]') }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">{{ SebutanDesa('Identitas [Desa]') }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @include('admin.identitas_desa.info_kades')

    <div class="box box-info">
        @if (can('u'))
            <div class="box-header with-border">
                <a href="{{ ci_route('identitas_desa.form') }}" class="btn btn-social btn-warning btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Ubah Data {{ ucwords(setting('sebutan_desa')) }}"><i class="fa fa-edit"></i> Ubah Data
                    {{ ucwords(setting('sebutan_desa')) }}</a>
                <a href="{{ ci_route('identitas_desa.maps.kantor') }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Lokasi Kantor {{ ucwords(setting('sebutan_desa')) }}"><i class='fa fa-map-marker'></i>
                    Lokasi
                    Kantor {{ ucwords(setting('sebutan_desa')) }}</a>
                <a href="{{ ci_route('identitas_desa.maps.wilayah') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Peta Wilayah {{ ucwords(setting('sebutan_desa')) }}"><i class='fa fa-map'></i>
                    Peta Wilayah
                    {{ ucwords(setting('sebutan_desa')) }}</a>
                @if (!$main)
                    <a href="{{ ci_route('identitas_desa.reset') }}" class="btn btn-social btn-danger btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Reset AppKey"><i class="fa fa-times"></i> Reset AppKey</a>
                @endif
            </div>
        @endif
        <div class="box-body">
            <div class="box-body bg-identitas">
                <img class="img-identitas img-responsive" src="{{ gambar_desa($main['path_logo']) }}" alt="logo-desa">
                <h3 class="text-identitas">{{ ucwords(setting('sebutan_desa') . ' ' . $main['nama_desa']) }}</h3>
                <p class="text-identitas">
                    <b>{{ ucwords(setting('sebutan_kecamatan') . ' ' . $main['nama_kecamatan'] . ', ' . setting('sebutan_kabupaten') . ' ' . $main['nama_kabupaten'] . ', Provinsi ' . $main['nama_propinsi']) }}</b>
                </p>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover tabel-rincian">
                    <tbody>
                        <tr>
                            <th colspan="3" class="subtitle_head">
                                <strong>{{ strtoupper(setting('sebutan_desa')) }}</strong>
                            </th>
                        </tr>
                        <tr>
                            <td width="300">Nama {{ ucwords(setting('sebutan_desa')) }}</td>
                            <td width="1">:</td>
                            <td>{{ $main['nama_desa'] }}</td>
                        </tr>
                        <tr>
                            <td>Kode {{ ucwords(setting('sebutan_desa')) }}</td>
                            <td>:</td>
                            <td>{{ kode_wilayah($main['kode_desa']) }}</td>
                        </tr>
                        <tr>
                            <td>Kode Pos {{ ucwords(setting('sebutan_desa')) }}</td>
                            <td>:</td>
                            <td>{{ $main['kode_pos'] }}</td>
                        </tr>
                        <tr>
                            <td>Nama {{ ucwords(setting('sebutan_kepala_desa')) }}</td>
                            <td>:</td>
                            <td>{{ $main['nama_kepala_desa'] }}</td>
                        </tr>
                        <tr>
                            <td>NIP {{ ucwords(setting('sebutan_kepala_desa')) }}</td>
                            <td>:</td>
                            <td>{{ $main['nip_kepala_desa'] }}</td>
                        </tr>
                        <tr>
                            <td>Alamat Kantor {{ ucwords(setting('sebutan_desa')) }}</td>
                            <td>:</td>
                            <td>{{ $main['alamat_kantor'] }}</td>
                        </tr>
                        <tr>
                            <td>E-Mail {{ ucwords(setting('sebutan_desa')) }}</td>
                            <td>:</td>
                            <td>{{ $main['email_desa'] }}</td>
                        </tr>
                        <tr>
                            <td>Nomor Telepon {{ ucwords(setting('sebutan_desa')) }}</td>
                            <td>:</td>
                            <td>{{ $main['telepon'] }}</td>
                        </tr>
                        <tr>
                            <td>Nomor Ponsel {{ ucwords(setting('sebutan_desa')) }}</td>
                            <td>:</td>
                            <td>{{ $main['nomor_operator'] }}</td>
                        </tr>
                        <tr>
                            <td>Website {{ ucwords(setting('sebutan_desa')) }}</td>
                            <td>:</td>
                            <td>{{ $main['website'] }}</td>
                        </tr>
                        <tr>
                            <th colspan="3" class="subtitle_head">
                                <strong>{{ strtoupper(setting('sebutan_kecamatan')) }}</strong>
                            </th>
                        </tr>
                        <tr>
                            <td>Nama {{ ucwords(setting('sebutan_kecamatan')) }}</td>
                            <td>:</td>
                            <td>{{ $main['nama_kecamatan'] }}</td>
                        </tr>
                        <tr>
                            <td>Kode {{ ucwords(setting('sebutan_kecamatan')) }}</td>
                            <td>:</td>
                            <td>{{ kode_wilayah($main['kode_kecamatan']) }}</td>
                        </tr>
                        <tr>
                            <td>Nama {{ ucwords(setting('sebutan_camat')) }}</td>
                            <td>:</td>
                            <td>{{ $main['nama_kepala_camat'] }}</td>
                        </tr>
                        <tr>
                            <td>NIP {{ ucwords(setting('sebutan_camat')) }}</td>
                            <td>:</td>
                            <td>{{ $main['nip_kepala_camat'] }}</td>
                        </tr>
                        <tr>
                            <th colspan="3" class="subtitle_head">
                                <strong>{{ strtoupper(setting('sebutan_kabupaten')) }}</strong>
                            </th>
                        </tr>
                        <tr>
                            <td>Nama {{ ucwords(setting('sebutan_kabupaten')) }}</td>
                            <td>:</td>
                            <td>{{ $main['nama_kabupaten'] }}</td>
                        </tr>
                        <tr>
                            <td>Kode {{ ucwords(setting('sebutan_kabupaten')) }}</td>
                            <td>:</td>
                            <td>{{ kode_wilayah($main['kode_kabupaten']) }}</td>
                        </tr>
                        <tr>
                            <th colspan="3" class="subtitle_head"><strong>PROVINSI</strong></th>
                        </tr>
                        <tr>
                            <td>Nama Provinsi</td>
                            <td>:</td>
                            <td>{{ $main['nama_propinsi'] }}</td>
                        </tr>
                        <tr>
                            <td>Kode Provinsi</td>
                            <td>:</td>
                            <td>{{ kode_wilayah($main['kode_propinsi']) }}</td>
                        </tr>
                        <tr>
                            <th colspan="3" class="subtitle_head"><strong>KONTAK PEMBERITAHUAN</strong></th>
                        </tr>
                        <tr>
                            <td>Nama Perangkat Desa</td>
                            <td>:</td>
                            <td>{{ $main['nama_kontak'] }}</td>
                        </tr>
                        <tr>
                            <td>No. HP/WA</td>
                            <td>:</td>
                            <td>{{ $main['hp_kontak'] }}</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{{ $main['jabatan_kontak'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
