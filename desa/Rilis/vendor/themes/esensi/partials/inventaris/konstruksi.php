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
                    <th class="text-center" rowspan="2">Fisik Bangunan (P, SP, D)</th>
                    <th class="text-center" rowspan="2">Luas (M<sup>2</sup>)</th>
                    <th class="text-center" colspan="2">Dokumen</th>
                    <th class="text-center" rowspan="2">Tgl,bln,thn Mulai</th>
                    <th class="text-center" rowspan="2">Status Tanah</th>
                    <th class="text-center" rowspan="2">Asal Usul Biaya</th>
                    <th class="text-center" rowspan="2">Nilai Kontrak (Rp)</th>
                </tr>
                <tr>
                    <th class="text-center" rowspan="1">Tanggal</th>
                    <th class="text-center" rowspan="1">Nomor</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($main as $data): ?>
                    <tr>
                        <td></td>
                        <td><?= $data->nama_barang; ?></td>
                        <td><?= $data->kondisi_bangunan; ?></td>
                        <td><?= (empty($data->luas_bangunan)) ? '-' : $data->luas_bangunan ?></td>
                        <td><?= (date('d M Y', strtotime($data->tanggal_dokument)) === '') ? '-' : date('d M Y', strtotime($data->tanggal_dokument)) ?></td>
                        <td><?= (empty($data->no_dokument)) ? '-' : $data->no_dokument ?></td>
                        <td nowrap><?= (date('d M Y', strtotime($data->tanggal)) === '') ? '-' : date('d M Y', strtotime($data->tanggal)) ?></td>
                        <td><?= (empty($data->status_tanah)) ? '-' : $data->status_tanah ?></td>
                        <td><?= $data->asal; ?></td>
                        <td class="text-right"><?= number_format($data->harga, 0, '.', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <?php if (count($main) > 0): ?>
                <tfoot>
                    <tr>
                        <th colspan="9" class="text-right">Total:</th>
                        <th class="text-right"><?= number_format($total, 0, '.', '.'); ?></th>
                    </tr>
                </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>
<?php $this->load->view("$folder_themes/partials/inventaris/script") ?>