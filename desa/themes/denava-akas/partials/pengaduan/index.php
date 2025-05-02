<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="modalfixed">
	<div class="pengaduan">
		<div class="article-single">
			<div class="container-page mb-20">
				<div class="headingpage border-grey-soft bg-white flexleft" style="border-radius:5px 5px 0 0;">
					<div class="headingpage-image border-grey-soft flexcenter">
						<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pengaduan-small.png") ?>" alt=""/>
					</div>
					<h2>PENGADUAN</h2>
				</div>
				<div class="box-article bg-white" style="border-radius:0 0 5px 5px;">
					<div class="box-statis border-grey-soft" style="border-radius:0 0 5px 5px;">
					<?php if (($notif = session('notif')) && (!session('notif')['data'])) : ?>
					<div class="alertpengaduan">
						<div id="notifikasi" class="alert alert-<?= $notif['status']; ?>" role="alert">
							<?= $notif['pesan']; ?>
						</div>
					</div>
					<?php endif; ?>
						<form action="<?= $search_action; ?>" method="get">
							<div style="width:100%;float:left;" class="mb-20">
								<div class="margin-min5">
									<div class="column-3" style="margin:0 0 10px;">
										<div class="pd-lr-5">
											<select class="form-input form-control border-grey-soft select2" id="caristatus" name="caristatus" style="margin:0;">
												<option value="">Semua Status</option>
												<option value="1" <?= selected($caristatus, 1); ?>>Menunggu Diproses</option>
												<option value="2" <?= selected($caristatus, 2); ?>>Sedang Diproses</option>
												<option value="3" <?= selected($caristatus, 3); ?>>Selesai Diproses</option>
											</select>
										</div>
									</div>
									<div class="column-3" style="margin:0 0 10px;">
										<div class="pd-lr-5 flexleft">
											<input type="text" name="cari" autocomplete="off" value="<?= html_escape($cari); ?>" placeholder="Cari pengaduan disini." class="form-input form-control border-grey-soft">
											<div style="float:right;" class="flexright">
												<button type="submit" class="tombol-kotak bg-color2 flexcenter" style="margin-left:10px;">
													<svg viewBox="0 0 56.966 56.966">
														<path d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23 s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92 c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17 s-17-7.626-17-17S14.61,6,23.984,6z"/>
													</svg>
												</button>
												<?php if (html_escape($cari)) : ?>
													<a href="<?= site_url('pengaduan'); ?>" class="tombol-kotak bg-color3 flexcenter" style="margin-left:10px;">
														<svg viewBox="0 0 24 24" style="height:50%;">
															<path d="M6.22566 4.81096C5.83514 4.42044 5.20197 4.42044 4.81145 4.81096C4.42092 5.20148 4.42092 5.83465 4.81145 6.22517L10.5862 11.9999L4.81151 17.7746C4.42098 18.1651 4.42098 18.7983 4.81151 19.1888C5.20203 19.5793 5.8352 19.5793 6.22572 19.1888L12.0004 13.4141L17.7751 19.1888C18.1656 19.5793 18.7988 19.5793 19.1893 19.1888C19.5798 18.7983 19.5798 18.1651 19.1893 17.7746L13.4146 11.9999L19.1893 6.22517C19.5799 5.83465 19.5799 5.20148 19.1893 4.81096C18.7988 4.42044 18.1657 4.42044 17.7751 4.81096L12.0004 10.5857L6.22566 4.81096Z"/>
														</svg>
													</a>
												<?php endif; ?>
											</div>
										</div>
									</div>			
									<div class="column-3" style="margin:0 0 10px;">
										<div class="pd-lr-5">
											<div class="form-pengaduan bg-gradient-hor flexcenter border-grey-soft" data-toggle="modal" data-target="#newpengaduan" style="padding:0 15px;color:#fff;" type="button">Buat Pengaduan</div>
										</div>
									</div>
								</div>	
							</div>
						</form>
						<div class="">
							<?php if ($pengaduan) : ?>
								<?php foreach ($pengaduan as $key => $value) : ?>
									<div class="pengaduan-row border-grey-soft">
										<div class="pengaduan-title" data-toggle="modal" data-target="#pengaduan<?= $value['id'] ?>">
											<div class="p-15">
												<h2 class="color-1"><?= $value['judul'] ?></h2>
												<h3>Pelapor : <b><?= $value['nama'] ?></b></h3>
												<h3 style="margin:0;">Tanggal : <?= $value['created_at'] ?></h3>
											</div>
											<div class="pengaduan-detail border-grey-soft flexleft color-1">Detail Laporan</div>
										</div>
										<div class="pengaduan-status border-grey-soft">
											<?php
											$statusOptions = ['Belum Diproses', 'Sedang Diproses', 'Selesai Diproses'];
											$status = $statusOptions[$value['status'] - 1];
											$icons = ['login.svg', 'check.svg', 'populer.svg'];
											$icon = $icons[$value['status'] - 1];
											?>
											<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/$icon") ?>" alt=""/>
											Status<br/><?= $status ?>
										</div>
										<div class="pengaduan-tanggapan border-grey-soft" data-toggle="modal" data-target="#pengaduan<?= $value['id'] ?>">
											<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/chat.svg") ?>" alt=""/>
											<?php if ($value['status'] == '1') : ?>
												<span class="<?= $value['jumlah'] > 0 ? 'primary' : 'danger'; ?>"><b><?= $value['jumlah']; ?></b><br/>Tanggapan</span>
											<?php else : ?>
												<span class="<?= $value['jumlah'] > 0 ? 'primary' : 'danger'; ?>"><b><?= $value['jumlah']; ?></b><br/>Tanggapan</span>
											<?php endif; ?>
										</div>
									</div>
									<div class="modal fade" id="pengaduan<?= $value['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="pengaduan<?= $value['id'] ?>" aria-hidden="true" data-backdrop="false">
										<div class="modal-dialog">
											<div class="modal-container-small bg-white">
												<div class="topmodal bg-gradient-hor flexleft" data-dismiss="modal" aria-hidden="true">Detail Pengaduan<div class="close-button"></div></div>
												<div class="modal-container-area">
													<div class="withscroll">
														<div class="pengaduan-modal p-15">
															<h2><?= $value['judul'] ?></h2>
															<h3>Pelapor : <b><?= $value['nama']; ?></b></h3>
															<h3>Tanggal : <?= $value['created_at'] ?></h3>
															<div class="speak">
																<div class="speak-icon"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/komentar.svg") ?>" alt=""/></div>
																<?=$value['isi']?>
															</div>
															<?php $file_foto = LOKASI_PENGADUAN . $value['foto']; ?>
															<?php if (file_exists(FCPATH . $file_foto)) : ?>
																<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= to_base64($file_foto) ?>" style="width:100%;margin:0 0 10px;display:block;" alt="">
															<?php endif; ?>
															<div class="flexleft">
																<?php
																$statusOptions = ['Belum Diproses', 'Sedang Diproses', 'Selesai Diproses'];
																$status = $statusOptions[$value['status'] - 1];
																?>
																<h3>Status : <?= $status ?>
																<img style="width:auto;height:30px;margin:-4px 0 0 5px;" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/{$statusOptions[$value['status'] - 1]}.svg") ?>" alt=""/>
																</h3>
															</div>
															<?php foreach ($pengaduan_balas as $keyna => $valuena) : ?>
																<?php if ($valuena['id_pengaduan'] && $valuena['id_pengaduan'] == $value['id']) : ?>
																	<div class="mb-20" style="width:100%;float:left;">
																		<h3 style="margin:0 0 5px;">Ditanggapi : <b><?= $valuena['nama']; ?></b> | <?= $valuena['created_at'] ?></h3>
																		<p><?= $valuena['isi'] ?></p>
																	</div>
																<?php endif; ?>
															<?php endforeach; ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
								<?php $this->load->view($folder_themes . "/commons/paging"); ?>
							<?php else : ?>
								<div class="margin-min10 flexcenter">
									<div class="no-found-title">
										<div class="pd-lr-10">
											<h2>404! PENGADUAN BELUM TERSEDIA</h2>
											<h3>Maaf, belum tersedia data pengaduan pada halaman ini.</h3>
										</div>
									</div>
									<div class="no-found-image">
										<div class="pd-lr-10">
											<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nodata.png") ?>" alt=""/>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>
						<div class="modal fade" id="newpengaduan" tabindex="-1" role="dialog" aria-labelledby="newpengaduan" aria-hidden="true" data-backdrop="false">
							<div class="modal-dialog">
								<div class="modal-container-small bg-white">
									<div class="topmodal bg-gradient-hor flexleft" data-dismiss="modal" aria-hidden="true">
										Buat Pengaduan
										<div class="close-button"></div>
									</div>
									<div class="modal-container-area">
										<div class="withscroll">
											<div class="pengaduan-modal p-15">
												<form action="<?= $form_action; ?>" method="POST" enctype="multipart/form-data">
													<?php if (($notif = session('notif')) && ($data = session('notif')['data'])) : ?>
													<div id="notifikasi" class="alert alert-<?= $notif['status'] == 'error' ? 'danger' : 'success'; ?>" role="alert">
														<?= $notif['pesan']; ?>
													</div>
													<?php endif; ?>
													<div class="margin-min10">
														<div class="column-2 mb-10">
															<div class="pd-lr-10">
																<input name="nama" type="text" autocomplete="off" class="form-control border-grey-soft" placeholder="*Nama" value="<?= $data['nama']; ?>" required style="margin:0;">
															</div>
														</div>
														<div class="column-2 mb-10">
															<div class="pd-lr-10">
																<input name="nik" type="text" autocomplete="off" maxlength="16" class="form-control border-grey-soft" placeholder="NIK" value="<?= $data['nik']; ?>" style="margin:0;">
															</div>
														</div>
														<div class="column-2 mb-10">
															<div class="pd-lr-10">
																<input name="email" type="email" autocomplete="off" class="form-control border-grey-soft" placeholder="Email" value="<?= $data['email']; ?>" style="margin:0;">
															</div>
														</div>
														<div class="column-2 mb-10">
															<div class="pd-lr-10">
																<input name="telepon" type="text" autocomplete="off" class="form-control border-grey-soft" placeholder="Telepon" value="<?= $data['telepon']; ?>" style="margin:0;">
															</div>
														</div>
													</div>
													<div class="form-group">
														<input name="judul" type="text" autocomplete="off" class="form-control border-grey-soft" placeholder="*Judul" value="<?= $data['judul']; ?>" required style="margin:0;">
													</div>
													<div class="form-group">
														<textarea name="isi" class="form-control border-grey-soft" autocomplete="off" placeholder="*Isi Pengaduan" rows="5" required style="margin:0;"><?= $data['isi']; ?></textarea>
														<small>*wajib diisi</small>
													</div>
													<div class="modal-body" style="padding:0;">
														<!-- Notifikasi -->
														<div class="form-group" style="margin:0 !important;">
															<div class="input-group">
																<input type="text" autocomplete="off" accept="image/*" onchange="readURL(this);" class="form-control border-grey-soft" id="file_path" placeholder="Unggah Foto" name="foto" value="<?= $data['foto']; ?>" style="margin:0;">
																<input type="file" autocomplete="off" accept="image/*" onchange="readURL(this);" class="hidden" id="file" name="foto" value="<?= $data['foto']; ?>">
																<span class="input-group-btn">
																	<button type="button" class="btn btn-info btn-flat" for="validatedCustomFile" id="file_browser" style="height:40px;padding-top:0;padding-bottom:0;"><i class="fa fa-search"></i></button>
																</span>
															</div>
															<small>Gambar: png, jpg, jpeg</small><br>
															<br><img id="blah" src="#" alt="gambar" class="img-responsive hidden" />
														</div>
														<div class="form-group" style="margin:0;">
															<table>
																<tr class="captcha"><td>&nbsp;</td>
																	<td style="width:50%;">
																		<img class="kode-gambar" id="captcha" src="<?= site_url('captcha') ?>" alt="CAPTCHA Image"/>
																		<a href="#" id="b-captcha" onclick="document.getElementById('captcha').src = '<?= site_url('captcha') ?><?= IS_PREMIUM && IS_240710 ? '?\' + Math.random();' : '\'; return false'?>" alt=""style="color: #000000;">
																			<i class="fa fa-refresh fa-lg" style="padding: 10px; color: #0b7d33"></i>
																		</a>
																	</td>
																	<td style="width:50%;">
																		<input type="text" name="captcha_code" autocomplete="off" class="form-control border-grey-soft" maxlength="6"  placeholder="Ketik kode di sini" value="<?= $notif['data']['captcha_code']; ?>" style="margin:0;" required>
																	</td>
																</tr>
															</table>
														</div>
													</div>
													<div class="modal-footer" style="border:none;padding:0;margin:15px 0;">
														<button type="button" class="btn bg-color5 pull-left" data-dismiss="modal" aria-hidden="true" style="color:#fff;"><i class="fa fa-times"></i> Tutup</button>
														<button type="submit" class="btn bg-color4 pull-right" style="color:#fff;"><i class="fa fa-pencil"></i> Kirim</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		window.setTimeout(function() {
			$("#notifikasi").fadeTo(500, 0).slideUp(500, function(){
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
