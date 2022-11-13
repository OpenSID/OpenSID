<!-- Perubahan script coding untuk bisa menampilkan modal bootstrap edit password pengguna login -->
<div class="modal-body">
	<div class="row">
		<div class="col-md-3">
			<div class="box box-primary">
				<div class="box-body box-profile">
					<img class="profile-user-img img-responsive img-circle" src="<?= AmbilFoto($main['foto']); ?>" alt="Foto">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<?php if ($main['email_verified_at'] === null): ?>
						<form action="<?= site_url('user_setting/kirim_verifikasi') ?>" method="POST">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-sm btn-warning btn-flat btn-block"><i class="fa fa-share-square"></i> Verifikasi Email</button>
							</span>
						</form>
					<?php endif; ?>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<?php if ($main['telegram_verified_at'] === null && setting('telegram_token')): ?>
						<span class="input-group-btn" style="padding-top: 0.5rem;">
							<button type="button" id="verif_telegram" class="btn btn-sm btn-warning btn-flat btn-block"><i class="fa fa-share-square"></i> Verifikasi Telegram</button>
						</span>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="col-sm-9">
			<div class="box box-danger">
				<form action="<?= site_url("user_setting/update/{$main['id']}") ?>" method="POST" id="validasi" enctype="multipart/form-data">
					<input type="hidden" name="telegram_verified_at" value="<?= $main['telegram_verified_at'] ?>">
					<div class="box-body">
						<div class="form-group">
							<label for="tgl_peristiwa">Username</label>
							<input name="nama" type="hidden" value="<?= $main['nama'] ?>" />
							<input class="form-control input-sm" type="text" value="<?= $main['username'] ?>" disabled autocomplete="off"></input>
						</div>
						<div class="form-group">
							<label for="email">Email</label>
							<input class="form-control input-sm" type="text" value="<?= $main['email'] ?>" readonly></input>
						</div>
						<div class="form-group">
							<label for="nama_lengkap">Nama Lengkap</label>
							<input class="form-control input-sm" type="text" name="nama" value="<?= $main['nama'] ?>"></input>
						</div>
						<div class="form-group">
							<label for="pass_lama">Kata Sandi Lama</label>
							<input class="form-control input-sm required" type="password" name="pass_lama" autocomplete="off"></input>
						</div>
						<div class="form-group">
							<label for="pass_baru">Kata Sandi Baru</label>
							<input class="form-control input-sm required pwdLengthNist" type="password" id="pass_baru" name="pass_baru" autocomplete="off"></input>
						</div>
						<div class="form-group">
							<label for="pass_baru1">Kata Sandi Baru (Ulangi)</label>
							<input class="form-control input-sm required pwdLengthNist" type="password" id="pass_baru1" name="pass_baru1" autocomplete="off"></input>
						</div>
						<div class="form-group">
							<label for="foto">Ganti Foto</label>
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" id="file_path" name="foto">
								<input type="file" class="hidden" id="file" name="foto">
								<input type="hidden" name="old_foto" value="<?= $pamong['foto'] ?>">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
								</span>
							</div>
						</div>
						<div class="form-group">
		                    <label for="notif_telegram" class="control-label">Notifikasi Telegram</label>
		                    <div class="btn-group col-xs-12 col-sm-8 input-group" data-toggle="buttons">
		                      <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label <?= compared_return($main['notif_telegram'], '1') ?> <?= jecho(setting('telegram_token'), null, 'disabled') ?>">
		                        <input type="radio" name="notif_telegram" class="form-check-input" value="1" autocomplete="off" <?= selected($main['notif_telegram'], 1) ?> <?= jecho(setting('telegram_token'), null, 'disabled') ?>> Aktif
		                      </label>
		                      <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label <?= compared_return($auth['notif_telegram'], '0') ?> <?= jecho(setting('telegram_token'), null, 'disabled') ?>" >
		                        <input type="radio" name="notif_telegram" class="form-check-input" value="0" autocomplete="off" <?= selected($auth['notif_telegram'], 0) ?> <?= jecho(setting('telegram_token'), null, 'disabled') ?>> Matikan
		                      </label>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label for="id_telegram">User ID Telegram</label>
		                    <input class="form-control input-sm" type="text" id="id_telegram" name="id_telegram" value="<?= $main['id_telegram'] ?>" <?= jecho(setting('telegram_token'), null, 'disabled') ?> />
		                </div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
						<button id="btnSubmit" type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class='fa fa-check'></i> Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="<?= base_url() ?>assets/bootstrap/js/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.validate.min.js"></script>
