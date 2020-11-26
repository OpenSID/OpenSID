<style>
	.input-sm
	{
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Daftar Pemudik Saat Pandemi Covid-19
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
						<li class="breadcrumb-item active">Data Pemudik</li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-outline card-info">
					<div class="card-header with-border">
						<a href="<?= site_url("covid19/form_pemudik")?>" title="Tambah Data Warga" class="btn btn-flat bg-olive btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left"><i class="fa fa-plus"></i> Tambah Warga Pemudik</a>
						<a href="<?= site_url("covid19/daftar/cetak")?>" class="btn btn-flat bg-purple btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Cetak" target="_blank"><i class="fa fa-print"></i> Cetak
						</a>
						<a href="<?= site_url("covid19/daftar/unduh")?>" class="btn btn-flat bg-navy btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Unduh" target="_blank"><i class="fa fa-download"></i> Unduh
						</a>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper dt-bootstrap no-footer">
									<form class="form-inline" id="mainform" name="mainform" action="" method="post">
												<div class="table-responsive">
													<table class="table table-bordered dataTable table-striped table-hover">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th>No</th>
																<th>Aksi</th>
																<th>NIK</th>
																<th>Nama</th>
																<th>Usia</th>
																<th>JK</th>
																<th>Alamat</th>
																<th>Asal Pemudik</th>
																<th>Tanggal Tiba</th>
																<th>Tujuan Pemudik</th>
																<th>Kontak</th>
																<th>Status</th>
																<th>Keluhan</th>
																<th>Wajib Pantau</th>
															</tr>
														</thead>
														<tbody>
															<?php
															$nomer = $paging->offset;
															foreach ($pemudik_list as $key=>$item):
																$nomer++;
															?>
															<tr>
																<td align="center" width="2"><?= $nomer; ?></td>
																<td nowrap>
																	<?php if ($this->CI->cek_hak_akses('h')): ?>
																		<a href="<?= site_url("covid19/edit_pemudik_form/$item[id]")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data Pemudik" title="Ubah Data Pemudik" class="btn btn-warning btn-flat btn-xs"><i class="fa fa-edit"></i></a>
																		<a href="#" data-href="<?= site_url("covid19/hapus_pemudik/$item[id]")?>" class="btn bg-maroon btn-flat btn-xs" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																	<?php endif; ?>
																</td>
																<td><?= $item["terdata_nama"] ?></td>
																<td nowrap><a href="<?= site_url('covid19/detil_pemudik/'.$item["id"])?>" title="Data terdata"><?= $item['terdata_info'];?></a></td>
																<td><?= $item["umur"] ?></td>
																<?php
																$jk = (strtoupper($item['sex']) === "PEREMPUAN") ? "Pr" : "Lk";
																?>
																<td><?= $jk?></td>
																<td><?= $item["info"];?></td>
																<td><?= $item["asal_mudik"];?></td>
																<td><?= $item["tanggal_datang"];?></td>
																<td><?= $item["tujuan_mudik"];?></td>
																<td><?= $item["no_hp"];?> - <?= $item["email"];?> </td>
																<td><?= $item["status_covid"];?></td>
																<td><?= $item["keluhan_kesehatan"];?></td>
																<td><?= ($item["is_wajib_pantau"] === '1' ? "Ya" : "Tidak"); ?></td>
															</tr>
															<?php endforeach; ?>
														</tbody>
													</table>
										</div>
									</form>
									<?php $this->load->view('global/paging'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('global/confirm_delete');?>

<div class="modal fade" id="modalBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
