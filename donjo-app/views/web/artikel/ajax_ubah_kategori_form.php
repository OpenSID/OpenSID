<form action="<?php echo $form_action?>" method="post" id="validasi">
	<table style="width:100%">
		<tr>
			<th style="white-space:nowrap; padding-right: 15px;">Nama Kategori</th>
			<td>
				<select name="kategori">
					<option value="">Kategori</option>
					<?php  foreach($list_kategori AS $kategori){?>
						<option
							<?php  if($kategori_sekarang['id_kategori']==$kategori['id']) :?>selected<?php  endif?> value="<?php  echo $kategori['id']?>"><?php  echo $kategori['judul']?>
						</option>
					<?php  }?>
				</select>
			</td>
		</tr>
	</table>
	<div class="buttonpane" style="text-align: right; width: 95%; position:absolute;bottom:0px;">
	    <div class="uibutton-group">
	        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
	        <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
	    </div>
	</div>
</form>
