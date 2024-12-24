<table>
    <tbody>
        <tr>
            <td>
                <h3 class="text-center">
                    KARTU INVENTARIS BARANG (KIB) <br>
                    D. JALAN, IRIGASI DAN JARINGAN
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
                            <th class="text-center" style="text-align:center;" rowspan="1">Kode Barang</th>
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
                                <td>{{ $data->tanggal_dokument }}</td>
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
</td>
</tr>
</tbody>
</table>
