@extends('admin.layouts.print_layout')
@section('title', 'Data Keluarga')
@section('styles')
    <style>
        .textx {
            mso-number-format: "\@";
        }

        td,
        th {
            mso-number-format: "\@";
        }
    </style>
@endsection
@section('header')
<div class="header" align="center">
    <label align="left">{{ get_identitas() }}</label>
    <h3> DATA KELUARGA </h3>
</div>
<br>
@endsection
@section('content')
<table class="border thick">
    <thead>
        <tr class="border thick">
            <th class="padat">No</th>
            <th width="150">Nomor KK</th>
            <th width="200">Kepala Keluarga</th>
            <th width="200">NIK</th>
            <th width="100">Jumlah Anggota</th>
            <th width="100">Jenis Kelamin</th>
            <th align="center" width="180">Alamat</th>
            <th width="100">{{ ucwords(setting('sebutan_dusun')) }}</th>
            <th width="30">RW</th>
            <th width="30">RT</th>
            <th width="100">Tanggal Terdaftar</th>
            <th width="100">Tanggal Cetak KK</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($main as $data)
            <tr>
                <td>{{ ++$start }}</td>
                <td>{{ $privasi_kk ? sensor_nik_kk($data->no_kk) : $data->no_kk }}</td>
                <td>{{ strtoupper($data->kepalaKeluarga->nama) }}</td>
                <td>{{ $privasi_kk ? sensor_nik_kk($data->kepalaKeluarga->nik) : $data->kepalaKeluarga->nik }}</td>
                <td>{{ $data->anggota->count() }}</td>
                <td>{{ $data->kepalaKeluarga->jenis_kelamin }}</td>
                <td>{{ strtoupper($data->kepalaKeluarga->alamat_wilayah) }}</td>
                <td>{{ strtoupper($data->kepalaKeluarga->wilayah->dusun) }}</td>
                <td>{{ strtoupper($data->kepalaKeluarga->wilayah->rw) }}</td>
                <td>{{ strtoupper($data->kepalaKeluarga->wilayah->rt) }}</td>
                <td>{{ tgl_indo($data->tgl_daftar) }}</td>
                <td>{{ tgl_indo($data->tgl_cetak_kk) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<label>Tanggal cetak : &nbsp; </label>
{{ tgl_indo(date('Y m d')) }}
@endsection