<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 

<div class="content-header">

</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Form Data Periode - <a href="<?=site_url()?>analisis_master/menu/<?=$_SESSION['analisis_master']?>"><?=$analisis_master['nama']?></a></h3>
</div>
<form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th>Nama Periode</th>
<td><input name="nama" type="text" class="inputbox" size="50" value="<?=$analisis_periode['nama']?>"/></td>
</tr>
<tr>
<th>Periode Aktif</th>
<td>
<div class="uiradio">
<?$ch='checked';?>
<input type="radio" id="a1" name="aktif" value="1"/<?if($analisis_periode['aktif'] == '1' OR $analisis_periode['aktif'] == ''){echo $ch;}?>><label for="a1">Aktif</label>
<input type="radio" id="a2" name="aktif" value="2"/<?if($analisis_periode['aktif'] == '2'){echo $ch;}?>><label for="a2">Tidak Aktif</label>
</div>
</td>
</tr> 
<tr>
<th>Tahap Pendataan</th>
<td>
<div class="uiradio">
<?$ch='checked';?>
<input type="radio" id="g1" name="id_state" value="1"/<?if($analisis_periode['id_state'] == '1' OR $analisis_periode['id_state'] == ''){echo $ch;}?>><label for="g1">Belum Pendataan / Input</label>
<input type="radio" id="g2" name="id_state" value="2"/<?if($analisis_periode['id_state'] == '2'){echo $ch;}?>><label for="g2">Sedang Pendataan / Input</label>
<input type="radio" id="g3" name="id_state" value="3"/<?if($analisis_periode['id_state'] == '3'){echo $ch;}?>><label for="g3">Selesai Pelaksanaan</label>
</div>
</td>
</tr> 
<tr>
<th>Tahun Pelaksanaan</th>
<td><input name="tahun_pelaksanaan" type="text" class="inputbox" size="4" value="<?=$analisis_periode['tahun_pelaksanaan']?>"/></td>
</tr>
<tr>
<th>Keterangan</th>
<td><textarea name="keterangan" style="resize:none;width:500px;height:40px;"/><?=$analisis_periode['keterangan']?></textarea></td>
</tr>
<tr>
</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?=site_url()?>analisis_periode" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset">Clear</button>
<button class="uibutton confirm" type="submit" >Simpan</button>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
