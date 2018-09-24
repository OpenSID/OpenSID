<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengelolaan Kategori Kelompok</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('kelompok')?>"> Daftar Kelompok</a></li>
      <li><a href="<?= site_url('kelompok_master')?>"> Daftar Ketegori Kelompok</a></li>
			<li class="active">Pengelolaan Kategori Kelompok</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url()?>kelompok_master" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Kategori Kelompok</a>
					</div>
					<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama">Klasifikasi/Kategori Kelompok</label>
								<div class="col-sm-8">
									<input  id="kelompok" class="form-control input-sm required" type="text" placeholder="Kategori Kelompok" name="kelompok" value="<?= $kelompok_master['kelompok']?>">	</input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="Deskripsi">Deskripsi Kelompok</label>
								<div class="col-sm-8">
								 	<textarea name="Deskripsi" class="form-control input-sm" placeholder="Deskripsi Kelompok"  rows="3"><?= $kelompok_master['deskripsi']?></textarea>
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
