<div class="header" align="center">
    <label align="left"><?= get_identitas() ?></label>
    <h3>
        <span>AGENDA SURAT MASUK</span>
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
            <th>Tanggal Penerimaan</th>
            <th>Nomor Surat</th>
            <th>Tanggal Surat</th>
            <th>Pengirim</th>
            <th>Isi Singkat</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($main as $data)
            <tr>
                <td><?= $data['nomor_urut'] ?></td>
                <td><?= tgl_indo($data['tanggal_penerimaan']) ?></td>
                <td><?= $data['nomor_surat'] ?></td>
                <td><?= tgl_indo($data['tanggal_surat']) ?></td>
                <td><?= $data['pengirim'] ?></td>
                <td><?= $data['isi_singkat'] ?></td>
            </tr>
        @endforeach
    </tbody>
</table>
