<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Slider Besar</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda')?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li class="active">Pengaturan Slider Besar</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" action="<?= site_url('web/update_slider')?>" method="POST" class="form-horizontal">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
								Pilih sumber gambar untuk ditampilkan di slider besar:
						</div>
						<div class="box-body">
							<div class="row">
								<div data-toggle="buttons">
									<div class="col-sm-12">
										<div class="radio">
											<label data-toggle="tooltip" title="<?= setting('jumlah_gambar_slider') ?? 10 ?> gambar utama artikel terbaru" data-placement="bottom" class="btn btn-info btn-flat btn-sm col-xs-12 col-md-4 col-lg-3 <?php if ($this->setting->sumber_gambar_slider == '1'): ?>active<?php endif ?>">
												<input id="sumber1" type="radio" name="pilihan_sumber" class="hidden" type="radio" value="1" <?php if ($this->setting->sumber_gambar_slider == '1'): ?>checked <?php endif ?> autocomplete="off"> Artikel Terbaru
											</label>
											<label class="control-label row"> <?= setting('jumlah_gambar_slider') ?? 10 ?> gambar utama artikel terbaru</lable>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="radio">
											<label data-toggle="tooltip" title="<?= setting('jumlah_gambar_slider') ?? 10 ?> gambar utama artikel terbaru yang masuk ke slider atas" data-placement="bottom" class="btn btn-info btn-flat btn-sm col-xs-12 col-md-4 col-lg-3 <?php if ($this->setting->sumber_gambar_slider == '2'): ?>active<?php endif ?>">
												<input id="sumber2" type="radio" name="pilihan_sumber" class="hidden" type="radio" value="2" <?php if ($this->setting->sumber_gambar_slider == '2'): ?>checked <?php endif ?> autocomplete="off"> Artikel Terbaru Pilihan
											</label>
											<label class="control-label row"> <?= setting('jumlah_gambar_slider') ?? 10 ?> gambar utama artikel terbaru yang masuk ke slider atas</lable>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="radio">
											<label data-toggle="tooltip" title="Gambar dalam album galeri yang dimasukkan ke slider" data-placement="bottom" class="btn btn-info btn-flat btn-sm col-xs-12 col-md-4 col-lg-3 <?php if ($this->setting->sumber_gambar_slider == '3'): ?>active<?php endif ?>">
												<input id="sumber3" type="radio" name="pilihan_sumber" class="hidden" type="radio" value="3" <?php if ($this->setting->sumber_gambar_slider == '3'): ?>checked <?php endif ?> autocomplete="off"> Album Galeri
											</label>
											<label class="control-label row">  Gambar dalam album galeri yang dimasukkan ke slider</lable>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-md-4 col-lg-3">
									<input style="margin-left: 5px; margin-top: 5px;" type="number" class="form-control input-sm text-right" name="jumlah_gambar_slider" id="jumlah_gambar_slider" value="<?= $this->setting->jumlah_gambar_slider ?? 10 ?>" min="1" max="20">
								</div>
								<label class="control-label col-md-6">Jumlah gambar slider yang ditampilkan</label>
							</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<?php if (can('u')): ?>
									<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

