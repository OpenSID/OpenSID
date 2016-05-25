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
	xmlhttp.open("GET","penduduk/ajax_penduduk_pindah_rw/"+str,true);
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
	xmlhttp.open("GET","penduduk/ajax_penduduk_pindah_rt/"+dusun+"/"+str,true);
	xmlhttp.send();
}
</script>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<input type="hidden" name="rt" value="">
<table>


<tr>
	<td>Dusun</td>
	<td><select name="dusun1" onchange="DusSel(this.value)">
	<option value="">Pilih Dusun&nbsp;</option>
	<?php foreach($dusun as $data){?>
		<option value="<?php echo ($data['dusun'])?>"><?php echo ununderscore(unpenetration($data['dusun']))?></option>
	<?php }?></select>
	</td>
</tr>
<tr id="rw"></tr>
<tr id="rt"></tr>	

	
</table>

<div class="buttonpane" style="text-align: right;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');">Tutup</button>
        <button class="uibutton confirm" type="submit">Pindah</button>
    </div>
</div>
</form>
