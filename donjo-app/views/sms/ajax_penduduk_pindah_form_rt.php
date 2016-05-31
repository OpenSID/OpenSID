		<td>RT</td>
		<td><select name="rt">
		<option value="">Pilih RT&nbsp;</option>
		<?php foreach($rt as $data){?>
			<option value="<?php echo $data['id']?>"><?php echo $data['rt']?></option>
		<?php }?></select>
		</td>