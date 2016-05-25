<div id="pageC">

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
<th>Kode Surat</th>
<td><input name="kode_surat" type="text" class="inputbox number" size="5" value="<?php echo $surat_master['kode_surat']?>"/></td>
</tr>
<tr>
<th>Nama Surat</th>
<td><input name="nama" type="text" class="inputbox number" size="50" value="<?php echo $surat_master['nama']?>"/></td>
</tr> 
<tr>
<th>URL</th>
<td><input name="url_surat" type="text" class="inputbox number" size="40" value="<?php echo $surat_master['url_surat']?>"/></td>
</tr>
</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?php echo site_url()?>surat_master" class="uibutton icon prev">Kembali</a>
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
