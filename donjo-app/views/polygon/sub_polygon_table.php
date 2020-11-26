<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Kategori Area</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('polygon')?>"> Daftar Tipe Area</a></li>
			<li class="active">Pengaturan Tipe Area</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form class="form-inline" id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-3">
          <?php $this->load->view('plan/nav.php')?>
				</div>
				<div class="col-md-9">
					<div class="card card-outline card-info">
            <div class="card-header with-border">
							<a href="<?= site_url("polygon/ajax_add_sub_polygon/$polygon[id]")?>" class="btn btn-flat btn-success btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left"  title="Tambah Kategori <?= $polygon['nama']?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Kategori <?= $polygon['nama']?>">
								<i class="fa fa-plus"></i>Tambah Kategori <?= $polygon['nama']?>
            	</a>
							<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?=site_url("polygon/delete_all_sub_polygon/$polygon[id]")?>')" class="btn btn-flat btn-danger btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<a href="<?= site_url("polygon")?>" class="btn btn-flat btn-info btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Tipe Area
            	</a>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper dt-bootstrap no-footer">
										<form class="form-inline" id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<h5 class="box-title text-center">Daftar Kategori <?= $polygon['nama']; ?></h5>
														<table class="table table-bordered dataTable table-hover">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<th><input type="checkbox" id="checkall"/></th>
																	<th>No</th>
																	<th>Aksi</th>
																	<th>Nama</th>
																	<th>Aktif</th>
																	<th>Warna</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($subpolygon as $data): ?>
																	<tr>
																		<td><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
																		<td><?=$data['no']?></td>
																		<td nowrap>
																			<a href="<?= site_url("polygon/ajax_add_sub_polygon/$polygon[id]/$data[id]")?>" class="btn btn-warning btn-flat btn-xs"  title="Ubah" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Kategori <?= $polygon[nama]?>"><i class="fa fa-edit"></i></a>
																			<?php if ($data['enabled'] == '2'): ?>
																				<a href="<?= site_url("polygon/polygon_lock_sub_polygon/$polygon[id]/$data[id]")?>" class="btn bg-navy btn-flat btn-xs" title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
																			<?php elseif ($data['enabled'] == '1'): ?>
																				<a href="<?= site_url("polygon/polygon_unlock_sub_polygon/$polygon[id]/$data[id]")?>" class="btn bg-navy btn-flat btn-xs" title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
																			<?php endif; ?>
																			<a href="#" data-href="<?= site_url("polygon/delete_sub_polygon/$polygon[id]/$data[id]")?>" class="btn bg-maroon btn-flat btn-xs"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																	  </td>
																		<td width="70%"><?= $data['nama']?></td>
																		<td><?= $data['aktif']?></td>
																		<td><div style="background-color:<?= $data['color']?>">&nbsp;<div></td>
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
		</form>
	</section>
</div>
<?php $this->load->view('global/confirm_delete');?>