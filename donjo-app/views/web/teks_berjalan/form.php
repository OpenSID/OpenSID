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
					<div class="card card-outline card-info">
						<div class="card-header with-border">
							<a href="<?= site_url().$this->controller?>" class="btn btn-flat btn-info btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left"  title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Teks
							</a>
						</div>
						<div class="card-body">
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label" for="isi_teks_berjalan">Isi teks berjalan</label>
									<textarea id="teks" class="form-control form-control-sm required" placeholder="Isi teks berjalan" name="teks" rows="5" style="resize:none;"><?= $teks['teks']?></textarea>
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
									<input class="form-control form-control-sm required" placeholder="Judul tautan ke artikel atau url" name="judul_tautan" value="<?= $teks['judul_tautan'] ? $teks['judul_tautan'] : '-- selengkapnya...' ?>" maxlength="150"></input>
								</div>
							</div>
						</div>
						<div class='card-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-flat btn-danger btn-xs' ><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-flat btn-info btn-xs pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
