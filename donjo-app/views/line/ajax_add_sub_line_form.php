<form id="validasi" action="<?= $form_action; ?>" method="POST">
	<div class='modal-body'>
		<div class="form-group">
			<label class="control-label">Nama Jenis Garis</label>
			<input name="nama" class="form-control input-sm nomor_sk required" maxlength="100" type="text" placeholder="Nama Jenis Garis" value="<?= $line['nama']?>"></input>
		</div>
		<div class="form-group">
			<label class="control-label">Jenis</label>
			<select class="form-control input-sm required" id="jenis" name="jenis" >
				<option value="solid" <?= selected($line['jenis'], 'solid'); ?>>Solid</option>
				<option value="dotted" <?= selected($line['jenis'], 'dotted'); ?>>Dotted</option>
				<option value="dashed" <?= selected($line['jenis'], 'dashed'); ?>>Dashed</option>
			</select>
		</div>
		<div class="form-group">
			<label class="control-label">Warna Garis</label>
			<div class="input-group my-colorpicker2">
				<input type="text" id="color" name="color" class="form-control input-sm warna required" placeholder="#FFFFFF" value="<?= $line['color']?>">
				<div class="input-group-addon input-sm">
					<i></i>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">Tebal Garis</label>
			<input name="tebal" class="form-control input-sm nomor_sk required" id="tebal" type="number" value="<?= $line['tebal'] ?? 3 ?>" min="1" max="10"></input>
		</div>
		<div class="form-group">
			<br/>
			<p id="showline"></p>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
	</div>
</form>
<?php $this->load->view('global/validasi_form'); ?>
<script type="text/javascript">
	$("#showline").hide();
	var j = document.getElementById("jenis");
	var t = document.getElementById("tebal");
	var c = document.getElementById("color");

	function show() {
		var isij = document.forms[0].jenis.value;
		var isit = document.forms[0].tebal.value;
		var isic = document.forms[0].color.value;
		$('#showline').css({
			'display': 'block',
			'border-bottom': isit + 'px ' + isij + ' ' + isic
		});
	}
	j.onchange = show;
	t.onkeyup = show;
	t.onclick = show;
	c.onchange = show;
	show();
</script>