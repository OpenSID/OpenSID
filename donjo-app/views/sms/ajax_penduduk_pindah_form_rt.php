		<td>RT</td>
		<td><select name="rt">
		<option value="">Pilih RT&nbsp;</option>
		<?foreach($rt as $data){?>
			<option value="<?=$data['id']?>"><?=$data['rt']?></option>
		<?}?></select>
		</td>