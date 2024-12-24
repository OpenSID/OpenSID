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
                <h2>LAPORAN DATA STATISTIK PENGUNJUNG WEBSITE {{ strtoupper($main['judul']) }}</h2>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <table class="border thick">
                    <thead>
                        <tr class="thick">
                            <th class="thick">No</th>
                            <th class="thick">{{ $main['lblx'] }}</th>
                            <th class="thick">Pengunjung (Orang)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($main['pengunjung'] as $no => $data)
                            <tr>
                                <td class="thick" align="center" width="2">{{ $no + 1 }}</td>
                                <td class="thick" align="center">
                                    {{ $main['lblx'] == 'Bulan' ? getBulan($data['Tanggal']) . ' ' . date('Y') : tgl_indo2($data['Tanggal']) }}</td>
                                <td class="thick" align="center">{{ ribuan($data['Jumlah']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray disabled color-palette">
                        <tr class="thick">
                            <th colspan="2" class="text-center">Total</th>
                            <th class="text-center">{{ ribuan($main['Total']) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
    </tbody>
</table>
