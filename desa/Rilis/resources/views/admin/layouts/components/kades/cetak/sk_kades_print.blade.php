<table>
    <tbody>
        <tr>
            <td align="center">
                @if ($aksi != 'unduh')
                    <img class="logo" src="{{ gambar_desa($config['logo']) }}" alt="logo-desa">
                @endif
                <h1 class="judul">
                    BUKU KEPUTUSAN KEPALA
                    {{ strtoupper(setting('sebutan_desa')) . ' ' . strtoupper($desa['nama_desa']) }}
                </h1>
            </td>
        </tr>
        <tr>
            <td align="center">
                <h2>
                    {{ strtoupper(setting('sebutan_kecamatan') . ' ' . $desa['nama_kecamatan'] . ' ' . setting('sebutan_kabupaten') . ' ' . $desa['nama_kabupaten']) }}
                </h2>
            </td>
        </tr>
        <tr>
            <td align="center">
                <h2>
                    {{ empty($tahun) ? '' : 'TAHUN ' . $tahun }}
                </h2>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <table class="border thick" width="100%">
                    <thead>
                        <tr class="border thick">
                            <th>NOMOR URUT</th>
                            <th>NOMOR DAN TANGGAL KEPUTUSAN KEPALA DESA</th>
                            <th>TENTANG</th>
                            <th>URAIAN SINGKAT</th>
                            <th>NOMOR DAN TANGGAL DILAPORKAN</th>
                            <th>KET.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($main as $no => $data)
                            <tr>
                                <td>
                                    {{ $no + 1 }}
                                </td>
                                <td>
                                    {{ 'Nomor ' . strip_kosong($data['attr']['no_kep_kades']) . ', Tanggal ' . tgl_indo_dari_str($data['attr']['tgl_kep_kades']) }}
                                </td>
                                <td>
                                    {{ $data['nama'] }}
                                </td>
                                <td>
                                    {{ $data['attr']['uraian'] }}
                                </td>
                                <td>
                                    {{ 'Nomor ' . strip_kosong($data['attr']['no_lapor']) . ', Tanggal ' . tgl_indo_dari_str($data['attr']['tgl_lapor']) }}
                                </td>
                                <td>
                                    {{ $data['attr']['keterangan'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
