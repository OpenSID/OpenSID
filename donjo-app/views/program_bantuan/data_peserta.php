<style type="text/css">
	.tabel, td {
		height: 30px;
		padding: 5px;
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Data Peserta Program Bantuan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('program_bantuan')?>"> Daftar Program Bantuan</a></li>
			<li><a href="<?= site_url("program_bantuan/detail/$detail[id]")?>"> Rincian Program Bantuan</a></li>
			<li class="active">Data Peserta Program Bantuan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url('program_bantuan')?>" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Program Bantuan</a>
						<a href="<?= site_url("program_bantuan/detail/$detail[id]")?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Rincian Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Rincian Program Bantuan</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<div class="row">
										<div class="col-sm-12">
											<div class="box-header with-border">
												<h3 class="box-title"><b>Rincian Program</b></h3>
											</div>
											<div class="box-body">
												<table class="table table-bordered table-striped table-hover" >
													<tbody>
														<tr>
															<td width="30%">Nama Program</td>
															<td> : <?= strtoupper($detail["nama"])?></td>
														</tr>
														<tr>
															<td>Sasaran Peserta</td>
															<td> : <?= $sasaran[$detail["sasaran"]]?></td>
														</tr>
														<tr>
															<td>Masa Berlaku</td>
															<td> : <?= fTampilTgl($detail["sdate"],$detail["edate"])?></td>
														</tr>
														<tr>
															<td>Keterangan</td>
															<td> : <?= $detail["ndesc"]?></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="box-header with-border">
												<h3 class="box-title"><b>Data Peserta</b></h3>
											</div>
											<div class="box-body">
												<table class="table table-bordered table-striped table-hover" >
													<tbody>
														<?php if ($individu): ?>
															<tr>
																<td width="30%"><?=$individu['judul_nik']?></td>
																<td> : <?= $individu['nik']; ?></td>
															</tr>
															<tr>
																<td>Nama <?=$individu['judul']?></td>
																<td> : <?= $individu['nama']; ?></td>
															</tr>
															<?php if ($detail["sasaran"] == 2): ?>
																<tr>
																	<td>No. KK</td>
																	<td> : <?= $individu['no_kk']; ?></td>
																</tr>
																<tr>
																	<td>Nama Kepala Keluarga</td>
																	<td> : <?= $individu['nama_kk']; ?></td>
																</tr>
																<tr>
																	<td>Status KK</td>
																	<td> : <?= $individu['hubungan']; ?></td>
																</tr>
															<?php endif; ?>
															<tr>
																<td>Alamat <?=$individu['judul']?></td>
																<td> : <?= $individu['alamat_wilayah']; ?></td>
															</tr>
															<tr>
																<td>Tempat Tanggal Lahir <?=$individu['judul']?></td>
																<td> : <?= $individu['tempatlahir']?>, <?= tgl_indo($individu['tanggallahir'])?></td>
															</tr>
															<tr>
																<td>Umur <?=$individu['judul']?></td>
																<td> : <?= $individu['umur']?> Tahun</td>
															</tr>
															<tr>
																<td>Pendidikan <?=$individu['judul']?></td>
																<td> : <?= $individu['pendidikan']?></td>
															</tr>
															<tr>
																<td>Warganegara / Agama <?=$individu['judul']?></td>
																<td> : <?= $individu['warganegara']?> / <?= $individu['agama']?></td>
															</tr>
														<?php endif; ?>
														<tr>
															<td colspan='2'><b>Identitas Pada Kartu Peserta</b></td>
														</tr>
														<tr>
															<td>Nomor Kartu Peserta</td>
															<td> : <?= $peserta["no_id_kartu"]?></td>
														</tr>
														<tr>
															<td>NIK</td>
															<td> : <?= $peserta["kartu_nama"]?></td>
														</tr>
														<tr>
														<tr>
															<td>Nama</td>
															<td> : <?= $peserta["kartu_nik"]?></td>
														</tr>
														<tr>
															<td>Tempat Lahir</td>
															<td> : <?= $peserta["kartu_tempat_lahir"]?></td>
														</tr>
														<tr>
															<td>Tanggal Lahir</td>
															<td> : <?= tgl_indo($peserta["kartu_tanggal_lahir"])?></td>
														</tr>
														<tr>
															<td>Alamat</td>
															<td> : <?= $peserta["kartu_alamat"]?></td>
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
				</div>
			</div>
		</div>
	</section>
</div>
