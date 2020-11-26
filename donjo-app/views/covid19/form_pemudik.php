<div class="content-wrapper">

	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Penambahan Pemudik Covid-19
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
						<li class="breadcrumb-item"><a href="<?= site_url('covid19')?>"> Daftar Pemudik</a></li>
						<li class="breadcrumb-item active">Penambahan Pemudik Covid-19</li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-outline card-info">
					<div class="card-header with-border">
						<div class="col-md-12">
							<a href="<?= site_url('covid19')?>" class="btn btn-flat btn-primary btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Kembali Ke Daftar Pemudik Saat Covid-19"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Pemudik Saat Covid-19</a>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="card-header with-border">
									<h3 class="card-title">Tambahkan Warga Pemudik</h3>
								</div>
								<div class="card-body">
									<form action="" id="main" name="main" method="POST"  class="form-horizontal">

										<div class="row">
											<label class="col-sm-3 control-label required"  for="terdata">NIK / Nama</label>
											<div class="col-sm-4">
												<select class="form-control select2 required" id="terdata" name="terdata"  onchange="formAction('main')" style="width: 100%;">
													<option value="">-- Silakan Masukan NIK / Nama--</option>
													<?php foreach ($list_penduduk as $item):
														if (strlen($item["id"])>0): ?>
															<option value="<?= $item['id']?>" <?php selected($individu['id'], $item['id']); ?>>Nama : <?= $item['nama']." - ".$item['info']?></option>
														<?php endif;
													endforeach; ?>
												</select>
											</div>
											<div class="col-sm-4">
												<a href="#" class="btn btn-block btn-success btn-xs" data-toggle="modal" data-target="#add-warga">
													<i class="fa fa-plus"></i>
													Tambah Penduduk Non Domisili
												</a>
												<span id="data_h_plus_msg" class="help-block">
													<code>Untuk penduduk pendatang/tidak tetap. Masukkan data di sini.</code>
												</span>
											</div>
										</div>

									</form>
									<div id="form-melengkapi-data-peserta">
										<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
											<div class="form-group">
												<label  class="col-sm-3 control-label"></label>
												<div class="col-sm-8">
													 <input type="hidden" name="id_terdata" value="<?= $individu['id']?>" class="form-control form-control-sm required">
												 </div>
											</div>
											<?php if ($individu): ?>
												<?php include("donjo-app/views/covid19/konfirmasi_pemudik.php"); ?>
											<?php endif; ?>

											<?php include("donjo-app/views/covid19/form_isian_pemudik.php"); ?>

										</form>
									</div>
									<div class="card-footer">
										<div class="col-xs-12">
											<button type="reset" class="btn btn-flat btn-danger btn-xs"><i class="fa fa-times"></i> Batal</button>
											<button type="submit" class="btn btn-flat btn-info btn-xs pull-right" onclick="$('#'+'validasi').submit();"><i class="fa fa-check"></i> Simpan</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

</div>

<div class='modal fade' id='add-warga' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
				<h4 class='modal-title' id='myModalLabel'><i class='fa fa-plus text-green'></i> Tambah Penduduk Pendatang / Tidak Tetap</h4>
			</div>
			<div class='modal-body'>
				<div class="row">
					<?php include("donjo-app/views/covid19/form_isian_penduduk.php"); ?>
				</div>
			</div>
			<div class='modal-footer'>
				<button type="button" class="btn btn-flat btn-warning btn-xs" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
				<a class='btn-ok'>
					<button type="submit" class="btn btn-flat btn-success btn-xs" onclick="$('#'+'form_penduduk').submit();"><i class='fa fa-trash-o'></i> Simpan</button>
				</a>
			</div>
		</div>
	</div>
</div>
