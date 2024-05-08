<div class="header" align="center">
    <label align="left">{{ get_identitas() }}</label>
    <h3> DAFTAR {{ strtoupper($kategori) }} {{ empty($tahun) ? '' : 'TAHUN ' . $tahun }}</h3>
    <br>
</div>
<table class="border thick">
    <thead>
        <tr class="border thick">
            <th>No</th>
            <th colspan="3">Judul / Tentang</th>
            <th colspan="2">Tahun</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($main as $data)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td colspan="3">{{ $data['nama'] }}</td>
                <td colspan="2" align="center">{{ $data['tahun'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
