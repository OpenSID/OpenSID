<div class="content-wrapper">
	<section class="content-header">
		<h1>Tema</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Tema</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<?php
						// Message
						echo $this->session->flashdata('msg');
					?>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="box-body">
									<form method='post' action=<?= site_url("zip/extract") ?> enctype='multipart/form-data'>
										<div class="row">
											<div class="col-sm-12">
												<table class="table table-bordered table-hover">
													<tbody>
														<tr>
															<td style="padding-top:20px;padding-bottom:10px;">
																<div class="form-group">
																	<label for="file" class="col-md-2 col-lg-2 control-label">Theme .zip:</label>
																	<div class="col-sm-12 col-md-8 col-lg-8">
																		<div class="input-group input-group-sm">
																			<input type='text' class="form-control" id="file_path" name="userfile">
																			<input type="file" name='file' class="hidden" id="file" name="userfile" accept="application/sql">
																			<span class="input-group-btn">
																				<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
																			</span>
																		</div>
																	</div>
																	<div class="col-sm-12 col-md-2 col-lg-2">
																		<button type="submit" name="submit" value="Upload & Extract" class="btn btn-block btn-success btn-sm"><i class="fa fa-spin fa-refresh"></i> Upload</button>
																	</div>
																</div>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
		<!--Tema Yang Aktif-->
			<?php
				$nama_tema = explode("/",  $tema_aktif );

				if ($nama_tema[0] == 'desa') {
					$nama = $nama_tema[1];
					$kat = 0; //desa/themes/
				} else {
					$nama = $nama_tema[0];
					$kat = 1; //themes
				}
			?>
			<div class="col-md-4 col-xs-12 col-center">
				<div class="box box-info">
					<div class="box-body box-profile">
						<img class="img-responsive" src="<?= base_url() ?>
															<?= ($kat == 0) ? 'desa/themes/' : 'themes/' ?>
															<?= $nama . '/thumbnail.jpg' ?>" alt=" <?= $nama ?>">
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="file" class="col-md-4 col-lg-4 control-label" align="left"><b><?= ucwords($nama); ?></b></label>
								<div class="col-sm-12 col-md-8 col-lg-8">
										<a href="#" class="btn btn-block btn-primary btn-sm" disabled=true><i class="fa fa-spin fa-refresh"></i> Sedang Digunakan</a>
								</div>
							</div>
							<br>
						</div>
					</div>
				</div>
			</div>
		<!--Tema Standar dan Tidak Aktif-->
			<?php if($tema_aktif !='hadakewa'){ ?>
			<div class="col-md-4 col-xs-12 col-center">
				<div class="box box-info">
					<div class="box-body box-profile">
						<img class="img-responsive" src="<?= base_url().'themes/hadakewa/thumbnail.jpg' ?>" alt="hadakewa" >
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="file" class="col-md-4 col-lg-4 control-label" align="left"><b>hadakewa</b></label>
								<div class="col-sm-12 col-md-8 col-lg-8">
									<a href="<?= site_url("tema/ganti_tema/hadakewa") ?>" class="btn btn-block btn-primary btn-sm"><i class="fa fa-lock"></i> Standar</a>
								</div>
							</div>
							<br>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
			
			<!--Tema Lainnnya Yang Ada Di Direktori-->
			<?php foreach ($list_tema as $tema => $data) {
				$nama_tema = explode("/",  $data);

				if ($nama_tema[0] == 'desa') {
					$nama = $nama_tema[1];
					$kat = 0; //desa/themes/
				} else {
					$nama = $nama_tema[0];
					$kat = 1; //themes
				}
			?>
			<?php if($tema_aktif != $data AND $data !='hadakewa'){ ?>
			<div class="col-md-4 col-xs-12 col-center">
				<div class="box box-info">
					<div class="box-body box-profile">
						<img class="img-responsive" src="<?= base_url() ?>
															<?= ($kat == 0) ? 'desa/themes/' : 'themes/' ?>
															<?= $nama . '/thumbnail.jpg' ?>" alt=" <?= $nama ?>" >
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">					
								<label for="file" class="col-md-4 col-lg-4 control-label" align="left"><b><?= ucwords($nama); ?></b></label>
								<?php if ($data == 'hadakewa') { ?>
									<div class="col-sm-12 col-md-8 col-lg-8">
										<a href="<?= site_url("tema/ganti_tema/" . $data) ?>" class="btn btn-block btn-primary btn-sm"><i class="fa fa-lock"></i> Standar</a>
									</div>
								<?php } else { ?>
									<div class="col-sm-12 col-md-3 col-lg-4">
										<a href="<?= site_url("tema/ganti_tema/" . $data) ?>" class="btn btn-block btn-success btn-sm"><i class="fa fa-lock"></i> Aktifkan</a>
									</div>
									<div class="col-sm-12 col-md-3 col-lg-4">
										<a href="<?= site_url("tema/delete/" . $nama . '/' . $kat) ?>" class="btn btn-block btn-danger btn-sm"><i class="fa fa-trash-o"></i> Hapus</a>
									</div>
								<?php }  ?>
								
							</div>
							<br>
						</div>
					</div>
				</div>
				</div>
			<?php }  ?>
			<?php } ?>
		</div>
	</section>
</div>
