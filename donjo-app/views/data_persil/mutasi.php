<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengelolaan Sebab Dan Tanggal Perubahan Persil</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('data_persil/clear')?>"> Daftar Persil</a></li>
			<li class="active">Pengelolaan Sebab Dan Tanggal Perubahan Persil</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-3">
				<?php $this->load->view('data_persil/menu_kiri.php')?>
			</div>
			<div class="col-md-9">
				<div class="box box-info">
					<div class="box-header">
							<?php if ($persil_detail["id"]): ?>
								<h4 class="text-center"><strong>Sebab Dan Tanggal Perubahan Persil <?= $persil_detail["nopersil"] ?> / C-DESA <?= $persil_detail["c_desa"] ?></strong></h4>
							<?php else: ?>
								<h4 class="text-center"><strong>Sebab Dan Tanggal Perubahan Persil <?= $persil_mutasi["nama"] ?> / C-DESA <?= $persil_mutasi["c_desa"] ?></strong></h4>
							<?php endif; ?>

						</div>
					<form id="validasi" action="<?= $form_action?>" method="POST" class="form-horizontal">
						<div class="box-body">
							<div class="form-group">
								<label for="nama"  class="col-sm-3 control-label">Sebab Mutasi</label>
								<div class="col-sm-4">
									<select class="form-control input-sm" name="jenis_mutasi" >
										<option value>-- Pilih Jenis Mutasi--</option>
										<?php foreach ($persil_jenis_mutasi as $key => $item): ?>
											<option value="<?= $item['id'] ?>" <?php selected($key, $persil_mutasi["jenis_mutasi"]) ?>><?= $item['nama']?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="nama"  class="col-sm-3 control-label">Tanggal Perubahan</label>
								<div class="col-sm-4">
									<div class="input-group input-group-sm date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input class="form-control input-sm pull-right" id="tgl_1" name="tanggalmutasi" type="text" value="<?= $persil_mutasi["tanggalmutasi"]?>">
									</div>
								</div>
							</div>
							<input  name="jenis" type="hidden" value="<?= $jenis ?>">
							<?php if ($persil_detail["id"]): ?>
								<input  name="id_persil" type="hidden" value="<?= $persil_detail["id"]?>">
							<?php else: ?>

							<input  name="id" type="hidden" value="<?= $persil_mutasi["id"]?>">
							<input  name="id_persil" type="hidden" value="<?= $persil_mutasi["id_persil"]?>">
							<?php endif; ?>
							<div class="form-group">
								<label for="nama"  class="col-sm-3 control-label">Sebab Mutasi</label>
								<div class="col-sm-4">
									<select class="form-control input-sm" name="sebabmutasi" >
										<option value>-- Pilih Sebab Mutasi--</option>
										<?php foreach ($persil_sebab_mutasi as $key => $item): ?>
											<option value="<?= $item['id'] ?>" <?php selected($key, $persil_mutasi["sebabmutasi"]) ?>><?= $item['nama']?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="nama"  class="col-sm-3 control-label">Luas Mutasi</label>
								<div class="col-sm-9">
									<input  name="luasmutasi"  type="text"  class="form-control input-sm luas" placeholder="Luas Mutasi" value="<?= $persil_mutasi["luasmutasi"] ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="nama"  class="col-sm-3 control-label">Perolehan Dari</label>
								<div class="col-sm-9">
									<input name="no_c_desa"  type="text"  class="form-control input-sm angka" placeholder="Nomor C-DESA" value="<?= $persil_mutasi["no_c_desa"] ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="nama"  class="col-sm-3 control-label">Keterangan</label>
								<div class="col-sm-9">
									<textarea  id="ket" class="form-control input-sm" type="text" placeholder="Sebab Dan Tanggal Perubahan" name="ket" ><?= $persil_mutasi["keterangan"] ?></textarea>
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
				</div>
			</div>
		</div>
	</section>
</div>