<!-- <script src="<?= base_url() ?>assets/bootstrap/js/bootstrap.min.js"></script> -->
<script src="<?= base_url() ?>assets/js/validasi.js"></script>
<script src="<?= base_url() ?>assets/js/localization/messages_id.js"></script>
<script src="<?= asset('js/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<link rel="stylesheet" href="<?= asset('js/sweetalert2/sweetalert2.min.css') ?>">
<script>
	$('document').ready(function() {
		setTimeout(function() {
			$('#pass_baru1').rules('add', {
				equalTo: '#pass_baru'
			})
		}, 500);

		$('#file_browser').click(function(e) {
			e.preventDefault();
			$('#file').click();
		});
		$('#file').change(function() {
			$('#file_path').val($(this).val());
		});
		$('#file_path').click(function() {
			$('#file_browser').click();
		});

		$('#verif_telegram').click(function() {
			Swal.fire({title: 'Mengirim OTP', allowOutsideClick: false, allowEscapeKey:false, showConfirmButton:false, didOpen: () => {Swal.showLoading()}});
			$.ajax({
				url: '<?= site_url("{$this->controller}/kirim_otp_telegram") ?>',
				type: 'Post',
				data: {
					'sidcsrf' : getCsrfToken(),
					'id_telegram' : $('#id_telegram').val()
				},
			})
			.done(function(response) {
				if (response.status == true) {
					Swal.fire({
					    title: 'Masukan Kode OTP',
					    input: 'text',
					    inputPlaceholder : 'Masukan Kode OTP',
						  inputValidator: (value) => {
						    if (isNaN(value)) {
							    return 'Kode OTP harus berupa angka'
							  }
						  },
					    showCancelButton: true,
					    confirmButtonText: 'Kirim',
					    cancelButtonText: 'Tutup',
					    showLoaderOnConfirm: true,
					    preConfirm: (otp) => {
					      const formData = new FormData();
					      formData.append('sidcsrf', getCsrfToken());
					      formData.append('id_telegram', response.data);
					      formData.append('otp', otp);

					      return fetch(`<?= site_url("{$this->controller}/verifikasi_telegram") ?>`, {
					              method: 'POST',
					              body: formData,
					      }).then(response => {
					          if (!response.ok) {
					              throw new Error(response.statusText)
					          }
					          return response.json()
					      })
					      .catch(error => {
					          Swal.showValidationMessage(
					            `Request failed: ${error}`
					          )
					      })
					    }
					 }).then((result) => {
					      if (result.isConfirmed) {
					          if (result.value.status == true) {
					          	$('.close').trigger("click"); //close modal
					          	Swal.fire({
									icon: 'success',
									title: result.value.message,
									showConfirmButton: false,
									timer: 1500
								})
					          } else {
					          		Swal.fire({ icon: 'error', title: result.value.message })
					          }
					      }
					})
				}else{
					Swal.fire({
			            icon: 'error',
			            text: response.messages,
			        })
				}

			})
			.fail(function(e) {
				Swal.fire({
				  icon: 'error',
				  text: e.statusText,
				})
			});
		});

		$('#id_telegram').change(function(event) {
			$('input[name="telegram_verified_at"]').val('')
		});
	});
</script>
