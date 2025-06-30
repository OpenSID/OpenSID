<table>
    <tbody>
        <tr>
            <td>
                <h3 class="text-center">
                    KARTU INVENTARIS BARANG (KIB) <br>
                    E. ASET TETAP LAINNYA
                </h3>
            </td>
        </tr>
        <tr>
            <td>
                <table style="width: 200px;">
                    <tr>
                        <td>{{ strtoupper(setting('sebutan_desa')) }}</td>
                        <td>: {{ strtoupper($desa['nama_desa']) }}</td>
                    </tr>
                    <tr>
                        <td>{{ strtoupper(setting('sebutan_kecamatan')) }}</td>
                        <td>: {{ strtoupper($desa['nama_kecamatan']) }}</td>
                    </tr>
                    <tr>
                        <td>{{ strtoupper(setting('sebutan_kabupaten')) }}</td>
                        <td>: {{ strtoupper($desa['nama_kabupaten']) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table>
                    <tr style="float: right;">
                        <td>KODE LOKASI : _ _ . _ _ . _ _ . _ _ . _ _ . _ _ . _ _ _</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <hr class="garis">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
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
                            <th class="text-center" style="text-align:center;" rowspan="1">Kode Barang</th>
                            <th class="text-center" style="text-align:center;" rowspan="1">Register</th>
                            <th class="text-center" style="text-align:center;" rowspan="1">Judui / Pencipta</th>
                            <th class="text-center" style="text-align:center;" rowspan="1">Spesifikasi</th>
                            <th class="text-center" style="text-align:center;" rowspan="1">Asal Daerah</th>
                            <th class="text-center" style="text-align:center;" rowspan="1">Pencipta</th>
                            <th class="text-center" style="text-align:center;" rowspan="1">Bahan</th>
                            <th class="text-center" style="text-align:center;" rowspan="1">Jenis</th>
                            <th class="text-center" style="text-align:center;" rowspan="1">Ukuran (M)</th>
                            <th class="text-center" style="text-align:center;" rowspan="1">Jenis</th>
                            <th class="text-center" style="text-align:center;" rowspan="1">Ukuran (cm)</th>
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
</td>
</tr>
</tbody>
</table>
