<div class="content-wrapper">
	<?php $detail = $data[0];?>
	<section class="content-header">
		<h1>Profil Penerima Manfaat Program</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('program_bantuan')?>"> Daftar Program Bantuan</a></li>
			<li class="active">Profil Penerima Program Bantuan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url('program_bantuan')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Program Bantuan</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<div class="row">
										<div class="col-sm-12">
											<div class="box-header with-border">
												<h3 class="box-title"><b>Profil Penerima Manfaat Program</b></h3>
											</div>
											<div class="box-body">
												<table class="table table-bordered  table-striped table-hover" >
													<tbody>
														<tr>
															<td style="padding-top : 10px;padding-bottom : 10px; width:30%;" >Nama Penerima</td>
															<td> : <?= strtoupper($profil["nama"])?></td>
														</tr>
														<tr>
															<td style="padding-top : 10px;padding-bottom : 10px;" >Keterangan</td>
															<td> : <?= $profil["ndesc"]?></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="box-header with-border">
												<h3 class="box-title"><b>Program yang pernah diikuti</b></h3>
											</div>
											<div class="box-body">
												<div class="table-responsive">
													<table class="table table-bordered dataTable table-hover">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th width="1%">No</th>
																<th nowrap>Waktu/Tanggal</th>
																<th nowrap>Nama Program</th>
																<th >Keterangan</th>
															</tr>
														</thead>
														<tbody>
															<?php $nomer = 1;?>
															<?php foreach ($programkerja as $item): ?>
																<tr>
																	<td class="text-center"><?= $nomer++?></td>
																	<td nowrap><?= fTampilTgl($item["sdate"],$item["edate"]);?></td>
																	<td nowrap><a href="<?= site_url("program_bantuan/detail/$item[id]")?>"><?= $item["nama"] ?></a></td>
																	<td nowrap width="60%"><?= $item["ndesc"];?></td>
																</tr>
															<?php endforeach; ?>
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
		</div>
	</section>
</div>

