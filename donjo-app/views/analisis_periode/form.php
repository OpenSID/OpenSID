<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 
<div class="content-header">
</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Form Data Periode - <a href="<?php echo site_url()?>analisis_master/menu/<?php echo $_SESSION['analisis_master']?>"><?php echo $analisis_master['nama']?></a></h3>
</div>
<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th>Nama Periode</th>
<td><input name="nama" type="text" class="inputbox" size="50" value="<?php echo $analisis_periode['nama']?>"/></td>
</tr>
<tr>
<th>Periode Aktif</th>
<td>
<div class="uiradio">
<?php $ch='checked';?>
<input type="radio" id="a1" name="aktif" value="1"/<?php if($analisis_periode['aktif'] == '1' OR $analisis_periode['aktif'] == ''){echo $ch;}?>><label for="a1">Aktif</label>
<input type="radio" id="a2" name="aktif" value="2"/<?php if($analisis_periode['aktif'] == '2'){echo $ch;}?>><label for="a2">Tidak Aktif</label>
</div>
</td>
</tr> 
<tr>
<th>Tahap Pendataan</th>
<td>
<div class="uiradio">
<?php $ch='checked';?>
<input type="radio" id="g1" name="id_state" value="1"/<?php if($analisis_periode['id_state'] == '1' OR $analisis_periode['id_state'] == ''){echo $ch;}?>><label for="g1">Belum Pendataan / Input</label>
<input type="radio" id="g2" name="id_state" value="2"/<?php if($analisis_periode['id_state'] == '2'){echo $ch;}?>><label for="g2">Sedang Pendataan / Input</label>
<input type="radio" id="g3" name="id_state" value="3"/<?php if($analisis_periode['id_state'] == '3'){echo $ch;}?>><label for="g3">Selesai Pelaksanaan</label>
</div>
</td>
</tr> 
<tr>
<th>Tahun Pelaksanaan</th>
<td><input name="tahun_pelaksanaan" type="text" class="inputbox" size="4" value="<?php echo $analisis_periode['tahun_pelaksanaan']?>"/></td>
</tr>
<?php if($analisis_periode == null){?>
<tr>
<th>Duplikat data pendataan sebelumnya</th>
<td>
<div class="uiradio">
<input type="radio" id="x1" name="duplikasi" value="1"><label for="x1"> Ya </label>
<input type="radio" id="x2" name="duplikasi" value="0" checked><label for="x2"> Tidak </label>
</div>
</td>
</tr> 
<?php } ?>

<tr>
<th>Keterangan</th>
<td><textarea name="keterangan" style="resize:none;width:500px;height:40px;"/><?php echo $analisis_periode['keterangan']?></textarea></td>
</tr>
<tr>
</table>
</div>
 
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?php echo site_url()?>analisis_periode" class="uibutton icon prev">Kembali</a>
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