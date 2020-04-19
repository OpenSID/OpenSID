<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/script.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<style type="text/css">
	.horizontal {
		padding-left: 0px; width: auto; padding-right: 30px;
	}
</style>
<form action="<?= site_url('keuangan/bersihkan_desa/'.$id_master)?>" method="post" id="validasi">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<div class="alert alert-warning" role="alert"><i class="fa fa-exclamation-triangle"></i>
								Data yang diimpor berisi data beberapa desa dan akan menyebabkan nilai laporan dan grafik salah. Untuk menghapus data desa lain, pilih desa anda dan klik tombol 'Berishkan'.
							</div>
						</div>
						<div class="form-group">
							<label>Pilih Desa Untuk Data Ini</label>
							<select class="form-control input-sm required" name="kode_desa">
								<option value="">Pilih Desa</option>
								<?php foreach ($desa_ganda as $desa): ?>
									<option value="<?= $desa['Kd_Desa']?>"><?= strtoupper($desa['Nama_Desa'].' - '.$desa['Kd_Desa'])?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Bersihkan</button>
	</div>
</form>
