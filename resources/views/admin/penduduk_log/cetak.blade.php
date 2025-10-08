@extends('admin.layouts.print_layout')

@section('title', 'Data Log Penduduk')

@push('css')
<style>
    .textx {
        mso-number-format: "\@";
    }

    td,
    th {
        font-size: 6.5pt;
        mso-number-format: "\@";
    }
</style>
@endpush

@section('header')
    <div class="header" align="center">
        <label align="left">{{ get_identitas() }}</label>
        <h3>DAFTAR PENDUDUK YANG STATUS DASARNYA MATI, HILANG ATAU PINDAH</h3>
        <br>
    </div>
@endsection

@section('content')
    <table class="border thick">
        <thead>
            <tr class="border thick">
                <th>No</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>No. KK / Nama KK</th>
                <th>Jenis Kelamin</th>
                <th>{{ ucwords(setting('sebutan_dusun')) }}</th>
                <th>RW</th>
                <th>RT</th>
                <th>Umur</th>
                <th>Status Menjadi</th>
                <th>Tanggal Peristiwa</th>
                <th>Tanggal Rekam</th>
                <th>Catatan Peristiwa</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($main as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $privasi_nik ? sensor_nik_kk($item->penduduk->nik) : $item->penduduk->nik }}</td>
                    <td>{{ strtoupper($item->penduduk->nama) }}</td>
                    <td>
                        {{ $privasi_nik ? sensor_nik_kk($item->keluarga->no_kk) : $item->keluarga->no_kk }}
                        {{ ' / ' . strtoupper($item?->penduduk?->keluarga?->kepalaKeluarga?->nama) }}
                    </td>
                    <td>{{ $item->penduduk->jenis_kelamin }}</td>
                    <td>{{ strtoupper($item->penduduk->wilayah->dusun) }}</td>
                    <td>{{ $item->penduduk->wilayah->rw }}</td>
                    <td>{{ $item->penduduk->wilayah->rt }}</td>
                    <td align="right">{{ $item?->penduduk?->umur }}</td>
                    <td>{{ \App\Models\LogPenduduk::kodePeristiwaAll($item->kode_peristiwa) }}</td>
                    <td>{{ tgl_indo($item->tgl_peristiwa) }}</td>
                    <td>{{ tgl_indo($item->tgl_lapor) }}</td>
                    <td>{{ $item->catatan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('signature')
    <br>
    <label>Tanggal cetak : &nbsp; </label>{{ tgl_indo(date('Y m d')) }}
@endsection
