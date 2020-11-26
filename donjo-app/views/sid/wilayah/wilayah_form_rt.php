<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Pengelolaan Data RT
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
						<li class="breadcrumb-item"><a href="<?= site_url('sid_core')?>"> Daftar <?= ucwords($this->setting->sebutan_dusun)?></a></li>
						<li class="breadcrumb-item"><a href="<?= site_url("sid_core/sub_rw/$id_dusun")?>"> Daftar RW</a></li>
						<li class="breadcrumb-item"><a href="<?= site_url("sid_core/sub_rt/$id_dusun/$id_rw")?>"> Daftar RT</a></li>
						<li class="breadcrumb-item active">Data RT</li>
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
						<a href="<?= site_url("sid_core/sub_rt/$id_dusun/$id_rw")?>" class="btn btn-flat btn-info btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left"  title="Kembali Ke Daftar RT">
							<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar RT
           	</a>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12">
								<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
									<div class="card-body">
											<div class="row mb-2">
													<label class="col-sm-3 control-label" for="rt">RT</label>
													<div class="col-sm-7">
														<input  id="rt" class="form-control form-control-sm digits required" type="text" placeholder="Nomor RT" name="rt" value="<?= $rt?>">
													</div>
											</div>
											<?php if ($rt): ?>
												<div class="row mb-2">
														<label class="col-sm-3 control-label" for="kepala_lama">Ketua RT Sebelumnya</label>
														<div class="col-sm-7">
															<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
																<strong> <?= $individu['nama']?></strong>
																<br />NIK - <?= $individu['nik']?>
															</p>
														</div>
												</div>
											<?php endif; ?>
											<div class="row mb-2">
													<label class="col-sm-3 control-label" for="id_kepala">Ketua RT</label>
													<div class="col-sm-7">
														<select class="form-control select2 input-sm" style="width: 100%;" id="id_kepala" name="id_kepala">
															<option selected="selected">-- Silakan Masukan NIK / Nama--</option>
															<?php foreach ($penduduk as $data): ?>
																<option value="<?= $data['id']?>">NIK :<?= $data['nik']." - ".$data['nama']." - ".$data['dusun']?></option>
															<?php endforeach; ?>
														</select>
													</div>
											</div>
									</div>
									<div class='card-footer'>
										<div class='col-xs-12'>
											<button type='reset' class='btn btn-flat btn-danger btn-xs invisible' ><i class='fa fa-times'></i> Batal</button>
											<button type='submit' class='btn btn-flat btn-info btn-xs pull-right'><i class='fa fa-check'></i> Simpan</button>
										</div>
									</div>
								</form>
							</div>
           	</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script src="<?= base_url()?>assets/js/validasi.js"></script>
<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<script type="text/javascript">
	setTimeout(function() {
		$('#rt').rules('add', {
			maxlength: 10
		})
	}, 500);
</script>
