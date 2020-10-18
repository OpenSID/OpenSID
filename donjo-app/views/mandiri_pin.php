<?php
/**
 * File ini:
 *
 * Form ganti pin layanan mandiri
 *
 * donjo-app/views/mandiri_pin.php
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
	<?php if (is_file("desa/css/siteman.css")): ?>
		<link type='text/css' href="<?= base_url()?>desa/css/siteman.css" rel='Stylesheet' />
	<?php endif; ?>
	<?php if (is_file(LOKASI_LOGO_DESA ."favicon.ico")): ?>
		<link rel="shortcut icon" href="<?= base_url()?><?=LOKASI_LOGO_DESA?>favicon.ico" />
	<?php else: ?>
		<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
	<?php endif; ?>
	<!-- Keyboard Default (Ganti dengan keyboard-dark.min.css untuk tampilan lain)-->
	<link rel="stylesheet" href="<?= base_url("assets/css/keyboard.min.css")?>">
	<link rel="stylesheet" href="<?= base_url("assets/front/css/mandiri-keyboard.css")?>">

	<script src="<?= base_url()?>assets/bootstrap/js/jquery.min.js"></script>
	<script src="<?= base_url()?>assets/bootstrap/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>assets/js/validasi.js"></script>
	<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>

	<?php if ($cek_anjungan): ?>
		<!-- keyboard widget css & script -->
		<script src="<?= base_url("assets/js/jquery.keyboard.min.js")?>"></script>
		<script src="<?= base_url("assets/js/jquery.mousewheel.min.js")?>"></script>
		<script src="<?= base_url("assets/js/jquery.keyboard.extension-all.min.js")?>"></script>
		<script src="<?= base_url("assets/front/js/mandiri-keyboard.js")?>"></script>
	<?php endif; ?>
	<?php require __DIR__ .'/head_tags.php' ?>
</head>
<body class="login">
	<div class="top-content">
		<div class="inner-bg">
			<div class="container">
				<div class="row">
					<div class="col-sm-4 col-sm-offset-4 form-box">
						<div class="form-top">
							<a href="<?=site_url(); ?>mandiri_web/balik_first"><img src="<?=gambar_desa($header['logo']);?>" alt="<?=$header['nama_desa']?>" class="img-responsive" /></a>
							<div class="login-footer-top">
								<h1>LAYANAN MANDIRI</h1>
								<br /><h1><?=ucwords($this->setting->sebutan_desa)?> <?=$header['nama_desa']?></h1>
								<h3>
									<br /><?=$header['alamat_kantor']?><br />Kodepos <?=$header['kode_pos']?>
									<br /><?=ucwords($this->setting->sebutan_kecamatan)?> <?=$header['nama_kecamatan']?><br /><?=ucwords($this->setting->sebutan_kabupaten)?> <?=$header['nama_kabupaten']?>
								</h3>
							</div>
							<div class="alert alert-danger">
								<?php if ($this->session->success == -1): ?>
									<?= $this->session->error_msg ?>
								<?php else: ?>
									Untuk keamanan silakan ubah PIN anda
								<?php endif; ?>
							</div>
						</div>
						<div class="form-bottom">
							<form action="<?=site_url("mandiri_web/update_pin/$main[nik]"); ?>" method="POST" id="validasi" enctype="multipart/form-data">
								<div class="form-group">
									<input class="form-username form-control input-sm required <?= jecho($cek_anjungan, TRUE, 'kbvnumber'); ?>" name="pin_lama" id="pin_lama" type="password" placeholder="PIN Lama" <?= jecho($this->session->mandiri_wait, 1, "disabled") ?> value="">
								</div>
								<div class="form-group">
									<input class="form-username form-control input-sm required <?= jecho($cek_anjungan, TRUE, 'kbvnumber'); ?>" name="pin1" id="pin1" type="password" placeholder="PIN Baru" <?= jecho($this->session->mandiri_wait, 1, "disabled") ?> value="">
								</div>
								<div class="form-group">
									<input class="form-username form-control input-sm required <?= jecho($cek_anjungan, TRUE, 'kbvnumber'); ?>" name="pin2" id="pin2" type="password" placeholder="Ulangi PIN Baru" <?= jecho($this->session->mandiri_wait, 1, "disabled") ?> value="">
								</div>
								<div class="form-group">
									<input type="checkbox" id="checkbox" class="form-checkbox"> Tampilkan PIN
								</div>
								<button type="submit" id="btnSubmit" class="btn btn-social btn-flat btn-info btn-sm"><i class='fa fa-check'></i>Simpan</button>
								<div class="login-footer-top">
									<br /><h3>Silakan coba login kembali setelah PIN baru berhasil disimpan</h3>
								</div>
							</form>
							<div class="login-footer-bottom"><a href="https://github.com/OpenSID/OpenSID" target="_blank">
								OpenSID</a> <?= AmbilVersi(); ?><br />
								IP Adress : <?= $this->input->ip_address(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<script>
$('document').ready(function()
{
	$('#checkbox').click(function(){
		var $pwd = $(".input-sm");
		if ($pwd.attr('type') === 'password') {
			$pwd.attr('type', 'text');
		} else {
			$pwd.attr('type', 'password');
		}
	});
});
</script>
