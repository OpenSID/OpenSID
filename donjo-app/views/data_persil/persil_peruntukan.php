<?php
	if ($persil_peruntukan_detail):
		$nama = $persil_peruntukan_detail[$id]["nama"];
		$ndesc = $persil_peruntukan_detail[$id]["ndesc"];
	else:
		$nama = "";
		$ndesc = "";
		$id = 0;
	endif;
?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengelolaan Data Peruntukan Persil</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('data_persil/clear')?>"> Daftar Persil</a></li>
			<li class="active">Pengelolaan Peruntukan Persil</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-3">
				<?php $this->load->view('data_persil/menu_kiri.php')?>
			</div>
			<div class="col-md-9">
				<div class="box box-info">
					<form id="validasi" action="<?= $form_action?>" method="POST" class="form-horizontal">
						<div class="box-body">
							<div class="form-group">
								<label class="control-label col-sm-3" for="nama">Nama Peruntukan Persil</label>
								<div class="col-sm-8">
									<input name="nama" class="form-control input-sm" type="text" placeholder="Tuliskan Peruntukan Persil" value="<?=$nama?>"></input>
									<input type="hidden" name="id" value="<?=$id?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="ndesc">Keterangan</label>
								<div class="col-sm-8">
									<textarea id="ndesc" name="ndesc" class="form-control input-sm required" placeholder="Keterangan"><?=$ndesc?></textarea>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</form>
					<div class="box-body">
						<?php if ($persil_peruntukan): ?>
								<?php if (count($persil_peruntukan)>0): ?>
									<div class="col-sm-12">
										<div class="table-responsive">
											<table class="table table-bordered dataTable table-hover">
												<thead class="bg-gray disabled color-palette">
													<tr>
													<th>No</th>
													<th>Aksi</th>
													<th>Nama</th>
													<th>Ketarangan</th>
												</thead>
												<tbody>
													<?php $nomer =0; foreach ($persil_peruntukan as $key=>$item): $nomer++;?>
														<tr>
															<td><?=$nomer?></td>
															<td nowrap>
																<a href="<?= site_url("data_persil/index/peruntukan/".$key)?>" class="btn bg-purple btn-flat btn-sm"  title="Rincian"><i class="fa fa-bars"></i></a>
																<a href="<?= site_url("data_persil/persil_peruntukan/".$key)?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah"><i class="fa fa-edit"></i></a>
																<a href="#" data-href="<?= site_url("data_persil/hapus_persil_peruntukan/".$key)?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
															</td>
															<td width="30%"><a href="<?php site_url('data_persil/index/peruntukan/'.$key.'/')?>"><?=$item[0]?></a></td>
															<td width="50%"><?= $item[1] ?></td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								<?php	endif ?>
							<?php	else: ?>
								<div class="col-md-12">
									<div class="box box-warning box-solid">
										<div class="box-header with-border">
											<h3 class="box-title">Belum Ada Data</h3>
										</div>
										<div class="box-body">
										Silakan ditambahkan data Peruntukan Persil dengan menggunakan formulir dari menu <a href="<?php site_url("data_persil/persil_peruntukan")?>"><i class="icon-plus"></i> Tambah Data Peruntukan Persil</a>
										</div>
									</div>
								</div>
							<?php	endif ?>
					</div>
				</div>
			</div>
		</div>
		<div class='modal fade' id='confirm-delete' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
			<div class='modal-dialog'>
				<div class='modal-content'>
					<div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
						<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
					</div>
					<div class='modal-body btn-info'>
						Apakah Anda yakin ingin menghapus data ini?
					</div>
					<div class='modal-footer'>
						<button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
						<a class='btn-ok'>
							<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" id="ok-delete"><i class='fa fa-trash-o'></i> Hapus</button>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
