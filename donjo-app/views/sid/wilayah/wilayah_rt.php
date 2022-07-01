<div class="content-wrapper">
	<section class="content-header">
		<h1>Wilayah Administratif RT</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('sid_core')?>"> Daftar Wilayah <?= ucwords($this->setting->sebutan_dusun)?></a></li>
			<li><a href="<?= site_url("sid_core/sub_rw/{$id_dusun}")?>"> Daftar Wilayah RW</a></li>
			<li class="active">Daftar Wilayah RT</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<?php if ($this->CI->cek_hak_akses('u')): ?>
							<a href="<?= site_url("sid_core/form_rt/{$id_dusun}/{$id_rw}")?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Data"><i class="fa fa-plus"></i> Tambah RT</a>
						<?php endif; ?>
						<a href="<?= site_url("sid_core/cetak_rt/{$id_dusun}/{$id_rw}")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank"><i class="fa fa-print "></i> Cetak</a>
						<a href="<?= site_url("sid_core/excel_rt/{$id_dusun}/{$id_rw}")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" target="_blank"><i class="fa fa-download"></i> Unduh</a>
						<a href="<?= site_url("sid_core/sub_rw/{$id_dusun}")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar RW">
							<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar RW
						</a>
					</div>
					<div class="box-header with-border">
						<strong>RW <?= $rw?> / <?= ucwords($this->setting->sebutan_dusun)?> <?= $dusun?> </strong>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" method="post">
										<div class="row">
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
																	<td><?= $data['no'] ?></td>
																	<td nowrap>
																		<?php if ($data['rt'] != '-'): ?>
																			<?php if ($this->CI->cek_hak_akses('u')): ?>
																				<a href="<?= site_url("sid_core/urut/rt/{$paging->page}/{$data['id']}/1/{$id_dusun}/{$id_rw}"); ?>" class="btn bg-olive btn-flat btn-sm <?php ($data['no'] == $paging->num_rows) && print 'disabled'; ?>" title="Pindah Posisi Ke Bawah"><i class="fa fa-arrow-down"></i></a>
																				<a href="<?= site_url("sid_core/urut/rt/{$paging->page}/{$data['id']}/2/{$id_dusun}/{$id_rw}"); ?>" class="btn bg-olive btn-flat btn-sm <?php ($data['no'] == 1 && $paging->page == $paging->start_link) && print 'disabled'; ?>" title="Pindah Posisi Ke Atas"><i class="fa fa-arrow-up"></i></a>
																				<a href="<?= site_url("sid_core/form_rt/{$id_dusun}/{$id_rw}/{$data['id']}")?>" class="btn bg-orange btn-flat btn-sm" title="Ubah"><i class="fa fa-edit"></i></a>
																			<?php endif; ?>
																			<?php if ($this->CI->cek_hak_akses('h')): ?>
	 																			<a href="#" data-href="<?= site_url("sid_core/delete/rt/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
	 																		<?php endif; ?>
																		<?php endif; ?>
																		<?php if ($data['rt'] != '-'): ?>
																			<div class="btn-group">
																				<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Peta</button>
																				<ul class="dropdown-menu" role="menu">
																					<li>
																						<a href="<?= site_url("sid_core/ajax_kantor_rt_maps/{$id_dusun}/{$id_rw}/{$data['id']}")?>" class="btn btn-social btn-flat btn-block btn-sm"><i class='fa fa-map-marker'></i> Lokasi Kantor RT</a>
																					</li>
																					<li>
																						<a href="<?= site_url("sid_core/ajax_wilayah_rt_maps/{$id_dusun}/{$id_rw}/{$data['id']}")?>" class="btn btn-social btn-flat btn-block btn-sm"><i class='fa fa-map'></i> Peta Wilayah RT</a>
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
<?php $this->load->view('global/confirm_delete'); ?>
