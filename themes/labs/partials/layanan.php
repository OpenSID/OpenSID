<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="block block-themed">
    <div class="block-header block-header-default">
        <h3 class="block-title"> <i class="si si-film"></i> Rekam Layanan Cetak Surat </h3>
    </div>
    <div class="block-content" data-toggle="slimscroll" data-height="500px" data-color="#ef5350" data-opacity="1"
        data-always-visible="true">
        <table class="table table-bordered table-vcenter">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Surat</th>
                    <th>Jenis Surat</th>
                    <th>Nama Staf</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($surat_keluar as $data): ?>
                <tr>
                    <td width="2"><?= $data['no']?></td>
                    <td><?= $data['no_surat']?></td>
                    <td><?= $data['format']?></td>
                    <td><?= $data['pamong']?></td>
                    <td><?= tgl_indo2($data['tanggal'])?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>