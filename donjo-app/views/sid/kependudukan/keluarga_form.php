<div class="content-wrapper">
	<section class="content-header">
		<h1>Data Keluarga</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> <?=ucwords($this->setting->sebutan_desa)?></a></li>
			<li><a href="<?= site_url('keluarga/clear')?>"> Daftar Keluarga</a></li>
			<li class="active">Data Keluarga</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="<?= $form_action?>" method="post" enctype="multipart/form-data">
			<div class="row">
				<?php if (empty($new)): ?>
					<div class="col-md-12">
						<div class='form-group'>
							<label for="no_kk">Nomor KK </label>
							<input id="no_kk"  name="no_kk" class="form-control input-sm <?php if ($new > 0 AND $rt_sel > 0): ?>required<?php endif; ?>" type="text" placeholder="Nomor KK" value="<?= $kk['no_kk']?>"></input>
						</div>
					</div>
				<?php endif; ?>
				<div id="nik_kepala" name="nik_kepala"></div>
				<?php if ($kk): ?>

				<?php elseif ($new): ?>
					<input type="hidden" name="new" value="1">
					<div class="col-md-12">
						<div class='box box-primary'>
							<?php if ($new): ?>
								<div class="box-header with-border">
									<a href="<?=site_url("keluarga")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Penduduk">
										<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Keluarga
									</a>
								</div>
								<?php else: ?>
								<div class="box-header with-border">
									<h3 class="box-title">NIK / Nama Kepala KK</h3>
									<div class="box-tools pull-right">
										<a href="<?= site_url()?>keluarga" class="btn btn-social btn-flat btn-info btn-sm"><i class="fa  fa-backward"></i> Kembali Ke Daftar  Keluarga</a>
									</div>
								</div>
							<?php endif; ?>
							<div class='box-body'>
								<div class="row">
									<div class='col-sm-12'>
										<div class="row">
											<div class='form-group col-sm-3'>
												<select name="dusun" class="form-control input-sm <?php if ($dusun): ?>required<?php endif; ?>" onchange="formAction('mainform','<?= site_url('keluarga/form/0/1')?>')">
													<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
														<?php foreach ($dusun as $data): ?>
															<option value="<?= $data['dusun']?>" <?php if ($dus_sel==$data['dusun']): ?>selected<?php endif; ?>><?= unpenetration(ununderscore($data['dusun']))?></option>
														<?php endforeach; ?>
												</select>
											</div>
											<div class='form-group col-sm-2'>
												<select class="form-control input-sm <?php if ($rw): ?>required<?php endif; ?>" name="rw" onchange="formAction('mainform','<?= site_url('keluarga/form/0/1')?>')">
													<option value="">Pilih RW</option>
													<?php foreach ($rw as $data): ?>
														<option value="<?= $data['rw']?>" <?php if ($rw_sel==$data['rw']): ?>selected<?php endif; ?>><?= $data['rw']?></option>
													<?php endforeach;?>
												</select>
											</div>
											<div class='form-group col-sm-2'>
												<select class="form-control input-sm <?php if ($rt): ?>required<?php endif; ?>" name="rt" onchange="formAction('mainform','<?= site_url('keluarga/form/0/1')?>')">
													<option value="">Pilih RT</option>
													<?php foreach ($rt as $data): ?>
														<option value="<?= $data['id']?>" <?php if ($rt_sel==$data['id']): ?>selected<?php endif; ?>><?= $data['rt']?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
									</div>
								</div>
								<?php if ($rt_sel): ?>
									<div class="row">
										<div class='col-sm-7'>
											<div class='form-group'>
												<label for="alamat"> Alamat</label>
												<input id="alamat"  name="alamat"  class="form-control input-sm" type="text" placeholder="Alamat"  value="<?= $penduduk['alamat']?>"></input>
											</div>
										</div>
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
												<input id="no_kk"  name="no_kk"  class="form-control input-sm" type="text" placeholder="Nomor KK"  value="<?= $no_kk?>"></input>
											</div>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
						<div class="row">
							<?php if (!empty($rt_sel) OR (!empty($penduduk))): ?>
								<div class='col-sm-12'>
									<div class="form-group bg-primary" style="padding:10px">
										<strong>DATA KEPALA KELUARGA :</strong>
									</div>
								</div>
								<?php include("donjo-app/views/sid/kependudukan/penduduk_form_isian.php"); ?>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</form>
	</section>
</div>

