<script>
function DusSel(str){
	if (str==""){
	  document.getElementById("rw").innerHTML="";
	  return;
	  }
	if (window.XMLHttpRequest){
		xmlhttp=new XMLHttpRequest();
	  }else{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  xmlhttp.onreadystatechange=function(){
	  if (xmlhttp.readyState==4 && xmlhttp.status==200){
	     document.getElementById("rw").innerHTML=xmlhttp.responseText;
	    }
	  }
	xmlhttp.open("GET","<?php echo site_url()?>penduduk/ajax_penduduk_pindah_rw/"+str,true);
	xmlhttp.send();
}

function RWSel(dusun,str){
	if (str==""){
	  document.getElementById("rt").innerHTML="";
	  return;
	  }if (window.XMLHttpRequest){
		xmlhttp=new XMLHttpRequest();
	  }else{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  xmlhttp.onreadystatechange=function(){
	  if (xmlhttp.readyState==4 && xmlhttp.status==200){
	     document.getElementById("rt").innerHTML=xmlhttp.responseText;
	    }
	  }
	xmlhttp.open("GET","<?php echo site_url()?>penduduk/ajax_penduduk_pindah_rt/"+dusun+"/"+str,true);
	xmlhttp.send();
}
</script>
<?php if ($is_anggota_keluarga): ?>
	<strong>
		Penduduk ini anggota keluarga, bukan penduduk lepas, dan tidak bisa dipindahkan perorangan. <br>
		Keluarga penduduk ini dapat dipindahkan pada modul Keluarga.
	</strong>
<?php else: ?>
	<strong>Alamat Sekarang</strong>
	<table style="margin-bottom: 10px;">
		<tr>
		  <td><?php echo $alamat_wilayah?></td>
		</tr>
	</table>

	<form action="<?php echo $form_action?>" method="post" id="validasi">
	<input type="hidden" name="rt" value="">

	<strong>Alamat Baru</strong>
	<table>
		<tr>
			<td style="padding-right: 5px">Alamat</td>
		  <td>
		    <input name="alamat" type="text" class="inputbox" size="60" value="<?php echo $data['alamat']?>"/>
		  </td>
		</tr>

		<tr>
			<td>Dusun</td>
			<td><select name="dusun1" onchange="DusSel(this.value)">
			<option value="">Pilih Dusun&nbsp;</option>
			<?php foreach($dusun as $data){?>
				<?php ///$data['dusun']=myUrlEncode($data['dusun']);?>
				<option value="<?php echo ($data['dusun'])?>"><?php echo ununderscore(unpenetration($data['dusun']))?></option>
			<?php }?></select>
			</td>
		</tr>
		<tr id="rw"></tr>
		<tr id="rt"></tr>
	</table>

	<div class="buttonpane" style="text-align: right;">
	    <div class="uibutton-group">
	        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
	        <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
	    </div>
	</div>
	</form>
<?php endif; ?>
