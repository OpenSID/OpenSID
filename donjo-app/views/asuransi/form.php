	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<form id="mainform" name="mainform" action="<?= $form_action?>" method="POST" enctype="multipart/form-data">
							<div class='form-group'>
								<label for="nama_asuransi">Nama Asuransi </label>
								<input id="nama_asuransi" name="nama_asuransi" class="form-control input-sm" type="text" maxlength="50" placeholder="Nama Asuransi" value="<?php echo $asuransi ?>"></input>
								<input type="hidden" name="id_asuransi" value="<?= $this->uri->segment(3) ?>">
								<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss='modal' aria-hidden='true'><i class="fa fa-times"></i> Batal</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>