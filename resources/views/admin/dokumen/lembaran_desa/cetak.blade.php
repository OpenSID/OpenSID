<div class="header" align="center">
    <h3>BUKU LEMBARAN {{ strtoupper(setting('sebutan_desa')) }} DAN BERITA {{ strtoupper(setting('sebutan_desa')) }}</h3>
    <h4>{{ get_identitas() }}</h4>
    <h4>{{ empty($tahun) ? '' : 'TAHUN ' . $tahun }}</h4>
    <br>
</div>
<table class="border thick">
    <thead>
        <tr class="border thick">
            <th rowspan="2">NOMOR URUT</th>
            <th rowspan="2">JENIS PERATURAN DI DESA</th>
            <th rowspan="2">NOMOR DAN TANGGAL DITETAPKAN</th>
            <th rowspan="2">TENTANG</th>
            <th colspan="2">DIUNDANGKAN</th>
            <th rowspan="2">KET.</th>
        </tr>
        <tr class="border thick">
            <th>TANGGAL</th>
            <th>NOMOR</th>
        </tr>
        <tr class="border thick">
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($main as $index => $data)
            <tr>
                <td class="padat">{{ $index + 1 }}</td>
                <td>{{ $data['attr']['jenis_peraturan'] }}</td>
                <td>{{ 'Nomor ' . strip_kosong($data['attr']['no_ditetapkan']) . ', Tanggal ' . tgl_indo_dari_str($data['attr']['tgl_ditetapkan']) }}</td>
                <td>{{ $data['nama'] }}</td>
                <td>{{ tgl_indo_dari_str($data['attr']['tgl_lembaran_desa']) }}</td>
                <td>{{ strip_kosong($data['attr']['no_lembaran_desa']) }}</td>
                <td>{{ $data['attr']['keterangan'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
