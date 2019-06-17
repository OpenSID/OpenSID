<td>RW</td>
<td><select name="rw" onchange="RWSel('<?php echo $dusun?>',this.value)" class="required">
<option>Pilih RW&nbsp;</option>
<?php foreach($rw as $data){?>
<option><?php echo $data['rw']?></option>
<?php }?></select>
</td>
