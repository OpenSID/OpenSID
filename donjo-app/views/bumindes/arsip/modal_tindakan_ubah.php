<form id="main_arsip" action="<?= site_url($this->controller."/ubah_dokumen/$tabel/$id_doc/$page/$o"); ?>" method="POST" class="form-horizontal">
	<div class="modal-body">
		<div class="form-group" id="form_ubah_arsip">
			<label class="col-sm-12 col-md-3" for="nama">Masukkan Lokasi Arsip</label>
			<div class="col-sm-12 col-md-4">
				<input id="lokasi_arsip" name="lokasi_arsip" class="form-control input-sm" type="text" value="<?=$value?>">
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
	</div>
</form>
<script>
	$(document).ready(function() {
		$("#main_arsip").validate();
	})
</script>
