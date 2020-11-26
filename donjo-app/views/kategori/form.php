<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Menu Dinamis / Kategori</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('kategori')?>"> Daftar Kategori</a></li>
			<li class="active">Pengaturan Menu</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action?>" method="POST" class="form-horizontal">
			<div class="row">
				<div class="col-md-3">
          <?php $this->load->view('kategori/menu_kiri.php')?>
				</div>
				<div class="col-md-9">
					<div class="card card-outline card-info">
            <div class="card-header with-border">
							<a href="<?= site_url("kategori")?>" class="btn btn-flat btn-info btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left"  title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Kategori
            	</a>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label class="control-label col-sm-4" for="nama">Nama Kategori</label>
								<div class="col-sm-6">
									<?php if ($kategori): ?>
										<input name="kategori_lama" type="hidden" value="<?=$kategori['kategori']?>">
									<?php endif; ?>
									<input name="kategori" class="form-control form-control-sm required nomor_sk" maxlength="50" type="text" value="<?=$kategori['kategori']?>"></input>
								</div>
							</div>
						</div>
						<div class='card-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-flat btn-danger btn-xs' ><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-flat btn-info btn-xs pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
