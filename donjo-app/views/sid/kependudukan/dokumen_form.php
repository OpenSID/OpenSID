<script src="<?= base_url()?>assets/js/validasi.js"></script>
<script>
$('#file_browser').click(function(e)
{
    e.preventDefault();
    $('#file').click();
});

$('#file').change(function()
{
    $('#file_path').val($(this).val());
});

$('#file_path').click(function()
{
    $('#file_browser').click();
});
</script>
<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<label for="nama">Nama / Jenis Dokumen</label>
							<input id="nama" name="nama" class="form-control input-sm required" type="text" placeholder="Nama Dokumen" value="<?= $dokumen['nama']?>"></input>	<input type="hidden" name="id_pend" value="<?= $penduduk['id']?>"/>
						</div>
						<div class="form-group">
							<label for="file" >Pilih File:</label>
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" id="file_path" name="satuan">
								<input type="file" class="hidden" id="file" name="satuan">
								<input type="hidden" name="old_file" value="<?= $dokumen['satuan']?>">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
								</span>
							</div>
							<p class="help-block">Kosongkan jika tidak ingin mengubah dokumen.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
	</div>
</form>
