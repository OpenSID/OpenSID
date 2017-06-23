<div id="pageC">
<script>
$(function(){
if ($('input[name=id_tipe]:unchecked').next('label').text()=='Pilihan (Tunggal)'){
$('tr.delik').hide();
}
if ($('input[name=id_tipe]:checked').next('label').text()=='Pilihan (Tunggal)'){
$('tr.delik').show();
} 
$('input[name=id_tipe]').click(function(){
if ($(this).next('label').text()=='Pilihan (Tunggal)'){
$('tr.delik').show();
} else {
$('tr.delik').hide();
}
});
});
</script>
<style>
tr.delik{
display:none;
}
</style>
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 
<div class="content-header">
</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Form Pertanyaan - <a href="<?php echo site_url()?>analisis_master/menu/<?php echo $_SESSION['analisis_master']?>"><?php echo $analisis_master['nama']?></a></h3>
</div>
<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th width="100">Tipe Pertanyaan</th>
<td>
<div class="uiradio">
<?php $ch='checked';?>
<input type="radio" id="group3" name="id_tipe" value="1"/<?php if($analisis_indikator['id_tipe'] == '1' OR $analisis_indikator['id_tipe'] == ''){echo $ch;}?>><label for="group3">Pilihan (Tunggal)</label>
<input type="radio" id="group2" name="id_tipe" value="2"/<?php if($analisis_indikator['id_tipe'] == '2'){echo $ch;}?>><label for="group2">Pilihan (Checkboxes)</label>
<input type="radio" id="group1" name="id_tipe" value="3"/<?php if($analisis_indikator['id_tipe'] == '3'){echo $ch;}?>><label for="group1">Isian Angka</label>
<input type="radio" id="group4" name="id_tipe" value="4"/<?php if($analisis_indikator['id_tipe'] == '4'){echo $ch;}?>><label for="group4">Isian Tulisan</label>
</div>
</td>
</tr>
<tr>
<th>Kode Pertanyaan</th>
<td><input name="nomor" type="text" class="inputbox number" size="5" value="<?php echo $analisis_indikator['nomor']?>"/></td>
</tr>
<tr>
<th>Pertanyaan</th>
<td><textarea name="pertanyaan" style="resize:none;width:500px;height:80px;"/><?php echo $analisis_indikator['pertanyaan']?></textarea></td>
</tr>
<tr class="delik">
<th>Bobot</th>
<td>
	<input name="bobot" type="text" class="inputbox number" size="10" value="<?php if($analisis_indikator['bobot']==""){echo "1";}else{echo $analisis_indikator['bobot'];}?>"/>
</td>
</tr>
<tr class="delik">
<th width="100" class="delik">Aksi Analisis</th>
<td>
<div class="uiradio">
<?php $ch='checked';?>
<input type="radio" id="gp2" name="act_analisis" value="1"/<?php if($analisis_indikator['act_analisis'] == '1'){echo $ch;}?>><label for="gp2">Ya</label>
<input type="radio" id="gp1" name="act_analisis" value="2"/<?php if($analisis_indikator['act_analisis'] == '2' OR $analisis_indikator['act_analisis'] == ''){echo $ch;}?>><label for="gp1">Tidak</label>
</div>
</td>
</tr>
<tr>
<tr>
<th>Kategori Pertanyaan</th>
<td>
<div class="uiradio">
<?php $ch='checked';?>				
<?php foreach($list_kategori AS $data){?>
<input type="radio" id="g<?php echo $data['id']?>" name="id_kategori" value="<?php echo $data['id']?>" <?php if($analisis_indikator['id_kategori'] == $data['id']){echo $ch;}?>><label for="g<?php echo $data['id']?>"><?php echo $data['kategori']?></label>
<?php }?>
</div>
</td>
</tr> 
<tr>
<th>Publikasi Indikator</th>
<td>
<div class="uiradio">
<?php $ch='checked';?>
<input type="radio" id="agp2" name="is_publik" value="1"/<?php if($analisis_indikator['is_publik'] == '1'){echo $ch;}?>><label for="agp2">Ya</label>
<input type="radio" id="agp1" name="is_publik" value="0"/<?php if($analisis_indikator['is_publik'] == '0' OR $analisis_indikator['is_publik'] == ''){echo $ch;}?>><label for="agp1">Tidak</label>
</div>
*) Tampilkan Agregasi Indikator di halaman depan (menu Data Desa -> Data Analisis).
</td>
</tr>
</table>
</div>
 
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?php echo site_url()?>analisis_indikator" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">

<button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>