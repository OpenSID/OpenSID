<form id="main_arsip" action="<?= site_url($this->controller."/hapus_dokumen/$tabel/$id_doc/$page"); ?>" method="POST" class="form-horizontal">
	<div class="modal-body">
		<div class="form-group" id="form_ubah_arsip">
			<label class="col-sm-12" for="nama">Anda yakin ingin menghapus dokumen ini?</label>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class="fa fa-check"></i> Ya</button>
	</div>
</form>
