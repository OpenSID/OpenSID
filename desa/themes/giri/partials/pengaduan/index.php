<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<style type="text/css">
	.padding {
		padding: 10px;
	}

	/* GRID */
	.col {
		padding: 10px 20px;
		margin-bottom: 10px;
		background: #fff;
		color: #666666;
		text-align: center;
		font-weight: 400;
		box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
	}

	.row h3 {
		color: #666666;
	}

	.row.grid {
		margin-left: 0;
	}

	.grid {
		position: relative;
		width: 100%;
		background: #fff;
		color: #666666;
		border-radius: 2px;
		margin-bottom: 25px;
		box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
	}

	.grid .grid-header {
		position: relative;
		border-bottom: 1px solid #ddd;
		padding: 15px 10px 10px 20px;
	}

	.grid .grid-header:before,
	.grid .grid-header:after {
		display: table;
		content: " ";
	}

	.grid .grid-header:after {
		clear: both;
	}

	.grid .grid-header span,
	.grid .grid-header>.fa {
		display: inline-block;
		margin: 0;
		font-weight: 300;
		font-size: 1.5em;
		float: left;
	}

	.grid .grid-header span {
		padding: 0 5px;
	}

	.grid .grid-header>.fa {
		padding: 5px 10px 0 0;
	}

	.grid .grid-header>.grid-tools {
		padding: 4px 10px;
	}

	.grid .grid-header>.grid-tools a {
		color: #999999;
		padding-left: 10px;
		cursor: pointer;
	}

	.grid .grid-header>.grid-tools a:hover {
		color: #666666;
	}

	.grid .grid-body {
		margin: 20px 0;
		font-size: 0.9em;
		line-height: 1.9em;
	}

	.grid .full {
		padding: 0 !important;
	}

	.grid .transparent {
		box-shadow: none !important;
		margin: 0px !important;
		border-radius: 0px !important;
	}

	.grid.top.black>.grid-header {
		border-top-color: #000000 !important;
	}

	.grid.bottom.black>.grid-body {
		border-bottom-color: #000000 !important;
	}

	.grid.top.blue>.grid-header {
		border-top-color: #007be9 !important;
	}

	.grid.bottom.blue>.grid-body {
		border-bottom-color: #007be9 !important;
	}

	.grid.top.green>.grid-header {
		border-top-color: #00c273 !important;
	}

	.grid.bottom.green>.grid-body {
		border-bottom-color: #00c273 !important;
	}

	.grid.top.purple>.grid-header {
		border-top-color: #a700d3 !important;
	}

	.grid.bottom.purple>.grid-body {
		border-bottom-color: #a700d3 !important;
	}

	.grid.top.red>.grid-header {
		border-top-color: #dc1200 !important;
	}

	.grid.bottom.red>.grid-body {
		border-bottom-color: #dc1200 !important;
	}

	.grid.top.orange>.grid-header {
		border-top-color: #f46100 !important;
	}

	.grid.bottom.orange>.grid-body {
		border-bottom-color: #f46100 !important;
	}

	.grid.no-border>.grid-header {
		border-bottom: 0px !important;
	}

	.grid.top>.grid-header {
		border-top-width: 4px !important;
		border-top-style: solid !important;
	}

	.grid.bottom>.grid-body {
		border-bottom-width: 4px !important;
		border-bottom-style: solid !important;
	}


	/* SUPPORT TICKET */
	.support ul {
		list-style: none;
		padding: 0px;
	}

	.support ul li {
		padding: 8px 10px;
	}

	.support ul li a {
		color: #999;
		display: block;
	}

	.support ul li a:hover {
		color: #666;
	}

	.support ul li.active {
		background: #0073b7;
	}

	.support ul li.active a {
		color: #fff;
	}

	.support ul.support-label li {
		padding: 2px 0px;
	}

	.support h2,
	.support-content h2 {
		margin-top: 5px;
	}

	.list-group li {
		padding: 15px 20px 12px 20px;
		cursor: pointer;
	}

	.list-group li:hover {
		background: #eee;
	}

	.support-content .fa-padding .fa {
		padding-top: 5px;
		width: 1.5em;
	}

	.support-content .info {
		color: #777;
		margin: 0px;
	}

	.support-content a {
		color: #111;
	}

	.support-content .info a:hover {
		text-decoration: underline;
	}

	.support-content .info .fa {
		width: 1.5em;
		text-align: center;
	}

	.support-content .number {
		color: #777;
	}

	.support-content img {
		margin: 0 auto;
		display: block;
	}

	.support-content .modal-body {
		padding-bottom: 0px;
	}

	.support-content-comment {
		padding: 10px 10px 10px 30px;
		background: #eee;
		border-top: 1px solid #ccc;
	}

	/* BACKGROUND COLORS */
	.bg-red,
	.bg-yellow,
	.bg-aqua,
	.bg-blue,
	.bg-light-blue,
	.bg-green,
	.bg-navy,
	.bg-teal,
	.bg-olive,
	.bg-lime,
	.bg-orange,
	.bg-fuchsia,
	.bg-purple,
	.bg-maroon,
	bg-gray,
	bg-black,
	.bg-red a,
	.bg-yellow a,
	.bg-aqua a,
	.bg-blue a,
	.bg-light-blue a,
	.bg-green a,
	.bg-navy a,
	.bg-teal a,
	.bg-olive a,
	.bg-lime a,
	.bg-orange a,
	.bg-fuchsia a,
	.bg-purple a,
	.bg-maroon a,
	bg-gray a,
	.bg-black a {
		color: #f9f9f9 !important;
	}

	.bg-white,
	.bg-white a {
		color: #999999 !important;
	}

	.bg-red {
		background-color: #f56954 !important;
	}

	.bg-yellow {
		background-color: #f39c12 !important;
	}

	.bg-aqua {
		background-color: #00c0ef !important;
	}

	.bg-blue {
		background-color: #0073b7 !important;
	}

	.bg-light-blue {
		background-color: #3c8dbc !important;
	}

	.bg-green {
		background-color: #00a65a !important;
	}

	.bg-navy {
		background-color: #001f3f !important;
	}

	.bg-teal {
		background-color: #39cccc !important;
	}

	.bg-olive {
		background-color: #3d9970 !important;
	}

	.bg-lime {
		background-color: #01ff70 !important;
	}

	.bg-orange {
		background-color: #ff851b !important;
	}

	.bg-fuchsia {
		background-color: #f012be !important;
	}

	.bg-purple {
		background-color: #932ab6 !important;
	}

	.bg-maroon {
		background-color: #85144b !important;
	}

	.bg-gray {
		background-color: #eaeaec !important;
	}

	.bg-black {
		background-color: #222222 !important;
	}
