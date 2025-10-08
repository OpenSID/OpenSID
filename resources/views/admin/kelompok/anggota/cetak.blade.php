@extends('admin.layouts.print_layout')


@section('title', 'Daftar Anggota ' . ucwords($label . ' ' . $kelompok['nama']))

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
    <h4 align="center" style="margin-bottom: 10px;"><u>Daftar Anggota {{ ucwords($label . ' ' . $kelompok['nama']) }}</u></h4>
    <br>
@endsection

@section('content')
    <table>
        <tbody>
            <tr>
                <td style="padding: 5px 20px;">
                    <table>
                        <tr>
                            <td width="13%"><strong>Nama {{ ucwords($label) }}</strong></td>
                            <td> : {{ $kelompok['nama'] }}</td>
                        </tr>
                        <tr>
                            <td width="13%"><strong>Ketua {{ ucwords($label) }}</strong></td>
                            <td> : {{ $kelompok['nama_ketua'] }}</td>
                        </tr>
                        <tr>
                            <td width="13%"><strong>Kategori {{ ucwords($label) }}</strong></td>
                            <td> : {{ $kelompok['kategori'] }}</td>
                        </tr>
                        <tr>
                            <td width="13%"><strong>Keterangan</strong></td>
                            <td> : {{ $kelompok['keterangan'] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 20px;">
                    <table class="border thick">
                        <thead>
                            <tr class="border thick">
                                <th>No.</th>
                                <th>No. Anggota</th>
                                <th>NIK</th>
                                <th>Nama Lengkap</th>
                                <th>Jenis Kelamin</th>
                                <th>Tempat / Tanggal Lahir</th>
                                <th>Agama</th>
                                <th>Jabatan</th>
                                <th>Pendidikan Terakhir</th>
                                @if ($tipe == 'Lembaga')
                                    <th>Nomor dan Tanggal Keputusan Pengangkatan</th>
                                    <th>Nomor dan Tanggal Keputusan Pemberhentian</th>
                                @endif
                                <th>Ket.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($main as $key => $data)
                                <tr>
                                    <td align="center">{{ $key + 1 }}</td>
                                    <td class="textx" align="center">{{ $data['no_anggota'] }}</td>
                                    <td class="textx">{{ $data['nik'] }}</td>
                                    <td>{{ $data['nama'] }}</td>
                                    <td>{{ $data['sex'] }}</td>
                                    <td>{{ strtoupper($data['tempatlahir'] . ' / ' . tgl_indo($data['tanggallahir'])) }}</td>
                                    <td>{{ $data['agama'] }}</td>
                                    <td>{{ $data['jabatan'] }}</td>
                                    <td>{{ $data['pendidikan'] }}</td>
                                    @if ($tipe == 'Lembaga')
                                        <td>{{ $data['nmr_sk_pengangkatan'] . ' / ' . tgl_indo_out($data['tgl_sk_pengangkatan']) }}</td>
                                        <td>{{ $data['nmr_sk_pemberhentian'] . ' / ' . tgl_indo_out($data['tgl_sk_pemberhentian']) }}</td>
                                    @endif
                                    <td>{{ $data['keterangan'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
@endsection
