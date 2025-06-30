<table>
    <tbody>
        <tr>
            <td>
                @if ($aksi != 'unduh')
                    <img class="logo" src="{{ gambar_desa($desa['logo']) }}" alt="logo-desa">
                @endif
                <h1 class="judul">
                    PEMERINTAH {!! strtoupper(setting('sebutan_kabupaten') . ' ' . $desa['nama_kabupaten'] . ' <br>' . setting('sebutan_kecamatan') . ' ' . $desa['nama_kecamatan'] . ' <br>' . setting('sebutan_desa') . ' ' . $desa['nama_desa']) !!}
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
                <h4>B4. BUKU PENDUDUK SEMENTARA</h4>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                <h4>BUKU PENDUDUK SEMENTARA BULAN {{ strtoupper(getBulan(date('m'))) }} TAHUN {{ date('Y') }}</h4>
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
                            <th rowspan="2">NAMA LENGKAP</th>
                            <th colspan="2">JENIS KELAMIN</th>
                            <th rowspan="2">NOMOR IDENTITAS / TANDA PENGENAL</th>
                            <th rowspan="2">TEMPAT DAN TANGGAL LAHIR / UMUR</th>
                            <th rowspan="2">PEKERJAAN</th>
                            <th colspan="2">KEWARGANEGARAAN</th>
                            <th rowspan="2">DATANG DARI</th>
                            <th rowspan="2">MAKSUD DAN TUJUAN KEDATANGAN</th>
                            <th rowspan="2">NAMA DAN ALAMAT YG DIDATANGI</th>
                            <th rowspan="2">DATANG TANGGAL</th>
                            <th rowspan="2">PERGI TANGGAL</th>
                            <th rowspan="2">KET</th>
                        </tr>
                        <tr class="border thick">
                            <th>L</th>
                            <th>P</th>
                            <th>KEBANGSAAN</th>
                            <th>KETURUNAN</th>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($main as $key => $data)
                            <tr>
                                <td class="padat" align="center">{{ $key + 1 }}</td>
                                <td>{{ $data->nama }}</td>
                                <td class="padat">{{ $data->sex == 1 ? 'L' : '' }}</td>
                                <td class="padat">{{ $data->sex == 2 ? 'P' : '' }}</td>
                                <td>{!! $privasi_nik ? sensor_nik_kk($data->nik) : ($aksi == 'unduh' ? $data->nik . '&nbsp' : $data->nik) !!}</td>
                                <td>{{ $data->tempatlahir . ', ' . tgl_indo_out($data->tanggallahir) }}</td>
                                <td>{{ $data->pekerjaan->nama }}</td>
                                <td>{{ $data->warganegara->nama }}</td>
                                <td>{{ empty($data->negara_asal) ? '-' : $data->negara_asal }}</td>
                                <td>{{ empty($data->alamat_sebelumnya) ? '-' : $data->alamat_sebelumnya }}</td>
                                <td>{{ empty($data->log_latest->maksud_tujuan_kedatangan) ? '-' : $data->log_latest->maksud_tujuan_kedatangan }}</td>
                                <td>{{ strtoupper($data->alamat_wilayah) }}</td>
                                <td>{{ empty($data->log_latest->tgl_lapor) ? '-' : tgl_indo_out($data->log_latest->tgl_lapor) }}</td>
                                <td>{{ $data->log_latest->kode_peristiwa == 6 ? tgl_indo_out($data->log_latest->tgl_lapor) : '-' }}</td>
                                <td>{{ empty($data->ket) ? '-' : $data->ket }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
