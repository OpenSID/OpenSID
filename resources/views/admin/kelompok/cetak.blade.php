@extends('admin.layouts.print_layout')


@section('title', 'Data ' . ucwords($tipe))

@section('styles')
    <style>
        td,
        th {
            font-size: 9pt;
        }

        table#ttd td {
            text-align: center;
            white-space: nowrap;
        }

        .underline {
            text-decoration: underline;
        }
    </style>
@endsection

@section('header')
    <div style="text-align: center; margin-bottom: 10px;">
        @if ($aksi != 'unduh')
            <img class="logo" src="{{ gambar_desa($desa['logo']) }}" alt="logo-desa" style="display: block; margin: 0 auto;">
        @endif
        <h1 class="judul">
            PEMERINTAH {!! strtoupper(setting('sebutan_kabupaten') . ' ' . $desa['nama_kabupaten'] . '<br>' . setting('sebutan_kecamatan') . ' ' . $desa['nama_kecamatan'] . '<br>' . setting('sebutan_desa') . ' ' . $desa['nama_desa']) !!}
        </h1>
    </div>
    <hr class="garis">
    <h4 align="center" style="margin-bottom: 10px;"><u>DATA {{ strtoupper($tipe) }}</u></h4>
    <br>
@endsection

@section('content')
    <table>
        <tbody>
            <tr>
                <td style="padding: 5px 20px;">
                    <table border=1 class="border thick">
                        <thead>
                            <tr class="border thick">
                                <th>NO</th>
                                <th>NAMA {{ strtoupper($tipe) }}</th>
                                <th>NAMA KETUA</th>
                                <th>KATEGORI {{ strtoupper($tipe) }}</th>
                                <th>JUMLAH ANGGOTA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($main as $data)
                                <tr>
                                    <td align="center">{{ $loop->iteration }}</td>
                                    <td>{{ $data['nama'] }}</td>
                                    <td>{{ $data['ketua']['nama'] }}</td>
                                    <td>{{ $data['kelompok_master']['kelompok'] }}</td>
                                    <td align="center">{{ $data['jml_anggota'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
@endsection