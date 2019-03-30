<div class="content-wrapper">
	<section class="content-header">
		<h1>Biodata Penduduk</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('penduduk/clear')?>"> Daftar Penduduk</a></li>
			<li class="active">Biodata Penduduk</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" onreset="reset_hamil();">
			<div class="row">
				<?php $edit_lokasi = ((empty($penduduk) OR $_SESSION['validation_error']) AND empty($id)); ?>
				<?php if ($edit_lokasi): ?>
					<div class="col-md-12">
						<div class='box box-primary'>
							<div class="box-header with-border">
								<a href="<?=site_url("penduduk/clear")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Penduduk">
									<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Penduduk
								</a>
							</div>
							<div class='box-body'>
								<div class="row">
									<div class='col-sm-12'>
										<div class="row">
											<div class='form-group col-sm-3'>
												<select name="dusun" class="form-control input-sm <?php if ($dusun): ?>required<?php endif; ?>" onchange="formAction('mainform','<?= site_url('penduduk/form')?>')">
													<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
														<?php foreach ($dusun as $data): ?>
															<option value="<?= $data['dusun']?>" <?php if ($dus_sel==$data['dusun']): ?>selected<?php endif; ?>><?= unpenetration(ununderscore($data['dusun']))?></option>
														<?php endforeach; ?>
												</select>
											</div>
											<div class='form-group col-sm-2'>
												<select class="form-control input-sm <?php if ($rw): ?>required<?php endif; ?>" name="rw" onchange="formAction('mainform','<?= site_url('penduduk/form')?>')">
													<option value="">Pilih RW</option>
													<?php foreach ($rw as $data): ?>
														<option value="<?= $data['rw']?>" <?php if ($rw_sel==$data['rw']): ?>selected<?php endif; ?>><?= $data['rw']?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class='form-group col-sm-2'>
												<select class="form-control input-sm <?php if ($rt): ?>required<?php endif; ?>" name="rt" onchange="formAction('mainform','<?= site_url('penduduk/form')?>')" >
													<option value="">Pilih RT</option>
													<?php foreach ($rt as $data): ?>
														<option value="<?= $data['id']?>" <?php if ($rt_sel==$data['id']): ?>selected<?php endif; ?>><?= $data['rt']?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<?php if (!empty($rt_sel) OR (!empty($penduduk))): ?>
					<?php include("donjo-app/views/sid/kependudukan/penduduk_form_isian.php"); ?>
				<?php endif; ?>
			</div>
		</form>
	</section>
</div>
