@extends('admin.layouts.print_layout')

@section('title', 'Data Rumah Tangga')

@section('header')
    <div class="header" align="center">
        <label align="left">{{ get_identitas() }}</label>
        <h3> Data Rumah Tangga </h3>
    </div>
    <br>
@endsection

@section('content')
<table class="border thick">
    <thead>
        <tr class="border thick">
            <th>No</th>
            <th width="150">Nomor Rumah Tangga</th>
            <th width="200">Kepala Rumah Tangga</th>
            <th width="100">NIK</th>
            <th width="100">Jumlah KK</th>
            <th width="100">Jumlah Anggota</th>
            <th width="100">Alamat</th>
            <th width="100">{{ ucwords(setting('sebutan_dusun')) }}</th>
            <th width="30">RW</th>
            <th width="30">RT</th>
            <th width="100">Tanggal Terdaftar</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($main as $key => $data)
            <tr>
                <td class="text-center" width="2">{{ $key + 1 }}</td>
                <td>{{ $data->no_kk }}</td>
                <td>{{ strtoupper($data->kepalaKeluarga->nama) }}</td>
                <td>{{ $privasi_nik ? sensor_nik_kk($data->kepalaKeluarga->nik) : $data->kepalaKeluarga->nik }}</td>
                <td class="padat">{{ $data->jumlah_kk }}</td>
                <td class="padat">{{ $data->anggota_count }}</td>
                <td>{{ strtoupper($data->kepalaKeluarga->alamat_wilayah) }}</td>
                <td>{{ strtoupper($data->kepalaKeluarga->keluarga->wilayah->dusun) }}</td>
                <td>{{ strtoupper($data->kepalaKeluarga->keluarga->wilayah->rw) }}</td>
                <td>{{ strtoupper($data->kepalaKeluarga->keluarga->wilayah->rt) }}</td>
                <td>{{ tgl_indo($data->tgl_daftar) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<br>
<label>Tanggal cetak : &nbsp; </label>
{{ tgl_indo(date('Y m d')) }}
@endsection
