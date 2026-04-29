@extends('admin.layouts.print_layout')

@section('title', "KARTU INVENTARIS BARANG (KIB) E. ASET TETAP LAINNYA")

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
            E. ASET TETAP LAINNYA
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
                                <th class="text-center" rowspan="2">No</th>
                                <th class="text-center" rowspan="2">Nama Barang</th>
                                <th class="text-center" colspan="2">Nomor</th>
                                <th class="text-center" colspan="2">Buku / Perpustakaan</th>
                                <th class="text-center" colspan="3">Barang Bercorak Kesenian/Kebudayaan</th>
                                <th class="text-center" colspan="2">Hewan / Ternak</th>
                                <th class="text-center" colspan="2">Tumbuhan</th>
                                <th class="text-center" rowspan="2">Jumlah</th>
                                <th class="text-center" rowspan="2">Tahun Cetak / Pembelian</th>
                                <th class="text-center" rowspan="2">Asal Usul</th>
                                <th class="text-center" rowspan="2">Harga (Rp)</th>
                                <th class="text-center" rowspan="2">Keterangan</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="text-align:center;" rowspan="1">Kode Barang
                                </th>
                                <th class="text-center" style="text-align:center;" rowspan="1">Register</th>
                                <th class="text-center" style="text-align:center;" rowspan="1">Judui /
                                    Pencipta</th>
                                <th class="text-center" style="text-align:center;" rowspan="1">Spesifikasi
                                </th>
                                <th class="text-center" style="text-align:center;" rowspan="1">Asal Daerah
                                </th>
                                <th class="text-center" style="text-align:center;" rowspan="1">Pencipta</th>
                                <th class="text-center" style="text-align:center;" rowspan="1">Bahan</th>
                                <th class="text-center" style="text-align:center;" rowspan="1">Jenis</th>
                                <th class="text-center" style="text-align:center;" rowspan="1">Ukuran (M)
                                </th>
                                <th class="text-center" style="text-align:center;" rowspan="1">Jenis</th>
                                <th class="text-center" style="text-align:center;" rowspan="1">Ukuran (cm)
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($print as $i => $data) : ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $data->nama_barang ?></td>
                                <td><?= $data->kode_barang ?></td>
                                <td><?= $data->register ?></td>
                                <td><?= empty($data->judul_buku) ? '-' : $data->judul_buku ?></td>
                                <td><?= empty($data->spesifikasi_buku) ? '-' : $data->spesifikasi_buku ?></td>
                                <td><?= empty($data->asal_daerah) ? '-' : $data->asal_daerah ?></td>
                                <td><?= empty($data->pencipta) ? '-' : $data->pencipta ?></td>
                                <td><?= empty($data->bahan) ? '-' : $data->bahan ?></td>
                                <td><?= empty($data->jenis_hewan) ? '-' : $data->jenis_hewan ?></td>
                                <td><?= empty($data->ukuran_hewan) ? '-' : $data->ukuran_hewan ?></td>
                                <td><?= empty($data->jenis_tumbuhan) ? '-' : $data->jenis_tumbuhan ?></td>
                                <td><?= empty($data->ukuran_tumbuhan) ? '-' : $data->ukuran_tumbuhan ?></td>
                                <td><?= $data->jumlah ?></td>
                                <td><?= $data->tahun_pengadaan ?></td>
                                <td><?= $data->asal ?></td>
                                <td><?= number_format($data->harga, 0, '.', '.') ?></td>
                                <td><?= $data->keterangan ?></td>
                            </tr>
                            <?php ++$i; ?>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="16" style="text-align:right">Total:</th>
                                <th colspan="2"><?= number_format($total, 0, '.', '.') ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
@endsection

