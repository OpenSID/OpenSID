<style>
	.input-sm
	{
		padding: 4px 4px;
	}
	@media (max-width:780px)
	{
		.btn-group-vertical
		{
			display: block;
		}
	}
	.table-responsive
	{
		min-height:275px;
	}
	.padat {width: 1%;}
	th.horizontal {width: 20%;}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Rincian C-DESA</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('cdesa')?>"> Daftar C-DESA</a></li>
			<li class="active">Rincian C-DESA</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-outline card-info">
					<div class="card-header with-border">
						<a href="<?=site_url("cdesa/create_mutasi/".$cdesa['id'])?>" class="btn btn-flat btn-success btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left"  title="Tambah Persil">
							<i class="fa fa-plus"></i>Tambah Mutasi Persil
						</a>
						<a href="<?=site_url('cdesa')?>" class="btn btn-flat btn-info btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Kembali Ke Daftar C-DESA"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar C-DESA</a>
						<a href="<?= site_url("cdesa/form_c_desa/".$cdesa['id'])?>" class="btn btn-flat bg-purple btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Cetak Data" target="_blank">
							<i class="fa fa-print"></i>Cetak C-DESA
						</a>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper dt-bootstrap no-footer">
									<form class="form-inline" id="mainform" name="mainform" action="" method="post">
										<input type="hidden" name="id" value="<?php echo $this->uri->segment(4) ?>">
										<div class="row">
											<div class="col-sm-12">
												<div class="card-header with-border">
													<h3 class="box-title">Rincian C-DESA</h3>
												</div>
												<div class="card-body">
													<table class="table table-bordered  table-striped table-hover" >
														<tbody>
															<tr>
																<th class="horizontal">Nama Pemilik</td>
																<td> : <?= $pemilik["namapemilik"]?></td>
															</tr>
															<tr>
																<th class="horizontal">NIK</td>
																<td> :  <?= $pemilik["nik"]?></td>
															</tr>
															<tr>
																<th class="horizontal">Alamat</td>
																<td> :  <?= $pemilik["alamat"]?></td>
															</tr>
															<tr>
																<th class="horizontal">Nomor C-DESA</td>
																<td> : <?= sprintf("%04s", $cdesa['nomor'])?></td>
															</tr>
															<tr>
																<th class="horizontal">Nama Pemilik Tertulis di C-Desa</td>
																<td> : <?= $cdesa["nama_kepemilikan"]?></td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
											<div class="col-sm-12">
												<div class="row">
													<div class="col-sm-9">
														<div class="card-header with-border">
															<h3 class="box-title">Daftar Persil C-Desa</h3>
														</div>
													</div>
												</div>
											</div>
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-bordered table-striped dataTable table-hover">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th>No</th>
																<th>Aksi</th>
																<th>No. Persil : No. Urut Bidang</th>
																<th>Kelas Tanah</th>
																<th>Lokasi</th>
																<th>Luas Keseluruhan Persil (M2)</th>
																<th>Jumlah Mutasi</th>
															</tr>
														</thead>
														<tbody>
															<?php $nomer = 0?>
															<?php foreach ($persil as $key => $item): $nomer++;?>
																<tr>
																	<td class="text-center padat"><?= $nomer?></td>
																	<td nowrap class="padat">
																		<a href='<?= site_url("cdesa/mutasi/$cdesa[id]/$item[id]")?>' class="btn bg-maroon btn-flat btn-xs"  title="Daftar Mutasi"><i class="fa fa-exchange"></i></a>
																	</td>
																	<td>
																		<a href="<?= site_url("data_persil/rincian/".$item["id"])?>">
																			<?= $item['nomor'].' : '.$item['nomor_urut_bidang']?>
																			<?php if ($cdesa['id'] == $item['cdesa_awal']): ?>
																				<code>( Pemilik awal )</code>
																			<?php endif; ?>
																		</a>
																	</td>
																	<td><?= $item['kelas_tanah']?></td>
																	<td><?= $item['alamat'] ?: $item['lokasi']?></td>
																	<td><?= $item['luas_persil']?></td>
																	<td><?= $item['jml_mutasi']?></td>
																</tr>
															<?php endforeach; ?>
														</tbody>
													</table>
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
		</div>
	</section>
</div>

