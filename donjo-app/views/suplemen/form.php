
<div class="content-wrapper">
	<section class="content-header">
		<h1>Form Data Suplemen</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('suplemen')?>"> Data Suplemen</a></li>
			<li class="active">Form Data Suplemen</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url('suplemen')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Suplemen</a>
					</div>

					<form id="validasi" action="<?= $form_action?>" method="POST" class="form-horizontal">
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-3 control-label" for="id_master">Sasaran Data</label>
								<div class="col-sm-7">
									<select class="form-control input-sm required" name="cid" id="cid">
										<option value="">-- Pilih Sasaran Data Suplemen --</option>
										<option value="1" <?php ($cid == 1 OR $suplemen['sasaran'] == 1) and print('selected') ?>>Penduduk Perorangan</option>
										<option value="2" <?php ($cid == 2 OR $suplemen['sasaran'] == 2) and print('selected') ?>>Keluarga - KK</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama">Nama Data Suplemen</label>
								<div class="col-sm-7">
									<input class="form-control input-sm nomor_sk required" maxlength="100" type="text" placeholder="Nama Data Suplemen" name="nama" id="nama" value="<?= $suplemen['nama']?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
								<div class="col-sm-7">
									 <textarea name="keterangan" id="keterangan" class="form-control input-sm" maxlength="300" placeholder="Keterangan" rows="3" style="resize:none;"><?= $suplemen['keterangan']?></textarea>
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
