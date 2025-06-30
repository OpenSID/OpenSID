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
                <h4>BUKU HASIL PEMBANGUNAN</h4>
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
                            <th>NOMOR URUT</th>
                            <th>JENIS/NAMA HASIL PEMBANGUNAN</th>
                            <th>VOLUME</th>
                            <th>BIAYA</th>
                            <th>LOKASI</th>
                            <th>KETERANGAN</th>
                        </tr>
                        <tr class="border thick">
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                            <th>6</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($main as $key => $data)
                            <tr>
                                <td align="center">{{ $key + 1 }}</td>
                                <td>{{ $data->judul }}</td>
                                <td>{{ $data->volume }}</td>
                                <td align="right">{{ Rupiah2($data->perubahan_anggaran > 0 ? $data->perubahan_anggaran : $data->anggaran) }}</td>
                                <td>{{ $data->wilayah->dusun }}</td>
                                <td>{{ $data->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
