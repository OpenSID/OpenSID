<script>
function DusSel(str){
if (str==""){
  document.getElementById("RW").innerHTML="";
  return;
  }if (window.XMLHttpRequest){
	xmlhttp=new XMLHttpRequest();
  }else{
	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function(){
  if (xmlhttp.readyState==4 && xmlhttp.status==200){
     document.getElementById("RW").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","sid_penduduk/ajax_penduduk_pindah_rw/"+str,true);
xmlhttp.send();
}

function RWSel(dusun,str){
if (str==""){
  document.getElementById("RT").innerHTML="";
  return;
  }if (window.XMLHttpRequest){
	xmlhttp=new XMLHttpRequest();
  }else{
	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function(){
  if (xmlhttp.readyState==4 && xmlhttp.status==200){
     document.getElementById("RT").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","sid_penduduk/ajax_penduduk_pindah_rt/"+dusun+"/"+str,true);
xmlhttp.send();
}
</script>
<form method="post" action="<?php echo $form_action?>" >
<input type="hidden" name="rt" value="">
<table>
<tr>
	<td>Nama</td><td><input class="inputbox2" name="cari" type="text" size="40"></td>
</tr>
<tr>
	<td>Umur</td><td><input class="inputbox2" name="umur_min" type="text" size="5" > - <input class="inputbox2" name="umur_max" type="text" size="5"></td>
</tr> 
<tr>
	<td>Dusun</td>
	<td><select name="dusun" onchange="DusSel(this.value)">
	<option value="">Pilih Dusun&nbsp;</option>
	<?php foreach($dusun as $data){?>
		<option><?php echo $data['dusun']?></option>
	<?php }?></select>
	</td>
</tr>
<tr id="RW"></tr>
<tr id="RT"></tr>	
<tr><td>Jenis Kelamin</td>
	 <td>
     <select name="sex" >
      <option value=""> -- </option>
	  <option value="1">LAKI-LAKI</option>
	  <option value="2">PEREMPUAN</option>
	  </select>
	</td>
</tr>	

<tr><td>Pekerjaan</td>
    <td><select name="pekerjaan_id">
      <option value=""> -- </option>
	  <?php foreach($pekerjaan AS $data){?>
		<option value="<?php echo $data['id']?>"><?php echo $data['nama']?></option>
	  <?php }?>
	</select>
     </td>
</tr>    

<tr><td>Status Perkawinan</td><td>
    <select name="status">
      <option value=""> -- </option><option value="1">BELUM KAWIN</option><option value="2">KAWIN</option><option value="3">CERAI HIDUP</option><option value="4">CERAI MATI</option><option value="5">TIDAK KAWIN</option>
	</select> </td>
</tr>
<tr><td>Agama</td><td>
    <select name="agama">
    <option value=""> -- </option>
	<?php foreach($agama AS $data){?>
		<option value="<?php echo $data['id']?>"><?php echo $data['nama']?></option>
	<?php }?>
    </select>
	</td>
</tr>  
<tr><td>Pendidikan Terakhir</td>
    <td>
	<select name="pendidikan_id">
      <option value=""> -- </option>
		<?php foreach($pendidikan AS $data){?>
			<option value="<?php echo $data['id']?>"><?php echo $data['nama']?></option>
		<?php }?>
	  </select>
  </td>
</tr>
<tr><td>Status Penduduk</td>
    <td><select name="status_penduduk">
      <option value=""> -- </option><option value="1">AKTIF</option><option value="2">TIDAK AKTIF</option>     </select>
  </td>
</tr>
</table>

<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
        <button class="uibutton confirm" type="submit"><span class="fa fa-search"></span> Cari</button>
    </div>
</div>
</form>