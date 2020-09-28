<div class="content-wrapper">
	<section class="content-header">
		<h1>Wilayah Administratif RW</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('sid_core'); ?>"> Daftar <?= ucwords($this->setting->sebutan_dusun)?></a></li>
			<li class="active">Daftar RW</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-header with-border">
				<a href="<?= site_url("sid_core/form_rw/$id_dusun")?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Data"><i class="fa fa-plus"></i> Tambah RW</a>
				<a href="<?= site_url("sid_core/cetak_rw/$id_dusun")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank"><i class="fa fa-print "></i> Cetak</a>
				<a href="<?= site_url("sid_core/excel_rw/$id_dusun")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" target="_blank"><i class="fa fa-download"></i> Unduh</a>
				<a href="<?= site_url("sid_core")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar RW">
					<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar <?= ucwords($this->setting->sebutan_dusun)?>
				</a>
			</div>
			<div class="box-header with-border">
				<strong><?= ucwords($this->setting->sebutan_dusun)?> <?= $dusun?></strong>
			</div>
			<div class="box-body">
				<form id="mainform" name="mainform" action="" method="post">
					<div class="table-responsive">
						<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
							<thead class="bg-gray disabled color-palette">
								<tr >
									<th>No</th>
									<th>Aksi</th>
									<th>RW</th>
									<th>Ketua RW</th>
									<th>RT</th>
									<th>KK</th>
									<th>L+P</th>
									<th>L</th>
									<th>P</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($main as $key => $data): ?>
									<tr>
										<td class="padat"><?= ($key + 1); ?></td>
										<td class="aksi">
											<a href="<?= site_url("sid_core/sub_rt/$id_dusun/$data[id]")?>" class="btn bg-purple btn-flat btn-sm" title="Rincian Sub Wilayah RW"><i class="fa fa-list"></i></a>
											<?php if ($data['rw'] != "-"): ?>
												<a href="<?= site_url("sid_core/form_rw/$id_dusun/$data[id]")?>" class="btn bg-orange btn-flat btn-sm" title="Ubah"><i class="fa fa-edit"></i></a>
											<?php endif; ?>
											<?php if ($data['rw'] != "-"): ?>
												<a href="#" data-href="<?= site_url("sid_core/delete/rw/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
											<?php endif; ?>
											<?php if ($data['rw'] != "-"): ?>
												<div class="btn-group">
													<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Peta</button>
													<ul class="dropdown-menu" role="menu">
														<li>
															<a href="<?= site_url("sid_core/ajax_kantor_rw_maps/$id_dusun/$data[id]")?>" class="btn btn-social btn-flat btn-block btn-sm"><i class='fa fa-map-marker'></i> Lokasi Kantor RW</a>
														</li>
														<li>
															<a href="<?= site_url("sid_core/ajax_wilayah_rw_maps/$id_dusun/$data[id]")?>" class="btn btn-social btn-flat btn-block btn-sm"><i class='fa fa-map-marker'></i> Peta Wilayah RW</a>
														</li>
													</ul>
												</div>
											<?php endif; ?>
										</td>
										<td><?= $data['rw']?></td>
										<?php if ($data['rw']=="-"): ?>
											<td>
												Pergunakan RW ini apabila RT berada langsung di bawah <?= ucwords($this->setting->sebutan_dusun)?>, yaitu tidak ada RW
											</td>
										<?php else: ?>
											<td width="60%" nowrap><strong><?= $data['nama_ketua']?></strong> - <?= $data['nik_ketua']?></td>
										<?php endif; ?>
										<td><a href="<?= site_url("sid_core/sub_rt/$id_dusun/$data[id]"); ?>" title="Rincian Sub Wilayah"><?= $data['jumlah_rt']?></a></td>
										<td class="padat"><?= $data['jumlah_kk']?></td>
										<td class="padat"><?= $data['jumlah_warga']?></td>
										<td class="padat"><?= $data['jumlah_warga_l']?></td>
										<td class="padat"><?= $data['jumlah_warga_p']?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
							<tfoot class="bg-gray disabled color-palette">
								<tr>
									<th colspan="4">TOTAL</th>
									<th class="padat"><?= $total['jmlrt']?></th>
									<th class="padat"><?= $total['jmlkk']?></th>
									<th class="padat"><?= $total['jmlwarga']?></th>
									<th class="padat"><?= $total['jmlwargal']?></th>
									<th class="padat"><?= $total['jmlwargap']?></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</form>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete');?>
