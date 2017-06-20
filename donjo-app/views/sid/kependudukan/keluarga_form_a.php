<style>
table.form.detail th{
 padding:5px;
 background:#fafafa;
 border-right:1px solid #eee;
}
table.form.detail td{
 padding:5px;
}
</style>

<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;">

<div class="content-header">
<h3>Form Manajemen KK</h3>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="<?php echo $form_action?>" method="post" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">

<table class="form">
<input name="id_kk" type="hidden" value="<?php echo $id_kk?>">
<input name="kk_level" type="hidden" value="0">
<input name="id_cluster" type="hidden" value="<?php echo $kk['id_cluster']?>">
<tr>
<th width="150">No. KK</th>
<td><?php echo $kk['no_kk']?></td>
</tr>

<tr>
<th>Kepala KK</th>
<td><?php echo unpenetration($kk['nama'])?></td>
</tr>

<tr>
  <th>Alamat</th>
  <td><?php echo $kk['alamat']?></td>
</tr>
<tr>
<th>Dusun</th>
<td><?php echo ununderscore(unpenetration($kk['dusun']))?></td>
</tr>

<tr>
<th>RW</th>
<td><?php echo $kk['rw']?></td>
</tr>

<tr>
<th>RT</th>
<td><?php echo $kk['rt']?></td>
</tr>

<?php include("donjo-app/views/sid/kependudukan/penduduk_form_isian.php"); ?>

</table>
</div>

<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>keluarga" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
<button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
</div>
</div>
</div>
</form>
</div>
</td></tr>
</table>
</div>
