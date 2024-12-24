<table>
    <tbody>
        <tr>
            <td>
                @if ($aksi != 'unduh')
                    <img class="logo" src="{{ gambar_desa($config['logo']) }}" alt="logo-desa">
                @endif
                <h1 class="judul">
                    PEMERINTAH {!! strtoupper(setting('sebutan_kabupaten') . ' ' . $config['nama_kabupaten'] . ' <br>' . setting('sebutan_kecamatan') . ' ' . $config['nama_kecamatan'] . ' <br>' . setting('sebutan_desa') . ' ' . $config['nama_desa']) !!}
                </h1>
            </td>
        </tr>
        <tr>
            <td>
                <hr class="garis">
            </td>
        </tr>
        <tr>
            <td class="text-center">
                <h4>BUKU RENCANA PEMBANGUNAN</h4>
            </td>
        </tr>
        @if ($tahun)
            <tr>
                <td class="text-center">
                    <h4>TAHUN {{ $tahun }}</h4>
                </td>
            </tr>
        @endif
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <table class="border thick">
                    <thead>
                        <tr class="border thick">
                            <th rowspan="2">NOMOR URUT</th>
                            <th rowspan="2">NAMA PROYEK / KEGIATAN</th>
                            <th rowspan="2">LOKASI</th>
                            <th colspan="4">SUMBER BIAYA</th>
                            <th rowspan="2">JUMLAH</th>
                            <th rowspan="2">PELAKSANA</th>
                            <th rowspan="2">MANFAAT</th>
                            <th rowspan="2">KET</th>
                        </tr>
                        <tr class="border thick">
                            <th>PROVINSI</th>
                            <th>PEMERINTAH</th>
                            <th>KAB/KOTA</th>
                            <th>SWADAYA</th>
                        </tr>
                        <tr class="border thick">
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                            <th>6</th>
                            <th>7</th>
                            <th>8</th>
                            <th>9</th>
                            <th>10</th>
                            <th>11</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($main as $key => $data)
                            <tr>
                                <td align="center">{{ $key + 1 }}</td>
                                <td>{{ $data->judul }}</td>
                                <td>{{ $data->wilayah->dusun }}</td>
                                <td align="right">{{ Rupiah2($data->sumber_biaya_provinsi) }}</td>
                                <td align="right">{{ Rupiah2($data->sumber_biaya_pemerintah) }}</td>
                                <td align="right">{{ Rupiah2($data->sumber_biaya_kab_kota) }}</td>
                                <td align="right">{{ Rupiah2($data->sumber_biaya_swadaya) }}</td>
                                <td align="right">{{ Rupiah2($data->sumber_biaya_jumlah) }}</td>
                                <td>{{ $data->pelaksana_kegiatan }}</td>
                                <td>{{ $data->manfaat }}</td>
                                <td>{{ $data->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
