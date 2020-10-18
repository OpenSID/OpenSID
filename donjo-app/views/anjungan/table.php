<div class="content-wrapper">
	<section class="content-header">
		<h1>Anjungan Layanan Mandiri</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Anjungan Layanan Mandiri</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
	        <div class="box-header with-border">
						<a href="<?=site_url('anjungan/form')?>" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Anjungan Layanan Mandiri"><i class="fa fa-plus"></i> Tambah Anjungan Layanan Mandiri</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
									<table class="table table-bordered table-striped dataTable table-hover">
										<thead class="bg-gray disabled color-palette">
											<tr>
												<th>No</th>
												<th>Aksi</th>
												<th>IP Address</th>
												<th>Keterangan</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($main as $i => $data): ?>
												<tr>
													<td class="padat"><?= $i + 1 ?></td>
													<td class="aksi">
														<a href="<?= site_url("anjungan/form/$data[id]"); ?>" class="btn bg-orange btn-flat btn-sm" title="Ubah Data"><i class='fa fa-edit'></i></a>
														<?php if ($data['status'] == '1'): ?>
															<a href="<?= site_url("anjungan/lock/$data[id]/2")?>" class="btn bg-navy btn-flat btn-sm"  title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
														<?php else: ?>
															<a href="<?= site_url("anjungan/lock/$data[id]/1")?>" class="btn bg-navy btn-flat btn-sm"  title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
														<?php endif ?>
														<a href="#" data-href="<?=site_url('anjungan/delete/'.$data[id])?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
													</td>
													<td><?= $data['ip_address'] ?></td>
													<td><?= $data['keterangan']; ?></td>
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
	</section>
</div>
<?php $this->load->view('global/confirm_delete');?>

