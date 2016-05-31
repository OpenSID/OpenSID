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
<th>Form Master Analisis</th>
<td>
<li>Data yang dibutuhkan untuk Import dengan memenuhi aturan data<a href="<?php echo base_url()?>assets/import/analisis.xls"> sebagai berikut</a><br>
<li>Contoh urutan format dapat dilihat pada <a href="<?php echo base_url()?>assets/import/ppls2.xls">tautan berikut</a><br></td>
</tr>
<tr>
<th>File Master Analisis</th>
<td><input name="userfile" type="file" /></td>
</tr>
</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?php echo site_url()?>analisis_master" class="uibutton icon prev">Kembali</a>
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
