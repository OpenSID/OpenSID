@extends('admin.layouts.print_layout')

@section('title', 'Data Penduduk')
@section('styles')
    <style>
        td,
        th {
            font-size: 6.5pt;
            mso-number-format: "\@";
        }
    </style>
@endsection

@section('header')
    <div class="header" align="center">
        <label align="left">{{ get_identitas() }}</label>
        <h3> DAFTAR CALON PEMILIH UNTUK TANGGAL PEMILIHAN {{ $tanggal_pemilihan }}</h3>
        <br>
    </div>
@endsection

@section('content')
    <table class="border thick">
        <thead>
            <tr class="border thick">
                <th>No</th>
                <th>No. KK</th>
                <th>NIK</th>
                <th>Tag Id Card</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Alamat</th>
                <th>{{ strtoupper(setting('sebutan_dusun')) }}</th>
                <th>RW</th>
                <th>RT</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>UMUR PADA {{ $tanggal_pemilihan }}</th>
                <th>Agama</th>
                <th>Pendidikan (dlm KK)</th>
                <th>Pekerjaan</th>
                <th>Kawin</th>
                <th>Hub. Keluarga</th>
                <th>Nama Ayah</th>
                <th>Nama Ibu</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($main as $data)
                <tr>
                    <td>{{ ++$start }}</td>
                    <td class="textx">{{ $privasi_nik ? sensor_nik_kk($data->keluarga->no_kk) : $data->keluarga->no_kk }} </td>
                    <td class="textx">{{ $privasi_nik ? sensor_nik_kk($data->nik) : $data->nik }}</td>
                    <td>{{ $data->tag_id_card }}</td>
                    <td>{{ strtoupper($data->nama) }}</td>
                    <td>{{ $data->jenis_kelamin }}</td>
                    <td>{{ strtoupper($data->keluarga->alamat ?? $data->alamat_sekarang) }}</td>
                    <td>{{ strtoupper($data->keluarga->wilayah->dusun ?? $data->wilayah->dusun) }}</td>
                    <td>{{ $data->keluarga->wilayah->rw ?? $data->wilayah->rw }}</td>
                    <td>{{ $data->keluarga->wilayah->rt ?? $data->wilayah->rt }}</td>
                    <td>{{ $data->tempatlahir }}</td>
                    <td>{{ tgl_indo($data->tanggallahir) }}</td>
                    <td align="right">{{ usia($data->tanggallahir, $tglPemilihan, '%y') }}</td>
                    <td>{{ $data->agama }}</td>
                    <td>{{ $data->pendidikan_kk }}</td>
                    <td>{{ $data->pekerjaan }}</td>
                    <td>{{ $data->status_perkawinan }}</td>
                    <td>{{ $data->penduduk_hubungan }}</td>
                    <td>{{ $data->nama_ayah }}</td>
                    <td>{{ $data->nama_ibu }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <label>Tanggal cetak : &nbsp; </label>{{ tgl_indo(date('Y m d')) }}
@endsection
