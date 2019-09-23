<div class="col-md-3">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Jenis Dokumen</h3>
			<div class="box-tools">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body no-padding">
			<ul class="nav nav-pills nav-stacked">
				<?php foreach($submenu as $k => $s): ?>
				<li <?php if ($_SESSION['submenu'] == $s): ?>class="active"<?php endif; ?>><a href="<?= site_url('dokumen_sekretariat/produk_hukum/'.$k)?>"><?= $s ?></a></li>
				<?php endforeach;?>
			</ul>
		</div>
	</div>
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Tambah Kategori</h3>
			<div class="box-tools">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		
		<div class="box-body">
		<form method="POST" action="#" id="form_kategori">	
			<div class="form-group">
				<label class="control-label col-sm-12" for="kategori_dokumen">Nama Kategori</label>
				<div class="col-sm-12">
					<input type="text" name="kategori_dokumen" id="kategori_dokumen" class="form-control input-sm" required>
				</div>				
			</div>	
		</form>	
		</div>		
		<div class="box-footer">
			<div class="col-sm-12">
				<button type="submit" id="simpan_kategori" onclick="save()" class="btn btn-social btn-flat btn-info btn-sm"><i class='fa fa-check'></i> Simpan</button>
			</div>
		</div>				
	</div>
</div>

<script type="text/javascript">
	function save() {
		var url = "<?= base_url('index.php/dokumen_sekretariat/tambah_kategori') ?>";
		var form = $('#form_kategori');
		var kategori = $('#kategori_dokumen').val();

		$.ajax({
			url: url,
			type: 'POST',
			data: {kategori: kategori},
			dataType: 'json',
			success: function(data){
				window.location.replace("<?= site_url('dokumen_sekretariat/produk_hukum') ?>");
			},
			error: function (err, jxqhr, thrownError) {
				alert(thrownError);
				console.log(err);
			}
		})
	}
</script>