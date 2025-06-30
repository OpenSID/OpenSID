<table>
    <tbody>
        <tr>
            <td>
                @if ($aksi != 'unduh')
                    <img class="logo" src="{{ gambar_desa($desa['logo']) }}" alt="logo-desa">
                @endif
                <h1 class="judul">
                    PEMERINTAH {{ strtoupper(setting('sebutan_kabupaten')) }} {{ strtoupper($desa['nama_kabupaten']) }} <br> {{ strtoupper(setting('sebutan_kecamatan')) }} {{ strtoupper($desa['nama_kecamatan']) }}<br> {{ strtoupper(setting('sebutan_desa')) }}
                    {{ strtoupper($desa['nama_desa']) }}
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
                <h4><u> DATA ARSIP LAYANAN SURAT {{ strtoupper(setting('sebutan_desa')) }} </u></h4>
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
                            <th>No Kode Surat</th>
                            <th>No Urut Surat</th>
                            <th>Jenis Surat</th>
                            <th>Nama Penduduk</th>
                            <th>Keterangan</th>
                            <th>Ditandatangani Oleh</th>
                            <th>Tanggal</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($main as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }} </td>
                                <td class="textx">{{ $data->formatSurat->kode_surat ?? '' }} </td>
                                <td class="textx">{{ $data->no_surat }} </td>
                                <td class="textx">{{ $data->formatSurat->nama ?? '' }} </td>
                                <td>
                                    {!! $data->penduduk->nama ?? ($data->nama_non_warga ? '<strong>Non-warga: </strong>' . $data->nama_non_warga . '<br><strong>NIK: </strong>' . $data->nik_non_warga : '') !!}
                                </td>
                                <td>{{ $data->keterangan }} </td>
                                <td>{{ $data->nama_pamong }} </td>
                                <td nowrap>{{ tgl_indo2($data->tanggal) }}</td>
                                <td>{{ $data->user->nama ?? '' }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
</table>
