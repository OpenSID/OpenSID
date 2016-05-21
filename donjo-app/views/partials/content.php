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


<?if($headline){?>
<div class="themes nobig2">
<div class='title'>
<h2><a href="<?=site_url("first/artikel/$headline[id]")?>"><?=$headline['judul']?></a></h2>
<label class="owner"><?=$headline['owner']?>, </label><label><?=tgl_indo2($headline['tgl_upload'])?></label>
</div>
<div class='entry'>
<p>
<? if($headline['gambar']!=''){?>
		<? if(is_file("assets/front/artikel/sedang_".$headline['gambar'])) {?>
			<a class="group2" href="<?=base_url()?>assets/front/artikel/sedang_<?=$headline['gambar']?>" title="">
<img width="200" class="head" src="<?=base_url()?>assets/front/artikel/sedang_<?=$headline['gambar']?>" /></a>
			<? }else{?>
			<img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?=base_url()?>assets/images/404-image-not-found.jpg" width="300" height="180"/>
		<? }?>

<? }?><?=$headline['isi']?></p>
</div>
</div>
<?}?>
<div class="new-artikel">
<h1>Artikel Terkini</h1>
</div>
<?foreach($artikel AS $data){?>
<div class="themes nobig">
<div class='title'>
<h2><a href="<?=site_url("first/artikel/$data[id]")?>"><?=$data['judul']?></a></h2>
<label class="owner"><?=$data['owner']?>, </label><label><?=tgl_indo2($data['tgl_upload'])?></label>
</div>

<div class='entry'>
<? if($data['gambar']!=''){?>
	<? if(is_file("assets/front/artikel/kecil_".$data['gambar'])) {?>
			<a class="group2" href="<?=base_url()?>assets/front/artikel/sedang_<?=$data['gambar']?>" title=""><img  style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?=base_url()?>assets/front/artikel/kecil_<?=$data['gambar']?>" /></a>
			<? }else{?>
			<img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?=base_url()?>assets/images/404-image-not-found.jpg" width="120" height="90"/>
		<? }?>
<?}?>
<p style="text-align: justify;"><?=$data['isi']?></p>
</div>
</div>
<?}?>
<div class="themes nobig2">
<div class="bleft">
            <label>Jumlah Total Artikel:</label>
			<label><strong><?=$paging->num_rows?></strong></label>
</div>
        <div class="bright">
            <div class="uibutton-group">
            <? if($paging->start_link): ?>
				<a href="<?=site_url("first/index/$paging->start_link")?>" class="uibutton"  >Awal</a>
			<? endif; ?>
			<? if($paging->prev): ?>
				<a href="<?=site_url("first/index/$paging->prev")?>" class="uibutton"  >Prev</a>
			<? endif; ?>
            </div>
            <div class="uibutton-group">
                
				<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?=site_url("first/index/$i")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
				<? endfor; ?>
            </div>
            <div class="uibutton-group">
			<? if($paging->next): ?>
				<a href="<?=site_url("first/index/$paging->next")?>" class="uibutton">Next</a>
			<? endif; ?>
			<? if($paging->end_link): ?>
                <a href="<?=site_url("first/index/$paging->end_link")?>" class="uibutton">Akhir</a>
			<? endif; ?>
            </div>
        </div>
</div>