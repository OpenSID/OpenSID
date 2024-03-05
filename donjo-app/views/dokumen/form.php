<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan <?= $kat_nama ?> Di <?= ucwords(setting('sebutan_desa')) ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda') ?>"><i class="fa fa-home"></i> Beranda</a></li>
			<?php if (in_array($kat, ['2', '3'])) : ?>
				<li><a href="<?= $kembali_ke ?: site_url("{$this->controller}/peraturan_desa/{$kat}"); ?>"> Daftar <?= $kat_nama ?></a></li>
			<?php else : ?>
				<li><a href="<?= site_url("{$this->controller}/index/{$kat}"); ?>"> Daftar <?= $kat_nama ?></a></li>
			<?php endif; ?>
			<li class="active">Pengaturan <?= $kat_nama ?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<div class="box box-info">
				<div class="box-header with-border">
					<?php if (in_array($kat, ['2', '3'])) : ?>
						<a href="<?= $kembali_ke ?: site_url("{$this->controller}/peraturan_desa/{$kat}"); ?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Artikel">
							<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar <?= $kat_nama ?> Di <?= ucwords(setting('sebutan_desa')) ?>
						</a>
					<?php else : ?>
						<a href="<?= site_url("{$this->controller}/index/{$kat}"); ?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Artikel">
							<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar <?= $kat_nama ?> Di <?= ucwords(setting('sebutan_desa')) ?>
						</a>
					<?php endif; ?>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label class="control-label col-sm-4" for="nama">Judul Dokumen</label>
						<div class="col-sm-6">
							<input name="nama" class="form-control input-sm nomor_sk required" type="text" maxlength="200" value="<?= $dokumen['nama'] ?>"></input>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4" for="nama">Tipe Dokumen</label>
						<div class="col-sm-6">
							<select name="tipe" id="tipe" class="form-control input-sm required">
								<option value="1" <?= $dokumen['tipe'] == 1 ? 'selected' : '' ?>>File</option>
								<option value="2" <?= $dokumen['tipe'] == 2 ? 'selected' : '' ?>>URL</option>
							</select>
						</div>
					</div>
					<div id="d-dokumen" style="display: <?= $dokumen['tipe'] == 2 ? 'none' : '' ?>;">
						<?php if ($dokumen['satuan']) : ?>
							<div class="form-group">
								<label class="col-sm-4 control-label">Dokumen</label>
								<div class="col-sm-4">
									 <i class="fa fa-file-pdf-o pop-up-pdf" aria-hidden="true" style="font-size: 60px;" data-title="Berkas <?= $dokumen['nomor_surat']?>" data-url="<?= site_url("{$this->controller}/berkas/{$dokumen['id']}/1/1")?>"></i>

								</div>
							</div>
						<?php endif; ?>
						<div class="form-group">
							<label class="control-label col-sm-4" for="upload">Unggah Dokumen</label>
							<div class="col-sm-6">
								<div class="input-group input-group-sm">
									<input type="text" class="form-control <?= $dokumen['tipe'] == 2 || $dokumen['tipe'] ? '' : 'required' ?>" id="file_path" name="satuan">
									<input id="file" type="file" class="hidden" name="satuan" accept=".pdf" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
									</span>
								</div>
								<?php if ($dokumen) : ?>
									<p class="small">(Kosongkan jika tidak ingin mengubah dokumen)</p>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div id="d-url" class="form-group" style="display: <?= $dokumen['tipe'] == 2 ? '' : 'none' ?>;">
						<label class="control-label col-sm-4" for="nama">Link/URL Dokumen</label>
						<div class="col-sm-6">
							<input id="url" name="url" class="form-control input-sm <?= $dokumen['tipe'] == 2 ? 'required' : '' ?>" type="text" value="<?= $dokumen['url'] ?>"></input>
						</div>
					</div>
					<input name="kategori" type="hidden" value="<?= $dokumen['kategori'] ?: $kat; ?>">
					<?php
                    if ($kat == 2 || $dokumen['kategori'] == 2) {
                        $this->load->view('dokumen/_sk_kades');
                    } elseif ($kat == 3 || $dokumen['kategori'] == 3) {
                        $this->load->view('dokumen/_perdes');
                    } else {
                        $this->load->view('dokumen/_informasi_publik');
                    }
        ?>
				</div>
				<div class='box-footer'>
					<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm'><i class='fa fa-times'></i> Batal</button>
					<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
				</div>
			</div>
		</form>
	</section>
</div>

<script>
	$('#tipe').on('change', function() {
		if (this.value == 1) {
			$('#d-dokumen').show();
			$('#d-url').hide();
			$("#file_path").addClass("required");
			$("#url").removeClass("required");
		} else {
			$('#d-dokumen').hide();
			$('#d-url').show();
			$("#file_path").removeClass("required");
			$("#url").addClass("required");
		}
	});
</script>