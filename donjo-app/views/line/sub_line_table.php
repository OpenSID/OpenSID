<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Kategori Garis</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('line')?>"> Daftar Tipe Garis</a></li>
			<li class="active">Pengaturan Kategori Garis</li>
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
								<a href="<?= site_url("line/ajax_add_sub_line/{$line['id']}")?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Kategori <?= $line['nama'] ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Kategori <?= $line['nama'] ?>">
									<i class="fa fa-plus"></i>Tambah Kategori <?= $line['nama'] ?>
								</a>
							<?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('h')): ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?=site_url('line/delete_all_sub_line/')?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<?php endif; ?>
							<a href="<?= site_url('line')?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Tipe Garis
							</a>
						</div>
						<div class="box-body">
							<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<form id="mainform" name="mainform" method="post">
									<div class="table-responsive">
										<h5 class="box-title">KATEGORI : <?= $line['nama']; ?></h5>
										<table class="table table-bordered table-striped dataTable table-hover tabel-daftar">
											<thead class="bg-gray disabled color-palette">
												<tr>
													<?php if ($this->CI->cek_hak_akses('u')): ?>
														<th><input type="checkbox" id="checkall"/></th>
													<?php endif; ?>
													<th>No</th>
													<?php if ($this->CI->cek_hak_akses('u')): ?>
														<th>Aksi</th>
													<?php endif; ?>
													<th>Kategori</th>
													<th>Aktif</th>
													<th>Tampil</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($subline as $data): ?>
													<tr>
														<?php if ($this->CI->cek_hak_akses('u')): ?>
															<td class="padat"><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
														<?php endif; ?>
														<td class="padat"><?=$data['no']?></td>
														<?php if ($this->CI->cek_hak_akses('u')): ?>
															<td class="aksi">
																<a href="<?= site_url("line/ajax_add_sub_line/{$line['id']}/{$data['id']}")?>" class="btn btn-warning btn-flat btn-sm"  title="Ubah" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Kategori <?= $line['nama'] ?>"><i class="fa fa-edit"></i></a>
																<?php if ($data['enabled'] == '2'): ?>
																	<a href="<?= site_url("line/line_lock_sub_line/{$line['id']}/{$data['id']}")?>" class="btn bg-navy btn-flat btn-sm" title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
																<?php elseif ($data['enabled'] == '1'): ?>
																	<a href="<?= site_url("line/line_unlock_sub_line/{$line['id']}/{$data['id']}")?>" class="btn bg-navy btn-flat btn-sm" title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
																<?php endif; ?>
																<?php if ($this->CI->cek_hak_akses('h')): ?>
																	<a href="#" data-href="<?= site_url("line/delete_sub_line/{$line['id']}/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																<?php endif; ?>
															</td>
														<?php endif; ?>
														<td width="60%"><?= $data['nama']?></td>
														<td class="padat"><?= $data['aktif']?></td>
														<td width="10%">
															<hr style="vertical-align: middle; margin: 0; border-bottom: <?= $data['tebal'] . 'px'; ?> <?= $data['jenis']; ?>  <?= $data['color']; ?>">
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
