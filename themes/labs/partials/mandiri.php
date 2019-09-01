<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- Block Tabs With Options Default Style -->
<div class="block">
    <ul class="nav nav-pills push align-items-center" data-toggle="tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#biodata">Biodata</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="#kkp">Kartu Keluarga</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#kelompok">Kelompok</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#dokumen">Dokumen</a>
        </li>
        <li class="nav-item ml-auto">
            <div class="block-options mr-15">
                <button type="button" class="btn-block-option" data-toggle="block-option"
                    data-action="fullscreen_toggle"></button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle"
                    data-action-mode="demo">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option" data-toggle="block-option"
                    data-action="content_toggle"></button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="close">
                    <i class="si si-close"></i>
                </button>
            </div>
        </li>
    </ul>
    <div class="block-content tab-content">
        <div class="tab-pane" id="kkp" role="tabpanel">
            <a class="block block-rounded block-link-shadow" href="<?= site_url("first/cetak_kk/$penduduk[id]/1"); ?>"
                target="_blank">
                <div class="block-content block-content-full text-center">
                    <div class="item item-circle bg-warning-light text-success mx-auto my-20">
                        <i class="fa fa-print"></i>
                    </div>
                    <div class="font-size-lg"><i class="si si-docs"></i> Cetak Kartu Keluarga</div>
                </div>
            </a>
        </div>
        <div class="tab-pane active" id="biodata" role="tabpanel">
            <div class="block block-themed">
                <div class="block-header block-header-default">
                    <h3 class="block-title"> <i class="si si-layers"></i> Biodata </h3>
                    <div class="block-options">
                        <div class="block-options-item">
                            <a href="<?= site_url("first/cetak_biodata/$penduduk[id]"); ?>" target="_blank">
                                <button class="btn btn btn-rounded btn-noborder btn-dark min-width-125 mb-10 btn-xs">
                                <i class="fa fa-print"></i> Cetak Biodata </button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="block-content" data-toggle="slimscroll" data-height="500px" data-color="#ef5350" data-opacity="1" data-always-visible="true">
                    <table class="table table-hover table-vcenter">
                        <thead>
                            <tr>
                                <th>Informasi</th>
                                <th class="d-none d-sm-table-cell" style="width: 50%;">Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nama</td>
                                <td class="d-none d-sm-table-cell"><?= strtoupper(unpenetration($penduduk['nama']))?>
                                </td>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <td class="d-none d-sm-table-cell"><?= $penduduk['nik']?></td>
                            </tr>
                            <tr>
                                <td>Akta Kelahiran</td>
                                <td class="d-none d-sm-table-cell"><?= $penduduk['no_kk']?></td>
                            </tr>
                            <tr>
                                <td>RT/RW</td>
                                <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['akta_lahir'])?></td>
                            </tr>
                            <tr>
                                <td><?= ucwords($this->setting->sebutan_dusun)?></td>
                                <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['dusun'])?></td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td class="d-none d-sm-table-cell">
                                    <?= strtoupper($penduduk['rt'])?>/<?= $penduduk['rw']?></td>
                            </tr>
                            <tr>
                                <td>Tempat, Tanggal Lahir</td>
                                <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['sex'])?></td>
                            </tr>
                            <tr>
                                <td>Agama</td>
                                <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['tempatlahir'])?>,
                                    <?= strtoupper($penduduk['tanggallahir'])?></td>
                            </tr>
                            <tr>
                                <td>Pendidikan</td>
                                <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['pendidikan_kk'])?></td>
                            </tr>
                            <tr>
                                <td>Pendidikan yang sedang ditempuh</td>
                                <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['pendidikan_sedang'])?>
                                </td>
                            </tr>
                            <tr>
                                <td>Pekerjaan</td>
                                <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['pekerjaan'])?></td>
                            </tr>
                            <tr>
                                <td>Status Perkawinan</td>
                                <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['kawin'])?></td>
                            </tr>
                            <tr>
                                <td>Warga Negara</td>
                                <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['warganegara'])?></td>
                            </tr>
                            <tr>
                                <td>Dokumen Paspor</td>
                                <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['dokumen_pasport'])?></td>
                            </tr>
                            <tr>
                                <td>Dokumen Kitas</td>
                                <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['dokumen_kitas'])?></td>
                            </tr>
                            <tr>
                                <td>Alamat Sebelumnya</td>
                                <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['alamat_sebelumnya'])?></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['alamat'])?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Perkawinan</td>
                                <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['tanggalperkawinan'])?></td>
                            </tr>
                            <td>Akta Perceraian</td>
                            <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['akta_perceraian'])?></td>
                            </tr>
                            <td>Tanggal Perceraian</td>
                            <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['tanggalperceraian'])?></td>
                            </tr>
                            </tr>
                            <td>NIK Ayah</td>
                            <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['ayah_nik'])?></td>
                            </tr>
                            </tr>
                            <td>Nama Ayah</td>
                            <td class="d-none d-sm-table-cell"><?= strtoupper(unpenetration($penduduk['nama_ayah']))?>
                            </td>
                            </tr>
                            </tr>
                            <td>NIK Ibu</td>
                            <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['ibu_nik'])?></td>
                            </tr>
                            </tr>
                            <td>Nama Ibu</td>
                            <td class="d-none d-sm-table-cell"><?= strtoupper(unpenetration($penduduk['nama_ibu']))?>
                            </td>
                            </tr>
                            </tr>
                            <td>Cacat</td>
                            <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['cacat'])?></td>
                            </tr>
                            </tr>
                            <td>Status</td>
                            <td class="d-none d-sm-table-cell"><?= strtoupper($penduduk['status'])?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="kelompok" role="tabpanel">
            <div class="block block-themed">
                <div class="block-header block-header-default">
                    <h3 class="block-title">  <i class="si si-users"></i> Keangotaan Kelompok </h3>
                </div>
                <div class="block-content">
                    <table class="table table-hover table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Nama Kelompok</th>
                                <th class="d-none d-sm-table-cell" style="width: 30%;">Kategori Kelompok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($list_kelompok as $kel){?>
                            <tr>
                                <th class="text-center" scope="row"><?= $no;?></th>
                                <td><?= $kel['nama']?></td>
                                <td class="d-none d-sm-table-cell"><?= $kel['kategori']?></td>
                            </tr>
                            <?php $no++; }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="dokumen" role="tabpanel">
        <div class="block block-themed">
    <div class="block-header block-header-default">
        <h3 class="block-title">  <i class="si si-map"></i> Dokumen Berkas </h3>
    </div>
    <div class="block-content">
        <table class="table table-hover table-vcenter">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Nama Dokumen</th>
                    <th class="d-none d-sm-table-cell">Berkas</th>
                    <th class="d-none d-sm-table-cell" style="width: 30%;">Tanggal Upload</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($list_dokumen as $data){?>
                <tr>
                    <th class="text-center" scope="row"><?= $no;?></th>
                    <td><?= $kel['nama']?></td>
                    <td><a href="<?= base_url().LOKASI_DOKUMEN?><?= urlencode($data['satuan'])?>"><?= $data['satuan']?></a></td>
                    <td class="d-none d-sm-table-cell"><?= tgl_indo2($data['tgl_upload'])?></td>
                </tr>
                <?php $no++;
      }?>
            </tbody>
        </table>
    </div>
</div>
        </div>
    </div>
</div>
<!-- END Block Tabs With Options Default Style -->