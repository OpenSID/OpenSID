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
		<h1>Komentar</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i>Home</a></li>
			<li class="active">Komentar</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?=site_url("komentar/delete_all/$p/$o")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-6">
													<select class="form-control input-sm " name="filter" onchange="formAction('mainform', '<?=site_url('komentar/filter')?>')">
														<option value="">Semua</option>
														<option value="1" <?php if ($filter_status==1): ?>selected<?php endif ?>>Aktif</option>
														<option value="2" <?php if ($filter_status==2): ?>selected<?php endif ?>>Tidak Aktif</option>
													</select>
													<select class="form-control input-sm " name="filter_user" onchange="formAction('mainform', '<?=site_url('komentar/filter_user')?>')">
														<option value="0" <?php if ($filter_user==0): ?>selected<?php endif ?>>Komentar User</option>
														<option value="9" <?php if ($filter_user==9): ?>selected<?php endif ?>>Komentar Admin</option>
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
																	<th><input type="checkbox" id="checkall"/></th>
																	<th>No</th>
																	<th width="10%">Aksi</th>
																	<th>Pengirim</th>
																	<?php if ($o==6): ?>
																		<th><a href="<?= site_url("komentar/index/$p/5")?>">Komentar <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==5): ?>
																		<th><a href="<?= site_url("komentar/index/$p/6")?>">Komentar <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	 <?php else: ?>
																		<th><a href="<?= site_url("komentar/index/$p/5")?>">Komentar <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																		<th>Artikel</th>
																	<?php if ($o==8): ?>
																		<th nowrap><a href="<?= site_url("komentar/index/$p/7")?>">Status <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==7): ?>
																		<th nowrap><a href="<?= site_url("komentar/index/$p/8")?>">Status <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th nowrap><a href="<?= site_url("komentar/index/$p/7")?>">Status <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<?php if ($o==10): ?>
																		<th nowrap><a href="<?= site_url("komentar/index/$p/9")?>">Dimuat Pada <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==9): ?>
																		<th nowrap><a href="<?= site_url("komentar/index/$p/10")?>">Dimuat Pada <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th nowrap><a href="<?= site_url("komentar/index/$p/9")?>">Dimuat Pada <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																</tr>
															</thead>
															<tbody>
															<?php foreach ($main as $data): ?>
																<tr>
																	<td><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
																	<td><?=$data['no']?></td>
																	<td nowrap>
																	<?php if($data['tipe'] == '0'): ?>	
																		<a href="<?= site_url("komentar/balas/$data[id]")?>" class="btn bg-green btn-flat btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Balas" title="Balas"><i class="fa fa-reply"></i></a>
																	<?php else : ?>
																		<a href="<?= site_url("komentar/ubah/$data[id]")?>" class="btn bg-yellow btn-flat btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah" title="Ubah"><i class="fa fa-edit"></i></a>
																	<?php endif ?>
																	<?php if ($data['status'] == '2'): ?>
																		 <a href="<?= site_url('komentar/status/'.$data['id'].'/1')?>" class="btn bg-navy btn-flat btn-sm"  title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
																 	<?php elseif ($data['status'] == '1'): ?>
																		 <a href="<?= site_url('komentar/status/'.$data['id'].'/2')?>" class="btn bg-navy btn-flat btn-sm"  title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
																 	<?php endif ?>
																		<a href="#" data-href="<?= site_url("komentar/delete/$p/$o/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																	</td>
																	<td nowrap><b><?= $data['owner'].'</b><br> [ '.$data['email'].' ]'?></td>
																	<td><?= $data['komentar']?></td>
																	<td>
																		<a href="<?= site_url('first/artikel/'),buat_slug($data)?>" target="_blank"><?= $data['artikel']?></a>
																	</td>
																	<td><?= $data['aktif']?></td>
																	<td nowrap><?= tgl_indo2($data['tgl_upload'])?></td>
																</tr>
															<?php endforeach; ?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</form>
										<?= $this->load->view('global/paging_table', $data)?>
									</div>
								</div>
							</div>
							<?= $this->load->view('global/confirm', $data)?>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>