<!-- Perubahan script coding untuk bisa menampilkan halaman edit form konfigurasi bentuk tampilan bootstrap (AdminLTE)  -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Identitas <?=ucwords($this->setting->sebutan_desa)?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Identitas <?=ucwords($this->setting->sebutan_desa)?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<form id="mainform" action="<?=$form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
				<div class="col-md-3">
					<div class="box box-primary">
						<div class="box-body box-profile">
							<?php if ($main['logo']): ?>
								<img class="profile-user-img img-responsive img-circle" src="<?=LogoDesa($main['logo'])?>" alt="Logo">
							<?php else: ?>
								<img class="profile-user-img img-responsive img-circle" src="<?= base_url()?>assets/files/logo/home.png" alt="Logo">
							<?php endif ?>
							<br/>
							<p class="text-center text-bold">Lambang Desa</p>
							<p class="text-muted text-center text-red">(Kosongkan, jika logo tidak berubah)</p>
							<br/>
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" id="file_path" >
								<input type="file" class="hidden" id="file" name="logo">
								<input type="hidden" name="old_logo" value="<?=$main['logo']?>">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="box box-primary">
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama">Nama <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-8">
									<input id="nama_desa" name="nama_desa" class="form-control input-sm required" type="text" placeholder="Nama <?=ucwords($this->setting->sebutan_desa)?>" value="<?=$main["nama_desa"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="kode_desa">Kode <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-2">
									<input id="kode_desa" name="kode_desa" class="form-control input-sm required" type="text" placeholder="Kode <?=ucwords($this->setting->sebutan_desa)?>" value="<?=$main["kode_desa"]?>" ></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="kode_pos">Kode Pos <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-2">
									<input id="kode_pos" name="kode_pos" class="form-control input-sm number" type="text" placeholder="Kode Pos <?=ucwords($this->setting->sebutan_desa)?>" value="<?=$main["kode_pos"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama_kepala_desa">Kepala <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-8">
									<input id="nama_kepala_desa" name="nama_kepala_desa" class="form-control input-sm  required" type="text" placeholder="Kepala <?=ucwords($this->setting->sebutan_desa)?>" value="<?=$main["nama_kepala_desa"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nip_kepala_desa">NIP Kepala <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-8">
									<input id="nip_kepala_desa" name="nip_kepala_desa" class="form-control input-sm" type="text" placeholder="NIP Kepala <?=ucwords($this->setting->sebutan_desa)?>" value="<?=$main["nip_kepala_desa"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="alamat_kantor">Alamat Kantor <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-8">
									<textarea id="alamat_kantor" name="alamat_kantor" class="form-control input-sm required" placeholder="Alamat Kantor <?=ucwords($this->setting->sebutan_desa)?>"><?=$main["alamat_kantor"]?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="email_desa">E-Mail <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-8">
									<input id="email_desa" name="email_desa" class="form-control input-sm email" type="text" placeholder="E-Mail <?=ucwords($this->setting->sebutan_desa)?>" value="<?=$main["email_desa"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="telepon">Telpon <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-8">
									<input id="telepon" name="telepon" class="form-control input-sm" type="text" placeholder="Telpon <?=ucwords($this->setting->sebutan_desa)?>" value="<?= $main["telepon"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="website">Website <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-8">
									<input id="website" name="website" class="form-control input-sm url" type="text" placeholder="Webiste <?=ucwords($this->setting->sebutan_desa)?>" value="<?=$main["website"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama_kecamatan">Nama <?=ucwords($this->setting->sebutan_kecamatan)?></label>
								<div class="col-sm-8">
									<input id="nama_kecamatan" name="nama_kecamatan" class="form-control input-sm required" type="text" placeholder="Nama <?=ucwords($this->setting->sebutan_kecamatan)?>" value="<?=$main["nama_kecamatan"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="kode_kecamatan">Kode <?=ucwords($this->setting->sebutan_kecamatan)?></label>
								<div class="col-sm-2">
									<input id="kode_kecamatan" name="kode_kecamatan" class="form-control input-sm required" type="text" placeholder="Kode <?=ucwords($this->setting->sebutan_kecamatan)?>" value="<?=$main['kode_kecamatan']?>" ></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama_kecamatan">Nama <?=ucwords($this->setting->sebutan_camat)?></label>
								<div class="col-sm-8">
									<input id="nama_kepala_camat" name="nama_kepala_camat" class="form-control input-sm required" type="text" placeholder="Nama <?=ucwords($this->setting->sebutan_camat)?>" value="<?=$main["nama_kepala_camat"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nip_kepala_camat">NIP <?=ucwords($this->setting->sebutan_camat)?></label>
								<div class="col-sm-4">
									<input id="nip_kepala_camat" name="nip_kepala_camat" class="form-control input-sm" type="text" placeholder="NIP <?=ucwords($this->setting->sebutan_camat)?>" value="<?=$main["nip_kepala_camat"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama_kabupaten">Nama <?=ucwords($this->setting->sebutan_kabupaten)?></label>
								<div class="col-sm-8">
									<input id="nama_kabupaten" name="nama_kabupaten" class="form-control input-sm required" type="text" placeholder="Nama <?=ucwords($this->setting->sebutan_kabupaten)?>" value="<?=$main["nama_kabupaten"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="kode_kabupaten">Kode <?=ucwords($this->setting->sebutan_kabupaten)?></label>
								<div class="col-sm-2">
									<input id="kode_kabupaten" name="kode_kabupaten" class="form-control input-sm required" type="text" placeholder="Kode <?=ucwords($this->setting->sebutan_kabupaten)?>" value="<?=$main["kode_kabupaten"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="propinsi">Provinsi</label>
								<div class="col-sm-5">
									<select  name="nama_propinsi" class="form-control select2 input-sm required" onchange="$('input[name=kode_propinsi]').val($(this).find(':selected').data('kode'));">
										<option value="">Pilih Provinsi</option>
										<?php foreach ($list_provinsi AS $data): ?>
											<option value="<?=$data['nama']?>" data-kode="<?=$data['kode']?>" <?php if (strtolower($main['nama_propinsi'])== strtolower($data['nama'])): ?>selected<?php endif ?>><?=$data['nama']?></option>
										<?php endforeach; ?>
									 </select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="kode_propinsi">Kode Provinsi</label>
								<div class="col-sm-2">
									<input id="kode_propinsi" name="kode_propinsi" class="form-control input-sm required" type="text" placeholder="Kode Provinsi" value="<?=$main["kode_propinsi"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="kode_propinsi">Peta Kantor / Wilayah <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-9">
									<a href="<?=site_url("hom_desa/ajax_kantor_maps")?>" data-remote="false" data-toggle="modal" data-target="#mapBox" data-title="Peta Kantor <?=ucwords($this->setting->sebutan_desa)?>" class="btn btn-social btn-flat bg-navy btn-sm"><i class='fa fa-map-marker'></i> Kantor <?=ucwords($this->setting->sebutan_desa)?></a>
									<a href="<?=site_url("hom_desa/ajax_wilayah_maps")?>" class="btn btn-social btn-flat bg-navy btn-sm"><i class='fa fa-map'></i> Wilayah <?=ucwords($this->setting->sebutan_desa)?></a>
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
					</div>
				</div>
			</form>
		</div>
	</section>
</div>
