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
                <h4>BUKU KADER PEMBERDAYAAN MASYARAKAT</h4>
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
                            <th>No. Urut</th>
                            <th>Nama</th>
                            <th>Umur</th>
                            <th>Jenis Kelamin</th>
                            <th>Pendidikan / Kursus</th>
                            <th>Bidang</th>
                            <th>Alamat</th>
                            <th>Keterangan</th>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($main as $key => $data)
                            <tr>
                                <td align="center">{{ $key + 1 }}</td>
                                <td class="textx">{{ $data->penduduk->nama }}</td>
                                <td align="center">{{ usia($data->penduduk->tanggallahir, null, '%y') }}</td>
                                <td align="center">{{ $data->penduduk->sex == 1 ? 'L' : 'P' }}</td>
                                <td>{!! str_replace(',', ', ', App\Enums\PendidikanKKEnum::valueOf($data->penduduk->pendidikan_kk_id) . '<br/>' . preg_replace('/[^a-zA-Z, ]/', '', $data->kursus)) !!}</td>
                                <td>{{ str_replace(',', ', ', preg_replace('/[^a-zA-Z, ]/', '', $data->bidang)) }}</td>
                                <td>{{ $data->penduduk->alamat_wilayah }}</td>
                                <td>{{ $data->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
