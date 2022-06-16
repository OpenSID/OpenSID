<style type="text/css">
	.nowrap { white-space: nowrap; }
</style>
<script>
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
		<h1>Klasifikasi Surat</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Klasifikasi Surat</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="<?php if ($this->modul_ini != 15): ?>col-md-9<?php else: ?>col-md-12<?php endif; ?>">
					<div class="box box-info">
            <div class="box-header with-border">
							<?php if ($this->CI->cek_hak_akses('u')): ?>
								<a href="<?= site_url("{$this->controller}/form")?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Klasifikasi Baru">
									<i class="fa fa-plus"></i>Tambah Klasifikasi Baru
	            	</a>
	            <?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('h')): ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?= site_url("{$this->controller}/delete_all/{$p}/{$o}")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('u')): ?>
								<a href="<?= site_url("{$this->controller}/impor")?>" class="btn btn-social btn-flat bg-black btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Impor Klasifikasi" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Impor Klasifikasi"><i class="fa fa-upload "></i> Impor</a>
							<?php endif; ?>
							<a href="<?= site_url("{$this->controller}/ekspor")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Ekspor Klasifikasi"><i class="fa fa-download"></i> Unduh</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" method="post">
											<input name="kategori" type="hidden" value="<?= $kat?>">
											<div class="row">
												<div class="col-sm-6">
													<select class="form-control input-sm " name="filter" onchange="formAction('mainform','<?= site_url($this->controller . '/filter')?>')">
														<option value="">Semua</option>
														<option value="1" <?php selected($filter, '1') ?>>Aktif</option>
														<option value="0" <?php selected($filter, '0') ?>>Tidak Aktif</option>
													</select>
												</div>
												<div class="col-sm-6">
													<div class="box-tools">
														<div class="input-group input-group-sm pull-right">
															<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("{$this->controller}/search")?>');$('#'+'mainform').submit();}">
															<div class="input-group-btn">
																<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("{$this->controller}/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
																	<?php if ($this->CI->cek_hak_akses('u')): ?>
																		<th><input type="checkbox" id="checkall"/></th>
																	<?php endif; ?>
																	<th>No</th>
																	<?php if ($this->CI->cek_hak_akses('u')): ?>
																		<th>Aksi</th>
																	<?php endif; ?>
																	<?php if ($o == 2): ?>
                                    <th class="nowrap"><a href="<?= site_url("{$this->controller}/index/{$p}/1")?>">Kode <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                  <?php elseif ($o == 1): ?>
                                    <th class="nowrap"><a href="<?= site_url("{$this->controller}/index/{$p}/2")?>">Kode <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                  <?php else: ?>
                                    <th class="nowrap"><a href="<?= site_url("{$this->controller}/index/{$p}/1")?>">Kode <i class='fa fa-sort fa-sm'></i></a></th>
                                  <?php endif; ?>
																	<?php if ($o == 4): ?>
                                    <th><a href="<?= site_url("{$this->controller}/index/{$p}/3")?>">Nama <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                  <?php elseif ($o == 3): ?>
                                    <th><a href="<?= site_url("{$this->controller}/index/{$p}/4")?>">Nama <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                  <?php else: ?>
                                    <th><a href="<?= site_url("{$this->controller}/index/{$p}/3")?>">Nama <i class='fa fa-sort fa-sm'></i></a></th>
                                  <?php endif; ?>
                                  <th>Keterangan</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($main as $data): ?>
																	<tr>
																		<?php if ($this->CI->cek_hak_akses('u')): ?>
																			<td><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
																		<?php endif; ?>
																		<td><?=$data['no']?></td>
																		<?php if ($this->CI->cek_hak_akses('u')): ?>
																			<td class='nowrap'>
																				<a href="<?= site_url("{$this->controller}/form/{$p}/{$o}/{$data['id']}")?>" class="btn btn-warning btn-flat btn-sm"  title="Ubah"><i class="fa fa-edit"></i></a>
																				<?php if ($data['enabled'] == '1'): ?>
																					<a href="<?= site_url("{$this->controller}/lock/{$p}/{$o}/{$data['id']}")?>" class="btn bg-navy btn-flat btn-sm"  title="Non Aktifkan"><i class="fa fa-unlock">&nbsp;</i></a>
																				<?php else: ?>
																					<a href="<?= site_url("{$this->controller}/unlock/{$p}/{$o}/{$data['id']}")?>" class="btn bg-navy btn-flat btn-sm"  title="Aktifkan"><i class="fa fa-lock"></i></a>
	                                      <?php endif ?>
																				<?php if ($this->CI->cek_hak_akses('h')): ?>
																					<a href="#" data-href="<?= site_url("{$this->controller}/delete/{$p}/{$o}/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																				<?php endif; ?>
																		  </td>
																		<?php endif; ?>
																	  <td><?= $data['kode']?></td>
																		<td width="30%"><?= $data['nama']?></td>
																		<td><?= $data['uraian']?></td>
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
                          <form id="paging" action="<?= site_url($this->controller . '/')?>" method="post" class="form-horizontal">
                            <label>
                              Tampilkan
                              <select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
                                <option value="50" <?php selected($per_page, 50); ?> >50</option>
                                <option value="100" <?php selected($per_page, 100); ?> >100</option>
                                <option value="200" <?php selected($per_page, 200); ?> >200</option>
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
                              <li><a href="<?= site_url("{$this->controller}/index/{$paging->start_link}/{$o}")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
                            <?php endif; ?>
                            <?php if ($paging->prev): ?>
                              <li><a href="<?= site_url("{$this->controller}/index/{$paging->prev}/{$o}")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                            <?php endif; ?>
                            <?php for ($i = $paging->start_link; $i <= $paging->end_link; $i++): ?>
                              <li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("{$this->controller}/index/{$i}/{$o}")?>"><?= $i?></a></li>
                            <?php endfor; ?>
                            <?php if ($paging->next): ?>
                              <li><a href="<?= site_url("{$this->controller}/index/{$paging->next}/{$o}")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                            <?php endif; ?>
                            <?php if ($paging->end_link): ?>
                              <li><a href="<?= site_url("{$this->controller}/index/{$paging->end_link}/{$o}")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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