			<div class="box-header with-border">
				<div class="col-md-10">
					<label>Tahun Anggaran: </label>
					<select name="tahun_anggaran" id="tahun_anggaran" onchange="setData();">
						<option value="">Pilih Tahun</option>
						<?php foreach ($tahun_anggaran as $tahun) :?>
							<option value="<?= $tahun ?>" <?php selected($tahun, $this->session->set_tahun)?>><?= $tahun ?></option>
						<?php endforeach ?>
					</select>

				</div>
			</div>
			<div class="col-md-3">
				<?php $this->load->view('keuangan/menu_laporan_manual');?>
			</div>

<script type="text/javascript">
	function setData()
	{
		var tahun = $('#tahun_anggaran').val();
		var semester = $('#semester').val();
		$.ajax({
			type  : 'GET',
			url   : '<?= site_url('keuangan_manual/setdata_laporan/')?>' + tahun + "/" + semester,
			dataType : 'json'
    }).then(function() {
			location.reload();
	  });
	}
</script>
