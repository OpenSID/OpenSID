<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<section id="layanan-mandiri">
	<div class="container">
		<div class="col-12 px-0">
			<div class="row">
				<div class="col-lg-7 mandiri-headline justify-content-start">
					<div class="col-lg-11">
						<h3 class="h3">Layanan Mandiri</h3>
						<?php if(!isset($_SESSION['mandiri']) OR $_SESSION['mandiri']<>1) : ?>
							<span>Silakan login untuk mengakses modul Layanan Mandiri. Untuk mendapatkan kode PIN, Anda perlu datang atau menghubungi operator desa.</span>
							<?php else : ?>
								<span>Anda telah login sebagai <?= $_SESSION['nama'] ?> dan dapat memanfaatkan fasilitas Layanan Mandiri yang tersedia.</span>
						<?php endif ?>
						
					</div>
				</div>
				<div class="col-lg-5 justify-content-end form-mandiri-wrapper">
					<div class="col-lg-12 form-mandiri-login">
						<?php if(!isset($_SESSION['mandiri']) OR $_SESSION['mandiri']<>1) : ?>
							<form action="<?= site_url('first/auth#layanan-mandiri') ?>" method="post" id="form-mandiri">
							<?php if($_SESSION['mandiri_wait']==1) : ?>
								<div class="my-1">
									<small class="d-block alert alert-warning">Gagal 3 kali, silakan coba kembali dalam <?php echo waktu_ind((time()- $_SESSION['mandiri_timeout'])*(-1));?> detik lagi</small>
									<small class="alert alert-danger my-2 d-block">
										Login Gagal. Username atau Password yang anda masukkan salah!
									</small>
								</div>
								<?php else : ?>
							
									<p class="h6 mb-3">Silakan Masukkan NIK dan PIN</p>
									<?php  if($_SESSION['mandiri_try'] AND $_SESSION['mandiri']==-1): ?>
										<small class="alert alert-warning text-small d-block">
											Kesempatan mencoba <?php echo ($_SESSION['mandiri_try']-1); ?> kali lagi.
										</small>
									<?php endif; ?>
									<?php  if($_SESSION['mandiri']==-1): ?>
										<small class="alert alert-danger text-small d-block">
											Login Gagal. Username atau Password yang Anda masukkan salah!
										</small>
									<?php endif; ?>
									<div class="form-group">
										<input type="text" class="form-control" placeholder="NIK" name="nik">
									</div>
									<div class="form-group">
										<input type="password" class="form-control" placeholder="PIN" name="pin">
									</div>
									<button class="btn btn-info mt-2">
										<i class="fa fa-send"></i> MASUK
									</button>
								<?php endif ?>
								</form>
							<?php else : ?>
								<ul class="list-layanan">
									<li class="list-item-layanan">
										<a href="<?=site_url('first/mandiri/1/1');?>" class="btn btn-item">PROFIL</a>
									</li>
									<li class="list-item-layan">
										<a href="<?=site_url('first/mandiri/1/2');?>" class="btn btn-item">LAYANAN</a>
									</li>
									<li class="list-item-layan">
										<a href="<?=site_url('first/mandiri/1/3');?>" class="btn btn-item">LAPOR</a>
									</li>
									<li>
										<a href="<?=site_url('first/mandiri/1/4');?>" class="btn btn-item">BANTUAN</a>
									</li>
									<li>
										<a href="<?=site_url('first/logout');?>" class="btn btn-danger">KELUAR</a>
									</li>
								</ul>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>