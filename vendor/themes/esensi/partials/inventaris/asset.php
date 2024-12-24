<div class="content py-1">
    <div class="box box-danger" style="padding-bottom: 2rem;">
    <div class="box-header with-border" style="margin-bottom: 15px;">
        <h class="box-title">Inventaris <?= $judul ?></h>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="inventaris">
            <thead class="bg-gray">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Barang</th>
                    <th class="text-center">Kode Barang / Nomor Registrasi</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Tahun Pembelian</th>
                    <th class="text-center">Asal Usul</th>
                    <th class="text-center">Harga (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($main as $data): ?>
                    <tr>
                        <td></td>
                        <td><?= $data->nama_barang; ?></td>
                        <td><?= $data->kode_barang; ?><br><?= $data->register; ?></td>
                        <td><?= $data->jumlah; ?></td>
                        <td><?= $data->tahun_pengadaan; ?></td>
                        <td><?= $data->asal; ?></td>
                        <td><?= number_format($data->harga, 0, '.', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <?php if (count($main) > 0): ?>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-right">Total:</th>
                        <th><?= number_format($total, 0, '.', '.'); ?></th>
                    </tr>
                </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>
<?php $this->load->view("$folder_themes/partials/inventaris/script") ?>