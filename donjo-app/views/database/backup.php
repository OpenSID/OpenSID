				<div class="tab-pane <?= jecho($act_tab, 1, 'active') ?>">
					<div class="row">
						<div class="col-md-12">
							<div class="box-header with-border">
								<h3 class="box-title"><strong>Backup Database SID</strong></h3>
							</div>
							<div class="box-body">
								<div class="row">
									<div class="col-sm-8">
										<?php if ($inkremental != null && $inkremental->status == '1'): ?>
										<p class="text-muted text-blue well well-sm no-shadow" style="margin-top: 10px;">
											<small>
												<?php if ($inkremental->ukuran == '0 Bytes'): ?>
													<strong class="text-red"><i class="fa fa-info-circle text-red"></i> Tidak ada file terbaru untuk dibackup.</strong>
												<?php else : ?>
													<strong><i class="fa fa-info-circle text-blue"></i> Backup inkremental sudah selesai dan siap untuk didownload</strong>
												<?php endif ?>
											</small>
										</p>
										<?php elseif ($inkremental->status == '-1') : ?>
											<p class="text-muted text-blue well well-sm no-shadow" style="margin-top: 10px;">
												<small>
													<strong class="text-red"><i class="fa fa-info-circle text-red"></i> Backup Gagal,  Informasi gagal ada di log Error</strong>
												</small>
											</p>
										<?php endif ?>
										<form class="form-horizontal">
											<table class="table table-bordered">
												<tbody>
													<tr>
														<td class="col-sm-10"><b>Backup Seluruh Database SID (.sql)</b></td>
														<td class="col-sm-2">
															<a href="<?= site_url('database/exec_backup')?>" class="btn btn-social btn-flat btn-block btn-info btn-sm" title="Perkiraan ukuran file backup sql berdasarkan jumlah tabel dan baris data adalah <?= $size_sql ?>"><i class="fa fa-download"></i> Unduh Database <b><code><?= $size_sql ?></code></b></a>
														</td>
													</tr>
													<tr>
														<td class="col-sm-10"><b>Backup Seluruh Folder Desa SID (.zip)</b> </td>
														<td class="col-sm-2">
															<a href="<?= site_url('database/desa_backup'); ?>" class="btn btn-social btn-flat btn-block btn-info btn-sm" title="Perkiraan ukuran folder desa sebelum di compress adalah <?= $size_folder ?>"><i class="fa fa-download"></i> Unduh Folder Desa <b><code><?= $size_folder ?></code></b></a>
														</td>
													</tr>
													<tr>
														<td class="col-sm-10"><b>Backup Inkremental Folder Desa SID (.zip)</b> </td>
														<td class="col-sm-2">
															<!-- Split button -->
															<div class="btn-group" style="width:100%">
																<button type="button" class="btn btn-social btn-flat <?= $inkremental->status == '0' ? 'btn-warning' : 'btn-info'  ?> btn-info btn-sm" id="Inkremental" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: calc(100% - 25px);"><i class="fa fa-download"></i> <?= $inkremental->status == '0' ? 'Backup Sedang Dalam Proses' : 'Backup Inkremental'  ?></button>
																<button type="button" class="btn btn-flat btn-sm dropdown-toggle <?= $inkremental->status == '0' ? 'btn-warning' : 'btn-info'  ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 23px;">
																	<span class="caret"></span>
																	<span class="sr-only">Toggle Dropdown</span>
																</button>
																<ul class="dropdown-menu">
																	<?php if ($inkremental == null || $inkremental->status == '2' || $inkremental->status == '-1'): ?>
																		<li><a href="#" id="buat-job">Buat Backup Inkremental</a></li>
																	<?php endif ?>
																	<?php if ($inkremental != null && $inkremental->status == '1' && $inkremental->ukuran != '0 Bytes'): ?>
																		<li><a href="<?= site_url('database/inkremental_download'); ?>">Download Backup Inkremental</a></li>
																	<?php endif ?>
																	<li><a href="<?= site_url('database/desa_inkremental'); ?>">Lihat Riwayat</a></li>
																</ul>
															</div>
														</td>
													</tr>
												</tbody>
											</table>
										</form>
										<p>Proses Unduh akan mengunduh keseluruhan database SID anda.</p>
										<div class="row">
											<ul>
												<li> Usahakan untuk melakukan backup secara rutin dan terjadwal. </li>
												<li> Backup yang dihasilkan sebaiknya disimpan di komputer terpisah dari server SID. </li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php if ($this->CI->cek_hak_akses('u') && ! config_item('demo_mode')): ?>
							<div class="col-md-12">
							<div class="box-header with-border">
								<h3 class="box-title"><strong>Restore Database SID</strong></h3>
							</div>
							<div class="box-body">
								<div class="row">
									<div class="col-sm-12">
										<p>Backup yang dibuat dapat dipergunakan untuk mengembalikan database SID anda apabila ada masalah. Klik tombol Restore di bawah untuk menggantikan keseluruhan database SID dengan data hasil backup terdahulu.</p>
										<form action="<?= $form_action?>" method="post" enctype="multipart/form-data" class="form-horizontal">
											<p>Batas maksimal pengunggahan berkas <strong><?= max_upload() ?> MB.</strong></p>
											<p>Proses ini akan membutuhkan waktu beberapa menit, menyesuaikan dengan spesifikasi komputer server SID dan sambungan internet yang tersedia.</p>
											<p></p>
											<table class="table table-bordered table-hover" >
												<tbody>
													<tr>
														<td style="padding-top:20px;padding-bottom:10px;">
															<div class="form-group">
																<label for="file"class="col-md-2 col-lg-3 control-label">Pilih File .Sql:</label>
																<div class="col-sm-12 col-md-5 col-lg-5">
																	<div class="input-group input-group-sm">
																		<input type="text" class="form-control" id="file_path" name="userfile">
																		<input type="file" class="hidden" id="file" name="userfile" data-submit="restore" accept="application/sql">
																		<span class="input-group-btn">
																			<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
																		</span>
																	</div>
																</div>
																<div class="col-sm-12 col-md-3 col-lg-2">
																	<button type="submit" id="restore" class="btn btn-block btn-success btn-sm" disabled="disabled"><i class="fa fa-spin fa-refresh"></i>Restore</button>
																</div>
															</div>
														</td>
													</tr>
												</tbody>
											</table>
										</form>
									</div>
								</div>
							</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script src="<?= asset('js/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<link rel="stylesheet" href="<?= asset('js/sweetalert2/sweetalert2.min.css') ?>">

