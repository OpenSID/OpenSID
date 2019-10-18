<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengelolaan Data RT</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('sid_core')?>"> Daftar <?= ucwords($this->setting->sebutan_dusun)?></a></li>
			<li><a href="<?= site_url("sid_core/sub_rw/$id_dusun")?>"> Daftar RW</a></li>
			<li><a href="<?= site_url("sid_core/sub_rt/$id_dusun/$rw")?>"> Daftar RT</a></li>
			<li class="active">Data RT</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						 <a href="<?= site_url("sid_core/sub_rt/$id_dusun/$rw")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar RT">
							<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar RT
           	</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
							<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
									<div class="box-body">
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label class="col-sm-3 control-label" for="rt">RT</label>
													<div class="col-sm-7">
														<input  id="rt" class="form-control input-sm digits required" type="text" placeholder="Nomor RT" name="rt" value="<?= $rt?>">
													</div>
												</div>
											</div>
											<?php if ($rt): ?>
												<div class="col-sm-12">
													<div class="form-group">
														<label class="col-sm-3 control-label" for="kepala_lama">Ketua RT Sebelumnya</label>
														<div class="col-sm-7">
															<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
																<strong> <?= $individu['nama']?></strong>
																<br />NIK - <?= $individu['nik']?>
															</P>
														</div>
													</div>
												</div>
											<?php endif; ?>
											<div class="col-sm-12">
												<div class="form-group">
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
										</div>

<div class="form-group">
								<label class="col-sm-3 control-label" for="kode_propinsi">Peta Kantor / Wilayah RT</label>
								<div class="col-sm-9">
									<a href="<?=site_url("sid_core/ajax_kantor_rt_maps/$dusun/$rw/$rt")?>" data-remote="false" data-toggle="modal" data-target="#mapBox" data-title="Peta Kantor RT <?= $rt?> RW <?= $rw?> <?= ucwords($this->setting->sebutan_dusun." ".$dusun)?> <?= ucwords($this->setting->sebutan_desa." ".$desa['nama_desa'])?>" class="btn btn-social btn-flat bg-navy btn-sm"><i class='fa fa-map-marker'></i> Kantor RT</a>
									<a href="<?=site_url("sid_core/ajax_wilayah_rt_maps/$dusun/$rw/$rt")?>" class="btn btn-social btn-flat bg-navy btn-sm"><i class='fa fa-map'></i> Wilayah RT</a>
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

