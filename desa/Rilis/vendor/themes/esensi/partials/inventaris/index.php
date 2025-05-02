<div class="content py-1">
    <div class="box box-danger" style="padding-bottom: 2rem;">
        <div class="box-header with-border" style="margin-bottom: 15px;">
            <h class="box-title">Inventaris <?= ucwords(setting('sebutan_desa')) ?></h>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="inventaris">
                    <thead class="bg-gray">
                        <tr>
                            <th class="text-center" rowspan="3" style="vertical-align: middle;">No</th>
                            <th class="text-center" rowspan="3" style="vertical-align: middle;">Jenis Barang</th>
                            <th class="text-center" width="340%" rowspan="3" style="vertical-align: middle;">Keterangan</th>
                            <th class="text-center" colspan="5" style="vertical-align: middle;">Asal barang</th>
                            <th class="text-center" rowspan="3" style="vertical-align: middle;">Aksi</th>
                        </tr>
                        <tr>
                            <th class="text-center" rowspan="2">Dibeli Sendiri</th>
                            <th class="text-center" colspan="3">Bantuan</th>
                            <th class="text-center" style="text-align:center;" rowspan="2">Sumbangan</th>
                        </tr>
                        <tr>
                            <th class="text-center" >Pemerintah</th>
                            <th class="text-center" >Provinsi</th>
                            <th class="text-center" >Kabupaten</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td nowrap>Tanah Kas Desa</td>
                            <td>Informasi mengenai segala yang menyangkut dengan tanah (dalam hal ini tanah yang digunakan dalam instansi tersebut).</td>
                            <td>
                                <?= $inventaris_tanah_pribadi->total?>
                            </td>
                            <td>
                                <?= $inventaris_tanah_pemerintah->total?>
                            </td>
                            <td>
                                <?= $inventaris_tanah_provinsi->total?>
                            </td>
                            <td>
                                <?= $inventaris_tanah_kabupaten->total?>
                            </td>
                            <td>
                                <?= $inventaris_tanah_sumbangan->total?>
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="...">
                                    <a href="<?= site_url('inventaris/tanah'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td nowrap>Peralatan dan Mesin</td>
                            <td>Informasi mengenai peralatan dan mesin</td>
                            <td>
                                <?= $inventaris_peralatan_pribadi->total?>
                            </td>
                            <td>
                                <?= $inventaris_peralatan_pemerintah->total?>
                            </td>
                            <td>
                                <?= $inventaris_peralatan_provinsi->total?>
                            </td>
                            <td>
                                <?= $inventaris_peralatan_kabupaten->total?>
                            </td>
                            <td>
                                <?= $inventaris_peralatan_sumbangan->total?>
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="...">
                                    <a href="<?= site_url('inventaris/peralatan-dan-mesin'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td nowrap>Gedung dan Bangunan</td>
                            <td>Informasi mengenai gedung dan bangunan yang dimiliki.</td>
                            <td>
                                <?= $inventaris_gedung_pribadi->total?>
                            </td>
                            <td>
                                <?= $inventaris_gedung_pemerintah->total?>
                            </td>
                            <td>
                                <?= $inventaris_gedung_provinsi->total?>
                            </td>
                            <td>
                                <?= $inventaris_gedung_kabupaten->total?>
                            </td>
                            <td>
                                <?= $inventaris_gedung_sumbangan->total?>
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="...">
                                    <a href="<?= site_url('inventaris/gedung-dan-bangunan'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td nowrap> Jalan Irigasi dan Jaringan</td>
                            <td>Informasi mengenai jaringan, seperti listrik atau Internet.</td>
                            <td>
                                <?= $inventaris_jalan_pribadi->total?>
                            </td>
                            <td>
                                <?= $inventaris_jalan_pemerintah->total?>
                            </td>
                            <td>
                                <?= $inventaris_jalan_provinsi->total?>
                            </td>
                            <td>
                                <?= $inventaris_jalan_kabupaten->total?>
                            </td>
                            <td>
                                <?= $inventaris_jalan_sumbangan->total?>
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="...">
                                    <a href="<?= site_url('inventaris/jalan-irigasi-dan-jaringan'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td nowrap> Asset Tetap Lainnya</td>
                            <td>Informasi mengenai aset tetap seperti barang habis pakai contohnya buku-buku.</td>
                            <td>
                                <?= $inventaris_asset_pribadi->total?>
                            </td>
                            <td>
                                <?= $inventaris_asset_pemerintah->total?>
                            </td>
                            <td>
                                <?= $inventaris_asset_provinsi->total?>
                            </td>
                            <td>
                                <?= $inventaris_asset_kabupaten->total?>
                            </td>
                            <td>
                                <?= $inventaris_asset_sumbangan->total?>
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="...">
                                    <a href="<?= site_url('inventaris/asset-tetap-lainnya'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td nowrap>Konstruksi Dalam Pengerjaan</td>
                            <td>Informasi mengenai bangunan yang masih dalam pengerjaan.</td>
                            <td>
                                <?= $inventaris_kontruksi_pribadi->total?>
                            </td>
                            <td>
                                <?= $inventaris_kontruksi_pemerintah->total?>
                            </td>
                            <td>
                                <?= $inventaris_kontruksi_provinsi->total?>
                            </td>
                            <td>
                                <?= $inventaris_kontruksi_kabupaten->total?>
                            </td>
                            <td>
                                <?= $inventaris_kontruksi_sumbangan->total?>
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="...">
                                    <a href="<?= site_url('inventaris/konstruksi-dalam-pengerjaan'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-center">Total</th>
                            <th><?= $inventaris_tanah_pribadi->total + $inventaris_peralatan_pribadi->total + $inventaris_gedung_pribadi->total + $inventaris_jalan_pribadi->total + $inventaris_asset_pribadi->total + $inventaris_kontruksi_pribadi->total?></th>
                            <th><?= $inventaris_tanah_pemerintah->total + $inventaris_peralatan_pemerintah->total + $inventaris_gedung_pemerintah->total + $inventaris_jalan_pemerintah->total + $inventaris_asset_pemerintah->total + $inventaris_kontruksi_pemerintah->total?></th>
                            <th><?= $inventaris_tanah_provinsi->total + $inventaris_peralatan_provinsi->total + $inventaris_gedung_provinsi->total + $inventaris_jalan_provinsi->total + $inventaris_asset_provinsi->total + $inventaris_kontruksi_provinsi->total?></th>
                            <th><?= $inventaris_tanah_kabupaten->total + $inventaris_peralatan_kabupaten->total + $inventaris_gedung_kabupaten->total + $inventaris_jalan_kabupaten->total + $inventaris_asset_kabupaten->total + $inventaris_kontruksi_kabupaten->total?></th>
                            <th><?= $inventaris_tanah_sumbangan->total + $inventaris_peralatan_sumbangan->total + $inventaris_gedung_sumbangan->total + $inventaris_jalan_sumbangan->total + $inventaris_asset_sumbangan->total + $inventaris_kontruksi_sumbangan->total?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
    </div>
</div>
<?php $this->load->view("$folder_themes/partials/inventaris/script") ?>