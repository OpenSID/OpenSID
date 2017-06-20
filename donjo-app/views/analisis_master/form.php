<script type="text/javascript" src="<?php echo base_url()?>assets/tiny_mce/jquery.tinymce.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/tiny_mce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
selector: 'textarea',
height: 600,
theme: 'modern',
plugins: [
'advlist autolink lists link image charmap print preview hr anchor pagebreak',
'searchreplace wordcount visualblocks visualchars code ',
'insertdatetime media nonbreaking save table contextmenu directionality',
'emoticons template paste textcolor colorpicker textpattern imagetools'
],
toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image,print preview media | forecolor backcolor emoticons',
image_advtab: true
});
</script>
<script>
$(function(){
if ($('input[name=subjek_tipe]:unchecked').next('label').text()=='Kelompok Masyarakat'){
$('tr.delik').hide();
}
if ($('input[name=subjek_tipe]:checked').next('label').text()=='Kelompok Masyarakat'){
$('tr.delik').show();
} 
$('input[name=subjek_tipe]').click(function(){
if ($(this).next('label').text()=='Kelompok Masyarakat'){
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
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 
<div class="content-header">
</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Form Master Analisis</h3>
</div>
<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th>Nama Analisis</th>
<td><input name="nama" type="text" class="inputbox" size="80" value="<?php echo $analisis_master['nama']?>"/></td>
</tr>
<tr>
<th width="100">Unit Analisis</th>
<td>
<div class="uiradio">
<?php $ch='checked';?>
<input type="radio" id="group3" name="subjek_tipe" value="1"/<?php if($analisis_master['subjek_tipe'] == '1' OR $analisis_master['subjek_tipe'] == ''){echo $ch;}?>><label for="group3">Penduduk</label>
<input type="radio" id="group2" name="subjek_tipe" value="2"/<?php if($analisis_master['subjek_tipe'] == '2'){echo $ch;}?>><label for="group2">Keluarga / KK</label>
<input type="radio" id="group1" name="subjek_tipe" value="3"/<?php if($analisis_master['subjek_tipe'] == '3'){echo $ch;}?>><label for="group1">Rumah Tangga</label>
<input type="radio" id="group4" name="subjek_tipe" value="4"/<?php if($analisis_master['subjek_tipe'] == '4'){echo $ch;}?>><label for="group4">Kelompok Masyarakat</label>
</div>
</td>
</tr>
<tr class="delik">
<th>Master Kelompok</th><td>
	<select name="id_kelompok">
		<option value="">-- Pilih Master Kelompok --</option>				
		<?php foreach($list_kelompok AS $data){?>
		<option value="<?php echo $data['id']?>" <?php if($analisis_master['id_kelompok'] == $data['id']) :?>selected<?php endif?>><?php echo $data['kelompok']?></option>
		<?php }?>
	</select>
</td>
</tr>
<tr>
<th>Status Analisis</th>
<td>
<div class="uiradio">
<?php $ch='checked';?>
<input type="radio" id="g1" name="lock" value="1"/<?php if($analisis_master['lock'] == '1' OR $analisis_master['lock'] == ''){echo $ch;}?>><label for="g1">Tidak Terkunci</label>
<input type="radio" id="g2" name="lock" value="2"/<?php if($analisis_master['lock'] == '2'){echo $ch;}?>><label for="g2">Terkunci</label>
</td>
</tr> 
<tr>
<th colspan="2">Rumus Penilaian Analisis</br>Sigma (Bobot (indikator) x Nilai (parameter)) / "Bilangan Pembagi"</th>
</tr>
<tr>
<th>Bilangan Pembagi</th>
<td><input name="pembagi" type="text" class="inputbox number" size="20" value="<?php echo $analisis_master['pembagi']?>"/> *) untuk tanda koma "," gunakan tanda titik "." sebagai substitusinya.</td>
</tr>
<th>Analisis Terhubung</th><td>
	<select name="id_child">
		<option value="">-- Pilih Analisis --</option>				
		<?php foreach($list_analisis AS $data){?>
		<option value="<?php echo $data['id']?>" <?php if($analisis_master['id_child'] == $data['id']) :?>selected<?php endif?>><?php echo $data['nama']?></option>
		<?php }?>
	</select>
	*) Kosongi jika tida ada analisis terhubung.
</td>
<tr>
<th width="120" colspan="2">Deskripsi Analisis</th>
</tr>
<tr>
<td colspan="2">
<textarea name="deskripsi" style="width: 800px; height: 500px;">
<?php echo $analisis_master['deskripsi']?>
</textarea>
</td>
</tr> 
</table>
</div>

<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?php echo site_url()?>analisis_master" class="uibutton icon prev">Kembali</a>
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