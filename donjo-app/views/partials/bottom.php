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

<?$i=1;foreach($slide AS $data){if($i<5){?>
<div class="contentbotm nobig">
	<div class="contentbotm_feature">
	<? if($data['gambar']!=''){?>
		<? if(is_file("assets/front/slide/kecil_".$data['gambar'])) {?>
			<img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?=base_url()?>assets/front/data/kecil_<?=$single_artikel['gambar']?>" />
			<? }else{?>
			<img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?=base_url()?>assets/images/404-image-not-found.jpg" />
		<? }?>
	<? }?>	

	</div>
</div>
<?}$i++;}?>
