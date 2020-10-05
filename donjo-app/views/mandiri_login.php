<?php
/**
 * File ini:
 *
 * Form login modul Layanan MAnfiti
 *
 * donjo-app/views/mandiri.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>

<style>
span.input-group-btn {
	position: absolute;
	display: inline-block;
	cursor: pointer;
	left: 10%;
	z-index: 2;
}
.fa-keyboard-o {
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
}
</style>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>
			<?=$this->setting->login_title
				. ' ' . ucwords($this->setting->sebutan_desa)
				. (($header['nama_desa']) ? ' ' . $header['nama_desa']: '')
				. get_dynamic_title_page_from_path();
			?>
		</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="robots" content="noindex">
		<!-- Jquery UI -->
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/jquery-ui.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?= base_url()?>assets/css/login-style.css" media="screen" type="text/css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/login-form-elements.css" media="screen" type="text/css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/bootstrap.bar.css" media="screen" type="text/css" />

		<!-- Keyboard Default (Ganti dengan keyboard-dark.min.css untuk tampilan lain)-->
		<link rel="stylesheet" href="<?= base_url("assets/css/keyboard.min.css")?>">
		<link rel="stylesheet" href="<?= base_url("assets/css/mandiri.css")?>">

		<?php if (is_file("desa/css/siteman.css")): ?>
			<link type='text/css' href="<?= base_url()?>desa/css/siteman.css" rel='Stylesheet' />
		<?php endif; ?>
		<?php if (is_file(LOKASI_LOGO_DESA ."favicon.ico")): ?>
			<link rel="shortcut icon" href="<?= base_url()?><?=LOKASI_LOGO_DESA?>favicon.ico" />
		<?php else: ?>
			<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
		<?php endif; ?>

		<!-- jQuery 3 -->
		<script src="<?= base_url()?>assets/bootstrap/js/jquery.min.js"></script>
		<!-- Jquery UI -->
		<script src="<?= base_url()?>assets/bootstrap/js/jquery-ui.min.js"></script>
		<script src="<?= base_url()?>assets/bootstrap/js/jquery.ui.autocomplete.scroll.min.js"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?= base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>

		<!-- keyboard widget css & script -->
		<script src="<?= base_url("assets/js/jquery.keyboard.min.js")?>"></script>
		<script src="<?= base_url("assets/js/jquery.keyboard.extension-mobile.min.js")?>"></script>
		<script src="<?= base_url("assets/js/jquery.mousewheel.min.js")?>"></script>
		<script src="<?= base_url("assets/js/jquery.keyboard.extension-typing.min.js")?>"></script>
		<script src="<?= base_url("assets/js/jquery.keyboard.extension-autocomplete.min.js")?>"></script>

		<?php require __DIR__ .'/head_tags.php' ?>
	</head>
	<body class="login">
		<div class="top-content">
			<div class="inner-bg">
				<div class="container">
					<div class="row">
						<div class="col-sm-4 col-sm-offset-4 form-box">
							<div class="form-top">
								<a href="<?=site_url(); ?>first/"><img src="<?=gambar_desa($header['logo']);?>" alt="<?=$header['nama_desa']?>" class="img-responsive" /></a>
								<div class="login-footer-top">
									<h1>Anjungan Layanan Mandiri</h1>
									<br /><h1><?=ucwords($this->setting->sebutan_desa)?> <?=$header['nama_desa']?></h1>
									<h3>
										<br /><?=$header['alamat_kantor']?><br />Kodepos <?=$header['kode_pos']?>
										<br /><?=ucwords($this->setting->sebutan_kecamatan)?> <?=$header['nama_kecamatan']?>
										<br /><?=ucwords($this->setting->sebutan_kabupaten)?> <?=$header['nama_kabupaten']?>
									</h3>
								</div>
								<hr />
							</div>
							<div class="form-bottom">
								<form id="validasi" class="login-form" action="<?=site_url('mandiri_web/auth')?>" method="post" >
									<?php if ($this->session->mandiri_wait == 1): ?>
										<div class="error login-footer-top">
											<p id="countdown" style="color:red; text-transform:uppercase"></p>
										</div>
									<?php else: ?>
										<div class="form-group">
											<span class="input-group-btn">
												<button id="nik-opener" class="btn btn-default" type="button"><i class="fa fa-keyboard-o fa-2x"></i></button>
											</span>
											<input class="form-control input-sm required" name="nik" id="nik" type="text" placeholder="NIK" <?php jecho($this->session->mandiri_wait, 1, "disabled") ?> value=""> </input>
										</div>
										<div class="form-group">
											<span class="input-group-btn">
												<button id="pin-opener" class="btn btn-default" type="button"><i class="fa fa-keyboard-o fa-2x"></i></button>
											</span>
											<input class="form-control input-sm required" name="pin" id="pin" type="password" placeholder="PIN" <?php jecho($this->session->mandiri_wait, 1, "disabled") ?> value=""> </input>
										</div>
										<hr />
										<button type="submit" class="btn">MASUK</button>
										<?php if ($this->session->mandiri == -1 && $this->session->mandiri_try < 4): ?>
											<div class="error">
												<p style="color:red; text-transform:uppercase">Login Gagal.<br />Nama pengguna atau kata sandi yang Anda masukkan salah!<br />
												<?php if ($this->session->mandiri_try): ?>
													Kesempatan mencoba <?= ($this->session->mandiri_try - 1); ?> kali lagi.</p>
												<?php endif; ?>
											</div>
										<?php elseif ($this->session->mandiri == -2): ?>
											<div class="error">
												Redaksi belum boleh masuk, SID belum memiliki sambungan internet!
											</div>
										<?php endif; ?>
									<?php endif; ?>
								</form>
								<hr/>
								<div class="login-footer-bottom"><a href="https://github.com/OpenSID/OpenSID" target="_blank">OpenSID</a> <?= substr(AmbilVersi(), 0, 20)?></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

<script>
$(function(){

	$('#nik')
	.keyboard({
		display: {
			'bksp'   : '\u2190',
			'accept' : 'Lanjut',
			'cancel' : 'Tutup',
		},
		openOn : null,
		stayOpen : true,
		layout: 'custom',
		customLayout: {
			'normal': [
				'1 2 3 4 5 6 7 8 9 0 {bksp}',
				'{cancel} {accept}'
			]
		}
	})
	.addTyping();

	$('#pin')
	.keyboard({
		display: {
			'bksp'   : '\u2190',
			'accept' : 'Lanjut',
			'cancel' : 'Tutup',
		},
		openOn : null,
		stayOpen : true,
		layout: 'custom',
		customLayout: {
			'normal': [
				'1 2 3 4 5 6 7 8 9 0 {bksp}',
				'{cancel} {accept}'
			]
		}
	})
	.addTyping();


	$('#nik-opener').click(function(){
		var kbnik = $('#nik').getkeyboard();
		if ( kbnik.isOpen ) {
			kbnik.close();
		} else {
			kbnik.reveal();
		}
	});

	$('#pin-opener').click(function(){
		var kbpin = $('#pin').getkeyboard();
		if ( kbpin.isOpen ) {
			kbpin.close();
		} else {
			kbpin.reveal();
		}
	});

});
</script>
