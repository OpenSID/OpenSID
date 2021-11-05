<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url('bumindes_pembangunan/form'); ?>" title="Tambah Kader Pembangunan" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Data</a>
							<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?= site_url("bumindes_pembangunan/delete_all")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<a href="<?= site_url("bumindes_pembangunan/cetakkader")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak" target="_blank"><i class="fa fa-print"></i> Cetak
						</a>	</div>
					
						<div class="box-body">
							<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<form id="mainform" name="mainform" method="post">
									
									<div class="table-responsive">

								
										<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
											<thead class="bg-gray disabled color-palette">
												<tr>
													<th><input type="checkbox" id="checkall"/></th>
													
													<th width="10%">Aksi</th>
													<th width="10%">No</th>
													<th width="30%">Nama</th>
													<th width="10%">Umur</th>
													<th width="10%">Jenis Kelamin</th>
													<th width="10%">Pendidikan/Kursus</th>
													<th width="10%">Bidang</th>
													<th width="10%">Alamat</th>
													<th>Keterangan</th>
												</tr>
											</thead>
							<tbody>
																<?php foreach ($pembangunan as $data): ?>
																	<tr>
																		<td><input type="checkbox" name="id_cb[]" value="<?=$data['idpenduduk']?>" /></td>
																		
																		<td align="center" nowrap>
																			<a href="<?= site_url("bumindes_pembangunan/form/$data[idpenduduk]")?>" class="btn bg-orange btn-flat btn-sm" data-remote="false" data-title="Ubah Data Kader" title="Ubah Data Kader"><i class="fa fa-edit"></i></a>
																			<a href="#" data-href="<?= site_url("bumindes_pembangunan/delete/$data[idpenduduk]/$data[pendidikan_kk_id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                                    </td>
									<td align="center"><?=$data['no']?></td>
                                    <td nowrap><?=$data['nama']?></td>
									
									<td align="center"><?=$data['umur']?></td>
																		<td align="center"><?=$data['jeniskelamin']?></td>
																		<td nowrap><?=$data['pendidikankursus']?></td>
																		<td nowrap><?=$data['pendidikanahli']?></td>
																		<td nowrap><?=$data['alamat_sekarang']?></td>
																		<td nowrap><?=$data['keterangan']?></td>
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
		</form>
	</section>
	<?php $this->load->view('global/confirm_delete'); ?>