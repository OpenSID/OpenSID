<?php
/**
 * File ini:
 *
 * View di modul Pemetaan
 *
 * /donjo-app/views/point/table.php
 */
?>

<script type="text/javascript">
	$(function()
	{
		var keyword = <?= $keyword?> ;
		$( "#cari" ).autocomplete(
		{
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Tipe Lokasi</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pengaturan Tipe Lokasi</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-3">
					<?php $this->load->view('plan/nav.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<?php if ($this->CI->cek_hak_akses('u')): ?>
								<a href="<?=site_url('point/form')?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Jenis Lokasi Baru">
									<i class="fa fa-plus"></i>Tambah Jenis Lokasi Baru
								</a>
							<?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('h')): ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?=site_url("point/delete_all/{$p}/{$o}")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<?php endif; ?>
							<a href="<?= site_url("{$this->controller}/clear") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" method="post">
											<div class="row">
												<div class="col-sm-6">
													<select class="form-control input-sm " name="filter" onchange="formAction('mainform', '<?=site_url('point/filter')?>')">
														<option value="">Semua</option>
														<option value="1" <?php if ($filter == 1): ?>selected<?php endif ?>>Aktif</option>
														<option value="2" <?php if ($filter == 2): ?>selected<?php endif ?>>Tidak Aktif</option>
													</select>
												</div>
												<div class="col-sm-6">
													<div class="box-tools">
														<div class="input-group input-group-sm pull-right">
															<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13):$('#'+'mainform').attr('action', '<?=site_url('point/search')?>');$('#'+'mainform').submit();endif">
															<div class="input-group-btn">
																<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url('point/search')?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped dataTable table-hover">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<th><input type="checkbox" id="checkall"/></th>
																	<th>No</th>
																	<th>Aksi</th>
																	<?php if ($o == 2): ?>
                                    <th><a href="<?= site_url("point/index/{$p}/1")?>">Jenis <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                  <?php elseif ($o == 1): ?>
                                    <th><a href="<?= site_url("point/index/{$p}/2")?>">Jenis <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                  <?php else: ?>
                                    <th><a href="<?= site_url("point/index/{$p}/1")?>">Jenis <i class='fa fa-sort fa-sm'></i></a></th>
                                  <?php endif; ?>
                                  <?php if ($o == 4): ?>
                                    <th nowrap><a href="<?= site_url("point/index/{$p}/3")?>">Aktif <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                  <?php elseif ($o == 3): ?>
                                    <th nowrap><a href="<?= site_url("point/index/{$p}/4")?>">Aktif <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                  <?php else: ?>
                                    <th nowrap><a href="<?= site_url("point/index/{$p}/3")?>">Aktif <i class='fa fa-sort fa-sm'></i></a></th>
                                  <?php endif; ?>
																	<th>Simbol</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($main as $data): ?>
																	<tr>
																		<td><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
																		<td><?=$data['no']?></td>
																		<td nowrap>
																			<?php if ($this->CI->cek_hak_akses('u')): ?>
																				<a href="<?= site_url("point/form/{$p}/{$o}/{$data['id']}")?>" class="btn btn-warning btn-flat btn-sm"  title="Ubah"><i class="fa fa-edit"></i></a>
																			<?php endif; ?>
																			<a href="<?= site_url("point/sub_point/{$data['id']}")?>" class="btn bg-purple btn-flat btn-sm"  title="Rincian <?= $data['nama']?>"><i class="fa fa-bars"></i></a>
																			<?php if ($this->CI->cek_hak_akses('u')): ?>
																				<a href="<?= site_url("point/ajax_add_sub_point/{$data['id']}")?>" class="btn bg-olive btn-flat btn-sm"  title="Tambah Kategori <?= $data['nama']?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Kategori <?= $data['nama']?>"><i class="fa fa-plus"></i></a>
																				<?php if ($data['enabled'] == '2'): ?>
																					<a href="<?= site_url('point/point_lock/' . $data['id'])?>" class="btn bg-navy btn-flat btn-sm" title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
																				<?php elseif ($data['enabled'] == '1'): ?>
																					<a href="<?= site_url('point/point_unlock/' . $data['id'])?>" class="btn bg-navy btn-flat btn-sm" title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
																				<?php endif; ?>
																			<?php endif; ?>
																			<?php if ($this->CI->cek_hak_akses('h')): ?>
																				<a href="#" data-href="<?= site_url("point/delete/{$p}/{$o}/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																			<?php endif; ?>
																	  </td>
																		<td width="60%"><?= $data['nama']?></td>
																		<td><?= $data['aktif']?></td>
																		<td><img src="<?= base_url(LOKASI_SIMBOL_LOKASI)?><?= $data['simbol']?>"></td>
																	</tr>
																<?php endforeach; ?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</form>
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="dataTables_length">
                          <form id="paging" action="<?= site_url('point')?>" method="post" class="form-horizontal">
                            <label>
                              Tampilkan
                              <select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
                                <option value="20" <?php selected($per_page, 20); ?> >20</option>
                                <option value="50" <?php selected($per_page, 50); ?> >50</option>
                                <option value="100" <?php selected($per_page, 100); ?> >100</option>
                              </select>
                              Dari
                              <strong><?= $paging->num_rows?></strong>
                              Total Data
                            </label>
                          </form>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers">
                          <ul class="pagination">
                            <?php if ($paging->start_link): ?>
                              <li><a href="<?=site_url("point/index/{$paging->start_link}/{$o}")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
                            <?php endif; ?>
                            <?php if ($paging->prev): ?>
                              <li><a href="<?=site_url("point/index/{$paging->prev}/{$o}")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                            <?php endif; ?>
                            <?php for ($i = $paging->start_link; $i <= $paging->end_link; $i++): ?>
                              <li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("point/index/{$i}/{$o}")?>"><?= $i?></a></li>
                            <?php endfor; ?>
                            <?php if ($paging->next): ?>
                              <li><a href="<?=site_url("point/index/{$paging->next}/{$o}")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                            <?php endif; ?>
                            <?php if ($paging->end_link): ?>
                              <li><a href="<?=site_url("point/index/{$paging->end_link}/{$o}")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
                            <?php endif; ?>
                          </ul>
                        </div>
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
<?php $this->load->view('global/confirm_delete'); ?>
