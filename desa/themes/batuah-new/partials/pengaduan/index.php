<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php $this->load->view("$folder_themes/plus/header_side"); ?>
<div class="mainpage">
	<div class="margin-side-10">
	<div class="mainbodyarea">
		<div class="bigarea hiddenrelative">
		<div class="bigarea-margin">
			<div class="col-default margin-top-10">
				<div class="bodypage bgwhite bordergrey">
					<div class="headstat-lebar flexcenter">
						<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pengaduan2.png") ?>"/>
						<div>
						<h2>Layanan</h2>
						<h1 class="color-1">Pengaduan</h1>
						</div>
					</div>
					<div class="pagebox">
						
						<form action="<?= $search_action; ?>" method="get">
						<div class="pengaduan-filter">
							<div class="margin-minlr-5">
								<div class="column-3">
								<div class="padding-leftright-5">
									<select class="form-input form-control" id="caristatus" name="caristatus">
										<option value="">Semua Status</option>
										<option value="1" <?= selected($caristatus, 1); ?>>Menunggu Diproses</option>
										<option value="2" <?= selected($caristatus, 2); ?>>Sedang Diproses</option>
										<option value="3" <?= selected($caristatus, 3); ?>>Selesai Diproses</option>
									</select>
								</div>	
								</div>	
								<div class="column-3">
								<div class="padding-leftright-5 flexright">
									<input type="text" name="cari" value="<?= $cari; ?>" placeholder="Cari pengaduan disini..." class="form-input form-control" style="overflow:hidden;">
									<button type="submit" class="b-btn bgcolor-1 flexcenter" style="margin:0 0 0 5px;color:#fff;"><i class="fa fa-search"></i></button>
								</div>	
								</div>	
								<div class="column-3" style="color:#fff;">
								<div class="padding-leftright-5">
									<button class="b-btn bgcolor-1 flexcenter" data-toggle="modal" data-target="#newpengaduan" type="button" style="cursor:pointer;position:relative;z-indek:20;display:block;width:100%;"><i class="fa fa-bullhorn"></i><p style="margin-left:5px;">Buat Pengaduan</p></button>
								</div>	
								</div>		
							</div>
						</div>	
						</form>
						<div class="width-default" style="margin-top:20px;">
							<?php if (($notif = $this->session->flashdata('notif')) && (! $this->session->flashdata('notif')['data'])) : ?>
							<div class="alertpengaduan">
							<div id="notifikasi" class="alert alert-<?= $notif['status']; ?>" role="alert">
								<?= $notif['pesan']; ?>
							</div>
							</div>
							<?php endif; ?>
							<?php if ($pengaduan) : ?>
								<?php foreach($pengaduan as $key => $value) : ?>
								<div class="<?= $value['status'] ?> allstatus" data-toggle="modal" data-target="#pengaduan<?= $value['id'] ?>">
								<div class="listrow bordergrey1">
									<div class="listrow-image">
										<div class="foto-pengaduan">
											<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/svgfile/pelapor.svg") ?>"/>
										</div>
									</div>
									<div class="listrow-title">
										<h2>Pelapor : <?= $value['nama']; ?></h2>
										<div class="info flexleft mb-10" style="width:100%;font-size:90%;">
										<div class="flexleft" style="margin:2px 0;"><i class="fa fa-calendar flexleft" style="margin-right:5px;"></i><?= $value['created_at'] ?></div>
										<div class="flexleft" style="margin:2px 0;">
										<?php if ($value['status'] == '1') : ?>
											<span class="badge bgorange" style="margin-left:5px;margin-right:5px;font-weight:normal;">Belum Diproses</span>
										<?php elseif ($value['status'] == '2') : ?>
											<span class="badge bgbiru" style="margin-left:5px;margin-right:5px;font-weight:normal;">Sedang Diproses</span>
										<?php elseif ($value['status'] == '3') : ?>
											<span class="badge bghijau" style="margin-left:5px;margin-right:5px;font-weight:normal;">Selesai Diproses</span>
										<?php endif; ?>
										<?php if ($value['status'] == '1') : ?>
										<span class="badge bgtoska badge-<?= $value['jumlah'] > 0 ? 'primary' : 'danger'; ?> pull-right" style="font-weight:normal;"><i class="fa fa-comments"></i> <?= $value['jumlah']; ?> Tanggapan</span>
										<?php else : ?>
										<span class="badge bgpink badge-<?= $value['jumlah'] > 0 ? 'primary' : 'danger'; ?> pull-right" style="font-weight:normal;"><i class="fa fa-comments"></i> <?= $value['jumlah']; ?> Tanggapan</span>
										<?php endif; ?>
										</div>
										</div>
										<h3 style="margin:5px 0 5px;"><b><?=$value['judul']?></b></h3>
										<p>
										<?= substr($value['isi'], 0, 50); ?>
										<?php if (strlen($value['isi']) > 50) : ?> ...selengkapnya<?php endif; ?>
										</p>
									</div>
									
								</div>		
								</div>
								
								<div class="lapakdetail-modal">
								<div class="bigmodal">
								<div class="modal center fade bgmodalcolor1" id="pengaduan<?= $value['id'] ?>" role="dialog" aria-labelledby="lapor" aria-hidden="true">
									<div class="modal-dialog bggrey1" role="document">
										<div class="modal-absolute bgwhite bordergrey1">
											<div class="modalhead bgcolor-1 flexcenter"><h1>Detail Pengaduan</h1></div>
											<div class="inner-modal" style="border-radius:0;">
											<div class="scroll-area" id="scrollstyle">
											<div class="lapakdetail" style="padding:10px;">
												<h1><?=$value['judul']?></h1>
												<table style="width:100%;">
													<tr>
														<td style="width:30%;">Pelapor</td><td style="width:15px;text-align:center;">:</td><td><b class="color-2"><?= $value['nama']; ?></b></td>	
													</tr>
													<tr>
														<td style="width:30%;">Tanggal</td><td style="width:15px;text-align:center;">:</td><td><?= $value['created_at'] ?></td>	
													</tr>
													<tr>
														<td style="width:30%;">Status</td><td style="width:15px;text-align:center;">:</td>
														<td>
														<?php if ($value['status'] == '1') : ?>
															<span class="badge bgorange" style="font-weight:normal;">Belum Diproses</span>
														<?php elseif ($value['status'] == '2') : ?>
															<span class="badge bgbiru" style="font-weight:normal;">Sedang Diproses</span>
														<?php elseif ($value['status'] == '3') : ?>
															<span class="badge bghijau" style="font-weight:normal;">Selesai Diproses</span>
														<?php endif; ?>
														</td>	
													</tr>
												</table>
												<p style="margin:10px 0 5px;"><b>Keterangan :</b></p>
												<p style="margin:0 0 15px;"><?=$value['isi']?></p>
												<?php if ($value['foto']) : ?>
													<img src="<?= base_url(LOKASI_PENGADUAN . $value['foto']); ?>" style="width:100%;height:auto;margin:0 0 15px;display:block;">
												<?php endif; ?>
												<?php if ($value['status'] == '1') : ?>
													<span class="badge bgtoska badge-<?= $value['jumlah'] > 0 ? 'primary' : 'danger'; ?>" style="font-weight:normal;"><i class="fa fa-comments"></i> <?= $value['jumlah']; ?> Tanggapan</span>
												<?php else : ?>
													<span class="badge bgpink badge-<?= $value['jumlah'] > 0 ? 'primary' : 'danger'; ?>" style="font-weight:normal;"><i class="fa fa-comments"></i> <?= $value['jumlah']; ?> Tanggapan</span>
												<?php endif; ?>
												<?php foreach ($pengaduan_balas as $keyna => $valuena) : ?>
												<?php if ($valuena['id_pengaduan'] && $valuena['id_pengaduan'] == $value['id']) : ?>
													<div style="width:100%;float:left;margin:10px 0 0;">
													<h3>Ditanggapi : <b><?= $valuena['nama']; ?></b> | <?= $valuena['created_at'] ?></h3><p><?= $valuena['isi'] ?></p>
													</div>
												<?php endif; ?>
												<?php endforeach; ?>
											</div>	
											</div>	
											</div>
											<div class="modalfoot bggrey1 bordergrey1 flexcenter" data-dismiss="modal" aria-label="Close">
												<div class="close-btn bgcolor-1"></div>
											</div>	
										</div>
										</div>
								</div>
								</div>
								</div>
								
								<?php endforeach; ?>
							<?php else: ?>
								<div class="blankdata" style="padding:10px 0 0;">
									<img style="width:100%;height:auto;display:block;border-radius:5px;" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nature.jpg") ?>"/>
									<div class="blankdata-title">
										<h2>Mohon maaf,</h2>
										<h3>Untuk sementara, belum ada laporan yang masuk...</h3>
									</div>
									<div class="blankdata-bottom">
										<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/girl.png") ?>"/>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<?php $this->load->view("$folder_themes/plus/middle_page"); ?>
		</div>
		</div>
		<div class="rightsidearea">
			<?php $this->load->view("$folder_themes/plus/side"); ?>			
		</div>
	</div>
	</div>
