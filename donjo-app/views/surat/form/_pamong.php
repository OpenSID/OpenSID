<tr>
	<th>Staf Pemerintah <?php echo ucwords($this->setting->sebutan_desa)?></th>
	<td>
		<select name="pamong" class="inputbox required" onchange="var nip=$(this).find(':selected').data('nip'); $('#pamong_nip').val(nip);$(this).closest('table').find('select[name=jabatan]').val($(this).find(':selected').data('jabatan')) ">
			<option value="">Pilih Staf Pemerintah <?php echo ucwords($this->setting->sebutan_desa)?></option>
			<?php foreach($pamong AS $data){?>
				<?php $tmp_nip = trim($data['pamong_nip'],'-'); ?>
				<option value="<?php echo $data['pamong_nama']?>" data-jabatan="<?php echo trim($data['jabatan']) ?>" <?php if($data['pamong_ttd']==1) { $pamong_nip = $data['pamong_nip']; echo "selected"; }?> data-nip="<?php echo $data['pamong_nip']?>">
					<?php echo $data['pamong_nama']?> (<?php echo $data['jabatan']?>) <?php if (!empty($tmp_nip)) echo "NIP: ".$data['pamong_nip']?>
				</option>
			<?php }?>
		</select>
		<input name="pamong_nip" id="pamong_nip" type="hidden" value="<?php echo $pamong_nip; ?>"/>
	</td>
</tr>
<tr>
	<th>Sebagai</th>
	<td>
		<select name="jabatan"  class="inputbox required">
			<option value="">Pilih Jabatan</option>
			<?php foreach($pamong AS $data){?>
				<option <?php if($data['pamong_ttd']==1) echo "selected"?>><?php echo trim($data['jabatan']) ?></option>
			<?php }?>
		</select>
	</td>
</tr>
