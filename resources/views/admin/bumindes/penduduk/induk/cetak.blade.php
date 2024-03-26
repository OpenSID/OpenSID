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
                <h4>B1. BUKU INDUK PENDUDUK</h4>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                <h4>BUKU INDUK PENDUDUK BULAN {{ strtoupper(getBulan(date('m'))) }} TAHUN {{ date('Y') }}</h4>
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
                            <th rowspan="2">JENIS KELAMIN</th>
                            <th rowspan="2">STATUS PERKAWINAN</th>
                            <th colspan="2">TEMPAT & TANGGAL LAHIR</th>
                            <th rowspan="2">AGAMA</th>
                            <th rowspan="2">PENDIDIKAN TERAKHIR</th>
                            <th rowspan="2">PEKERJAAN</th>
                            <th rowspan="2">DAPAT MEMBACA HURUF</th>
                            <th rowspan="2">KEWARGANEGARAAN</th>
                            <th rowspan="2">ALAMAT LENGKAP</th>
                            <th rowspan="2">KEDUDUKAN DLM KELUARGA</th>
                            <th rowspan="2">NIK</th>
                            <th rowspan="2">NOMOR KK</th>
                            <th rowspan="2">KET</th>
                        </tr>
                        <tr class="border thick">
                            <th>TEMPAT LAHIR</th>
                            <th width="70px">TGL</th>
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
                            <th>14</th>
                            <th>15</th>
                            <th>16</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($main as $key => $data)
                            <tr>
                                <td align="center">{{ $key + 1 }}</td>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->jenisKelamin->nama }}</td>
                                <td>{{ strtoupper(in_array($data->status_kawin, [1, 2]) ? $data->statusKawin->nama : ($data->sex == 1 ? 'DUDA' : 'JANDA')) }}</td>
                                <td>{{ $data->tempatlahir }}</td>
                                <td>{{ tgl_indo_out($data->tanggallahir) }}</td>
                                <td>{{ $data->agama->nama }}</td>
                                <td>{{ $data->pendidikan->nama }}</td>
                                <td>{{ $data->pekerjaan->nama }}</td>
                                <td>{{ strtoupper($data->bahasa->nama) }}</td>
                                <td>{{ $data->warganegara->nama }}</td>
                                <td>{{ strtoupper($data->alamat_wilayah) }}</td>
                                <td>{{ $data->hubungan->nama }}</td>
                                <td>{!! $privasi_nik ? sensor_nik_kk($data->nik) : ($aksi == 'unduh' ? $data->nik . '&nbsp' : $data->nik) !!}</td>
                                <td>{!! $privasi_nik ? sensor_nik_kk($data->keluarga->no_kk) : ($aksi == 'unduh' ? $data->no_kk . '&nbsp' : $data->keluarga->no_kk) !!}</td>
                                <td>{{ $data->ket }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
