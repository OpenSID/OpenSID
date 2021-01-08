<div class="content-wrapper">
	<section class="content-header">
		<h1>Form Manajemen Pengguna</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('man_user')?>"> Daftar Pengguna</a></li>
			<li class="active">Form Manajemen Pengguna</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
				<div class="col-md-3">
					<div class="box box-primary">
						<div class="box-body box-profile">
							<?php if ($user['foto']): ?>
								 <img class="profile-user-img img-responsive img-circle" src="<?=AmbilFoto($user['foto'])?>" alt="Pengguna">
							<?php else: ?>
								<img class="profile-user-img img-responsive img-circle" src="<?= base_url()?>assets/files/user_pict/kuser.png" alt="Pengguna">
							<?php endif ?>
							<br/>
							<p class="text-center text-bold">Foto Pengguna</p>
							<p class="text-muted text-center text-red">(Kosongkan, jika foto tidak berubah)</p>
							<br/>
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" id="file_path" name="foto">
								<input type="file" class="hidden" id="file" name="foto">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="box box-primary">
						<div class="box-header with-border">
							<a href="<?=site_url()?>man_user" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Manajemen Pengguna</a>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-3 control-label" for="group">Group</label>
								<div class="col-sm-8">
									<select class="form-control input-sm required" id="id_grup" name="id_grup">
										<?php if ($user['id'] == '1'): ?>
											<option <?php selected($user['id_grup'], '1'); ?> value="1">Administrator</option>
										<?php else: ?>
											<?php foreach ($user_group as $item): ?>
												<option <?php selected($user['id_grup'], $item['id']); ?> value="<?= $item[id] ?>"><?= $item['nama'] ?></option>
											<?php endforeach ?>
										<?php endif ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="username">Username</label>
								<div class="col-sm-8">
									<input id="username" name="username" class="form-control input-sm required username" type="text" placeholder="Username" value="<?=$user['username']?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="password">Kata Sandi</label>
								<div class="col-sm-8">
									<input id="password" name="password" class="form-control input-sm required pwdLengthNist_atau_kosong" type="password" placeholder="Kata Sandi" <?php if ($user): ?>value="radiisi"<?php endif ?> ></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama">Nama</label>
								<div class="col-sm-8">
									<input id="nama" name="nama" class="form-control input-sm required nama" minlength="3" maxlength="50" type="text" placeholder="Nama" value="<?=$user['nama']?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="phone">Nomor HP</label>
								<div class="col-sm-8">
									<input id="phone" name="phone" class="form-control input-sm bilangan" minlength="8" maxlength="15" type="text" placeholder="Nomor HP" value="<?=$user['phone']?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="email">Mail</label>
								<div class="col-sm-8">
									<input id="email" name="email" class="form-control input-sm email" type="text" placeholder="Alamat E-mail" value="<?=$user['email']?>"></input>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
								<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
</div>
