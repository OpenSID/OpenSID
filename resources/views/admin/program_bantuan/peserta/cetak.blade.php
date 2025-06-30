<table>
    <tbody>
        <tr>
            <td align="center">
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
                {{-- <hr style="border-bottom: 2px solid #000000; height:0px;"> --}}
                <hr class="garis">
            </td>
        </tr>
        <tr>
            <td align="center">
                <h4><u>Daftar Peserta Program
                        <?= $main['detail']['nama'] ?>
                    </u></h4>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px 20px;">
                <strong>Sasaran Peserta : </strong>
                <?= $sasaran[$main['detail']['sasaran']] ?><br>
                <strong>Masa Berlaku : </strong>
                <?= fTampilTgl($main['detail']['sdate'], $main['detail']['edate']) ?><br>
                <strong>Keterangan : </strong>
                <?= $main['detail']['ndesc'] ?>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px 20px;">
                <table class="border thick">
                    <thead>
                        <tr class="border thick">
                            <th rowspan="2">No</th>
                            <th rowspan="2">
                                <?= $main['detail']['judul_peserta'] ?>
                            </th>
                            <?php if (! empty($main['detail']['judul_peserta_plus'])) : ?>
                            <th rowspan="2" nowrap class="text-center">
                                <?= $main['detail']['judul_peserta_plus'] ?>
                            </th>
                            <?php endif; ?>
                            <th rowspan="2">
                                <?= $main['detail']['judul_peserta_info'] ?>
                            </th>
                            <th colspan="7" style="text-align: center;">Identitas di Kartu Peserta</th>
                        </tr>
                        <tr class="border thick">
                            <th>No. Kartu Peserta</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Tempat Lahir</th>
                            <th>Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($main['peserta'] as $key => $item)
                            <tr>
                                <td align="center">
                                    {{ $key + 1 }}
                                </td>
                                <td class='textx'>
                                    {{ $item->nik }}
                                </td>
                                @if (!empty($item->peserta_plus))
                                    <td>
                                        {{ $item->peserta_plus }}
                                    </td>
                                @endif
                                <td>
                                    {{ $item->peserta_info }}
                                </td>
                                <td class='textx' align="center">
                                    {{ $item->no_id_kartu }}
                                </td>
                                <td class='textx'>
                                    {{ $item->kartu_nik }}
                                </td>
                                <td>
                                    {{ $item->kartu_nama }}
                                </td>
                                <td>
                                    {{ $item->kartu_tempat_lahir }}
                                </td>
                                <td class='textx'>
                                    {{ tgl_indo_out($item->kartu_tanggal_lahir) }}
                                </td>
                                <td>
                                    {{ $item->sex }}
                                </td>
                                <td>
                                    {{ $item->kartu_alamat }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
