<!-- widget Layanan Mandiri -->

<?php
	if( ! isset($_SESSION['mandiri']) OR $_SESSION['mandiri']<>1) {

		if($_SESSION['mandiri_wait'] == 1) { ?>
			<div class="single_bottom_rightbar wow fadeInDown">
				<h2><i class="fa fa-user"></i> Layanan Mandiri</h2>
			</div>
			<div id="note" align="center" style="margin-bottom:10px;">
				<font color="red">Gagal 3 kali, NIK atau PIN yang Anda masukkan salah!</font>
			</div>
			<div id="note" align="center" style="margin-bottom:10px;">
				<font color="red">Coba dalam <?= waktu_ind((time()- $_SESSION['mandiri_timeout'])*(-1));?> lagi.</font>
			</div>
		<?php } else { ?>
			<div class="">
				<div class="single_bottom_rightbar wow fadeInDown">
					<h2><i class="fa fa-user"></i> Layanan Mandiri</h2>
					<ul role="tablist" class="nav nav-tabs custom-tabs">
						<li class="active" role="presentation"><a data-toggle="tab" role="tab" aria-controls="home" href="#mostPopular3">Login</a></li>
						<li role="presentation"><a data-toggle="tab" role="tab" aria-controls="messages" href="#recentComent3">Daftar</a></li>
					</ul>
					<div class="tab-content">
						<div id="mostPopular3" class="tab-pane fade in active" role="tabpanel">
							<form class="contact_form" action="<?= site_url('first/auth')?>" method="post">
								<input style="margin-bottom:10px;" name="nik" type="text" placeholder="Ketik Nomor KTP" maxlength="16" class="form-control" value="" required>
								<input style="margin-bottom:10px;" name="pin" type="password" placeholder="Ketik Kode PIN" value="" maxlength="6" class="form-control" required>
								<button type="submit" id="but" class="btn btn-primary btn-block">Masuk</button>
								<?php  if($_SESSION['mandiri_try'] AND isset($_SESSION['mandiri']) AND $_SESSION['mandiri']==-1){ ?>
									<div id="note" align="center" style="margin-top:10px;" >
										<font color="red">Kesempatan mencoba <?= ($_SESSION['mandiri_try']-1); ?> kali lagi.</font>
									</div>
								<?php }?>
								<?php  if(isset($_SESSION['mandiri']) AND $_SESSION['mandiri']==-1){ ?>
									<div id="note" align="center" style="margin-top:10px;" >
										<font color="red">Login Gagal. NIK atau PIN yang Anda masukkan salah!</font>
									</div>
								<?php }?>
							</form>
						</div>
						<div id="recentComent3" class="tab-pane fade" role="tabpanel">
							<ul id="ul-menu">
								<table border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td><center>Silakan datang atau hubungi operator <?= $this->setting->sebutan_desa?> untuk mendapatkan kode PIN Anda.</center></td>
									</tr>
								</table>
							</ul>
						</div>
					</div>
				</div>
				<div class="">

				</div>
			</div>
		<?php }
	} else {
	?>
	<div >
		<div class="single_bottom_rightbar wow fadeInDown">
			<h2>Menu Layanan</h2>
		</div>
		<div class="box-body">
			<ul id="ul-mandiri">
				<table id="mandiri" width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td colspan="3" align="center"><b><?= $_SESSION['nama'];?></b><br><br></td>
					</tr>
					<tr>
						<td width="20%" bgcolor="#eee">NIK</td>
						<td width="3%" class="titik" bgcolor="#eee">:</td>
						<td width="77%" bgcolor="#eee"><?= $_SESSION['nik'];?></td>
					</tr>
					<tr>
						<td>No. KK</td>
						<td class="titik">:</td>
						<td ><?= $_SESSION['no_kk']?></td>
					</tr>
					<tr>
						<td colspan="3"><h4><a href="<?= site_url();?>first/mandiri/1/1" class=""><button type="button" class="btn btn-primary btn-block">PROFIL</button></a> </h4></td>
					</tr>
					<tr>
						<td colspan="3"><h4><a href="<?= site_url();?>first/mandiri/1/3" class=""><button type="button" class="btn btn-primary btn-block">KOTAK PESAN</button></a> </h4></td>
					</tr>
					<tr>
						<td colspan="3"><h4><a href="<?= site_url();?>first/mandiri_surat" class=""><button type="button" class="btn btn-primary btn-block">PERMOHONAN SURAT</button></a> </h4></td>
					</tr>
					<tr>
						<td colspan="3"><h4><a href="<?= site_url();?>first/mandiri/1/2" class=""><button type="button" class="btn btn-primary btn-block">RIWAYAT LAYANAN</button></a> </h4></td>
					</tr>
					<tr>
						<td colspan="3"><h4><a href="<?= site_url();?>first/mandiri/1/4" class=""><button type="button" class="btn btn-primary btn-block">PROGRAM BANTUAN</button></a></h4></td>
					</tr>
					<tr>
						<td colspan="3"><h4><a href="<?= site_url();?>first/logout"  class=""><button type="button" class="btn btn-danger btn-block">KELUAR</button></a></h4></td>
					</tr>
				</table>
			</div>
		</div>
		<?php if(isset($_SESSION['lg']) AND $_SESSION['lg'] == 1) { ?>
			<div class="single_bottom_rightbar wow fadeInDown">
				<h2> Ubah PIN</h2>
				Untuk keamanan silahkan ubah kode PIN Anda.
			</div>
			<div class="box-body">
				<form class="contact_form" action="<?= site_url('first/ganti')?>" method="post">
					<input class="form-control" style="margin-bottom:10px;" name="pin1" type="password" maxlength="6" placeholder="PIN Baru" value="" >
					<input class="form-control" style="margin-bottom:10px;" name="pin2" type="password" maxlength="6" placeholder="Ulangi PIN Baru" value="" >
					<button type="submit" id="but" class="btn btn-primary btn-block">GANTI</button>
				</form>
				<?php if ($flash_message) { ?>
					<div id="notification" class='box-header label-danger'><?= $flash_message ?></div>
					<script type="text/javascript">
						$('document').ready(function(){
							$('#notification').delay(4000).fadeOut();
						});
					</script>
				<?php } ?>
			</div><hr>
		<?php } else if(isset($_SESSION['lg']) AND $_SESSION['lg'] == 2) { ?>
			<div class="single_bottom_rightbar wow fadeInDown">
				<div class="box-body">
					<div id="note">
						PIN Baru berhasil Disimpan!
					</div>
					<div id="note">
						Silahkan login kembali.
					</div>
				</div>
			</div>
			<?php unset($_SESSION['lg']);
		}
	}
?>
<?php $this->load->view('head_tags_front') ?>