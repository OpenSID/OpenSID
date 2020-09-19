<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Teks Berjalan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pengaturan Teks Berjalan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action?>" method="POST">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url().$this->controller?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Teks
							</a>
						</div>
						<div class="box-body">
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label" for="isi_teks_berjalan">Isi teks berjalan</label>
									<textarea id="teks" class="form-control input-sm required" placeholder="Isi teks berjalan" name="teks" rows="5" style="resize:none;"><?= $teks['teks']?></textarea>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<label class="control-label">Tautan ke artikel</label>
									<select class="form-control select2 " id="tautan" name="tautan" style="width: 100%;">
										<option value="">-- Cari Judul Artikel --</option>
										<?php foreach ($list_artikel as $artikel): ?>
											<option value="<?= $artikel['id']?>" <?php selected($artikel['id'], $teks['tautan']); ?>><?=tgl_indo($artikel['tgl_upload']).' | '.$artikel['judul']?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label">Judul tautan</label>
									<input class="form-control input-sm required" placeholder="Judul tautan ke artikel atau url" name="judul_tautan" value="<?= $teks['judul_tautan'] ? $teks['judul_tautan'] : '-- selengkapnya...' ?>" maxlength="150"></input>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
