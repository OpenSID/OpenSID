<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Data Keluarga
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fas fa-home"></i> Home</a></li>
						<li class="breadcrumb-item"><a href="<?= site_url('keluarga/clear')?>"> Daftar Keluarga</a></li>
						<li class="breadcrumb-item active">Data Keluarga</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="<?= $form_action?>" method="post" enctype="multipart/form-data">
			<div class="row">
				<div id="nik_kepala" name="nik_kepala"></div>
					<div class="col-md-12">
						<div class='card card-outline card-primary'>
							<div class="card-header with-border">
								<a href="<?=site_url("keluarga")?>" class="btn btn-flat btn-info btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left"  title="Kembali Ke Daftar Penduduk">
									<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Keluarga
								</a>
							</div>
							<div class='card-body'>
								<div class="row">
									<div class='col-sm-7'>
										<div class='form-group'>
											<label for="alamat"> Nomor KK</label>
											<?php
												// $penduduk dipakai kalau validasi data gagal
												if ($penduduk):
													$no_kk = $penduduk['no_kk'];
												else:
													$no_kk = $kk['no_kk'];
												endif;
											?>
											<input id="no_kk"  name="no_kk"  class="form-control form-control-sm nik" type="text" placeholder="Nomor KK"  value="<?= $no_kk?>"></input>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class='col-sm-12'>
								<div class="form-group bg-primary" style="padding:10px">
									<strong>DATA KEPALA KELUARGA :</strong>
								</div>
							</div>
							<?php include("donjo-app/views/sid/kependudukan/penduduk_form_isian.php"); ?>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
