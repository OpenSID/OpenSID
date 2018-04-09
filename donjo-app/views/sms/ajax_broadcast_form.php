<script>
function DusSel(str){
	if (str==""){
	  document.getElementById("RW").innerHTML="";
	  return;
	  }
	if (window.XMLHttpRequest){
		xmlhttp=new XMLHttpRequest();
	  }else{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  xmlhttp.onreadystatechange=function(){
	  if (xmlhttp.readyState==4 && xmlhttp.status==200){
	     document.getElementById("RW").innerHTML=xmlhttp.responseText;
	    }
	  }
	xmlhttp.open("GET","ajax_penduduk_rw/"+str,true);
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
	xmlhttp.open("GET","ajax_penduduk_rt/"+dusun+"/"+str,true);
	xmlhttp.send();
}
</script>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<input type="hidden" name="rt" value="">
<table>

<tr>
	<td>Umur</td><td><input class="inputbox2" name="umur_min1" type="text" size="5" > - <input class="inputbox2" name="umur_max1" type="text" size="5"></td>
</tr> 
<tr>
	<td>Dusun</td>
	<td><select name="dusun1" onchange="DusSel(this.value)">
	<option value="">Pilih Dusun&nbsp;</option>
	<?php foreach($dusun as $data){?>
		<option value="<?php echo $data['dusun']?>"><?php echo ununderscore($data['dusun'])?></option>
	<?php }?></select>
	</td>
</tr>
<tr id="RW"></tr>
<tr id="RT"></tr>	
<tr><td>Jenis Kelamin</td>
	 <td>
     <select name="sex1" >
      <option value=""> -- </option>
	  <option value="1">LAKI-LAKI</option>
	  <option value="2">PEREMPUAN</option>
	  </select>
	</td>
</tr>	

<tr><td>Pekerjaan</td>
    <td><select name="pekerjaan1">
      <option value=""> -- </option>
	  <?php foreach($pekerjaan AS $data){?>
		<option value="<?php echo $data['id']?>"><?php echo $data['nama']?></option>
	  <?php }?>
	</select>
     </td>
</tr>    

<tr><td>Status Perkawinan</td><td>
    <select name="status1">
      <option value=""> -- </option><option value="1">BELUM KAWIN</option><option value="2">KAWIN</option><option value="3">CERAI HIDUP</option><option value="4">CERAI MATI</option><option value="5">TIDAK KAWIN</option>
	</select> </td>
</tr>
<tr><td>Agama</td><td>
    <select name="agama1">
    <option value=""> -- </option>
	<?php foreach($agama AS $data){?>
		<option value="<?php echo $data['id']?>"><?php echo $data['nama']?></option>
	<?php }?>
    </select>
	</td>
</tr>  

<tr><td>Pendidikan Terakhir</td>
    <td>
	<select name="pendidikan1">
      <option value=""> -- </option>
		<?php foreach($pendidikan AS $data){?>
			<option value="<?php echo $data['id']?>"><?php echo $data['nama']?></option>
		<?php }?>
	  </select>
  </td>
</tr>

<tr><td>Status Penduduk</td>
    <td><select name="status_penduduk1">
      <option value=""> -- </option><option value="1">AKTIF</option><option value="2">TIDAK AKTIF</option>     </select>
  </td>
</tr>

<tr><td>Group Kontak</td>
    <td>
	<select name="grup1">
      <option value=""> -- </option>
		<?php foreach($grup AS $data){?>
			<option value="<?php echo $data['nama_grup']?>"><?php echo $data['nama_grup']?></option>
		<?php }?>
	  </select>
  </td>
</tr>

	<tr>
		<td width="100">Isi Pesan</td><td><textarea name="TextDecoded1" class=" required" style="resize: none; height:100px; width:250px;" size="300" maxlength='160'></textarea></td>
	</tr>
</table>

<div class="buttonpane" style="text-align: right;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
        <button class="uibutton confirm" type="submit"><span class="fa fa-paper-plane"></span> Kirim</button>
    </div>
</div>
</form>
