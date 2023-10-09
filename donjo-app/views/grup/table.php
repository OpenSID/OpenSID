<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Grup Pengguna</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pengaturan Grup Pengguna</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-3">
				<?php $this->load->view('man_user/menu_kiri.php')?>
			</div>
			<div class="col-md-9">
				<div class="box box-info">
					<div class="box-header with-border">
						<?php if ($this->CI->cek_hak_akses('u')): ?>
							<a href="<?= site_url('grup/form')?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Grup Baru</a>
						<?php endif; ?>
						<?php if ($this->CI->cek_hak_akses('h')): ?>
							<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?=site_url("grup/delete_all/{$p}/{$o}")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
						<?php endif; ?>
					</div>
					<div class="box-body">
						<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
							<form id="mainform" name="mainform" method="post">
								<div class="row">
									<div class="col-sm-4">
										<select class="form-control input-sm" name="jenis" onchange="formAction('mainform', '<?= site_url('grup/filter/jenis'); ?>')">
											<option value="">Jenis Grup</option>
											<?php foreach ($list_jenis_grup as $data): ?>
												<option value="<?= $data['id']; ?>" <?= selected($jenis, $data['id']); ?>><?= set_ucwords($data['nama']); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="col-sm-8">
										<div class="box-tools">
											<div class="input-group input-group-sm pull-right">
												<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?=site_url('grup/search')?>');$('#'+'mainform').submit();}">
												<div class="input-group-btn">
													<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action','<?=site_url('grup/search')?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive">
											<table class="table table-bordered table-striped dataTable table-hover tabel-daftar">
												<thead class="bg-gray disabled color-palette">
													<tr>
														<?php if ($this->CI->cek_hak_akses('h')): ?>
															<th><input type="checkbox" id="checkall"/></th>
														<?php endif; ?>
														<th>No</th>
														<th>Aksi</th>
														<?php if ($o == 2): ?>
															<th><a href="<?= site_url("grup/index/{$p}/1")?>">Group <i class='fa fa-sort-asc fa-sm'></i></a></th>
														<?php elseif ($o == 1): ?>
															<th><a href="<?= site_url("grup/index/{$p}/2")?>">Group <i class='fa fa-sort-desc fa-sm'></i></a></th>
														<?php else: ?>
															<th><a href="<?= site_url("grup/index/{$p}/1")?>">Group <i class='fa fa-sort fa-sm'></i></a></th>
														<?php endif; ?>
														<th>Jumlah Pengguna</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($main as $data): ?>
														<tr>
															<?php if ($this->CI->cek_hak_akses('h')): ?>
																<td class="padat">
																	<?php if ($data['boleh_hapus']): ?>
																		<input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" />
																	<?php endif; ?>
																</td>
															<?php endif; ?>
															<td class="padat"><?=$data['no']?></td>
															<td class="aksi">
															<?php if ($data[id] != 1): ?>
																<a href="<?= site_url("grup/form/{$p}/{$o}/{$data['id']}/1")?>" class="btn bg-info btn-flat btn-sm"  title="Lihat"><i class='fa fa-eye fa-sm'></i></a>
															<?php endif; ?>
															<?php if ($this->CI->cek_hak_akses('u') && $data['jenis'] != 1): ?>
																<a href="<?=site_url("grup/form/{$p}/{$o}/{$data['id']}")?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah"><i class="fa fa-edit"></i></a>
															<?php endif ?>
															<?php if ($this->CI->cek_hak_akses('h') && $data['boleh_hapus']): ?>
																<a href="#" data-href="<?=site_url("grup/delete/{$p}/{$o}/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
															<?php endif; ?>
															</td>
															<td><?=$data['nama']?></td>
															<td class="padat"><?=$data['jml_pengguna']?></td>
														</tr>
													<?php endforeach; ?>
												</tbody>
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
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
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

