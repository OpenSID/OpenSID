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

<?if($single_artikel['id']){?>
<div class="themes bigfull">
<div class='title'>
<h2><a href="#"><?=$single_artikel['judul']?></a></h2>
</div>
	<div class='entry'>
		<p>

<? if($single_artikel['gambar']!=''){?>
		<? if(is_file("assets/front/artikel/kecil_".$single_artikel['gambar'])) {?>
			<a class="group2" href="<?=base_url()?>assets/front/artikel/sedang_<?=$single_artikel['gambar']?>" title="<?=$single_artikel['judul']?>"><img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?=base_url()?>assets/front/artikel/kecil_<?=$single_artikel['gambar']?>" /></a>
			<? }else{?>
			<img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?=base_url()?>assets/images/404-image-not-found.jpg" />
		<? }?>
<? }?>	

<?=$single_artikel['isi']?>		</p>
		</div>
<div class="entry" style="display:block;">

<? if($single_artikel['gambar1']!=''){?>
		<? if(is_file("assets/front/artikel/kecil_".$single_artikel['gambar1'])) {?>
			<a class="group2" href="<?=base_url()?>assets/front/artikel/sedang_<?=$single_artikel['gambar1']?>" title="<?=$single_artikel['judul']?>"><img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?=base_url()?>assets/front/artikel/kecil_<?=$single_artikel['gambar1']?>" /></a>
			<? }else{?>
			<img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?=base_url()?>assets/images/404-image-not-found.jpg" />
		<? }?>
<? }?>	
<? if($single_artikel['gambar2']!=''){?>
		<? if(is_file("assets/front/artikel/kecil_".$single_artikel['gambar2'])) {?>
			<a class="group2" href="<?=base_url()?>assets/front/artikel/sedang_<?=$single_artikel['gambar2']?>" title="<?=$single_artikel['judul']?>"><img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?=base_url()?>assets/front/artikel/kecil_<?=$single_artikel['gambar2']?>" /></a>
			<? }else{?>
			<img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?=base_url()?>assets/images/404-image-not-found.jpg" />
		<? }?>
<? }?>	
<? if($single_artikel['gambar3']!=''){?>
		<? if(is_file("assets/front/artikel/kecil_".$single_artikel['gambar3'])) {?>
			<a class="group2" href="<?=base_url()?>assets/front/artikel/sedang_<?=$single_artikel['gambar3']?>" title="<?=$single_artikel['judul']?>"><img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?=base_url()?>assets/front/artikel/kecil_<?=$single_artikel['gambar3']?>" /></a>
			<? }else{?>
			<img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?=base_url()?>assets/images/404-image-not-found.jpg" />
		<? }?>
<? }?>	
	
<? if(isset($single_artikel['dokumen'])){if($single_artikel['dokumen']!=''){?>
		<? if(is_file("assets/front/dokumen/".$single_artikel['dokumen'])) {?>
			<a href="<?=base_url()?>assets/front/dokumen/<?=$single_artikel['dokumen']?>" ><?=$single_artikel['link_dokumen']?></a>
			<? }?>
<? }}?>
</div>
<div class="art-spacer"  style="display:block;clear:both;">
	Ditulis oleh: <b><?=$single_artikel['owner']?><br></b>
	<small>Pada: <?=tgl_indo2($single_artikel['tgl_upload'])?></small>
</div>
<style>
#pageshare {float:left;padding:0 0 0px 0;z-index:10;}
#pageshare .sbutton {float:left;margin:0px 4px;}
</style>
<div id='pageshare' title="bagikan ke teman anda">
<div class='sbutton' id='fb'><a name="fb_share" href="http://www.facebook.com/sharer.php?u='<?=site_url()?>first/artikel/<?=$single_artikel['id']?>'">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script></div>
<div class='sbutton' id='rt'><a href="http://twitter.com/share" class="twitter-share-button">Tweet</a><script src='http://platform.twitter.com/widgets.js' type="text/javascript"></script></div>
</div>
</br>&nbsp;
</br>
<div style="clear:both;">
<h3>Komentar Artikel Terkait</h3>
<?foreach($komentar AS $data){?>
<?if($data['enabled']==1){?>
<div class="kom-box">
	<span class="post-title">
		<b><?=$data['owner']?><br></b>
		<small><?=tgl_indo2($data['tgl_upload'])?></small>
		<p><b>Berkata: </b><?=$data['komentar']?>
		</p>
	</span>
</div>
<?}?>
<?}?>
</div>	
<div class="themes comments">
<h3>Post Komentar :</h3>
<br/>
        <table width=100%>
        <form name='form' action="<?=site_url("first/add_comment/$single_artikel[id]")?>" method=POST onSubmit=\"return validasi(this)\">
        <tr class="komentar"><td>Nama</td><td> <input type=text name="owner" size=20 maxlength=30></td></tr>
        <tr class="komentar"><td>Alamat e-mail</td><td> <input type=text name="email" size=20 maxlength=30></td></tr>
        <tr class="komentar"><td valign=top>Komentar</td><td> <textarea name="komentar" style='width: 300px; height: 100px;'></textarea></td></tr>
        <tr><td>&nbsp;</td><td><input type="submit" value="Kirim"></td></tr>
		</form>
		</table><br />
</div>
	<input type="button" value="Kembali" onclick="self.history.back()">
</div>
<?}?>