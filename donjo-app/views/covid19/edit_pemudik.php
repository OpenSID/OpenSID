<?php $this->load->view('global/validasi_form'); ?>
<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<table class="table table-bordered table-striped table-hover" >
							<tbody>
								<tr>
									<td style="padding-top : 10px;padding-bottom : 10px; width:40%;" ><?= $judul_terdata_nama?></td>
									<td> : <?= $terdata_nama?></td>
								</tr>
								<tr>
									<td style="padding-top : 10px;padding-bottom : 10px; width:40%;" ><?= $judul_terdata_info?></td>
									<td> :  <?= $terdata_info?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<?php $this->load->view('covid19/form_isian_pemudik'); ?>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
		</div>
	</div>
</form>
