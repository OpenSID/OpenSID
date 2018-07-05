<div class="content-wrapper">
	<section class="content-header">
		<h1>Laporan Kelompok Rentan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Laporan Kelompok Rentan</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<form id="mainform" name="mainform" action="<?= site_url('laporan/bulan')?>" method="post" class="form-horizontal">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url("laporan_rentan/cetak")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  target="_blank"><i class="fa fa-print "></i> Print</a>
							<a href="<?= site_url("laporan_rentan/excel/$lap")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  target="_blank"><i class="fa  fa-download"></i> Unduh</a>
						</div>
						<div class="box-header  with-border">
							<?php foreach ($config as $data):?>
							<h4 class="text-center"><strong>PEMERINTAH KABUPATEN/KOTA <?= strtoupper($data['nama_kabupaten'])?></strong></h4>
							<h5 class="text-center"><strong>DATA PILAH KEPENDUDUKAN MENURUT UMUR DAN FAKTOR KERENTANAN (LAMPIRAN A - 9)</strong></h5>
						</div>
						<div class="box-header  with-border">
							<div class="form-group">
								<label class="col-sm-2 col-md-1 control-label" for="kelurahan"><?= ucwords($this->setting->sebutan_desa)?>/Kel</label>
								<div class="col-sm-4 col-md-2">
									<input type="text" class="form-control input-sm" value="<?= unpenetration($data['nama_desa'])?>" disabled/></input>
								</div>
								<label class="col-sm-2 col-md-1 control-label" for="kecamatan"><?= ucwords($this->setting->sebutan_kecamatan)?></label>
								<div class="col-sm-4 col-md-2">
									<input type="text" class="form-control input-sm" value="<?= unpenetration($data['nama_kecamatan'])?>" disabled/></input>
								</div>
							<?php endforeach; ?>
							<?php $bln = date("m");?>
								<label class="col-sm-2 col-md-2 control-label" for="laporan">Lap. Bulan</label>
								<div class="col-sm-4 col-md-1">
									<input type="text" class="form-control input-sm" value="<?= $bln?>" disabled/></input>
								</div>
								<label class="col-sm-2 col-md-1 control-label" for="filter"><?= ucwords($this->setting->sebutan_dusun)?></label>
								<div class="col-sm-4 col-md-2">
									<select class="form-control input-sm" name="dusun" onchange="formAction('mainform','<?= site_url('laporan_rentan/dusun')?>')">
										<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
										<?php foreach ($list_dusun as $data):?>
											<option value="<?= $data['dusun']?>" <?php if ($dusun==$data['dusun']):?>selected<?php endif;?>><?= ununderscore(unpenetration($data['dusun']))?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<?php if ($dusun!=''):?>
										<h4>DATA PILAH <?= strtoupper($this->setting->sebutan_dusun)?> <?= $dusun ?></h4>
									<?php endif; ?>
									<div class="table-responsive">
										<table class="table table-bordered table-hover ">
											<thead class="bg-gray disabled color-palette">
												<tr>
													<th rowspan="2" ><?= ucwords($this->setting->sebutan_dusun)?></th>
													<th rowspan="2">RW</th>
													<th rowspan="2">RT</th>
													<th colspan="2">KK</th>
													<th colspan="6" class="text-center">Kondisi dan Kelompok Umur</th>
													<th colspan="7" class="text-center">Cacat</th>
													<th colspan="2">Sakit Menahun</th>
													<th rowspan="2">Hamil</th>
												</tr>
												<tr>
													<th>L</th>
													<th>P</th>
													<th>> 1  TH</th>
													<th>1-5 TH</th>
													<th>6-12 TH</th>
													<th>13-15 TH</th>
													<th>16-18 TH</th>
													<th>> 60 TH</th>
													<th>Cacat Fisik</th>
													<th>Cacat Netra/ Buta</th>
													<th>Cacat Rungu/ Wicara</th>
													<th>Cacat Mental/ Jiwa</th>
													<th>Cacat Fisik dan Mental</th>
													<th>Cacat Lainnya</th>
													<th>Tidak Cacat</th>
													<th>L</th>
													<th>P</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$bayi=0;
													$balita=0;
													$sd=0;
													$smp=0;
													$sma=0;
													$lansia=0;
													$cacat=0;
													$sakit_L=0;
													$sakit_P=0;
													$hamil=0;
													$jenis_cacat=array('cacat_fisik','cacat_netra','cacat_rungu','cacat_mental','cacat_fisik_mental','cacat_lainnya','tidak_cacat');
													$total_cacat=array();
												?>
												<?php foreach ($main as $data): $id_cluster=$data['id_cluster'];?>
												<tr>
													<td><?= $data['dusunnya']?></td>
													<td><?= $data['rw']?></td>
													<td><?= $data['rt']?></td>
													<td><a href="<?= site_url("penduduk/lap_statistik/$id_cluster/1")?>"><?= $data['L']?></a></td>
													<td><a href="<?= site_url("penduduk/lap_statistik/$id_cluster/2")?>"><?= $data['P']?></a></td>
													<td><a href="<?= site_url("penduduk/lap_statistik/$id_cluster/3")?>"><?= $data['bayi']?></a></td>
													<td><a href="<?= site_url("penduduk/lap_statistik/$id_cluster/4")?>"><?= $data['balita']?></a></td>
													<td><a href="<?= site_url("penduduk/lap_statistik/$id_cluster/5")?>"><?= $data['sd']?></a></td>
													<td><a href="<?= site_url("penduduk/lap_statistik/$id_cluster/6")?>"><?= $data['smp']?></a></td>
													<td><a href="<?= site_url("penduduk/lap_statistik/$id_cluster/7")?>"><?= $data['sma']?></a></td>
													<td><a href="<?= site_url("penduduk/lap_statistik/$id_cluster/8")?>"><?= $data['lansia']?></a></td>
													<?php foreach ($jenis_cacat as $key => $cacat) : ?>
														<?php $kode_cacat = $key + 1;?>
														<td><a href="<?= site_url("penduduk/lap_statistik/$id_cluster/9$kode_cacat")?>"><?= $data[$cacat]?></a></td>
													<?php endforeach; ?>
													<td><a href="<?= site_url("penduduk/lap_statistik/$id_cluster/10")?>"><?= $data['sakit_L']?></a></td>
													<td><a href="<?= site_url("penduduk/lap_statistik/$id_cluster/11")?>"><?= $data['sakit_P']?></a></td>
													<td><a href="<?= site_url("penduduk/lap_statistik/$id_cluster/12")?>"><?= $data['hamil']?></a></td>
													<?php
														$bayi=$bayi+$data['bayi'];
														$balita=$balita+$data['balita'];
														$sd=$sd+$data['sd'];
														$smp=$smp+$data['smp'];
														$sma=$sma+$data['sma'];
														$lansia=$lansia+$data['lansia'];
														$cacat=$cacat+$data['cacat'];
														$sakit_L=$sakit_L+$data['sakit_L'];
														$sakit_P=$sakit_P+$data['sakit_P'];
														$hamil=$hamil+$data['hamil'];
													?>
												</tr>
												  <?php endforeach;?>
											</tbody>
											<tfoot>
												<tr>
													<th colspan="5">Total</th>
													<th><?= $bayi;?></th>
													<th><?= $balita;?></th>
													<th><?= $sd;?></th>
													<th><?= $smp;?></th>
													<th><?= $sma;?></th>
													<th><?= $lansia;?></th>
													<th><?= $cacat;?></th>
													<th><?= $sakit_L;?></th>
													<th><?= $sakit_P;?></th>
													<th><?= $hamil;?></th>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
</div>