<script type="text/javascript">
	$(function() {
		$('#buat-job').click(function (e) {
			e.preventDefault();
			var lokasi;
			Swal.fire({
				title: 'Backup Inkremental',
				text: "Backup Inkremental membutuhkan resource yang besar dan membuat akses ke aplikasi menjadi lambat. Disarankan menjalankan backup Inkremental disaat aplikasi tidak di akses.",
				footer: `<div class="text-bold text-center text-red">Backup Inkremental akan berjalan di belakang layar dan tidak bisa dibatalkan sampai proses selesai</div>`,
				icon: "warning",
				showCancelButton: true,
				confirmButtonText: 'Lanjutkan',
			}).then((result) => {
			  /* Read more about isConfirmed, isDenied below */
				if (result.isConfirmed) {
					Swal.fire({
						title: 'Lokasi Simpan',
						showDenyButton: true,
						showCancelButton: false,
						confirmButtonText: 'Temporary folder',
						denyButtonText: `Backup folder`,
						footer: `
						<div class="text-bold text-warning">
							<ul>
								<li>Temporary folder : file backup akan terhapus otomatis oleh sistem</li>
								<li>folder backup : file backup akan disimpan di dalam folder backup_inkremental dan tidak akan dihapus oleh sistem. </li>
							</ul>
						</div>
						`,
					}).then((result) => {
						if (result.isConfirmed) {
							backup('null')
						} else if (result.isDenied) {
							backup('backup')
						}else{
							return;
						}
					})
				}else{
					return;
				}
			})
		});
		var backup = function (lokasi) {
			notification('success', 'Backup Sedang Dalam Proses');
			$('#Inkremental').html(`<i class="fa fa-download"></i> Backup Dalam Proses`).addClass( "btn-warning" );
			$('button.dropdown-toggle').addClass( "btn-warning" );
			$.ajax({
				url: '<?= site_url('database/inkremental_job'); ?>',
				type: 'Post',
				data: {lokasi: lokasi}
			})
			.done(function(response) {
				if (response.status == true) {
					$('#buat-job').remove();
				}else{
					notification('danger', response.message);
					$('#Inkremental').html(`<i class="fa fa-download"></i> Backup Dalam Proses`).removeClass( "btn-warning" );
					$('button.dropdown-toggle').removeClass( "btn-warning" );
				}
			})
			.fail(function(e) {
				notification('danger', e);
			});
		}

	});
</script>
