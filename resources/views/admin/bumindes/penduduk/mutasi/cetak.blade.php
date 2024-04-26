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
                <h4>B.2 BUKU MUTASI PENDUDUK {{ strtoupper(setting('sebutan_desa')) }}</h4>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                <h4>BUKU MUTASI PENDUDUK BULAN {{ strtoupper(getBulan(date('m'))) }} TAHUN {{ date('Y') }}</h4>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <table class="border thick">
                    <thead>
                        <tr class="border thick">
                            <th rowspan="2">NOMOR URUT</th>
                            <th rowspan="2">NAMA LENGKAP / PANGGILAN</th>
                            <th colspan="2">TEMPAT & TANGGAL LAHIR</th>
                            <th rowspan="2">JENIS KELAMIN</th>
                            <th rowspan="2">KEWARGANEGARAAN</th>
                            <th colspan="2">PENAMBAHAN</th>
                            <th colspan="4">PENGURANGAN</th>
                            <th rowspan="2">KET</th>
                        </tr>
                        <tr class="border thick">
                            <th>TEMPAT LAHIR</th>
                            <th>TANGGAL</th>
                            <th>DATANG DARI</th>
                            <th>TANGGAL</th>
                            <th>PINDAH KE</th>
                            <th>TANGGAL</th>
                            <th>MENINGGAL</th>
                            <th>TANGGAL</th>
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
                            <th>12</th>
                            <th>13</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($main as $key => $data)
                            <tr>
                                <td class="padat">{{ $key + 1 }}</td>
                                <td>{{ strtoupper($data->penduduk->nama) }}</td>
                                <td>{{ $data->penduduk->tempatlahir }}</td>
                                <td>{{ tgl_indo_out($data->penduduk->tanggallahir) }}</td>
                                <td>{{ strtoupper($data->penduduk->jenisKelamin->nama) }}</td>
                                <td>{{ $data->penduduk->warganegara->nama }}</td>
                                <td>{{ $data->kode_peristiwa == 5 ? strtoupper($data->penduduk->alamat_sebelumnya) : '-' }}</td>
                                <td>{{ $data->kode_peristiwa == 5 ? tgl_indo_out($data->penduduk->created_at) : '-' }}</td>
                                <td>{{ strtoupper($data->kode_peristiwa == 3 ? $data->alamat_tujuan : '-') }}</td>
                                <td>{{ $data->kode_peristiwa == 3 ? tgl_indo_out($data->tgl_peristiwa) : '-' }}</td>
                                <td>{{ strtoupper($data->kode_peristiwa == 2 ? $data->meninggal_di : '-') }}</td>
                                <td>{{ $data->kode_peristiwa == 2 ? tgl_indo_out($data->tgl_peristiwa) : '-' }}</td>
                                <td>{{ $data->catatan ? strtoupper($data->catatan) : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
