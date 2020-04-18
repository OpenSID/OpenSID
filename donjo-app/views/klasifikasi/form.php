<div class="content-wrapper">
	<section class="content-header">
		<h1><?= empty($data) ? 'Tambah' : 'Ubah'?> Klasifikasi Surat</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url().$this->controller.'/index'?>"><i class="fa fa-dashboard"></i> Daftar Klasifikasi Surat</a></li>
			<li class="active"><?= empty($data) ? 'Tambah' : 'Ubah'?> Klasifikasi Surat</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
            <div class="box-header with-border">
							<a href="<?= site_url().$this->controller.'/index/'.$kat?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Klasifikasi Surat
            	</a>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="control-label col-sm-4" for="kode">Kode</label>
								<div class="col-sm-6">
									<input name="kode" class="form-control input-sm bilangan_titik required" type="text" placeholder="Kode" value="<?=$data['kode']?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4" for="nama">Nama</label>
								<div class="col-sm-6">
									<input name="nama" class="form-control input-sm required" type="text"placeholder="Nama" value="<?=$data['nama']?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4" for="uraian">Keterangan</label>
								<div class="col-sm-6">
									<textarea name="uraian" class="form-control input-sm required" placeholder="Keterangan" ><?= $data['uraian']?></textarea>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
