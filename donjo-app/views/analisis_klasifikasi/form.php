<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 

<div class="content-header">

</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Form Data Klasifikasi - <a href="<?php echo site_url()?>analisis_master/menu/<?php echo $_SESSION['analisis_master']?>"><?php echo $analisis_master['nama']?></a></h3>
</div>
<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th>Klasifikasi</th>
<td><input name="nama" type="text" class="inputbox" size="100" value="<?php echo $analisis_klasifikasi['nama']?>"/></td>
</tr>
<tr>
<th>Nilai Minimal</th>
<td><input name="minval" type="text" class="inputbox" size="10" value="<?php echo $analisis_klasifikasi['minval']?>"/></td>
</tr>
<tr>
<th>Nilai Maksimal</th>
<td><input name="maxval" type="text" class="inputbox" size="10" value="<?php echo $analisis_klasifikasi['maxval']?>"/></td>
</tr>
</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?php echo site_url()?>analisis_klasifikasi" class="uibutton icon prev">Kembali</a>
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
