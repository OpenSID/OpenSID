<form method="post" action="<?php echo $form_action?>" >
<input type="hidden" name="rt" value="">
<table>
<tr>
	<td>Umur</td><td><input class="inputbox2" name="umur_min" type="text" size="5" value="<?php echo $umur_min?>"> - <input class="inputbox2" name="umur_max" type="text" size="5" value="<?php echo $umur_max?>"></td>
</tr> 
<tr><td>Pekerjaan</td>
 <td><select name="pekerjaan_id">
 <option value=""> -- </option>
	 <?php foreach($pekerjaan AS $data){?>
		<option value="<?php echo $data['id']?>" <?php selected($pekerjaan_id,$data['id']); ?> ><?php echo $data['nama']?></option>
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
	<?php foreach($list_agama AS $data){?>
		<option value="<?php echo $data['id']?>" <?php selected($agama,$data['id']); ?> ><?php echo $data['nama']?></option>
	<?php }?>
 </select>
	</td>
</tr> 
<tr><td>Pendidikan Sedang</td>
 <td>
	<select name="pendidikan_sedang_id">
 <option value=""> -- </option>
		<?php foreach($pendidikan AS $data){?>
			<option value="<?php echo $data['id']?>" <?php selected($pendidikan_sedang_id,$data['id']); ?> ><?php echo $data['nama']?></option>
		<?php }?>
	 </select>
 </td>
</tr>
<tr><td>Pendidikan KK</td>
 <td>
	<select name="pendidikan_kk_id">
 <option value=""> -- </option>
		<?php foreach($pendidikan_kk AS $data){?>
			<option value="<?php echo $data['id']?>" <?php selected($pendidikan_kk_id,$data['id']); ?> ><?php echo $data['nama']?></option>
		<?php }?>
	 </select>
 </td>
</tr>
<tr><td>Status Hubungan Dalam Keluarga</td>
 <td>
	<select name="hubungan">
 <option value=""> -- </option>
		<?php foreach($list_hubungan AS $data){?>
			<option value="<?php echo $data['id']?>" <?php selected($hubungan,$data['id']); ?> ><?php echo $data['nama']?></option>
		<?php }?>
	 </select>
 </td>
</tr>
<tr><td>Golongan Darah</td>
 <td>
	<select name="golongan_darah">
 <option value=""> -- </option>
		<?php foreach($list_golongan_darah AS $data){?>
			<option value="<?php echo $data['id']?>" <?php selected($golongan_darah,$data['id']); ?> ><?php echo $data['nama']?></option>
		<?php }?>
	 </select>
 </td>
</tr>
<tr><td>Cacat / Difable</td>
 <td>
	<select name="cacat">
 <option value=""> -- </option>
		<?php foreach($list_cacat AS $data){?>
			<option value="<?php echo $data['id']?>" <?php selected($cacat,$data['id']); ?> ><?php echo $data['nama']?></option>
		<?php }?>
	 </select>
 </td>
</tr>
<tr><td>Status Penduduk</td>
 <td><select name="status_penduduk">
 <option value=""> -- </option><option value="1">AKTIF</option><option value="2">TIDAK AKTIF</option> </select>
 </td>
</tr>
</table>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
 <div class="uibutton-group">
 <button class="uibutton" type="button" onclick="$('#window').dialog('close');">Close</button>
 <button class="uibutton confirm" type="submit">Search</button>
 </div>
</div>
</form>