</style>

<div class="single_category wow fadeInDown" style="margin-bottom: 20px;">
	<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Pengaduan</span></h2>
</div>

<div class="row">
	<div class="col-md-12">
		<form action="<?= $search_action; ?>" method="get">
			<table style="width: -webkit-fill-available">
				<tr>
					<td style="padding-right: 5px"><button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#newpengaduan">Formulir Pengaduan</button></td>
					<td style="width: 20%; padding-right: 5px">
						<select class="form-control select2" id="caristatus" name="caristatus">
							<option value="">Semua Status</option>
							<option value="1" <?= selected($caristatus, 1); ?>>Menunggu Diproses</option>
							<option value="2" <?= selected($caristatus, 2); ?>>Sedang Diproses</option>
							<option value="3" <?= selected($caristatus, 3); ?>>Selesai Diproses</option>
						</select>
					</td>
					<td>
						<div class="input-group">
							<input type="text" name="cari" value="<?= $cari; ?>" placeholder="Cari pengaduan disini..." class="form-control">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
								<?php if ($cari) : ?>
									<a href="<?= site_url('pengaduan'); ?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
								<?php endif; ?>
							</span>
						</div>
					</td>
				</tr>
			</table>
		</form>
		<br />

		<!-- Notifikasi -->
		<?php if (($notif = session('notif')) && (! session('notif')['data'])) : ?>
			<div class="alert alert-<?= $notif['status'] == 'error' ? 'danger' : 'success'; ?>" role="alert">
				<?= $notif['pesan']; ?>
			</div>
		<?php endif; ?>
		
		<?php if ($pengaduan) : ?>
			<ul class="list-group fa-padding">
				<?php foreach ($pengaduan as $key => $value) : ?>
					<li class="list-group-item status<?= $value['status'] ?> allstatus" data-toggle="modal" data-target="#pengaduan<?= $value['id'] ?>">
						<div class="media">
							<div class="media-body" style="display: block;">
								<table>
									<tr>
										<td rowspan="2"><i class="fa fa-user pull-left" style="font-size: -webkit-xxx-large"></i></td>
										<td>
											<h4 style="margin-bottom: 0px"><?= $value['nama']; ?></h4>
										</td>
									</tr>
									<tr>
										<td class="text-muted"><?= $value['created_at'] ?> | <?= $value['judul'] ?> | <?php if ($value['status'] == '1') : ?>
												<span class="label label-danger">Menunggu Diproses</span>
											<?php elseif ($value['status'] == '2') : ?>
												<span class="label label-info">Sedang Diproses</span>
											<?php elseif ($value['status'] == '3') : ?>
												<span class="label label-success">Selesai Diproses</span>
											<?php endif; ?>
										</td>
									</tr>
								</table><br>
								<p class="info">
									<span><?= substr($value['isi'], 0, 50); ?> <?php if (strlen($value['isi']) > 50) : ?><label class="text-info">read more...</label><?php endif; ?></span>
									<span class="label label-<?= $value['jumlah'] > 0 ? 'success' : 'danger'; ?> pull-right"><i class="fa fa-comments"></i> <?= $value['jumlah']; ?> Tanggapan</span>
								</p>
							</div>
						</div>
					</li>

					<!-- BEGIN DETAIL TICKET -->
					<div class="modal fade" id="pengaduan<?= $value['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="pengaduan<?= $value['id'] ?>" aria-hidden="true">
						<div class="modal-wrapper">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header bg-blue">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										<h4 class="modal-title"><i class="fa fa-file"></i> <?= $value['judul'] ?></h4>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="col-md-12">
												<p class="text-muted">Pengaduan oleh <?= $value['nama']; ?> | <?= $value['created_at'] ?></p>
												<p><?= $value['isi'] ?></p>
												<?php $file_foto = LOKASI_PENGADUAN . $value['foto']; ?>
												<?php if (file_exists(FCPATH . $file_foto)) : ?>
													<img class="img-responsive" src="<?= to_base64($file_foto) ?>">
												<?php endif; ?>
											</div>
										</div>
										<?php foreach ($pengaduan_balas as $keyna => $valuena) : ?>
											<?php if ($valuena['id_pengaduan'] && $valuena['id_pengaduan'] == $value['id']) : ?>
												<div class="row support-content-comment">
													<div class="col-md-12">
														<p>Ditanggapi oleh <?= $valuena['nama']; ?> | <?= $valuena['created_at'] ?></p>
														<p><?= $valuena['isi'] ?></p>
													</div>
												</div>
											<?php endif; ?>
										<?php endforeach; ?>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- END DETAIL TICKET -->
				<?php endforeach; ?>
			</ul>

			<?php $this->load->view("{$folder_themes}/commons/page"); ?>

		<?php else : ?>
			<div class="alert alert-info" role="alert">
				Data tidak tersedia
			</div>
		<?php endif; ?>
	</div>
