<style>
	.table
	{
    font-size: 12px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Identitas <?=ucwords($this->setting->sebutan_desa)?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Identitas <?=ucwords($this->setting->sebutan_desa)?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header">
							<a href="<?= site_url("hom_desa/konfigurasi_form")?>" class="btn btn-social btn-flat btn-warning btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Ubah Biodata" ><i class="fa fa-edit"></i> Ubah Data <?=ucwords($this->setting->sebutan_desa)?></a>
              <a href="<?=site_url("hom_desa/ajax_kantor_maps")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-map-marker'></i> Lokasi Kantor <?=ucwords($this->setting->sebutan_desa)?></a>
							<a href="<?=site_url("hom_desa/ajax_wilayah_maps")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-map'></i> Peta Wilayah <?=ucwords($this->setting->sebutan_desa)?></a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<div class="box-header with-border">
										<h1 class="box-title"><?=ucwords($this->setting->sebutan_desa)?> <?=$main["nama_desa"]?></h1>
										<br>
									</div>
								</div>
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover" >
											<tr>
												<td colspan="3">
													<?php if ($main['logo']): ?>
														 <img class="profile-user-img img-responsive img-circle" src="<?=LogoDesa($main['logo'])?>" alt="Logo">
													<?php else: ?>
														<img class="profile-user-img img-responsive img-circle" src="<?= base_url()?>assets/files/logo/home.png" alt="Logo">
  												<?php endif; ?>
												</td>
											</tr>
										</table>
									</div>
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover" >
											<tbody>
												<tr>
													<td width="300">Nama <?=ucwords($this->setting->sebutan_desa)?></td><td width="1">:</td>
													<td><?=$main["nama_desa"]?></td>
												</tr>
												<tr>
													<td width="300">Kode <?=ucwords($this->setting->sebutan_desa)?></td><td width="1">:</td>
													<td><?=$main["kode_desa"]?></td>
												</tr>
												<tr>
													<td width="300">Kode Pos <?=ucwords($this->setting->sebutan_desa)?></td><td width="1">:</td>
													<td><?=$main["kode_pos"]?></td>
												</tr>
												<tr>
													<td width="300">Kepala <?=ucwords($this->setting->sebutan_desa)?></td><td width="1">:</td>
													<td><?=$main["nama_kepala_desa"]?></td>
												</tr>
												<tr>
													<td width="300">NIP Kepala <?=ucwords($this->setting->sebutan_desa)?></td><td width="1">:</td>
													<td><?=$main["nip_kepala_desa"]?></td>
												</tr>
												<tr>
													<td width="300">Alamat Kantor <?=ucwords($this->setting->sebutan_desa)?></td><td width="1">:</td>
													<td><?=$main["alamat_kantor"]?></td>
												</tr>
												<tr>
													<td width="300">E-Mail <?=ucwords($this->setting->sebutan_desa)?></td><td width="1">:</td>
													<td><?=$main["email_desa"]?></td>
												</tr>
												<tr>
													<td width="300">Telpon <?=ucwords($this->setting->sebutan_desa)?></td><td width="1">:</td>
													<td><?= $main["telepon"]?></td>
												</tr>
												<tr>
													<td width="300">Website <?=ucwords($this->setting->sebutan_desa)?></td><td width="1">:</td>
													<td><?=$main["website"]?></td>
												</tr>
												<tr>
													<th colspan="3" class="subtitle_head"><strong><?=ucwords($this->setting->sebutan_kecamatan)?></strong></th>
												</tr>
												<tr>
													<td width="300">Nama <?=ucwords($this->setting->sebutan_kecamatan)?></td><td width="1">:</td>
													<td><?=$main["nama_kecamatan"]?></td>
												</tr>
												<tr>
													<td width="300">Kode <?=ucwords($this->setting->sebutan_kecamatan)?></td><td width="1">:</td>
													<td><?=$main['kode_kecamatan']?></td>
												</tr>
												<tr>
													<td width="300">Nama <?=ucwords($this->setting->sebutan_camat)?></td><td width="1">:</td>
													<td><?=$main["nama_kepala_camat"]?></td>
												</tr>
												<tr>
													<td width="300">NIP <?=ucwords($this->setting->sebutan_camat)?></td><td width="1">:</td>
													<td><?=$main["nip_kepala_camat"]?></td>
												</tr>
												<tr>
													<th colspan="3" class="subtitle_head"><strong><?=ucwords($this->setting->sebutan_kabupaten)?></strong></th>
												</tr>
												<tr>
													<td width="300">Nama <?=ucwords($this->setting->sebutan_kabupaten)?></td><td width="1">:</td>
													<td><?=$main["nama_kabupaten"]?></td>
												</tr>
												<tr>
													<td width="300">Kode <?=ucwords($this->setting->sebutan_kabupaten)?></td><td width="1">:</td>
													<td><?=$main["kode_kabupaten"]?></td>
												</tr>
												<tr>
													<th colspan="3" class="subtitle_head"><strong>Provinsi</strong></th>
												</tr>
												<tr>
													<td width="300">Provinsi</td><td width="1">:</td>
													<td><?=$main["nama_propinsi"]?></td>
												</tr>
												<tr>
													<td width="300">Kode Provinsi</td><td width="1">:</td>
													<td><?=$main["kode_propinsi"]?></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
					  		</div>
					  	</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
