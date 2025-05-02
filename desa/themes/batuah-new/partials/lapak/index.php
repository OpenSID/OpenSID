<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php $this->load->view("$folder_themes/plus/header_side"); ?>
<div class="mainpage">
	<div class="margin-side-10">
	<div class="mainbodyarea">
		<div class="bigarea hiddenrelative">
		<div class="bigarea-margin">
			<div class="col-default margin-top-10">
				<div class="bodypage bgwhite bordergrey">
					<div class="headstat-lebar flexcenter">
						<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/lapak.png") ?>"/>
						<div>
						<h1 class="color-1">Lapak</h1>
						<h2><?= ucwords($this->setting->sebutan_desa); ?></h2>
						</div>
					</div>
					<div class="pagebox">
						<div class="flexcenter" style="margin-bottom:20px;">
							<form method="get" class="form-inline text-center">
							<div class="row">
								<div class="col-sm-12 kat2 flexcenter">
									<select class="form-control select2" id="id_kategori" name="id_kategori">
										<option selected value="">Semua Kategori</option>
										<?php foreach ($kategori as $kategori_item) : ?>
											<option value="<?= $kategori_item->id ?>" <?= selected($id_kategori, $kategori_item->id) ?>><?= $kategori_item->kategori ?></option>
										<?php endforeach; ?>
									</select>
									<div class="flexcenter">
									<input type="text" name="keyword" maxlength="50" class="form-control" value="<?= $keyword; ?>" placeholder="Cari Produk">
									<button type="submit" class="b-btn bgcolor-1 flexcenter" style="margin:0 3px;"><p>Cari</p></button>
									</div>
								</div>
							</div>
						</form>
						</div>
						<?php if ($produk): ?>
						<div class="rowsame margin-minlr-5 lapakstyle">
							<?php foreach ($produk as $in => $pro): ?>
							<?php $foto = json_decode($pro->foto); ?>
								<div class="lapak-col bordergrey1">
								<div class="hiddenrelative forhover">
									<?php if ($pro->foto): ?>
										<div id="foto-produk-<?= $in; ?>" class="carousel slide" data-ride="carousel">
											<div class="carousel-inner">
											<?php for ($i = 0; $i < $this->setting->banyak_foto_tiap_produk; $i++): ?>
											<?php if ($foto[$i]): ?>
												<div class="item <?= jecho($i, 0, 'active'); ?>">
												<?php if (is_file(LOKASI_PRODUK . $foto[$i])): ?>
													<a href="<?= base_url(LOKASI_PRODUK . $foto[$i]); ?>"  data-fancybox="images">
														<div class="image-lapak">
														<img src="<?= base_url(LOKASI_PRODUK . $foto[$i]); ?>" alt="Foto <?= ($i+1); ?>">
														<div class="hoverimage flexcenter">
															<div class="hoverimage-left bgcolor-1 hover-width"></div>
															<div class="hoverimage-right bgcolor-1 hover-width"></div>
															<div>
																<a href="<?= base_url(LOKASI_PRODUK . $foto[$i]); ?>"  data-fancybox="images" data-caption="<?= $pro->nama; ?>">
																<div class="hoverimage-icon flexcenter hover-height">
																<svg viewBox="0 0 24 24"><path d="M9.5,13.09L10.91,14.5L6.41,19H10V21H3V14H5V17.59L9.5,13.09M10.91,9.5L9.5,10.91L5,6.41V10H3V3H10V5H6.41L10.91,9.5M14.5,13.09L19,17.59V14H21V21H14V19H17.59L13.09,14.5L14.5,13.09M13.09,9.5L17.59,5H14V3H21V10H19V6.41L14.5,10.91L13.09,9.5Z"/></svg>
																</div>
																</a>
															</div>
														</div>
														</div>
													</a>
												<?php else: ?>
													<div class="image-lapak">
														<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pengganti.jpg") ?>"/>
													</div>
												<?php endif; ?>
												</div>
											<?php endif; ?>
											<?php endfor; ?>
											</div>
											<a class="left carousel-control flexcenter" href="#foto-produk-<?= $in; ?>" data-slide="prev" style="border-radius:5px !important;"><div class="carousel-control-leftarrow"></div></a>
											<a class="right carousel-control flexcenter" href="#foto-produk-<?= $in; ?>" data-slide="next" style="border-radius:5px !important;"><div class="carousel-control-rightarrow"></div></a>
										</div>	
									<?php else: ?>
										<div class="image-lapak">
											<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pengganti.jpg") ?>"/>
										</div>
									<?php endif; ?>
									<div class="absolute-title">
										<h2 class="hover-height"><?= $pro->nama; ?></h2>
									</div>
								</div>
								<div class="hiddenrelative margin-topbottom-10">
									<div class="harga-produk flexcenter">
									<?php $harga_potongan = ($pro->tipe_potongan == 1) ? ($pro->harga * ($pro->potongan / 100)) : $pro->potongan; ?>
									<p>
										<?php if ($pro->potongan != 0): ?>
										<font style="color:red; text-decoration: line-through red;"><?= rupiah($pro->harga); ?></font>
										<?php endif; ?>
									</p>
									<p class="color-2"><b><?= rupiah($pro->harga - $harga_potongan); ?></b></p>	
									</div>
								</div>
								<div class="flexcenter lapakinfo" style="text-align:center;">
									<div>
									<p>Kategori : <?= $pro->kategori; ?></p>
									<p class="desktop-only">Pelapak : <?= $pro->pelapak; ?></p>
									</div>
								</div>
								<div class="lapak-open flexcenter">
									<a data-remote="false" data-toggle="modal" data-target="#detail<?= $in; ?>" title="Detail"><div class="lapak-open-item bordergrey1 flexcenter"><div class="lapak-icon bgorange flexcenter"><svg viewBox="0 0 24 24"><path d="M19,20H4C2.89,20 2,19.1 2,18V6C2,4.89 2.89,4 4,4H10L12,6H19A2,2 0 0,1 21,8H21L4,8V18L6.14,10H23.21L20.93,18.5C20.7,19.37 19.92,20 19,20Z" /></svg></div><p>Detail</p></div></a>
									<?php if ($pro->telepon): ?>
										<?php $pesan = strReplaceArrayRecursive(['[nama_produk]' => $pro->nama, '[link_web]' => base_url('lapak'), '<br />' => "%0A"], nl2br($this->setting->pesan_singkat_wa)); ?>
										<a href="https://api.whatsapp.com/send?phone=<?=format_telpon($pro->telepon);?>&amp;text=<?= $pesan; ?>" rel="noopener noreferrer" target="_blank" title="WhatsApp"><div class="lapak-open-item bordergrey1 flexcenter"><div class="lapak-icon bghijau flexcenter"><svg viewBox="0 0 24 24"><path d="M12,13A5,5 0 0,1 7,8H9A3,3 0 0,0 12,11A3,3 0 0,0 15,8H17A5,5 0 0,1 12,13M12,3A3,3 0 0,1 15,6H9A3,3 0 0,1 12,3M19,6H17A5,5 0 0,0 12,1A5,5 0 0,0 7,6H5C3.89,6 3,6.89 3,8V20A2,2 0 0,0 5,22H19A2,2 0 0,0 21,20V8C21,6.89 20.1,6 19,6Z" /></svg></div><p>Beli</p></div></a>
									<?php endif; ?>
									<a data-remote="false" data-toggle="modal" data-target="#map-modal" title="Lokasi" data-lat="<?= $pro->lat?>" data-lng="<?= $pro->lng?>" data-zoom="<?= $pro->zoom?>" data-title="Lokasi <?= $pro->pelapak?>"><div class="lapak-open-item bordergrey1 flexcenter"><div class="lapak-icon bgbiru flexcenter"><svg viewBox="0 0 24 24"><path d="M12,2C15.31,2 18,4.66 18,7.95C18,12.41 12,19 12,19C12,19 6,12.41 6,7.95C6,4.66 8.69,2 12,2M12,6A2,2 0 0,0 10,8A2,2 0 0,0 12,10A2,2 0 0,0 14,8A2,2 0 0,0 12,6M20,19C20,21.21 16.42,23 12,23C7.58,23 4,21.21 4,19C4,17.71 5.22,16.56 7.11,15.83L7.75,16.74C6.67,17.19 6,17.81 6,18.5C6,19.88 8.69,21 12,21C15.31,21 18,19.88 18,18.5C18,17.81 17.33,17.19 16.25,16.74L16.89,15.83C18.78,16.56 20,17.71 20,19Z" /></svg></div><p>Lokasi</p></div></a>						
								</div>
								</div>
								
								<div class="lapakdetail-modal">
								<div class="bigmodal">
									<div class="modal center fade bgmodalcolor1" id="detail<?= $in; ?>" role="dialog" aria-labelledby="lapor" aria-hidden="true" data-backdrop="false">
										<div class="modal-dialog bggrey1" role="document">
										<div class="modal-absolute bgwhite bordergrey1">
											<div class="modalhead bgcolor-1 flexcenter"><h1>Detail Produk</h1></div>
											<div class="inner-modal" style="border-radius:0;">
											<div class="scroll-area" id="scrollstyle">
											<div class="lapakdetail" style="padding:10px;">
												<h1><?= $pro->nama; ?></h1>
												<div class="hiddenrelative margin-topbottom-10">
													<table style="width:100%;">
														<tr>
															<?php $harga_potongan = ($pro->tipe_potongan == 1) ? ($pro->harga * ($pro->potongan / 100)) : $pro->potongan; ?>
															<?php if ($pro->potongan != 0): ?>
															<td>Harga Sebelumnya</td><td style="width:15px;text-align:center;">:</td><td><font style="color:red; text-decoration: line-through red;"><?= rupiah($pro->harga); ?></font></td>
															<?php endif; ?>
														</tr>
														<tr>
															<td>Harga Terbaru</td><td style="width:15px;text-align:center;">:</td><td><b class="color-2"><?= rupiah($pro->harga - $harga_potongan); ?></b></td>
														</tr>
														<tr>
															<td>Kategori Produk</td><td style="width:15px;text-align:center;">:</td><td><b><?= $pro->kategori; ?></b></td>
														</tr>
														<tr>
															<td>Pelapak</td><td style="width:15px;text-align:center;">:</td><td><b><?= $pro->pelapak; ?></b></td>
														</tr>
													</table>
												</div>
												<p style="margin:10px 0 5px;"><b>Deskripsi Produk</b></p>
												<p style="margin:0 0 15px;"><?= nl2br($pro->deskripsi); ?></p>
												<?php if ($pro->foto): ?>
												<div id="foto-produk-<?= $in; ?>" class="carousel slide" data-ride="carousel">
													<?php for ($i = 0; $i < $this->setting->banyak_foto_tiap_produk; $i++): ?>
													<?php if ($foto[$i]): ?>
														<div class="item <?= jecho($i, 0, 'active'); ?>">
														<div class="ptb-5">
														<?php if (is_file(LOKASI_PRODUK . $foto[$i])): ?>
															<img style="width:100%;height:auto;margin:0 0 5px;" src="<?= base_url(LOKASI_PRODUK . $foto[$i]); ?>" alt="Foto <?= ($i+1); ?>">
														<?php endif; ?>
														</div>
														</div>
													<?php endif; ?>
													<?php endfor; ?>
												</div>	
												<?php else: ?>
													<div class="image-lapak">
														<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pengganti.jpg") ?>"/>
													</div>
												<?php endif; ?>
											</div>	
											</div>	
											</div>
											<div class="modalfoot bggrey1 bordergrey1 flexcenter" data-dismiss="modal" aria-label="Close">
												<div class="close-btn bgcolor-1"></div>
											</div>	
										</div>
										</div>
									</div>
								</div>
								</div>
							<?php endforeach; ?>
							
							<div class="bigmodal">
								<div class='modal fade center fade bgmodalcolor1' id="map-modal" tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' data-backdrop="false">
									<div class="modal-dialog bggrey1" role="document">
										<div class="modal-absolute bgwhite bordergrey1">
											<div class="modalhead bgcolor-1 flexcenter"><h1>Lokasi</h1></div>
											<div class="inner-modal">
											<div class="modal-body" style="padding:0;"></div>	
											</div>
											<div class="modalfoot bggrey1 bordergrey1 flexcenter" data-dismiss="modal" aria-label="Close">
												<div class="close-btn bgcolor-1"></div>
											</div>	
										</div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="flexcenter">
						<?php $this->load->view("$folder_themes/commons/page"); ?>
						</div>
						<?php else: ?>
							<div class="blankdata">
								<img style="width:100%;height:auto;display:block;border-radius:5px;" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nature.jpg") ?>"/>
								<div class="blankdata-title">
								<h2>Mohon maaf,</h2>
									<h3>Untuk saat ini belum ada produk isi lapak yang dapat ditawarkan...</h3>
								</div>
								<div class="blankdata-bottom">
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/girl.png") ?>"/>
								</div>
							</div>
						<?php endif;?>
					</div>
				</div>
			</div>
			<?php $this->load->view("$folder_themes/plus/middle_page"); ?>
		</div>
		</div>
		<div class="rightsidearea">
			<?php $this->load->view("$folder_themes/plus/side"); ?>			
		</div>
	</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(document).on('shown.bs.modal', '#map-modal', function(event) {
			let link = $(event.relatedTarget);
			let title = link.data('title');
			let modal = $(this);
			modal.find('.modal-title').text(title);
			modal.find('.modal-body').html("<div id='map' style='width: 100%;'></div>");

			let posisi = [link.data('lat'), link.data('lng')];
			let zoom = link.data('zoom');
			let logo = L.icon({
				iconUrl: "<?= setting('icon_lapak_peta') ?>",
			});

			$("#lat").val(link.data('lat'));
			$("#lng").val(link.data('lng'));

			var options = {
				maxZoom: <?= setting('max_zoom_peta') ?>,
				minZoom: <?= setting('min_zoom_peta') ?>,
			};

			pelapak = L.map('map', options).setView(posisi, zoom);
			getBaseLayers(pelapak, "<?= setting('mapbox_key') ?>", "<?= setting('jenis_peta') ?>");
			pelapak.addLayer(new L.Marker(posisi, {
				icon: logo
			}));
		});
	});
</script>