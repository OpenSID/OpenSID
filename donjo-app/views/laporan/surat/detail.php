<div class="table-responsive">
    <table class="table table-bordered dataTable table-striped table-hover">
        <thead class="bg-gray disabled color-palette">
            <tr>
                <th>No</th>
                <th>Tanggal</a>
                <th>Nama</th>
                <th>Keterangan</th>
                <th>TTD</th>
                <th>User</th>
                <th>Berkas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($result as $i => $row): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td nowrap><?= tgl_indo2($row['tanggal'])?></td>
                <td>
                    <?php if ($row['nama']): ?>
                    <?= $row['nama']; ?>
                    <?php elseif ($row['nama_non_warga']): ?>
                    <strong>Non-warga: </strong><?= $row['nama_non_warga']; ?><br>
                    <strong>NIK: </strong><?= $row['nik_non_warga']; ?>
                    <?php endif; ?>
                </td>
                <td><?= $row['keterangan']?></td>
                <td><?= $row['pamong']?></td>
                <td><?= $row['nama_user']?></td>
                <td><?php if ( $row['url_surat'] || $row['url_lampiran']): ?>
                    <?php if( $row['url_surat'] ): ?>
                    <a href="<?= $row['url_surat'] ?>" class="btn btn-social btn-flat bg-purple btn-sm" title="Unduh Surat"
                        target="_blank">
                        <i class="fa fa-file-word-o"></i> Surat
                    </a>
                    <?php endif; if( $row['url_lampiran']) : ?>
                    <a href="<?= $row['url_lampiran'] ?>" class="btn btn-social btn-flat bg-olive btn-sm"
                        title="Unduh Surat" target="_blank">
                        <i class="fa fa-paperclip"></i> Lampiran
                    </a>
                    <?php endif ?>
                    <?php else: echo '&mdash;'; endif; ?>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>