</div>

<div class="lapakdetail-modal">
<div class="modal center fade bgmodalcolor1" id="newpengaduan" role="dialog" aria-labelledby="lapor" aria-hidden="true">	
	<div class="modal-dialog bggrey1" role="document">
		<div class="modal-absolute bgwhite bordergrey1">
			<div class="modalhead bgcolor-1 flexcenter"><h1>Buat Pengaduan</h1></div>
			<div class="inner-modal" style="border-radius:0;">
				<div class="scroll-area" id="scrollstyle">
					<div class="lapakdetail" style="padding:10px;">
						<form action="<?= $form_action; ?>" method="POST" enctype="multipart/form-data">
					<?php if (($notif = $this->session->flashdata('notif')) && ($data = $this->session->flashdata('notif')['data'])) : ?>
						<div class="flexcenter mb-10">
						<div class="alert alert-danger" role="alert">
							<?= $notif['pesan']; ?>
						</div>
						</div>
					<?php endif; ?>
					<label style="font-size:95%;margin:0;padding:0;line-height:1.1;font-weight:500;">Masukkan NIK</label>
					<input name="nik" type="text" maxlength="16" class="form-control bordergrey" placeholder="NIK" value="<?= $data['nik']; ?>" style="margin:0 0 5px;">
					<label style="font-size:95%;margin:0;padding:0;line-height:1.1;font-weight:500;">Masukkan Nama</label>
					<input name="nama" type="text" class="form-control bordergrey" placeholder="Nama*" value="<?= $data['nama']; ?>" required style="margin:0 0 5px;">
					<label style="font-size:95%;margin:0;padding:0;line-height:1.1;font-weight:500;">Masukkan Email</label>
					<input name="email" type="email" class="form-control bordergrey" placeholder="Email" value="<?= $data['email']; ?>" style="margin:0 0 5px;">
					<label style="font-size:95%;margin:0;padding:0;line-height:1.1;font-weight:500;">Masukkan No. Telp.</label>
					<input name="telepon" type="text" class="form-control bordergrey" placeholder="Telepon" value="<?= $data['telepon']; ?>" style="margin:0 0 5px;">
					<label style="font-size:95%;margin:0;padding:0;line-height:1.1;font-weight:500;">Judul Pengaduan</label>
					<input name="judul" type="text" class="form-control bordergrey" placeholder="Judul*" value="<?= $data['judul']; ?>" required style="margin:0 0 5px;">
					<label style="font-size:95%;margin:0;padding:0;line-height:1.1;font-weight:500;">Isi/detail Pengaduan</label>
					<textarea style="margin:0;" name="isi" class="form-control bordergrey" placeholder="Isi Pengaduan*" rows="5" required style="margin:0 0 5px;"><?= $data['isi']; ?></textarea>
					
					<div class="form-group flexcenter" style="margin:0 0 20px !important;">
						<div class="input-group flexcenter">
							<input type="text" accept="image/*" onchange="readURL(this);" class="form-control bordergrey" id="file_path" placeholder="Unggah Foto" name="foto" value="<?= $data['foto']; ?>" style="margin:0;">
							<input type="file" accept="image/*" onchange="readURL(this);" class="hidden" id="file" name="foto" value="<?= $data['foto']; ?>">
							<button type="button" class="b-btn bgcolor-1 flexcenter" id="file_browser" style="margin:0 5px;"><i class="fa fa-search" style="color:#fff;"></i></button>
						</div>
						<small>Gambar: png,jpg,jpeg</small><br>
						<br><img id="blah" src="#" alt="gambar" class="img-responsive hidden" />
					</div>
					<div class="form-group" style="margin:0;">
						<div class="width-default relative-hidden">
							<div class="rowsame" style="margin:0 -5px;">
								<div class="image-captha2 bordergrey1 flexcenter">
									<a href="#" id="b-captcha" onclick="document.getElementById('captcha').src = '<?= site_url('captcha') ?>'; return false">
										<img style="width:100%;height:auto;" id="captcha" src="<?= site_url('captcha') ?>" alt="CAPTCHA Image">
									</a>
								</div>
								<input type="text" name="captcha_code" class="captha-isi2 bordergrey1 flexcenter" placeholder="Ketik Captha" maxlength="6" required>
							</div>
						</div>
					</div>
					<div class="width-default flexcenter" style="padding:15px 0 !important;color:#fff;">
						<button type="submit" class="b-btn bgcolor-1 flexcenter"><i class="fa fa-pencil"></i><p style="margin-left:5px;">Kirim</p></button>
					</div>
				</form>
					</div>	
				</div>
			</div>
			<div class="modalfoot bggrey1 bordergrey1 flexcenter" data-dismiss="modal" aria-label="Close">
				<div class="close-btn bgcolor-1"></div>
			</div>	
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