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
                <h2>BUKU EKSPEDISI</h2>
                {!! $tahun ? '<h3>' . $tahun . '</h3>' : '' !!}
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
                            <th>NOMOR URUT</th>
                            <th>TANGGAL PENGIRIMAN</th>
                            <th>TANGGAL DAN NOMOR SURAT</th>
                            <th>ISI SINGKAT SURAT YANG DIKIRIM</th>
                            <th>DITUJUKAN KEPADA</th>
                            <th>KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($main as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ tgl_indo($data['tanggal_pengiriman']) }}</td>
                                <td>{{ tgl_indo($data['tanggal_surat']) . ' / ' . $data['nomor_surat'] }}</td>
                                <td>{{ $data['isi_singkat'] }}</td>
                                <td>{{ $data['tujuan'] }}</td>
                                <td>{{ $data['keterangan'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
