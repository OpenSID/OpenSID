<div class="content-wrapper">
	<section class="content-header">
		<h1>Data Peserta Program Bantuan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('program_bantuan')?>"> Daftar Program Bantuan</a></li>
			<li><a href="<?= site_url()?>program_bantuan/detail/1/<?= $detail['id']?>/1"> Rincian Program Bantuan</a></li>
			<li class="active">Data Peserta Program Bantuan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url('program_bantuan')?>" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Program Bantuan</a>
						<a href="<?= site_url()?>program_bantuan/detail/1/<?= $detail['id']?>/1" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Rincian Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Rincian Program Bantuan</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<div class="row">
										<div class="col-sm-12">
											<div class="box-header with-border">
												<h3 class="box-title">Rincian Program</h3>
											</div>
											<div class="box-body">
												<table class="table table-bordered  table-striped table-hover" >
													<tbody>
														<tr>
															<td style="padding-top : 10px;padding-bottom : 10px; width:30%;" >Nama Program</td>
															<td> : <?= strtoupper($detail["nama"])?></td>
														</tr>
														<tr>
															<td style="padding-top : 10px;padding-bottom : 10px;" >Sasaran Peserta</td>
															<td> : <?= $sasaran[$detail["sasaran"]]?></td>
														</tr>
                            <tr>
															<td style="padding-top : 10px;padding-bottom : 10px;" >Masa Berlaku</td>
															<td> : <?= fTampilTgl($detail["sdate"],$detail["edate"])?></td>
														</tr>
                            <tr>
															<td style="padding-top : 10px;padding-bottom : 10px;" >Keterangan</td>
															<td> : <?= $detail["ndesc"]?></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
                    <div class="col-sm-12">
											<div class="box-header with-border">
												<h3 class="box-title">Data Peserta</h3>
											</div>
											<div class="box-body">
												<table class="table table-bordered  table-striped table-hover" >
													<tbody>
														<tr>
															<td style="padding-top : 10px;padding-bottom : 10px; width:30%;" >
                                <?php if ($detail["sasaran"] == 1): ?>
                                  NIK / Nama
                                <?php elseif ($detail["sasaran"] == 2): ?>
                                  No. KK / Nama KK
                                <?php elseif ($detail["sasaran"] == 3): ?>
                                  No. Rumah Tangga / Nama Kepala Rumah Tangga
                                <?php elseif ($detail["sasaran"] == 4): ?>
                                  Nama Kelompok / Nama Ketua Kelompok
                                <?php endif; ?>
                              </td>
															<td> : <?= $peserta["peserta_nama"]." / ".$peserta["peserta_info"]?></td>
														</tr>
                            <?php if ($individu): ?>
                              <?php if ($detail["sasaran"] == 1): ?>
                                <tr>
                                  <td style="padding-top : 10px;padding-bottom : 10px;" >Alamat</td>
                                  <td>
                                    <?= $individu['alamat_wilayah']; ?>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="padding-top : 10px;padding-bottom : 10px;" >Tempat Tanggal Lahir (Umur)</td>
                                  <td>
                                    <?= $individu['tempatlahir']?> <?= tgl_indo($individu['tanggallahir'])?> (<?= $individu['umur']?> Tahun)
                                  </td>
                                </tr>
                                <tr>
                                  <td style="padding-top : 10px;padding-bottom : 10px;" >Pendidikan</td>
                                  <td>
                                    <?= $individu['pendidikan']?>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="padding-top : 10px;padding-bottom : 10px;" >Warganegara / Agama</td>
                                  <td>
                                    <?= $individu['warganegara']?> / <?= $individu['agama']?>
                                  </td>
                                </tr>
                              <?php elseif ($detail["sasaran"] == 2): ?>
                                <tr>
                                  <td style="padding-top : 10px;padding-bottom : 10px;" >Alamat Keluarga</td>
                                  <td>
                                    <?= $individu['alamat_wilayah']; ?>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="padding-top : 10px;padding-bottom : 10px;" >Tempat Tanggal Lahir (Umur) KK</td>
                                  <td>
                                    <?= $individu['tempatlahir']?> <?= tgl_indo($individu['tanggallahir'])?> (<?= $individu['umur']?> Tahun)
                                  </td>
                                </tr>
                                <tr>
                                  <td style="padding-top : 10px;padding-bottom : 10px;" >Pendidikan KK</td>
                                  <td>
                                    <?= $individu['pendidikan']?>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="padding-top : 10px;padding-bottom : 10px;" >Warganegara / Agama KK</td>
                                  <td>
                                    <?= $individu['warganegara']?> / <?= $individu['agama']?>
                                  </td>
                                </tr>
                              <?php elseif ($detail["sasaran"] == 3): ?>
                                <tr>
                                  <td style="padding-top : 10px;padding-bottom : 10px;" >Alamat Kepala Rumah Tangga</td>
                                  <td>
                                    <?= $individu['alamat_wilayah']; ?>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="padding-top : 10px;padding-bottom : 10px;" >Tempat Tanggal Lahir (Umur) Kepala RTM</td>
                                  <td>
                                    <?= $individu['tempatlahir']?> <?= tgl_indo($individu['tanggallahir'])?> (<?= $individu['umur']?> Tahun)
                                  </td>
                                </tr>
                                <tr>
                                  <td style="padding-top : 10px;padding-bottom : 10px;" >Pendidikan Kepala RTM</td>
                                  <td>
                                    <?= $individu['pendidikan']?>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="padding-top : 10px;padding-bottom : 10px;" >Warganegara / Agama Kepala RTM</td>
                                  <td>
                                    <?= $individu['warganegara']?> / <?= $individu['agama']?>
                                  </td>
                                </tr>
                              <?php elseif ($detail["sasaran"] == 4): ?>
                                <tr>
                                  <td style="padding-top : 10px;padding-bottom : 10px;" >Alamat Ketua Kelompok</td>
                                  <td>
                                    <?= $individu['alamat_wilayah']; ?>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="padding-top : 10px;padding-bottom : 10px;" >Tempat Tanggal Lahir (Umur) Ketua Kelompok</td>
                                  <td>
                                    <?= $individu['tempatlahir']?> <?= tgl_indo($individu['tanggallahir'])?> (<?= $individu['umur']?> Tahun)
                                  </td>
                                </tr>
                                <tr>
                                  <td style="padding-top : 10px;padding-bottom : 10px;" >Pendidikan Ketua Kelompok</td>
                                  <td>
                                    <?= $individu['pendidikan']?>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="padding-top : 10px;padding-bottom : 10px;" >Warganegara / Agama Ketua Kelompok</td>
                                  <td>
                                    <?= $individu['warganegara']?> / <?= $individu['agama']?>
                                  </td>
                                </tr>

                              <?php endif; ?>
                            <?php endif; ?>
														<tr>
															<td style="padding-top : 10px;padding-bottom : 10px;" >Nomor Kartu Peserta</td>
															<td> : <?= $peserta["no_id_kartu"]?></td>
														</tr>
                            <tr>
															<td style="padding-top : 10px;padding-bottom : 10px;"  colspan='2'>Identitas Pada Kartu Peserta</td>
														</tr>
                            <tr>
															<td style="padding-top : 10px;padding-bottom : 10px;" >NIK</td>
															<td> : <?= $peserta["kartu_nama"]?></td>
														</tr>
                            <tr>
                            <tr>
															<td style="padding-top : 10px;padding-bottom : 10px;" >Nama</td>
															<td> : <?= $peserta["kartu_nik"]?></td>
														</tr>
                            <tr>
															<td style="padding-top : 10px;padding-bottom : 10px;" >Tempat Lahir</td>
															<td> : <?= $peserta["kartu_tempat_lahir"]?></td>
														</tr>
                            <tr>
															<td style="padding-top : 10px;padding-bottom : 10px;" >Tanggal Lahir</td>
															<td> : <?= tgl_indo($peserta["kartu_tanggal_lahir"])?></td>
														</tr>
                            <tr>
															<td style="padding-top : 10px;padding-bottom : 10px;" >AlamatNama</td>
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

