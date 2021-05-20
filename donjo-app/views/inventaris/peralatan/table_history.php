<div class="content-wrapper">
	<section class="content-header">
		<h1>Rincian Jejak Inventaris</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url() ?>inventaris_peralatan/"><i class="fa fa-dashboard"></i>Daftar Asset Lainnya</a></li>
			<li class="active">Rincian Jejak Data</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form class="form-horizontal" id="validasi" name="form_asset" method="post" action="<?= $form_action?>">
			<div class="row">
			 
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<table id="tabel4" class="table table-bordered dataTable table-hover">
													<thead class="bg-gray">
														<tr>
															<th class="text-center" >No</th>
															<th class="text-center" >Aksi</th>
															<th class="text-center">Nama Barang</th>
															<th class="text-center">Kode Barang / Nomor Registrasi</th>
															<th class="text-center">Tahun Pengadaan</th>
															<th class="text-center">Tanggal</th>
															<th class="text-center">Status Asset</th>
															<th class="text-center">Jenis Mutasi</th>
															<th class="text-center" width="300px">Keterangan</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($main as $data): ?>
															<tr>
																<td></td>
																<td nowrap>
																	<?php if ($data->status == "0"): ?>
																		<a href="<?= site_url('inventaris_asset/form_mutasi/'.$data->id); ?>" title="Mutasi Data" class="btn bg-olive btn-flat btn-sm"><i class="fa fa-external-link-square"></i></a>
																	<?php endif; ?>
																	<a href="<?= site_url('inventaris_asset/edit_mutasi/'.$data->id); ?>" title="Edit Data"  class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i> </a>
																	<a href="#" data-href="<?= site_url("api_inventaris_asset/delete_mutasi/$data->id")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																</td>
																<td><?= $data->nama_barang;?></td>
																<td><?= $data->kode_barang;?><br><?= $data->register;?></td>
																<td><?= $data->tahun_pengadaan;?></td>
																<td nowrap><?= date('d M Y',strtotime($data->tahun_mutasi));?></td>
																<td><?= $data->status_mutasi;?></td>
																<td><?= $data->jenis_mutasi;?></td>
																<td><?= $data->keterangan;?></td>
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
		</form>
	</section>
</div>

<script type="text/javascript">
	$(document).ready(function()
	{
		var status = $("#status").val();
		if (status == 'Hapus') {
			$("#mutasi").parent().parent().show();
		} 
		else 
		{
			$("#mutasi").parent().parent().hide();
		}
	})
</script>