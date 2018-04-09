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
xmlhttp.open("GET","sms/ajax_penduduk_pindah/<?php echo $penduduk['id']?>/"+str,true);
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
xmlhttp.open("GET","sms/ajax_penduduk_pindah/<?php echo $penduduk['id']?>/"+dusun+"/"+str,true);
xmlhttp.send();
}
</script>
<form method="post" action="<?php echo $form_action?>" id="mainform" name="mainform" >
<input type="hidden" name="rt" value="">
<table class="form">
	<tr>
		<td width="60">Dusun</td>
		<td><select onchange="DusSel(this.value)" class="required">
		<option value="">Pilih Dusun&nbsp;</option>
		<?php foreach($dusun as $data){?>
			<option><?php echo $data['dusun']?></option>
		<?php }?></select>
		</td>
	</tr>
	<tr id="RW"></tr>
	<tr id="RT"></tr>
</table>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
        <button class="uibutton confirm" type="submit"><span class="fa fa-arrow-circle-right"></span> Pindah</button>
    </div>
</div>
</form>
