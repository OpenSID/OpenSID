<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 

<div class="content-header">

</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Form Data Parameter</h3>
<p> &nbsp; Pertanyaan : <?php echo $analisis_indikator['pertanyaan']?></p>
</div>
<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th>Jawaban</th>
<td><textarea name="jawaban" style="resize:none;width:500px;height:40px;"/><?php echo $analisis_parameter['jawaban']?></textarea></td>
</tr>
<tr>
<th>Nilai</th>
<td><input name="nilai" type="text" class="inputbox" size="10" value="<?php echo $analisis_parameter['nilai']?>"/></td>
</tr>
</tr>  
</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?php echo site_url()?>analisis_indikator/parameter/<?php echo $analisis_indikator['id'];?>" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
<button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
