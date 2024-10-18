<div class="header" align="center">
    <label align="left"><?= get_identitas() ?></label>
    <h3>
        <span>AGENDA SURAT KELUAR</span>
        @if ($tahun)
            TAHUN {{ $tahun }}
        @endif
    </h3>
    <br>
</div>
<table class="border thick">
    <thead>
        <tr class="border thick">
            <th>Nomor Urut</th>
            <th>Nomor Surat</th>
            <th>Tanggal Surat</th>
            <th>Ditujukan Kepada</th>
            <th>Isi Singkat</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($main as $data)
            <tr>
                <td><?= $data['nomor_urut'] ?></td>
                <td><?= $data['nomor_surat'] ?></td>
                <td><?= tgl_indo($data['tanggal_surat']) ?></td>
                <td><?= $data['tujuan'] ?></td>
                <td><?= $data['isi_singkat'] ?></td>
            </tr>
        @endforeach
    </tbody>
</table>
