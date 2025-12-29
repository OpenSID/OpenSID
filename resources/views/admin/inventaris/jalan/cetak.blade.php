@extends('admin.layouts.print_layout')

@section('title', "KARTU INVENTARIS BARANG (KIB) C. GEDUNG DAN BANGUNAN")

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

        /* Style berikut untuk unduh excel.
        Cetak mengabaikan dan menggunakan style dari report.css
       */
        table#inventaris {
            border: solid 2px black;
        }

        td.border {
            border: dotted 0.5px gray;
        }

        th.border {
            border: solid 0.5pt gray;
        }

        .pull-left {
            position: relative;
            width: 50%;
            float: left;
        }

        .pull-right {
            position: relative;
            width: 50%;
            float: right;
            text-align: right;
            /* padding-right:20px; */
        }
    </style>
@endsection

@section('header')
    <div class="" align="center">
        <h3> KARTU INVENTARIS BARANG (KIB) <br>
            C. GEDUNG DAN BANGUNAN
        </h3>
        <br>
    </div>
    <div style="padding-bottom: 35px;">
        <div class="pull-left" style="width: auto">
            <table>
                <tr>
                    <td>{{ strtoupper(setting('sebutan_desa')) }}</td>
                    <td style="padding-left: 10px">{{ strtoupper(' : ' . $desa['nama_desa']) }}</td>
                </tr>
                <tr>
                    <td>{{ strtoupper(setting('sebutan_kecamatan')) }}</td>
                    <td style="padding-left: 10px">{{ strtoupper(' : ' . $desa['nama_kecamatan']) }}</td>
                </tr>
                <tr>
                    <td>{{ strtoupper(setting('sebutan_kabupaten')) }}</td>
                    <td style="padding-left: 10px">{{ strtoupper(' : ' . $desa['nama_kabupaten']) }}</td>
                </tr>
            </table>
        </div>
        <div class="pull-right">
            KODE LOKASI : _ _ . _ _ . _ _ . _ _ . _ _ . _ _ . _ _ _
        </div>

    </div>
    <br>
@endsection

@section('content')
    <table>
        <tbody>
            <tr>
                <td>
                    <table id="inventaris" class="list border thick">
                        <thead>
                            <tr>
                                <th class="text-center" rowspan="2" style="width: 10px;">No</th>
                                <th class="text-center" rowspan="2">Nama Barang</th>
                                <th class="text-center" colspan="2">Nomor</th>
                                <th class="text-center" rowspan="2">Kontruksi</th>
                                <th class="text-center" rowspan="2">Panjang (M)</th>
                                <th class="text-center" rowspan="2">Luas (M<sup>2</sup>)</th>
                                <th class="text-center" rowspan="2">Lebar (M)</th>
                                <th class="text-center" rowspan="2">Letak / Lokasi</th>
                                <th class="text-center" colspan="2">Dokumen</th>
                                <th class="text-center" rowspan="2">Status Tanah</th>
                                <th class="text-center" rowspan="2">Nomor Kode Tanah</th>
                                <th class="text-center" rowspan="2">Asal Usul</th>
                                <th class="text-center" rowspan="2">Harga (Rp)</th>
                                <th class="text-center" rowspan="2">Kondisi (B, KB, RB)</th>
                                <th class="text-center" rowspan="2">Ket</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="text-align:center;" rowspan="1">Kode Barang
                                </th>
                                <th class="text-center" style="text-align:center;" rowspan="1">Register</th>
                                <th class="text-center" style="text-align:center;" rowspan="1">Tanggal</th>
                                <th class="text-center" style="text-align:center;" rowspan="1">Nomor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($print as $no => $data)
                                <tr>
                                    <td>{{ $no + 1 }}</td>
                                    <td>{{ $data->nama_barang }}</td>
                                    <td>{{ $data->kode_barang }}</td>
                                    <td>{{ $data->register }}</td>
                                    <td>{{ $data->kontruksi }}</td>
                                    <td>{{ $data->panjang }}</td>
                                    <td>{{ $data->luas }}</td>
                                    <td>{{ $data->lebar }}</td>
                                    <td>{{ $data->letak }}</td>
                                    <td>{{ tgl_indo2($data->tanggal_dokument) }}</td>
                                    <td>{{ $data->no_dokument }}</td>
                                    <td>{{ $data->status_tanah }}</td>
                                    <td>{{ $data->kode_tanah }}</td>
                                    <td>{{ $data->asal }}</td>
                                    <td>{{ number_format($data->harga, 0, '.', '.') }}</td>
                                    <td>{{ $data->kondisi }}</td>
                                    <td>{{ $data->keterangan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfooot>
                            <tr>
                                <th colspan="14" style="text-align:right">Total:</th>
                                <th colspan="3">{{ number_format($total, 0, '.', '.') }}</th>
                            </tr>
                        </tfooot>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
@endsection