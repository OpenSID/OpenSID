<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript">
	$('document').ready(function(){
		$('select[name=pamong_ttd]').change(function(e) {
			$('input[name=jabatan_ttd]').val($(this).find(':selected').data('jabatan'));
		});
		$('select[name=pamong_ketahui]').change(function(e) {
			$('input[name=jabatan_ketahui]').val($(this).find(':selected').data('jabatan'));
		});
		$('select[name=pamong_ttd]').trigger('change');
		$('select[name=pamong_ketahui]').trigger('change');
	});
</script>
<style type="text/css">
	table#inventaris th, table#inventaris td {
		text-align: left;
		white-space: nowrap;
	}
</style>
<form action="<?php echo $form_action?>" target="_blank" method="post" id="validasi">
	<table id="inventaris" style="width:100%" class="">
		<tr>
			<th>Tahun :</th>
			<td>
        <select name="tahun" class="required">
            <option value="">Pilih Tahun Mutasi</option>
            <?php foreach($tahun_mutasi as $tahun): ?>
              <option value="<?php echo $tahun['tahun']?>" <?php if($tahun['tahun'] == date('Y')) echo 'selected'; ?>><?php echo $tahun['tahun']?></option>
            <?php endforeach; ?>
        </select>
			</td>
		</tr>
		<tr>
			<th>Pamong tertanda :</th>
			<td>
				<input name="jabatan_ttd" type="hidden">
        <select name="pamong_ttd"  class="inputbox">
          <option value="">Pilih Staf Penandatangan</option>
          <?php foreach($pamong AS $data){?>
            <option value="<?php echo $data['pamong_nama']?>" data-jabatan="<?php echo trim($data['jabatan'])?>" <?php if(strpos(strtolower($data['jabatan']),'sekretaris')!==false) echo 'selected'; ?>><?php echo $data['pamong_nama']?>(<?php echo $data['jabatan']?>)</option>
          <?php }?>
        </select>
			</td>
		</tr>
		<tr>
			<th>Pamong mengetahui :</th>
			<td>
				<input name="jabatan_ketahui" type="hidden">
        <select name="pamong_ketahui"  class="inputbox">
          <option value="">Pilih Staf Mengetahui</option>
          <?php foreach($pamong AS $data){?>
            <option value="<?php echo $data['pamong_nama']?>" data-jabatan="<?php echo trim($data['jabatan'])?>" <?php if(strpos(strtolower($data['jabatan']),'kepala')!==false and strpos(strtolower($data['jabatan']),'dusun')===false) echo 'selected'; ?>><?php echo $data['pamong_nama']?>(<?php echo $data['jabatan']?>)</option>
          <?php }?>
        </select>
			</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
	</table>
	<div class="buttonpane" style="float: right;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window ').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
      <button class="uibutton confirm" type="submit" onclick="$('#window').dialog('close');"><span class="fa fa-print"></span> <?php echo $aksi?></button>
    </div>
	</div>
</form>

