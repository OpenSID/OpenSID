<!-- Perubahan script coding untuk bisa menampilkan halaman edit form konfigurasi bentuk tampilan bootstrap (AdminLTE) -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Identitas <?=ucwords($this->setting->sebutan_desa)?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?=site_url('hom_desa/konfigurasi')?>"></i> Identitas <?=ucwords($this->setting->sebutan_desa)?></a></li>
			<li class="active">Ubah Identitas <?=ucwords($this->setting->sebutan_desa)?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<form id="mainform" action="<?=$form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal" id="validasi">
				<div class="col-md-3">
					<div class="box box-primary">
						<div class="box-body box-profile">
							<img class="profile-user-img img-responsive img-circle" src="<?=gambar_desa($main['logo'])?>" alt="Logo">
							<br/>
							<p class="text-center text-bold">Lambang <?=ucwords($this->setting->sebutan_desa)?></p>
							<p class="text-muted text-center text-red">(Kosongkan, jika logo tidak berubah)</p>
							<br/>
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" id="file_path" >
								<input type="file" class="hidden" id="file" name="logo">
								<input type="hidden" name="old_logo" value="<?=$main['logo']?>">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
								</span>
							</div>
						</div>
					</div>
					<div class="box box-primary">
						<div class="box-body box-profile">
							<img class="img-responsive" src="<?=gambar_desa($main['kantor_desa'], TRUE)?>" alt="Kantor <?=ucwords($this->setting->sebutan_desa)?>">
							<br/>
							<p class="text-center text-bold">Kantor <?=ucwords($this->setting->sebutan_desa)?></p>
							<p class="text-muted text-center text-red">(Kosongkan, jika kantor <?=($this->setting->sebutan_desa)?> tidak berubah)</p>
							<br/>
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" id="file_path2" >
								<input type="file" class="hidden" id="file2" name="kantor_desa">
								<input type="hidden" name="old_kantor_desa" value="<?=$main['kantor_desa']?>">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat" id="file_browser2"><i class="fa fa-search"></i> Browse</button>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="box box-primary">
						<div class="box-header with-border">
							<a href="<?=site_url()?>hom_desa/konfigurasi" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Data <?=ucwords($this->setting->sebutan_desa)?>"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Data Identitas <?=ucwords($this->setting->sebutan_desa)?></a>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama">Nama <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-8">
									<input id="nama_desa" name="nama_desa" class="form-control input-sm nama_terbatas required" maxlength="50" type="text" placeholder="Nama <?=ucwords($this->setting->sebutan_desa)?>" value="<?=$main["nama_desa"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="kode_desa">Kode <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-2">
									<input id="kode_desa" name="kode_desa" class="form-control input-sm bilangan required" maxlength="6" type="text" placeholder="Kode <?=ucwords($this->setting->sebutan_desa)?>" value="<?=$main["kode_desa"]?>" ></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="kode_pos">Kode Pos <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-2">
									<input id="kode_pos" name="kode_pos" class="form-control input-sm number" maxlength="6" type="text" placeholder="Kode Pos <?=ucwords($this->setting->sebutan_desa)?>" value="<?=$main["kode_pos"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama_kepala_desa">Kepala <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-8">
									<input id="nama_kepala_desa" name="nama_kepala_desa" class="form-control input-sm nama required" maxlength="50" type="text" placeholder="Kepala <?=ucwords($this->setting->sebutan_desa)?>" value="<?=$main["nama_kepala_desa"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nip_kepala_desa">NIP Kepala <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-8">
									<input id="nip_kepala_desa" name="nip_kepala_desa" class="form-control input-sm nomor_sk" maxlength="50" type="text" placeholder="NIP Kepala <?=ucwords($this->setting->sebutan_desa)?>" value="<?=$main["nip_kepala_desa"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="alamat_kantor">Alamat Kantor <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-8">
									<textarea id="alamat_kantor" name="alamat_kantor" class="form-control input-sm alamat required" maxlength="100" placeholder="Alamat Kantor <?=ucwords($this->setting->sebutan_desa)?>" rows="3" style="resize:none;"><?=$main["alamat_kantor"]?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="email_desa">E-Mail <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-8">
									<input id="email_desa" name="email_desa" class="form-control input-sm email" maxlength="50" type="text" placeholder="E-Mail <?=ucwords($this->setting->sebutan_desa)?>" value="<?=$main["email_desa"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="telepon">Telpon <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-8">
									<input id="telepon" name="telepon" class="form-control input-sm bilangan" type="text" maxlength="15" placeholder="Telpon <?=ucwords($this->setting->sebutan_desa)?>" value="<?= $main["telepon"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="website">Website <?=ucwords($this->setting->sebutan_desa)?></label>
								<div class="col-sm-8">
									<input id="website" name="website" class="form-control input-sm url" maxlength="50" type="text" placeholder="Website <?=ucwords($this->setting->sebutan_desa)?>" value="<?=$main["website"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama_kecamatan">Nama <?=ucwords($this->setting->sebutan_kecamatan)?></label>
								<div class="col-sm-8">
									<input id="nama_kecamatan" name="nama_kecamatan" class="form-control input-sm nama_terbatas required" maxlength="50" type="text" placeholder="Nama <?=ucwords($this->setting->sebutan_kecamatan)?>" value="<?=$main["nama_kecamatan"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="kode_kecamatan">Kode <?=ucwords($this->setting->sebutan_kecamatan)?></label>
								<div class="col-sm-2">
									<input id="kode_kecamatan" name="kode_kecamatan" class="form-control input-sm bilangan required" type="text" maxlength="50" placeholder="Kode <?=ucwords($this->setting->sebutan_kecamatan)?>" value="<?=$main['kode_kecamatan']?>" ></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama_kecamatan">Nama <?=ucwords($this->setting->sebutan_camat)?></label>
								<div class="col-sm-8">
									<input id="nama_kepala_camat" name="nama_kepala_camat" class="form-control input-sm nama required" maxlength="50" type="text" placeholder="Nama <?=ucwords($this->setting->sebutan_camat)?>" value="<?=$main["nama_kepala_camat"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nip_kepala_camat">NIP <?=ucwords($this->setting->sebutan_camat)?></label>
								<div class="col-sm-4">
									<input id="nip_kepala_camat" name="nip_kepala_camat" class="form-control input-sm nomor_sk" maxlength="50" type="text" placeholder="NIP <?=ucwords($this->setting->sebutan_camat)?>" value="<?=$main["nip_kepala_camat"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama_kabupaten">Nama <?=ucwords($this->setting->sebutan_kabupaten)?></label>
								<div class="col-sm-8">
									<input id="nama_kabupaten" name="nama_kabupaten" class="form-control input-sm nama_terbatas required" maxlength="50" type="text" placeholder="Nama <?=ucwords($this->setting->sebutan_kabupaten)?>" value="<?=$main["nama_kabupaten"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="kode_kabupaten">Kode <?=ucwords($this->setting->sebutan_kabupaten)?></label>
								<div class="col-sm-2">
									<input id="kode_kabupaten" name="kode_kabupaten" class="form-control input-sm bilangan required" maxlength="50" type="text" placeholder="Kode <?=ucwords($this->setting->sebutan_kabupaten)?>" value="<?=$main["kode_kabupaten"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="propinsi">Provinsi</label>
								<div class="col-sm-4">
									<select name="nama_propinsi" class="form-control select2 input-sm required" onchange="$('input[name=kode_propinsi]').val($(this).find(':selected').data('kode'));" style="width: 100%;">
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
									<input id="kode_propinsi" name="kode_propinsi" class="form-control input-sm bilangan required" maxlength="50" type="text" placeholder="Kode Provinsi" value="<?=$main["kode_propinsi"]?>"></input>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
</div>
