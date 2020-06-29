<div class="modal fade" id="massageBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header btn-info'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
				<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> &nbsp;Peringatan</h4>
			</div>
			<form action="<?= site_url('setting/tracking') ?>" method="post">
				<div class='modal-body'>
					Kami mendeteksi bahwa anda telah mematikan tracking website. Hal ini akan mempengaruhi website anda dalam menerima informasi yang kami kirimkan melalui server OpenSID.

					<br><br>

					Hidupkan kembali tracking untuk mendapatkan informasi dari server OpenSID ????
				</div>
				<div class="modal-footer">
					<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tidak</button>
					<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Ya</button>
				</div>
			</form>
		</div>
	</div>
</div>
