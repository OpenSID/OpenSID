<table class="table table-bordered table-striped table-hover">
    <tbody>
        <tr>
            <td>Nomor Kartu Peserta</td>
            <td> : {{ $data->no_id_kartu }}</td>
        </tr>
        <tr>
            <td>NIK</td>
            <td> : {{ $data->kartu_nik }}</td>
        </tr>
        <tr>
            <td>Nama</td>
            <td> : {{ $data->kartu_nama }}</td>
        </tr>
        <tr>
            <td>Tempat Lahir</td>
            <td> : {{ $data->kartu_tempat_lahir }}</td>
        </tr>
        <tr>
            <td>Tanggal Lahir</td>
            <td> : {{ tgl_indo($data->kartu_tanggal_lahir) }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td> : {{ $data->kartu_alamat }}</td>
        </tr>
    </tbody>
</table>
