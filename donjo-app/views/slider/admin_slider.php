<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Slider Besar</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"Pengaturan Slider Besar</li>
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
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="table table-bordered">
											<tbody>
												<div class="btn-group">
													<tr>
														<td width="20%">
															<div data-toggle="buttons">
																<label class="btn btn-info btn-flat btn-sm col-xs-12 <?php if ($this->setting->sumber_gambar_slider == '1'): ?>active<?php endif ?>">
																	<input id="sumber1" type="radio" name="pilihan_sumber" class="form-check-input <?php if ($this->setting->sumber_gambar_slider == '1'): ?>active<?php endif ?>" type="radio" value="1" <?php if ($this->setting->sumber_gambar_slider == '1'): ?>checked <?php endif ?> autocomplete="off"> Artikel Terbaru sfds sdffd dsfds
																</label>
															</div>
														</td>
														<td >10 gambar utama artikel terbaru</td>
													</tr>
													<tr>
														<td>
															<div data-toggle="buttons">
																<label class="btn btn-info btn-flat btn-sm col-xs-12 <?php if ($this->setting->sumber_gambar_slider == '2'): ?>active<?php endif ?>">
																	<input id="sumber2" type="radio" name="pilihan_sumber" class="form-check-input <?php if ($this->setting->sumber_gambar_slider == '2'): ?>active<?php endif ?>" type="radio" value="2" <?php if ($this->setting->sumber_gambar_slider == '2'): ?>checked <?php endif ?> autocomplete="off"> Artikel Terbaru Pilihan
																</label>
															</div>
														</td>
														<td>10 gambar utama artikel terbaru yang masuk ke slider atas</td>
													</tr>
													<tr>
														<td>
															<div data-toggle="buttons">
																<label class="btn btn-info btn-flat btn-sm col-xs-12 <?php if ($this->setting->sumber_gambar_slider == '3'): ?>active<?php endif ?>">
																	<input id="sumber3" type="radio" name="pilihan_sumber" class="form-check-input" type="radio" value="3" <?php if ($this->setting->sumber_gambar_slider == '3'): ?>checked <?php endif ?> autocomplete="off"> Album Galeri
																</label>
															</div>
														</td>
														<td>Gambar dalam album galeri yang dimasukkan ke slider</td>
													</tr>
												</div>
											</tbody>
										</table>
									</div>
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

