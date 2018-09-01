<!-- Perubahan script coding untuk bisa menampilkan modal bootstrap edit password pengguna login -->
<form action="<?=site_url("user_setting/update/$main[id]")?>" method="POST" id="validasi" enctype="multipart/form-data">
	<div class="modal-body" id="maincontent">
		<div class="row">
			<div class="col-md-3">
				<div class="box box-primary">
					<div class="box-body box-profile">
						<?php if ($main['foto']): ?>
							<img class="profile-user-img img-responsive img-circle" src="<?=AmbilFoto($main['foto'])?>" alt="Photo">
						<?php else: ?>
							<img class="profile-user-img img-responsive img-circle" src="<?= base_url()?>assets/files/user_pict/kuser.png" alt="Photo">
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="col-sm-9">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<label for="tgl_peristiwa">Username</label>
							<input name="nama" type="hidden" value="<?=$main['nama']?>" />
							<input class="form-control input-sm" type="text"  value="<?=$main['username']?>" disabled=""></input>
						</div>
						<div class="form-group">
							<label for="catatan">Nama Lengkap</label>
							<input class="form-control input-sm" type="text" name="nama" value="<?=$main['nama']?>" ></input>
						</div>
						<div class="form-group">
							<label for="catatan">Password Lama</label>
							<input class="form-control input-sm" type="password" name="pass_lama" ></input>
						</div>
						<div class="form-group">
							<label for="catatan">Password Baru</label>
							<input class="form-control input-sm" type="password" name="pass_baru" ></input>
						</div>
						<div class="form-group">
							<label for="catatan">Password Baru (Ulangi)</label>
							<input class="form-control input-sm" type="password" name="pass_baru1" ></input>
						</div>
						<div class="form-group">
							<label for="catatan">Ganti Photo</label>
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" id="file_path" name="foto">
								<input type="file" class="hidden" id="file" name="foto">
								<input type="hidden" name="old_foto" value="<?=$pamong['foto']?>">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class='fa fa-check'></i> Simpan</button>
		</div>
	</div>
</form>
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
