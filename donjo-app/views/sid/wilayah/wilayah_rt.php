<div class="content-wrapper">
	<section class="content-header">
		<h1>Wilayah Administratif RT</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('sid_core'); ?>"> Daftar Wilayah <?= ucwords($this->setting->sebutan_dusun)?></a></li>
			<li><a href="<?= site_url("sid_core/sub_rw/$id_dusun"); ?>"> Daftar Wilayah RW</a></li>
			<li class="active">Daftar Wilayah RT</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-header with-border">
				<a href="<?= site_url("sid_core/form_rt/$id_dusun/$id_rw")?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Data"><i class="fa fa-plus"></i> Tambah RT</a>
				<a href="<?= site_url("sid_core/cetak_rt/$id_dusun/$id_rw")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank"><i class="fa fa-print "></i> Cetak</a>
				<a href="<?= site_url("sid_core/excel_rt/$id_dusun/$id_rw")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" target="_blank"><i class="fa fa-download"></i> Unduh</a>
				<a href="<?= site_url("sid_core/sub_rw/$id_dusun")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar RW">
					<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar RW
				</a>
			</div>
			<div class="box-header with-border">
				<strong>RW <?= $rw?> / <?= ucwords($this->setting->sebutan_dusun)?> <?= $dusun?> </strong>
			</div>
			<div class="box-body">
				<form id="mainform" name="mainform" action="" method="post">
					<div class="table-responsive">
						<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
							<thead class="bg-gray disabled color-palette">
								<tr>
									<th>No</th>
									<th>Aksi</th>
									<th>Nama RT</th>
									<th>Ketua RT</th>
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
											<?php if ($data['rt'] != "-"): ?>
												<a href="<?= site_url("sid_core/form_rt/$id_dusun/$id_rw/$data[id]")?>" class="btn bg-orange btn-flat btn-sm" title="Ubah"><i class="fa fa-edit"></i></a>
												<a href="#" data-href="<?= site_url("sid_core/delete/rt/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
											<?php endif; ?>
											<?php if ($data['rt'] != "-"): ?>
												<div class="btn-group">
													<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Peta</button>
													<ul class="dropdown-menu" role="menu">
														<li>
															<a href="<?= site_url("sid_core/ajax_kantor_rt_maps/$id_dusun/$id_rw/$data[id]")?>" class="btn btn-social btn-flat btn-block btn-sm"><i class='fa fa-map-marker'></i> Lokasi Kantor RT</a>
														</li>
														<li>
															<a href="<?= site_url("sid_core/ajax_wilayah_rt_maps/$id_dusun/$id_rw/$data[id]")?>" class="btn btn-social btn-flat btn-block btn-sm"><i class='fa fa-map-marker'></i> Peta Wilayah RT</a>
														</li>
													</ul>
												</div>
											<?php endif; ?>
										</td>
										<td width="10%" nowrap><?= $data['rt']?></td>
										<td width="60%" nowrap><strong><?= $data['nama_ketua']?></strong> - <?= $data['nik_ketua']?></td>
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
