<div class="col-md-3">
	<div class="card card-outline card-info">
		<div class="card-body">
			<div class="row mb-2">
			<label class="control-label" for="tahun_anggaran">Tahun Anggaran: </label>
			<select name="tahun_anggaran" id="tahun_anggaran" class="form-control form-control-sm select2" onchange="setData();">
				<option value="">Pilih Tahun</option>
				<?php foreach ($tahun_anggaran as $tahun) :?>
					<option value="<?= $tahun ?>" <?php selected($tahun, $this->session->set_tahun)?>><?= $tahun ?></option>
				<?php endforeach ?>
			</select>
			</div>
		</div>
	</div>
	<?php $this->load->view('keuangan/menu_laporan');?>
</div>

<script type="text/javascript">
	function setData()
	{
		var tahun = $('#tahun_anggaran').val();
		var semester = $('#semester').val();
		$.ajax({
			type  : 'GET',
			url   : '<?= site_url('keuangan/setdata_laporan/')?>' + tahun + "/" + semester,
			dataType : 'json'
    }).then(function() {
			location.reload();
	  });
	}
</script>
