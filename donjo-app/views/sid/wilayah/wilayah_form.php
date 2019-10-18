<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengelolaan Data <?= ucwords($this->setting->sebutan_dusun)?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('sid_core')?>"> Daftar <?= ucwords($this->setting->sebutan_dusun)?></a></li>
			<li class="active">Data <?= ucwords($this->setting->sebutan_dusun)?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url("sid_core")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Wilayah">
							<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar <?= ucwords($this->setting->sebutan_dusun)?>
           	</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data"  class="form-horizontal">
									<div class="box-body">
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label class="col-sm-3 control-label" for="dusun">Nama  <?= ucwords($this->setting->sebutan_dusun)?></label>
													<div class="col-sm-7">
														<input  id="dusun" class="form-control input-sm required" type="text" placeholder="Nama  <?= ucwords($this->setting->sebutan_dusun)?>" name="dusun" value="<?= $dusun?>">
													</div>
												</div>
											</div>

                                                                                       <?php if ($dusun): ?>
												<div class="col-sm-12">
													<div class="form-group">
														<label class="col-sm-3 control-label" for="kepala_lama">Kepala  <?= ucwords($this->setting->sebutan_dusun)?> Sebelumnya</label>
														<div class="col-sm-7">
															<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
																<strong><?= $individu["nama"]?></strong>
																<br/>NIK - <?= $individu["nik"]?>
															</P>
														</div>
													</div>
												</div>
											<?php endif; ?>
											<div class="col-sm-12">
												<div class="form-group">
													<label class="col-sm-3 control-label" for="id_kepala">NIK / Nama Kepala  <?= ucwords($this->setting->sebutan_dusun)?></label>
													<div class="col-sm-7">
														<select class="form-control select2" style="width: 100%;" id="id_kepala" name="id_kepala">
															<option selected="selected">-- Silakan Masukan NIK / Nama--</option>
															<?php foreach ($penduduk as $data): ?>
																<option value="<?= $data['id']?>">NIK :<?= $data['nik']." - ".$data['nama']." - ".$data['dusun']?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
										</div>
									

                                                                        <div class="form-group">
								<label class="col-sm-3 control-label" for="kode_propinsi">Peta Kantor / Wilayah <?=ucwords($this->setting->sebutan_dusun)?></label>
								<div class="col-sm-9">
									<a href="<?=site_url("sid_core/ajax_kantor_dusun_maps/$dusun")?>" data-remote="false" data-toggle="modal" data-target="#mapBox" data-title="Peta Kantor <?= ucwords($this->setting->sebutan_dusun." ".$dusun)?> " class="btn btn-social btn-flat bg-navy btn-sm"><i class='fa fa-map-marker'></i> Kantor <?=ucwords($this->setting->sebutan_dusun)?></a>
									<a href="<?=site_url("sid_core/ajax_wilayah_dusun_maps/$dusun")?>" class="btn btn-social btn-flat bg-navy btn-sm"><i class='fa fa-map'></i> Wilayah <?=ucwords($this->setting->sebutan_dusun)?></a>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
						<div  class="modal fade" id="mapBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
							<div class='modal-dialog'>
								<div class='modal-content'>
									<div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
										<h4 class='modal-title' id='myModalLabel'></h4>
									</div>
									<div class="fetched-data"></div>
								</div>
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

