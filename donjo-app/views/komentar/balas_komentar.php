<form action="<?= $form_action?>" method="post">
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label" for="komentar">Isi Komentar </label>
                    <textarea name="komentar" class="form-control input-sm required" placeholder="Isi Komentar..."><?= $komentar['komentar']?></textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm pull-left'> <i class='fa fa-times'></i> Batal</button>
		<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>