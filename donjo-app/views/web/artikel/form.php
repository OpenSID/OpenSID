<script type="text/javascript" src="<?= base_url()?>assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init(
	{
		selector: 'textarea',
		height: 500,
		theme: 'silver',
		plugins: [
					"advlist autolink link image lists charmap print preview hr anchor pagebreak",
					"searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
					"table contextmenu directionality emoticons paste textcolor responsivefilemanager code laporan_keuangan penerima_bantuan sotk"
		],
		toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
		toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor | print preview code | fontselect fontsizeselect",
		toolbar3: "| laporan_keuangan | penerima_bantuan | sotk",
		image_advtab: true ,
		external_filemanager_path:"<?= base_url()?>assets/filemanager/",
		filemanager_title:"Responsive Filemanager" ,
		filemanager_access_key:"<?= $this->session->fm_key; ?>",
		external_plugins:
		{
			"filemanager" : "<?= base_url()?>assets/filemanager/plugin.min.js"
		},
		templates: [
		{
			title: 'Test template 1', content: 'Test 1'
		},
		{
			title: 'Test template 2', content: 'Test 2'
		}],
		content_css: [
			'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
			'//www.tinymce.com/css/codepen.min.css'
		],
		relative_urls : false,
		remove_script_host : false
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Form Artikel <?= $kategori['kategori']; ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('web'); ?>"> Daftar Artikel</a></li>
			<li class="active">Form Artikel</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action; ?>" method="POST" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-8">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?=site_url('web')?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Artikel
							</a>
							<?php if ($artikel['slug']): ?>
								<a href="<?= site_url('artikel/' . buat_slug($artikel)); ?>" target="_blank" class="btn btn-social btn-flat bg-green btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-eye"></i> Lihat Artikel</a>
							<?php endif; ?>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="control-label" for="judul">Judul Artikel</label>
								<input id="judul" name="judul" class="form-control input-sm required" type="text" placeholder="Judul Artikel" minlength="5" maxlength="100" value="<?= htmlspecialchars($artikel['judul'])?>" ></input>
								<span class="help-block"><code>Judul artikel minimal 5 karakter dan maksimal 100 karakter</code></span>
							</div>
							<div class="form-group">
								<label class="control-label" for="kode_desa">Isi Artikel</label>
								<textarea name="isi" class="form-control input-sm required" style="height:350px;">
									<?=$artikel['isi']?>
								</textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="box box-info collapsed-box">
						<div class="box-header with-border">
							<h3 class="box-title">Unggah Gambar</h3>
							<div class="box-tools">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
							</div>
						</div>
						<div class="box-body no-padding">
							<div class="col-sm-12">
								<div class="form-group">
									<?php if ($artikel['gambar']): ?>
										<input type="hidden" name="old_gambar" value="<?= $artikel['gambar']?>">
										<img class="profile-user-img img-responsive img-circle" src="<?= AmbilFotoArtikel($artikel['gambar'], 'kecil')?>" alt="Gambar Utama">
										<p class="text-center"><label class="control-label"><input type="checkbox" name="gambar_hapus" value="<?= $artikel['gambar']?>" /> Hapus Gambar</label></p>
									<?php else: ?>
										<img class="profile-user-img img-responsive img-circle" src="<?= base_url()?>assets/files/logo/home.png" alt="Tidak Ada Gambar">
									<?php endif; ?>
									<label class="control-label" for="gambar">Gambar Utama</label>
									<div class="input-group input-group-sm">
										<input type="text" class="form-control" id="file_path">
										<input type="file" class="hidden" id="file" name="gambar">
										<span class="input-group-btn">
											<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
										</span>
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<?php if ($artikel['gambar1']): ?>
										<input type="hidden" name="old_gambar1" value="<?= $artikel['gambar1']?>">
										<img class="profile-user-img img-responsive img-circle" src="<?= AmbilFotoArtikel($artikel['gambar1'], 'kecil')?>" alt="Gambar Utama">
										<p class="text-center"><label class="control-label"><input type="checkbox" name="gambar1_hapus" value="<?= $artikel['gambar1']?>" /> Hapus Gambar</label></p>
									<?php else: ?>
										<img class="profile-user-img img-responsive img-circle" src="<?= base_url()?>assets/files/logo/home.png" alt="Tidak Ada Gambar">
									<?php endif; ?>
									<label class="control-label" for="gambar1">Gambar Tambahan</label>
									<div class="input-group input-group-sm">
										<input type="text" class="form-control" id="file_path1">
										<input type="file" class="hidden" id="file1" name="gambar1">
										<span class="input-group-btn">
											<button type="button" class="btn btn-info btn-flat" id="file_browser1"><i class="fa fa-search"></i> Browse</button>
										</span>
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<?php if ($artikel['gambar2']): ?>
										<input type="hidden" name="old_gambar2" value="<?= $artikel['gambar2']?>">
										<img class="profile-user-img img-responsive img-circle" src="<?= AmbilFotoArtikel($artikel['gambar2'], 'kecil')?>" alt="Gambar Utama">
										<p class="text-center"><label class="control-label"><input type="checkbox" name="gambar2_hapus" value="<?= $artikel['gambar2']?>" /> Hapus Gambar</label></p>
									<?php else: ?>
										<img class="profile-user-img img-responsive img-circle" src="<?= base_url()?>assets/files/logo/home.png" alt="Tidak Ada Gambar">
									<?php endif; ?>
									<label class="control-label" for="gambar2">Gambar Tambahan</label>
									<div class="input-group input-group-sm">
										<input type="text" class="form-control" id="file_path2">
										<input type="file" class="hidden" id="file2" name="gambar2">
										<span class="input-group-btn">
											<button type="button" class="btn btn-info btn-flat" id="file_browser2"><i class="fa fa-search"></i> Browse</button>
										</span>
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<?php if ($artikel['gambar3']): ?>
										<input type="hidden" name="old_gambar3" value="<?= $artikel['gambar3']?>">
										<img class="profile-user-img img-responsive img-circle" src="<?= AmbilFotoArtikel($artikel['gambar3'], 'kecil')?>" alt="Gambar Utama">
										<p class="text-center"><label class="control-label"><input type="checkbox" name="gambar3_hapus" value="<?= $artikel['gambar3']?>" /> Hapus Gambar</label></p>
									<?php else: ?>
										<img class="profile-user-img img-responsive img-circle" src="<?= base_url()?>assets/files/logo/home.png" alt="Tidak Ada Gambar">
									<?php endif; ?>
									<label class="control-label" for="gambar3">Gambar Tambahan</label>
									<div class="input-group input-group-sm">
										<input type="text" class="form-control" id="file_path3">
										<input type="file" class="hidden" id="file3" name="gambar3">
										<span class="input-group-btn">
											<button type="button" class="btn btn-info btn-flat" id="file_browser3"><i class="fa fa-search"></i> Browse</button>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php if ($cat == 1000): ?>
						<input type="hidden" name="id_agenda" value="<?= $artikel['id_agenda']?>">
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title">Pengaturan Agenda Desa</h3>
								<div class="box-tools">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								</div>
							</div>
							<div class="box-body no-padding">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="control-label" for="tgl_agenda">Tanggal Kegiatan</label>
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar-check-o"></i>
											</div>
											<input class="form-control input-sm pull-right tgl_jam" name="tgl_agenda" type="text" value="<?= $artikel['tgl_agenda']?>">
										</div>
										<span class="help-block"><code>(Isikan Tanggal Kegiatan)</code></span>
										<label class="control-label" for="lokasi_kegiatan">Lokasi Kegiatan</label>
										<div class="input-group input-group-sm">
											<div class="input-group-addon">
												<i class="fa fa-map-marker"></i>
											</div>
											<input class="form-control input-sm pull-right" name="lokasi_kegiatan" type="text" placeholder="Masukan lokasi tempat dilakukan kegiatan" value="<?= $artikel['lokasi_kegiatan']?>">
										</div>
										<span class="help-block"><code>(Isikan Lokasi Tempat Dilakukan Kegiatan)</code></span>
										<label class="control-label" for="koordinator_kegiatan">Koordinator Kegiatan</label>
										<div class="input-group input-group-sm">
											<div class="input-group-addon">
												<i class="fa fa-user"></i>
											</div>
											<input class="form-control input-sm pull-right" name="koordinator_kegiatan" type="text" placeholder="Masukan nama koordinator" value="<?= $artikel['koordinator_kegiatan']?>">
										</div>
										<span class="help-block"><code>(Isikan Koordinator Kegiatan)</code></span>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Pengaturan Lainnya</h3>
							<div class="box-tools">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body no-padding">
							<div class="col-sm-12">
								<?php if ($artikel['dokumen']): ?>
									<div class="form-group">
										<div class="mailbox-attachment-info">
											<a href="<?= base_url() . LOKASI_DOKUMEN . $artikel['dokumen']?>" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> Unduh Dokumen</a>
										</div>
									</div>
								<?php endif; ?>
								<div class="form-group">
									<label class="control-label" for="dokumen">Dokumen Lampiran</label>
									<div class="input-group input-group-sm">
										<input type="text" class="form-control" id="file_path4">
										<input type="file" class="hidden" id="file4" name="dokumen">
										<span class="input-group-btn">
											<button type="button" class="btn btn-info btn-flat" id="file_browser4"><i class="fa fa-search"></i> Browse</button>
										</span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label" for="nama_dokumen">Nama Dokumen</label>
									<input id="link_dokumen" name="link_dokumen" class="form-control input-sm" type="text" value="<?= $artikel['link_dokumen']?>"></input>
									<span class="help-block"><code>(Nantinya akan menjadi link unduh/download)</code></span>
								</div>
								<div class="form-group">
									<label class="control-label" for="tgl_upload">Tanggal Posting</label>
									<div class="input-group input-group-sm date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input class="form-control input-sm pull-right tgl_jam" name="tgl_upload" type="text" value="<?= $artikel['tgl_upload']?>">
									</div>
									<span class="help-block"><code>(Kosongkan jika ingin langsung di post, bisa digunakan untuk artikel terjadwal)</code></span>
								</div>
							</div>
						</div>
					</div>
					<div class="box box-info">
						<div class="box-body no-padding">
							<div class='box-footer'>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
