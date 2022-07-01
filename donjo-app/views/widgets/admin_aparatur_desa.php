<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Aparatur Desa</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pengaturan Aparatur Desa</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
            <div class="box-header with-border">
              <a href="<?=site_url('web_widget')?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Widget
            	</a>
						</div>
						<div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="container-fluid">
                    <div class="jumbotron">
                      <p>Widget Aparatur Desa menampilkan foto staf pemerintah desa. Klik tombol berikut untuk mengubah data aparatur desa, termasuk foto staf pemerintah desa</p>
                      <a class="btn btn-primary btn-large" href="<?= site_url('pengurus/clear')?>">
                        Pemerintah Desa
                      </a>
                    </div>
                  </div>
                </div>
              </div>
							<div class="row">
								<div class="col-sm-12">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-12">
                        <div class="form-group">
                          <label class="col-xs-12 col-md-3 col-lg-2">Tampilkan nama/jabatan</label>
                          <div class="col-xs-12 col-sm-2">
                            <select class="form-control input-sm" name="setting[overlay]">
                            <option value="1" <?php selected($setting['overlay'], true)?>>Ya</option>
                              <option value="0" <?php selected($setting['overlay'], false)?>>Tidak</option>
                            </select>
                          </div>
                        </div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
            <div class="box-footer">
						  <div class="col-xs-12">
							  <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
								<?php if ($this->CI->cek_hak_akses('u')): ?>
									<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

