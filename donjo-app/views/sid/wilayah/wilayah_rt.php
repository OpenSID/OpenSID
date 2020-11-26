<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Wilayah Administratif RW
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
						<li class="breadcrumb-item"> Daftar Wilayah <?= ucwords($this->setting->sebutan_dusun)?></li>
						<li class="breadcrumb-item"><a href="<?= site_url("sid_core/sub_rw/$id_dusun")?>"> Daftar Wilayah RW</a></li>
						<li class="breadcrumb-item active">Daftar RT</li>
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
						<a href="<?= site_url("sid_core/form_rt/$id_dusun/$id_rw")?>" class="btn btn-flat btn-success btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Tambah Data"><i class="fa fa-plus"></i> Tambah RT</a>
						<a href="<?= site_url("sid_core/cetak_rt/$id_dusun/$id_rw")?>" class="btn btn-flat bg-purple btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Cetak Data" target="_blank"><i class="fa fa-print "></i> Cetak</a>
						<a href="<?= site_url("sid_core/excel_rt/$id_dusun/$id_rw")?>" class="btn btn-flat bg-navy btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Unduh Data" target="_blank"><i class="fa fa-download"></i> Unduh</a>
						<a href="<?= site_url("sid_core/sub_rw/$id_dusun")?>" class="btn btn-flat btn-info btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Kembali Ke Daftar RW">
							<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar RW
						</a>
					</div>
					<div class="card-header with-border">
						<strong>RW <?= $rw?> / <?= ucwords($this->setting->sebutan_dusun)?> <?= $dusun?> </strong>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper dt-bootstrap no-footer">
									<form class="form-inline" id="mainform" name="mainform" action="" method="post">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-bordered table-striped dataTable table-hover">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th class="padat">No</th>
																<th class="padat">Aksi</th>
																<th>RT</th>
																<th width="30%">Ketua RT</th>
																<th>NIK Ketua RT</th>
																<th>KK</th>
																<th>L+P</th>
																<th>L</th>
																<th>P</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($main as $indeks => $data): ?>
																<tr>
																	<td><?= $indeks + 1 ?></td>
																	<td nowrap>
																		<?php if ($data['rt']!="-"): ?>
																			<a href="<?= site_url("sid_core/form_rt/$id_dusun/$id_rw/$data[id]")?>" class="btn bg-orange btn-flat btn-xs" title="Ubah"><i class="fa fa-edit"></i></a>
																			<a href="#" data-href="<?= site_url("sid_core/delete/rt/$data[id]")?>" class="btn bg-maroon btn-flat btn-xs" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																		<?php endif; ?>
																		<?php if ($data['rt']!="-"): ?>
																			<div class="btn-group">
																				<button type="button" class="btn btn-flat btn-info btn-xs" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Peta</button>
																				<ul class="dropdown-menu text-xs" role="menu">
																					<li>
																						<a class="dropdown-item" href="<?= site_url("sid_core/ajax_kantor_rt_maps/$id_dusun/$id_rw/$data[id]")?>" class="btn btn-flat btn-block btn-xs"><i class='fa fa-map-marker'></i> Lokasi Kantor RT</a>
																					</li>
																					<li>
																						<a class="dropdown-item" href="<?= site_url("sid_core/ajax_wilayah_rt_maps/$id_dusun/$id_rw/$data[id]")?>" class="btn btn-flat btn-block btn-xs"><i class='fa fa-map-marker'></i> Peta Wilayah RT</a>
																					</li>
																				</ul>
																			</div>
																		<?php endif; ?>
																	</td>
																	<td><?= $data['rt']?></td>
																	<td nowrap><strong><?= $data['nama_ketua']?></strong></td>
																	<td nowrap><?= $data['nik_ketua']?></td>
																	<td><?= $data['jumlah_kk']?></td>
																	<td><?= $data['jumlah_warga']?></td>
																	<td><?= $data['jumlah_warga_l']?></td>
																	<td><?= $data['jumlah_warga_p']?></td>
																</tr>
															<?php endforeach; ?>
														</tbody>
														<tfoot>
															<tr>
																<th colspan="5"><label>TOTAL</label></th>
																<th><?= $total['jmlkk']?></th>
																<th><?= $total['jmlwarga']?></th>
																<th><?= $total['jmlwargal']?></th>
																<th><?= $total['jmlwargap']?></th>
															</tr>
														</tfoot>
													</table>
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
<?php $this->load->view('global/confirm_delete');?>
