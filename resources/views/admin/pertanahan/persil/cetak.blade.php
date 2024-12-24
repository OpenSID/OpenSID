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
                <h4><u> DATA PERSIL </u></h4>
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
                            <th>No</th>
                            <th>No. Persil : No. Urut Bidang</th>
                            <th>Kelas Tanah</th>
                            <th>Luas (M2)</th>
                            <th>Lokasi</th>
                            <th>C-Desa Awal</th>
                            <th>Jml Mutasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($persil as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="textx">{{ $item->nomor . ' : ' . $item->nomor_urut_bidang }}</td>
                                <td>{{ $item->refKelas->kode }}</td>
                                <td>{{ $item->luas_persil }}</td>
                                <td>{{ $item->wilayah ? $item->wilayah->alamat : $item->lokasi }}</td>
                                <td>{{ $item->cdesa?->nomor }}</td>
                                <td>{{ $item->mutasi_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
