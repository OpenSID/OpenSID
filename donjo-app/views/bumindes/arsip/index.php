<div class="content-wrapper">
    <section class='content-header'>
		<h1>Dokumen Arsip <?= ucwords($this->setting->sebutan_desa) ?></h1>
		<ol class='breadcrumb'>
			<li><a href='<?= site_url('beranda') ?>'><i class='fa fa-home'></i> Beranda</a></li>
			<li class='active'>Arsip <?= ucwords($this->setting->sebutan_desa) ?></li>
		</ol>
	</section>
    <section class="content" id="maincontent">
        <?php $this->load->view('bumindes/arsip/navigasi') ?>
        <div class="box box-info">
            <div class="box-header with-border">
                <a href="<?= site_url("{$this->controller}/clear") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
            </div>
            <div class="box-body with-border">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <form id="mainform" action="<?= site_url('bumindes_arsip') ?>" name="mainform" method="post">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <select class="form-control input-sm select2" name="jenis" onchange="$('#mainform').submit()">
                                                <option value="0">Pilih Jenis Dokumen</option>
                                                <?php foreach ($list_jenis as $key => $jenis):?>
                                                    <option value="<?= $key ?>" <?= selected($this->session->data_filter_jenis, $key) ?>><?= strtoupper(str_replace('_', ' ', $jenis))?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control input-sm" name="tahun" onchange="$('#mainform').submit()">
                                                <option value="0">Pilih Tahun</option>
                                                <?php foreach ($list_tahun as $tahun): ?>
                                                    <option value="<?= $tahun ?>" <?= selected($this->session->data_filter_tahun, $tahun) ?>><?= $tahun ?></option>
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
                                                        <th><?= url_order($o, "{$this->controller}/{$page}", 1, 'Nomor Dokumen'); ?></th>
                                                        <th><?= url_order($o, "{$this->controller}/{$page}", 3, 'Tanggal Dokumen'); ?></th>
                                                        <th><?= url_order($o, "{$this->controller}/{$page}", 5, 'Nama Dokumen'); ?></th>
                                                        <th><?= url_order($o, "{$this->controller}/{$page}", 7, 'Jenis Dokumen'); ?></th>
                                                        <th><?= url_order($o, "{$this->controller}/{$page}", 9, 'Lokasi Dokumen'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php if ($main): ?>
                                                    <?php foreach ($main as $key => $data): ?>
                                                        <tr>
                                                            <td class="padat"><?= ($key + $paging->offset + 1); ?></td>
                                                            <td class="aksi">
                                                                <?php if (isset($data['lampiran'])):?>
                                                                    <?php if ($data['lampiran'] != ''): ?>
                                                                        <a href="<?= site_url('keluar/unduh/lampiran/') . $data['id']?>" class="btn bg-blue btn-flat btn-sm" title="Unduh Lampiran"><i class="fa fa-paperclip">&nbsp;</i></a>
                                                                    <?php endif ?>
                                                                    <a href="<?= site_url('keluar/unduh/rtf/') . $data['id']?>" class="btn bg-black btn-flat btn-sm" title="Unduh Berkas"><i class="fa fa-download">&nbsp;</i></a>
                                                                <?php else: ?>
                                                                <a href="<?= site_url('bumindes_arsip/tindakan_lihat/') . $data['kategori'] . '/' . $data['id'] . '/lihat' ?>" target="_blank" class="btn bg-blue btn-flat btn-sm" title="Lihat Berkas"><i class="fa fa-eye">&nbsp;</i></a>
                                                                <a href="<?= site_url('bumindes_arsip/tindakan_lihat/') . $data['kategori'] . '/' . $data['id'] . '/unduh' ?>" class="btn bg-black btn-flat btn-sm" title="Unduh Berkas"><i class="fa fa-download">&nbsp;</i></a>
                                                                <?php endif ?>
                                                                <?php if (can('u')) : ?>
                                                                    <a href="<?= site_url('bumindes_arsip/tindakan_ubah/') . $data['kategori'] . '/' . $data['id'] . '/' . $page . '/' . $o?>" class="btn bg-yellow btn-flat btn-sm" title="Ubah Lokasi Arsip" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Lokasi Arsip"><i class="fa fa-edit">&nbsp;</i></a>
                                                                <?php endif ?>
                                                                <a href="<?= site_url($data['modul_asli'])?>" class="btn bg-green btn-flat btn-sm" title="Tampilkan di modul aslinya"><i class="fa fa-list">&nbsp;</i></a>
                                                            </td>
                                                            <td class="aksi"><?= $data['nomor_dokumen'] ?? '-' ?></td>
                                                            <td class="aksi"><?= tgl_indo2($data['tanggal_dokumen']) ?></td>
                                                            <td><?= $data['nama_dokumen'] ?></td>
                                                            <td class="aksi"><?= strtoupper(str_replace('_', ' ', $data['nama_jenis'])) ?></td>
                                                            <td><?= $data['lokasi_arsip'] ?></td>
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