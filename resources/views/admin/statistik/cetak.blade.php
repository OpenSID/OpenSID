@extends('admin.layouts.print_layout')

@section('title', 'Laporan Statistik Kependudukan')

@section('header')
    <div class="header" align="center">
        @if ($aksi != 'unduh')
            <img class="logo" src="{{ gambar_desa($desa['logo']) }}" alt="logo-desa">
        @endif
        <h3>
            PEMERINTAH {!! strtoupper(setting('sebutan_kabupaten') . ' ' . $desa['nama_kabupaten'] . ' <br>' . setting('sebutan_kecamatan') . ' ' . $desa['nama_kecamatan'] . ' <br>' . setting('sebutan_desa') . ' ' . $desa['nama_desa']) !!}
        </h3>
        <h3>LAPORAN DATA STATISTIK KEPENDUDUKAN MENURUT {{ strtoupper($stat) }}</h3>
    </div>
    <br>
    <table style="margin: 0 auto;">
        <tbody>
            <tr>
                <td class="top" width="60%">
                    <div class="nowrap">
                        <label style="width: 150px;">Laporan. No</label>
                        <label>:</label>
                        <span>{{ $laporan_no }}</span>
                    </div>
                </td>
            </tr>
            @if ($dusun)
                <tr>
                    <td class="top" width="60%">
                        <div class="nowrap">
                            <label style="width: 150px;">{{ ucwords(setting('sebutan_dusun')) }}</label>
                            <label>:</label>
                            <span>{{ ucwords($dusun) }}</span>
                        </div>
                    </td>
                </tr>
            @endif
            @if ($rw)
                <tr>
                    <td class="top" width="60%">
                        <div class="nowrap">
                            <label style="width: 150px;">RW</label>
                            <label>:</label>
                            <span>{{ $rw }}</span>
                        </div>
                    </td>
                </tr>
            @endif
            @if ($rt)
                <tr>
                    <td class="top" width="60%">
                        <div class="nowrap">
                            <label style="width: 150px;">RT</label>
                            <label>:</label>
                            <span>{{ $rt }}</span>
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
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
    <table class="border thick data">
        <thead>
            <tr class="thick">
                <th class="thick">No</th>
                <th class="thick" width="50%">{{ $stat }}</th>
                <th class="thick" width="16%">Jumlah</th>
                <th class="thick" width="16%">Laki-laki</th>
                <th class="thick" width="16%">Perempuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($main as $no => $data)
                @php
                    if ($data['id'] == 666 || $data['id'] == 777 || $data['id'] == 888) {
                        $no = '';
                    } else {
                        $no++;
                    }
                @endphp
                <tr>
                    <td class="thick" align="center" width="2">{{ $no }}</td>
                    <td class="thick">{{ strtoupper($data['nama']) }}</td>
                    <td class="thick" align="right">{{ $data['jumlah'] }}</td>
                    <td class="thick" align="right">{{ $data['laki'] }}</td>
                    <td class="thick" align="right">{{ $data['perempuan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <label>Tanggal cetak : &nbsp; </label>
    {{ tgl_indo(date('Y m d')) }}
@endsection
