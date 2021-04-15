<?php $this->load->view('global/validasi_form'); ?>
<form action="<?= site_url('database/proses_sinkronkan')?>" method="post" id="validasi" enctype="multipart/form-data">
	<div class="modal-body">
		<div class="alert alert-info">
			<p>Data penduduk, keluarga, dll yang akan disinkronkan perlu disiapkan dalam format .csv dan kemudian dibungkus dalam format .zip. Informasi lebih lanjut dapat ditemukan di panduan.</p>
			<p>Proses sinkronkan ini akan mengacak data penduduk dan data keluarga untuk menjaga privasi data.</p>
		</div>

		<div class="form-group">
			<label for="file"  class="control-label">Berkas Data Untuk Disinkronkan :</label>
			<div class="input-group input-group-sm">
				<input type="text" class="form-control" id="file_path">
				<input type="file" class="hidden" id="file" name="sinkronkan">
				<span class="input-group-btn">
					<button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
				</span>
			</div>
			<p class="help-block small">Pastikan format berkas .zip berisi data dalam format .csv</p>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
	</div>
</form>
