@extends('admin.layouts.print_layout')

@section('title', 'Data Penduduk')

@section('header')
    <div class="header" align="center">
        <label align="left">{{ get_identitas() }}</label>
        <h3> DATA PENDUDUK </h3>
        <h3> {{ $judul }}</h3>
    </div>
    <br>
@endsection

@section('styles')
    <style>
        td,
        th {
            font-size: 6.5pt;
            mso-number-format: "\@";
        }
    </style>
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
                <th>Alamat</th>
                <th>{{ ucwords(setting('sebutan_dusun')) }}</th>
                <th>RW</th>
                <th>RT</th>
                <th>Jenis Kelamin</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Umur</th>
                <th>Agama</th>
                <th>Pendidikan (dlm KK)</th>
                <th>Pekerjaan</th>
                <th>Kawin</th>
                <th>Hub. Keluarga</th>
                <th>Nama Ayah</th>
                <th>Nama Ibu</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($main as $data)
                <tr>
                    <td>{{ $start + $loop->iteration }}</td>
                    <td>{{ $privasi_nik ? sensor_nik_kk($data->keluarga->no_kk) : $data->keluarga->no_kk }}</td>
                    <td>{{ $privasi_nik ? sensor_nik_kk($data->nik) : $data->nik }}</td>
                    <td>{{ $data->tag_id_card }}</td>
                    <td>{{ strtoupper($data->nama) }}</td>
                    <td>{{ strtoupper($data->keluarga->alamat_wilayah ?? $data->alamat_wilayah) }}</td>
                    <td>{{ strtoupper($data->wilayah->dusun) }}</td>
                    <td>{{ $data->wilayah->rw }}</td>
                    <td>{{ $data->wilayah->rt }}</td>
                    <td>{{ $data->jenis_kelamin }}</td>
                    <td>{{ $data->tempatlahir }}</td>
                    <td>{{ tgl_indo($data->tanggallahir) }}</td>
                    <td align="right">{{ $data->umur }}</td>
                    <td>{{ $data->agama }}</td>
                    <td>{{ $data->pendidikan_kk }}</td>
                    <td>{{ $data->pekerjaan }}</td>
                    <td>{{ $data->status_perkawinan }}</td>
                    <td>{{ $data->penduduk_hubungan }}</td>
                    <td>{{ $data->nama_ayah }}</td>
                    <td>{{ $data->nama_ibu }}</td>
                    <td>
                        @if ($data->status == 1)
                            Tetap
                        @else
                            Pendatang
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <label>Tanggal cetak : &nbsp; </label>
    {{ tgl_indo(date('Y m d')) }}
@endsection
