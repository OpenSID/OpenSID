<div class="single_page_area">
    <h2 class="post_titile">Data <?= $judul ?></h2>
    <div class="box-body">
        <div class="table-responsive">
            <table id="inventaris" class="table table-bordered dataTable table-hover">
                <thead class="bg-gray">
                    <tr>
                        <th class="text-center" rowspan="2">No</th>
                        <th class="text-center" rowspan="2">Nama Barang</th>
                        <th class="text-center" rowspan="2">Kode Barang / Nomor Registrasi</th>
                        <th class="text-center" rowspan="2">Kondisi Bangunan (B, KB, RB)</th>
                        <th class="text-center" rowspan="2">Letak/Lokasi</th>
                        <th class="text-center" colspan="2">Dokumen Gedung</th>
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
                            <td><?= $data->kondisi_bangunan; ?></td>
                            <td><?= $data->letak; ?></td>
                            <td><?= (empty($data->tanggal_dokument)) ? '-' : $data->tanggal_dokument ?></td>
                            <td><?= (empty($data->no_dokument)) ? '-' : $data->no_dokument	?></td>
                            <td><?= $data->status_tanah; ?></td>
                            <td><?= $data->asal; ?></td>
                            <td><?= number_format($data->harga, 0, '.', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <?php if (count($main) > 0): ?>
                    <tfoot>
                        <tr>
                            <th colspan="9" class="text-right">Total:</th>
                            <th><?= number_format($total, 0, '.', '.'); ?></th>
                        </tr>
                    </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>
<?php $this->load->view("$folder_themes/partials/inventaris/script") ?>