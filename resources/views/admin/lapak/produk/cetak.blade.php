<table>
    <tbody>
        <tr>
            <td>
                @if ($aksi != 'unduh')
                    <img class="logo" src="{{ gambar_desa($config['logo']) }}" alt="logo-desa">
                @endif
                <h1 class="judul" align="center">
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
            <td align="center">
                <h4><u>DATA PRODUK</u></h4>
            </td>
        </tr>
        <tr></tr>
        <tr>
            <td style="padding: 5px 20px;">
                <table border=1 class="border thick">
                    <thead>
                        <tr class="border thick">
                            <th class="padat">NO</th>
                            <th>PELAPAK</th>
                            <th>PRODUK</th>
                            <th>KATEGORI</th>
                            <th>HARGA</th>
                            <th>SATUAN</th>
                            <th>POTONGAN</th>
                            <th>DESKRIPSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($main as $key => $data)
                            <tr>
                                <td class="padat">{{ $loop->iteration }}</td>
                                <th>{{ $data->pelapak->penduduk->nama }}</th>
                                <th>{{ $data->nama }}</th>
                                <th>{{ $data->kategori->kategori }}</th>
                                <th class="text-right">{{ rupiah2($data->harga) }}</th>
                                <th class="padat">{{ $data->satuan }}</th>
                                <th class="text-right">{{ $data->tipe == 1 ? $data->potongan . '%' : rupiah2($data->potongan) }}</th>
                                <th>{{ $data->deskripsi }}</th>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
