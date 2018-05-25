<style type="text/css">
	table#dokumen th, table#dokumen td {
		text-align: left;
		white-space: nowrap;
	}
	.bawah{
		position:absolute;
		bottom:10px;
		right:10px;
		width:430px;
	}
</style>
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
<script type="text/javascript">
	$("#validasi").submit(function() {
	  $(this).closest(".ui-dialog-content").dialog("close");
	});
</script>
<form action="<?= $form_action?>" method="POST" id="validasi">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
	<table id="dokumen" width="100%" style="margin-bottom: 20px;">
		<tr>
			<th width="100">Tahun Laporan</td>
			<td>
        <select name="tahun" class="required">
            <option value="">Pilih Tahun Laporan</option>
            <?php foreach($tahun_laporan as $tahun): ?>
              <option value="<?= $tahun['tahun']?>"><?= $tahun['tahun']?></option>
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
            <option value="<?= $data['pamong_nama']?>" data-jabatan="<?= trim($data['jabatan'])?>" <?php if(strpos(strtolower($data['jabatan']),'sekretaris')!==false) echo 'selected'; ?>><?= $data['pamong_nama']?>(<?= $data['jabatan']?>)</option>
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
            <option value="<?= $data['pamong_nama']?>" data-jabatan="<?= trim($data['jabatan'])?>" <?php if(strpos(strtolower($data['jabatan']),'kepala')!==false and strpos(strtolower($data['jabatan']),'dusun')===false) echo 'selected'; ?>><?= $data['pamong_nama']?>(<?= $data['jabatan']?>)</option>
          <?php }?>
        </select>
			</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
	</table>
</div>
<div class="ui-layout-south panel bottom bawah">
	<div class="right">
    <div class="uibutton-group">
    <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
		<button class="uibutton confirm" type="submit">
			<?php if (strpos($form_action, '/cetak') !== false): ?>
				<span class="fa fa-print"></span> Cetak
			<?php else: ?>
				<span class="fa fa-download"></span> Unduh
			<?php endif; ?>
		</button>
		</div>
	</div>
</div>
</form>
