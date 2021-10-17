<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View Masuk Layanan Mandiri
 *
 * donjo-app/views/kehadiran/masuk.php
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

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Layanan Kehadiran Perangkat Desa <?= ucwords($this->setting->sebutan_desa . ' '. $desa['nama_desa']); ?></title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<?php if (is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
		<link rel="shortcut icon" href="<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico" />
	<?php else: ?>
		<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
	<?php endif; ?>
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?= base_url()?>rss.xml" />
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/font-awesome.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?= base_url()?>assets/css/AdminLTE.min.css">
	<!-- AdminLTE Skins. -->
	<link rel="stylesheet" href="<?= base_url()?>assets/css/skins/_all-skins.min.css">
	<!-- Style Mandiri Modification -->
	<link rel="stylesheet" href="<?= base_url()?>assets/css/mandiri-style.css">
	<link rel="stylesheet" href="<?= base_url()?>assets/css/keyboard-basic.min.css">
	<?php if (is_file("desa/pengaturan/siteman/siteman.css")): ?>
		<link rel='Stylesheet' href="<?= base_url()?>desa/pengaturan/siteman/siteman.css">
	<?php endif; ?>
	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

	<script src="<?= base_url()?>assets/bootstrap/js/jquery.min.js"></script>

	<?php if ($cek_anjungan): ?>
		<!-- Keyboard Default (Ganti dengan keyboard-dark.min.css untuk tampilan lain)-->
		<link rel="stylesheet" href="<?= base_url("assets/css/keyboard.min.css")?>">
		<link rel="stylesheet" href="<?= base_url("assets/front/css/mandiri-keyboard.css")?>">
	<?php endif; ?>

	<?php $this->load->view('head_tags'); ?>
	<?php if ($latar_login): ?>
		<style type="text/css">
			body.login-page {
				background: url('<?= base_url($latar_login) ?>');
			}
		</style>
	<?php else: ?>
	<style>
		.login-box-body{ 
			margin: auto;width:380px;
			/*float: left !important; */
		}
		.numpad{
			min-height:100px;
			min-width:370px;
			 
			margin-right: 50px !important;
			float: left !important;
		}
		.numpad_keypad{
			width:370px;
			margin-bottom:5px;
			clear:both;
		}
		.numpad_keypad .td{
			width:100px;font-size:60px;
			height:100px;
			border:1px solid black;
			border-radius:30px;
			padding:10px;
			background:gray;
			margin:10px;
			vertical-align:middle;
			text-align:center;
			float:left;cursor: pointer;
		}
	</style>
	<?php endif; ?>
	<style>
.kehadiran-page{
	width: 100%;
	background: url(<?=base_url("assets/css/images/latar_login.jpg");?>) no-repeat center fixed  ;
	background-size:cover;
}
/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 120px;
  height: 64px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 52px;
  width: 52px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .8s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(51px);
  -ms-transform: translateX(52px);
  transform: translateX(52px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 64px;
}

.slider.round:before {
  border-radius: 50%;
}
.form-hadir{
	width:150px;margin:auto;text-align:center;
}
.text-mid{
	text-align:center;margin:5px auto 3px;
}
	</style>
</head>

<body class="kehadiran-page">
<?php 
if(isset($login_type))
{
	$this->load->view('kehadiran/login_view');
}

if(isset($status))
{
	$this->load->view('kehadiran/status_view');
}
?>

	<!-- jQuery 3 -->
	<script src="<?= base_url()?>assets/bootstrap/js/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="<?= base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
	<!-- SlimScroll -->
	<script src="<?= base_url()?>assets/bootstrap/js/jquery.slimscroll.min.js"></script>
	<!-- FastClick -->
	<script src="<?= base_url()?>assets/bootstrap/js/fastclick.js"></script>
	<!-- AdminLTE App -->
	<script src="<?= base_url()?>assets/js/adminlte.min.js"></script>
	<!-- Validasi -->
	<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
	<script src="<?= base_url()?>assets/js/validasi.js"></script>
	<script src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
	<script src="<?= base_url()?>assets/js/jquery.keyboard.min.js"></script>

	<?php if ($cek_anjungan): ?>
		<!-- keyboard widget css & script -->
		<script src="<?= base_url("assets/js/jquery.keyboard.min.js")?>"></script>
		<script src="<?= base_url("assets/js/jquery.mousewheel.min.js")?>"></script>
		<script src="<?= base_url("assets/js/jquery.keyboard.extension-all.min.js")?>"></script>
		<script src="<?= base_url("assets/front/js/mandiri-keyboard.js")?>"></script>
	<?php endif; ?>
<?php $this->load->view('kehadiran/js/masuk_js');?>
</body>
</html>
