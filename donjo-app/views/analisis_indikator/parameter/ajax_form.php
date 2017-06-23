<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/validasi.js"></script>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<table class="form">
<tr>
<th>Kode</th>
<td><input name="kode_jawaban" type="text" class="inputbox" size="5" value="<?php echo $analisis_parameter['kode_jawaban']?>"/></td>
</tr>
<tr>
<th>Jawaban</th>
<td><textarea name="jawaban" class="required" style="resize:none;width:300px;height:30px;"><?php echo $analisis_parameter['jawaban']?></textarea></td>
</tr>
<tr>
<th>Nilai</th>
<td><input name="nilai" type="text" class="inputbox" size="10" value="<?php echo $analisis_parameter['nilai']?>"/></td>
</tr>
</tr> 
</table>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
 <div class="uibutton-group">
 <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
 </div>
</div>
</form>