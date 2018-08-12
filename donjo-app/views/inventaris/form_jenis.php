<div class="content-wrapper">
	<section class="content-header">
		<h1>Inventaris dan Kekayaan Desa</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?= site_url().$this->controller.'/index/'?>"><i class="fa fa-dashboard"></i> Inventaris dan Kekayaan Desa</a></li>
			<li class="active">Jenis Invenyaris</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action?>" method="post" class="form-horizontal">
			<div class="row">
				<div class="col-md-3">
          <?php	$this->load->view('inventaris/menu_kiri.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
            <div class="box-header with-border">
             <a href="<?= site_url().$this->controller.'/index/'?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Data Inventaris
            	</a>
						</div>
						<div class="box-header with-border">
							<div class="mailbox-attachment-info">
								<p class="text-red">Modul ini akan dihilangkan pada rilis September 2018 karena telah diganti dengan modul Inventaris baru. Pastikan memindahkan semua data ke format Inventaris baru sebelum rilis tersebut.</p>
							</div>
						</div>
            <div class="box-body">
							<div class="form-group">
								<label class="control-label col-sm-4" for="nama">Jenis Barang</label>
								<div class="col-sm-6">
									<input name="nama" class="form-control input-sm" type="text" value="<?= $jenis['nama']?>"></input>
								</div>
              </div>
              <div class="form-group">
								<label class="control-label col-sm-4" for="keterangan">Keterangan</label>
								<div class="col-sm-6">
                  <textarea id="keterangan" name="keterangan" class="form-control input-sm" placeholder="Keterangan"><?= $jenis['keterangan']?></textarea>
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

