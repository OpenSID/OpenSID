<div class="content-wrapper">
	<section class="content-header">
		<h1>Data Anggota Kelompok</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('kelompok')?>"> Daftar Kelompok</a></li>
			<li class="active">Data Anggota Kelompok</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
					<a href="<?= site_url()?>kelompok" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Daftar Kelompok</a>
					</div>
					<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data"  class="form-horizontal">
						<div class="box-body">
							<div class='col-xs-12'>
								<?php if (!@$pend): ?>
									<div class="form-group">
										<label class="col-sm-3 control-label"  for="id_penduduk">Nama Anggota</label>
										<div class="col-sm-5">
											<select class="form-control input-sm select2 required" id="id_penduduk" name="id_penduduk">
												<option value="">-- Silakan Masukan NIK / Nama --</option>
												<?php foreach ($list_penduduk as $data): ?>
													 <option value="<?= $data['id']?>">NIK :<?= $data['nik']." - ".$data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
								<?php endif; ?>
								<div class="form-group">
									<label  class="col-sm-3 control-label" for="no_anggota">Nomor Anggota</label>
									<div class="col-sm-5">
										<input  id="no_anggota" class="form-control input-sm" type="text" placeholder="Nomor Anggota" name="no_anggota" value="<?=$pend['no_anggota']; ?>">
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<div class="col-xs-12">
								<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
								<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>
