<div class="content py-1">
    <div class="box box-danger" style="padding-bottom: 2rem;">
    <div class="box-header with-border" style="margin-bottom: 15px;">
        <h class="box-title">Inventaris <?= $judul ?></h>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="inventaris">
            <thead class="bg-gray">
                <tr>
                    <th class="text-center" rowspan="2">No</th>
                    <th class="text-center" rowspan="2">Nama Barang</th>
                    <th class="text-center" rowspan="2">Kode Barang / Nomor Registrasi</th>
                    <th class="text-center" rowspan="2">Kondisi (B, KB, RB)</th>
                    <th class="text-center" rowspan="2">Jenis Konstruksi</th>
                    <th class="text-center" rowspan="2">Luas (M<sup>2</sup>)</th>
                    <th class="text-center" colspan="2">Dokumen Kepemilikan</th>
                    <th class="text-center" rowspan="2">Status Tanah</th>
                    <th class="text-center" rowspan="2">Asal Usul</th>
                    <th class="text-center" rowspan="2">Harga (Rp)</th>
                </tr>
                <tr>
                    <th class="text-center" style="text-align:center;" rowspan="1">Tanggal</th>
                    <th class="text-center" style="text-align:center;" rowspan="1">Nomor</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($main as $data): ?>
                    <tr>
                        <td></td>
                        <td><?= $data->nama_barang; ?></td>
                        <td><?= $data->kode_barang; ?><br><?= $data->register; ?></td>
                        <td><?= $data->kondisi; ?></td>
                        <td><?= $data->kontruksi; ?></td>
                        <td><?= (empty($data->luas)) ? '-' : $data->luas ?></td>
                        <td><?= (date('d M Y', strtotime($data->tanggal_dokument)) === '') ? '-' : date('d M Y', strtotime($data->tanggal_dokument)) ?></td>
                        <td><?= (empty($data->no_dokument)) ? '-' : $data->no_dokument ?></td>
                        <td><?= $data->status_tanah; ?></td>
                        <td><?= $data->asal; ?></td>
                        <td><?= number_format($data->harga, 0, '.', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <?php if (count($main) > 0): ?>
                <tfoot>
                    <tr>
                        <th colspan="10" class="text-right">Total:</th>
                        <th><?= number_format($total, 0, '.', '.'); ?></th>
                    </tr>
                </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>
<?php $this->load->view("$folder_themes/partials/inventaris/script") ?>