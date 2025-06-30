<table>
    <tbody>
        <tr>
            <td>
                @if ($aksi != 'unduh')
                    <img class="logo" src="{{ gambar_desa($desa['logo']) }}" alt="logo-desa">
                @endif
                <h1 class="judul">
                    PEMERINTAH
                    {!! strtoupper(setting('sebutan_kabupaten') . ' ' . $desa['nama_kabupaten'] . ' <br>' . setting('sebutan_kecamatan') . ' ' . $desa['nama_kecamatan'] . ' <br>' . setting('sebutan_desa') . ' ' . $desa['nama_desa']) !!}
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
                <h4><u> {{ strtoupper($main['title']) }} </u></h4>
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
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Tempat Lahir</th>
                            <th>Tanggal Lahir</th>
                            <th>Nama Ayah</th>
                            <th>Nama Ibu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($main as $key => $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $sensor_nik ? sensor_nik_kk($data->nik) : $data->nik }}</td>
                                <td>{{ $data->tempatlahir }}</td>
                                <td>{{ $data->tanggallahir?->format('Y-m-d') }}</td>
                                <td>{{ $data->nama_ayah }}</td>
                                <td>{{ $data->nama_ibu }}</td>
                            </tr>
                        @endforeach
                </table>
            </td>
        </tr>
    </tbody>
</table>
</table>
