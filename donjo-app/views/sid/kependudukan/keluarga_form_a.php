<?php if ($this->CI->cek_hak_akses('u')): ?>
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Biodata Anggota Keluarga</h1>
			<ol class="breadcrumb">
				<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
				<li><a href="<?= site_url('keluarga/clear')?>"> Daftar Keluarga</a></li>
				<li class="active">Biodata Anggota Keluarga</li>
			</ol>
		</section>
		<section class="content" id="maincontent">
			<form id="mainform" name="mainform" action="<?= $form_action?>" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-3">
								<div class="box box-primary">
									<div class="box-body box-profile">
										<img class="penduduk" src="<?= AmbilFoto($penduduk['foto']); ?>" alt="Foto Penduduk">
										<br/>
										<p class="text-muted text-center"> (Kosongkan jika tidak ingin mengubah foto)</p>
										<br/>
										<div class="input-group input-group-sm">
											<input type="text" class="form-control" id="file_path" name="foto">
											<input type="file" class="hidden" id="file" name="foto">
											<input type="hidden" name="old_foto" value="<?= $penduduk['foto']?>">
											<span class="input-group-btn">
												<button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
											</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-9">
								<div class='box box-primary'>
									<div class="box-header with-border">
										<a href="<?= site_url('keluarga')?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Keluarga">
											<i class="fa fa-arrow-circle-left"></i>Kembali Ke Daftar Keluarga
										</a>
										<a href="<?= site_url("keluarga/anggota/1/0/{$id_kk}")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Keluarga">
											<i class="fa fa-arrow-circle-left"></i>Kembali Ke Daftar Anggota Keluarga
										</a>
									</div>
									<div class='box-body'>
										<div class="row">
											<div class='col-sm-12'>
												<div class="form-group subtitle_head">
													<label class="text-right"><strong>DATA KELUARGA :</strong></label>
												</div>
											</div>
											<div class='col-sm-4'>
												<div class='form-group'>
													<label >No. KK </label>
													<input class="form-control input-sm" type="text" value="<?= $kk['no_kk']?>" disabled></input>
													<input name="id_kk" type="hidden" value="<?= $id_kk?>">
													<input name="kk_level" type="hidden" value="0">
													<input name="id_cluster" type="hidden" value="<?= $kk['id_cluster']?>">
												</div>
											</div>
											<div class='col-sm-8'>
												<div class='form-group'>
													<label>Kepala KK</label>
													<input class="form-control input-sm" type="text" value="<?= $kk['nama']?>" disabled></input>
												</div>
											</div>
											<div class='col-sm-12'>
												<div class='form-group'>
													<label>Alamat </label>
													<input class="form-control input-sm" type="text" value="<?= $kk['alamat']?> Dusun <?= $kk['dusun']?> - RW <?= $kk['rw']?> - RT <?= $kk['rt']?>" disabled></input>
												</div>
											</div>
											<div class='col-sm-12'>
												<div class="form-group subtitle_head">
													<label class="text-right"><strong>DATA ANGGOTA :</strong></label>
												</div>
											</div>
										</div>
										<?php $this->load->view('sid/kependudukan/penduduk_form_isian_bersama'); ?>
									</div>
									<div class='box-footer'>
										<div class='col-xs-12'>
											<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
											<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</section>
	</div>
<?php endif; ?>