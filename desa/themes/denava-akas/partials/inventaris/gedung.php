<div class="article-single">
    <div class="container-page mb-20">
        <div class="relative">
            <div class="headingpage border-grey-soft bg-white flexleft" style="border-radius:5px 5px 0 0;">
                <div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/arsip.svg") ?>" alt=""/></div>
                <h2>Data <?= $judul ?></h2>
            </div>
            <div class="big-screen">
                <div class="righthead flexright">
                    <div class="righthead-item border-grey-soft flexcenter">
                        <a class="btn btn-sm btn-info" href="<?= site_url(); ?>inventaris">Kembali</a>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="box-article bg-white" style="border-radius:0 0 5px 5px;">
            <div class="box-statis border-grey-soft" style="border-radius:0 0 5px 5px;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="inventaris">
                        <thead class="bg-gray">
                            <tr>
                                <th class="text-center" rowspan="2">No.</th>
                                <th class="text-center" rowspan="2">Nama Barang</th>
                                <th class="text-center" rowspan="2">Kode Barang / Nomor Registrasi</th>
                                <th class="text-center" rowspan="2">Kondisi Bangunan<br>(B, KB, RB)</th>
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
                            <?php $i=1; ?>
                            <?php foreach ($main as $data): ?>
                                <tr>
                                    <td class="angka text-center"><?= $i; ?></td>
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
                                <?php $i++; ?>
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
    </div>
</div>
<?php $this->load->view("$folder_themes/partials/inventaris/script") ?>
