<?php
/*
 * Berkas default dari halaman web utk publik
 * 
 * Copyright 2013 
 * Rizka Himawan <himawan.rizka@gmail.com>
 * Muhammad Khollilurrohman <adsakle1@gmail.com>
 * Asep Nur Ajiyati <asepnurajiyati@gmail.com>
 *
 * SID adalah software tak berbayar (Opensource) yang boleh digunakan oleh siapa saja selama bukan untuk kepentingan profit atau komersial.
 * Lisensi ini mengizinkan setiap orang untuk menggubah, memperbaiki, dan membuat ciptaan turunan bukan untuk kepentingan komersial
 * selama mereka mencantumkan asal pembuat kepada Anda dan melisensikan ciptaan turunan dengan syarat yang serupa dengan ciptaan asli.
 * Untuk mendapatkan SID RESMI, Anda diharuskan mengirimkan surat permohonan ataupun izin SID terlebih dahulu, 
 * aplikasi ini akan tetap bersifat opensource dan anda tidak dikenai biaya.
 * Bagaimana mendapatkan izin SID, ikuti link dibawah ini:
 * http://lumbungkomunitas.net/bergabung/pendaftaran/daftar-online/
 * Creative Commons Attribution-NonCommercial 3.0 Unported License
 * SID Opensource TIDAK BOLEH digunakan dengan tujuan profit atau segala usaha  yang bertujuan untuk mencari keuntungan. 
 * Pelanggaran HaKI (Hak Kekayaan Intelektual) merupakan tindakan  yang menghancurkan dan menghambat karya bangsa.
 */
?>

<html>
<head>
<title>Gallery</title>
<link type='text/css' href="<?=base_url()?>assets/front/css/first.css" rel='Stylesheet' />
<link type='text/css' href="<?=base_url()?>assets/front/css/colorbox.css" rel='Stylesheet' />
		<link type='text/css' href="<?=base_url()?>assets/css/ui-buttons.css" rel='Stylesheet' />
<script src="<?=base_url()?>assets/front/js/jquery.js"></script>
<script src="<?=base_url()?>assets/front/js/layout.js"></script>
<script src="<?=base_url()?>assets/front/js/jquery.colorbox.js"></script>
<script>
	$(document).ready(function(){
		$(".group2").colorbox({rel:'group2', transition:"fade"});
				$(".group3").colorbox({rel:'group3', transition:"fade"});
	});
</script>
</head>

<body class="sub">
<div id="content">
<div id="header">
<div id="headleft">
<div id="sid-logo"><img src="<?=base_url()?>assets/images/logo/<?=$desa['logo']?>" alt=""/></div>
<div id="sid-judul">Desa <?=unpenetration($desa['nama_desa'])?></a></div>
<div id="sid-info">Kecamatan <?=unpenetration($desa['nama_kecamatan'])?></a></div>
<div id="sid-moto">Kabupaten <?=unpenetration($desa['nama_kabupaten'])?></a></div>
<div id="sid-alamat"><?=unpenetration($desa['alamat_kantor'])?></a></div>
</div>

<div id="headright">
<div id="menu_vert">
<div id="menuwrapper">
<?php $this->load->view('partials/menu.tpl.php');?>
</div>
</div>
</div>
				<div id="headright">
					<div id="menu_vert2" style="height:90px;">
					<? $i=0;foreach($slide AS $data){?>
					<? if($data['gambar']!='' AND $i<4){if(is_file("assets/front/artikel/kecil_".$data['gambar'])) {?>
						<img src="<?=base_url()?>/assets/front/artikel/kecil_<?=$data['gambar']?>" width="127" height="90"><?
						$i++;}elseif(is_file("assets/front/artikel/".$data['gambar'])){?>
						
						<img src="<?=base_url()?>/assets/front/artikel/<?=$data['gambar']?>" width="127" height="90"><?
						$i++;}?>
					<?}}?>
					</div>
				</div>
</div>
<div id="mainmenu">
<div id="mainmenuget">

<?php $this->load->view('partials/menu.left.php');?>

</div>
</div>
<div id="sidebar">
<div id="sidebartop" class="rtside">

<?php $this->load->view('partials/side.right.php');?>

</div>
</div>

<div id="main" class="Home">

<?php $this->load->view('partials/gallery.php');?>

</div>
</div>
</div>
<br>
<div id="foot">
<div id="footer">
<?php $this->load->view('partials/copywright.tpl.php');?>
</div>           
</div>
</body>
</html>