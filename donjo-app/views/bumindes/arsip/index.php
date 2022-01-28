<div class="content-wrapper">
    <section class='content-header'>
		<h1>Dokumen Arsip Desa</h1>
		<ol class='breadcrumb'>
			<li><a href='<?= site_url('hom_sid') ?>'><i class='fa fa-home'></i> Home</a></li>
			<li class='active'>Arsip Desa</li>
		</ol>
	</section>
    <section class="content" id="maincontent">
        <div class="box box-info">
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-4 col-xs-4">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3><?=$dokumen_desa['total']?></h3>
                                <p><?=$dokumen_desa['title']?></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-document"></i>
                            </div>
                            <a href="<?= site_url($this->controller . "/clear/{$dokumen_desa['uri']}") ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-$ col-xs-4">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3><?=$surat_masuk['total']?></h3>
                                <p><?=$surat_masuk['title']?></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-email"></i>
                            </div>
                            <a href="<?= site_url($this->controller . "/clear/{$surat_masuk['uri']}") ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xs-4">
                        <div class="small-box bg-blue">
                            <div class="inner">
                                <h3><?=$surat_keluar['total']?></h3>
                                <p><?=$surat_keluar['title']?></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-email"></i>
                            </div>
                            <a href="<?= site_url($this->controller . "/clear/{$surat_keluar['uri']}") ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-$ col-xs-4">
                        <div class="small-box bg-purple">
                            <div class="inner">
                                <h3><?=$kependudukan['total']?></h3>
                                <p><?=$kependudukan['title']?></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person"></i>
                            </div>
                            <a href="<?= site_url($this->controller . "/clear/{$kependudukan['uri']}") ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xs-4">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3><?=$layanan_surat['total']?></h3>
                                <p><?=$layanan_surat['title']?></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-document-text"></i>
                            </div>
                            <a href="<?= site_url($this->controller . "/clear/{$layanan_surat['uri']}") ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header with-border">
                <a href="<?= site_url($this->controller . "/clear") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
            </div>
            <div class="box-body with-border">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <form id="mainform" action="<?= site_url("bumindes_arsip") ?>" name="mainform" method="post">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <select class="form-control input-sm select2" name="jenis" onchange="$('#mainform').submit()">
                                                <option value="0">Pilih Jenis Dokumen</option>
                                                <?php foreach($list_jenis as $key => $jenis):?>
                                                    <option value="<?=$key?>" <?=($this->session->data_filter_jenis==$key)? 'selected' : '' ?>><?=strtoupper(str_replace('_', ' ',$jenis))?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control input-sm" name="tahun" onchange="$('#mainform').submit()">
                                                <option value="0">Pilih Tahun</option>
                                                <?php foreach($list_tahun as $tahun): ?>
                                                    <option value="<?=$tahun?>" <?=($this->session->data_filter_tahun==$tahun) ? 'selected': ''?>><?=$tahun?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="box-tools">
                                            <div class="input-group input-group-sm pull-right">
                                                <input name="cari" id="cari" class="form-control" placeholder="Cari Dokumen..." type="text" value="<?= $this->session->data_filter_cari ?? '' ?>">
                                                <div class="input-group-btn">
                                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
                                                <thead class="bg-gray color-palette">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Aksi</th>
                                                        <th width="8%"><?= url_order($o, "{$this->controller}/{$page}", 1, 'Nomor Dokumen'); ?></th>
                                                        <th width="9%"><?= url_order($o, "{$this->controller}/{$page}", 3, 'Tanggal Dokumen'); ?></th>
                                                        <th width="25%"><?= url_order($o, "{$this->controller}/{$page}", 5, 'Nama Dokumen'); ?></th>
                                                        <th><?= url_order($o, "{$this->controller}/{$page}", 7, 'Jenis Dokumen'); ?></th>
                                                        <th><?= url_order($o, "{$this->controller}/{$page}", 9, 'Lokasi Dokumen'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php if ($main): ?>
                                                    <?php foreach ($main as $key => $data): ?>
                                                        <tr>
                                                            <td class="padat"><?= ($key+$paging->offset+1); ?></td>
                                                            <td class="aksi">
                                                                <?php if(isset($data['lampiran'])):?>
                                                                    <?php if($data['lampiran'] != ''): ?>
                                                                        <a href="<?= site_url('keluar/unduh/lampiran/').$data['id']?>" class="btn bg-blue btn-flat btn-sm" title="Unduh Lampiran"><i class="fa fa-paperclip">&nbsp;</i></a>
                                                                    <?php endif ?>
                                                                    <a href="<?= site_url('keluar/unduh/rtf/').$data['id']?>" class="btn bg-black btn-flat btn-sm" title="Unduh Berkas"><i class="fa fa-download">&nbsp;</i></a>
                                                                <?php else: ?>
                                                                <a href="<?= site_url('bumindes_arsip/tindakan_lihat/').$data['kategori'].'/'.$data['id'].'/lihat' ?>" target="_blank" class="btn bg-blue btn-flat btn-sm" title="Lihat Berkas"><i class="fa fa-eye">&nbsp;</i></a>
                                                                <a href="<?= site_url('bumindes_arsip/tindakan_lihat/').$data['kategori'].'/'.$data['id'].'/unduh' ?>" class="btn bg-black btn-flat btn-sm" title="Unduh Berkas"><i class="fa fa-download">&nbsp;</i></a>
                                                                <?php endif ?>
                                                                <a href="<?= site_url('bumindes_arsip/tindakan_ubah/').$data['kategori'].'/'.$data['id'].'/'.$page.'/'.$o?>" class="btn bg-yellow btn-flat btn-sm" title="Ubah Lokasi Arsip" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Lokasi Arsip"><i class="fa fa-edit">&nbsp;</i></a>
                                                                <a href="<?= site_url($data['modul_asli'])?>" class="btn bg-green btn-flat btn-sm" title="Tampilkan di modul aslinya"><i class="fa fa-list">&nbsp;</i></a>
                                                            </td>
                                                            <td><?= $data['nomor_dokumen']?></td>
                                                            <td><?= tgl_indo2($data['tanggal_dokumen'])?></td>
                                                            <td><?= $data['nama_dokumen']?></td>
                                                            <td><?= strtoupper(str_replace('_', ' ', $data['nama_jenis']))?></td>
                                                            <td><?= $data['lokasi_arsip']?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td class="text-center" colspan="7">Data Tidak Tersedia</td>
                                                    </tr>
                                                <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php $this->load->view('global/paging'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>