</div>

<!-- Formulir Pengaduan -->
<div class="modal fade" id="newpengaduan" tabindex="-1" role="dialog" aria-labelledby="newpengaduan" aria-hidden="true">
	<div class="modal-wrapper">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-blue">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title"><i class="fa fa-pencil"></i> Buat Pengaduan Baru</h4>
				</div>
				<form action="<?= $form_action; ?>" method="POST" enctype="multipart/form-data">
					<div class="modal-body">
						<!-- Notifikasi -->
						<?php if (($notif = session('notif')) && ($data = session('notif')['data'])) : ?>
							<div class="alert alert-danger" role="alert">
								<?= $notif['pesan']; ?>
							</div>
						<?php endif; ?>
						<div class="form-group">
							<input name="nik" type="text" maxlength="16" class="form-control" placeholder="NIK" value="<?= $data['nik']; ?>">
						</div>
						<div class="form-group">
							<input name="nama" type="text" class="form-control" placeholder="Nama*" value="<?= $data['nama']; ?>" required>
						</div>
						<div class="form-group">
							<input name="email" type="email" class="form-control" placeholder="Email" value="<?= $data['email']; ?>">
						</div>
						<div class="form-group">
							<input name="telepon" type="text" class="form-control" placeholder="Telepon" value="<?= $data['telepon']; ?>">
						</div>
						<div class="form-group">
							<input name="judul" type="text" class="form-control" placeholder="Judul*" value="<?= $data['judul']; ?>" required>
						</div>
						<div class="form-group">
							<textarea name="isi" class="form-control" placeholder="Isi Pengaduan*" rows="5" required><?= $data['isi']; ?></textarea>
						</div>
						<div class="form-group">
							<div class="input-group">
								<input type="text" accept="image/*" onchange="readURL(this);" class="form-control" id="file_path" placeholder="Unggah Foto" name="foto" value="<?= $data['foto']; ?>">
								<input type="file" accept="image/*" onchange="readURL(this);" class="hidden" id="file" name="foto" value="<?= $data['foto']; ?>">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i></button>
								</span>
							</div>
							<small>Gambar: png,jpg,jpeg</small><br>
							<br><img id="blah" src="#" alt="gambar" class="img-responsive hidden" />
						</div>
						<div class="form-group">
							<table>
								<tr class="captcha">
									<td>&nbsp;</td>
									<td>
										<a href="#" id="b-captcha" onclick="document.getElementById('captcha').src = '<?= site_url('captcha') ?>'; return false" style="color: #000000;">
											<img id="captcha" src="<?= site_url('captcha') ?>" alt="CAPTCHA Image" />
										</a>
									</td>
									<td>&nbsp;&nbsp;&nbsp;</td>
									<td>
										<input type="text" name="captcha_code" class="form-control" maxlength="6" placeholder="Masukkan kode diatas" required />
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="modal-footer">
						<a href="<?= site_url('pengaduan') ?> " class="btn btn-danger pull-left"><i class="fa fa-times"></i> Tutup</a>
						<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> Kirim</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		window.setTimeout(function() {
			$("#notifikasi").fadeTo(500, 0).slideUp(500, function() {
				$(this).remove();
			});
		}, 2000);

		var data = "<?= session('notif')['data'] ?>";
		if (data) {
			$('#newpengaduan').modal('show');
		}
	});

	$('#b-captcha').click();

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function(e) {
				$('#blah').removeClass('hidden');
				$('#blah').attr('src', e.target.result).width(150).height(auto);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}
</script>