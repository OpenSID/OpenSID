<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="article-single">
	<div class="container-page mb-20">
		<div class="headingpage border-grey-soft bg-white flexleft" style="border-radius:5px 5px 0 0;">
			<div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/shop.svg") ?>" alt=""/></div>
			<h2>PRODUK <?= strtoupper($this->setting->sebutan_desa); ?></h2>
		</div>
		<div class="box-article bg-white" style="border-radius:0 0 5px 5px;">
			<div class="box-statis border-grey-soft" style="border-radius:0 0 5px 5px;">
				<div class="modalfixed">
					<form method="get" class="form-inline text-center mt-10 mb-15">
						<div class="row">
							<div class="col-sm-12">
								<select class="form-control select2" id="id_kategori" name="id_kategori">
									<option selected value="">Semua Kategori</option>
									<?php foreach ($kategori as $kategori_item) : ?>
										<option value="<?= $kategori_item->id ?>" <?= selected($id_kategori, $kategori_item->id) ?>><?= $kategori_item->kategori ?></option>
									<?php endforeach; ?>
								</select>
								<input type="text" name="keyword" autocomplete="off" maxlength="50" class="form-control" value="<?= htmlspecialchars_decode($keyword); ?>" placeholder="Cari Produk">
								<button type="submit" class="btn btn-primary">Cari</button>
								<?php if ($keyword): ?>
									<a href="<?=site_url('lapak')?>" class="btn btn-info">Tampilkan Semua</a>
								<?php endif ?>
							</div>
						</div>
					</form>
					<?php if ($produk): ?>
						<div class="lapakstyle">
							<div class="margin-min7">
								<?php foreach ($produk as $in => $pro): ?>
									<?php $foto = json_decode($pro->foto); ?>
									<div class="column-lapak">
										<div class="pd-lr-7">
											<div class="imglist">
												<div class="lapak-inner">
													<?php if ($pro->foto): ?>
														<div id="foto-produk-<?= $in; ?>" class="carousel slide" data-ride="carousel">
															<div class="carousel-inner">
																<?php for ($i = 0; $i < $this->setting->banyak_foto_tiap_produk; $i++): ?>
																	<?php if ($foto[$i]): ?>
																		<div class="item <?= jecho($i, 0, 'active'); ?>">
																			<?php if (is_file(LOKASI_PRODUK . $foto[$i])): ?>
																				<a href="<?= base_url(LOKASI_PRODUK . $foto[$i]); ?>"  data-fancybox="images">
																					<div class="image-lapak">
																						<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= base_url(LOKASI_PRODUK . $foto[$i]); ?>" alt="Foto <?= ($i+1); ?>">
																						<div class="gradient-grey-bottom"></div>
																					</div>
																				</a>
																			<?php else: ?>
																				<div class="image-lapak">
																					<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $latar_website ? $latar_website : base_url('assets/front/css/images/latar_website.jpg'); ?>" alt=""/>
																					<div class="gradient-grey-bottom"></div>
																				</div>
																			<?php endif; ?>
																		</div>
																	<?php endif; ?>
																<?php endfor; ?>
															</div>
															<a class="left carousel-control flexcenter" href="#foto-produk-<?= $in; ?>" data-slide="prev">
																<div class="carousel-control-leftarrow"></div>
															</a>
															<a class="right carousel-control flexcenter" href="#foto-produk-<?= $in; ?>" data-slide="next">
																<div class="carousel-control-rightarrow"></div>
															</a>
														</div>	
													<?php else: ?>
														<div class="image-lapak">
															<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $latar_website ? $latar_website : base_url('assets/front/css/images/latar_website.jpg'); ?>" alt=""/>
															<div class="gradient-grey-bottom"></div>
														</div>
													<?php endif; ?>
													<div class="lapak-absolute" style="text-align:center;">
														<h2><?= htmlspecialchars_decode($pro->nama); ?></h2>
													</div>
												</div>
											</div>
											<!-- Jika ingin menambahkan badge potongan dalam persen
												<?= persen(($pro->tipe_potongan == 1) ? $pro->potongan/100 : $pro->potongan / $pro->harga); ?>
											-->
											<div class="info-lapak bg-grey-medium">
												<div class="harga-produk flexcenter">
													<?php $harga_potongan = ($pro->tipe_potongan == 1) ? ($pro->harga * ($pro->potongan / 100)) : $pro->potongan; ?>
													<h2>
														<?php if ($pro->potongan != 0): ?>
															<font style="color:red; text-decoration: line-through red;"><?= rupiah($pro->harga); ?></font>
														<?php endif; ?>
													</h2>
													<h3><b><?= rupiah($pro->harga - $harga_potongan); ?></b></h3>	
												</div>
												<div class="flexcenter">
													<a data-remote="false" data-toggle="modal" data-target="#detail<?= $in; ?>" title="Detail" >
														<div class="tombol-medium flexcenter bg-grey-dark3"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/komentar.svg") ?>" alt=""/><p>Detail</p>
														</div>
													</a>
													<?php if ($pro->telepon): ?>
														<?php $pesan = strReplaceArrayRecursive(['[nama_produk]' => $pro->nama, '[link_web]' => base_url('lapak'), '<br />' => "%0A"], nl2br($this->setting->pesan_singkat_wa)); ?>
														<a href="https://api.whatsapp.com/send?phone=<?=format_telpon($pro->telepon);?>&amp;text=<?= htmlspecialchars_decode($pesan); ?>" rel="noopener noreferrer" target="_blank" title="WhatsApp">
															<div class="tombol-medium flexcenter bg-grey-dark3"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/shop.svg") ?>" alt=""/><p>Beli</p></div>
														</a>
													<?php endif; ?>
													<a data-remote="false" data-toggle="modal" data-target="#map-modal" title="Lokasi" data-lat="<?= $pro->lat?>" data-lng="<?= $pro->lng?>" data-zoom="<?= $pro->zoom?>" data-title="Lokasi <?= $pro->pelapak?>">	
														<div class="tombol-medium flexcenter bg-grey-dark3"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/location.svg") ?>" alt=""/><p>Lokasi</p></div>
													</a>
												</div>
											</div>
										</div>
									</div>
									<div class='modal fade' id="detail<?= $in; ?>" tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' data-backdrop="false">
										<div class='modal-dialog'>
											<div class="modal-container-big bg-white">
												<div class="topmodal bg-gradient-hor flexleft" data-dismiss="modal" aria-hidden="true">Detail Produk<div class="close-button"></div></div>
												<div class="modal-container-area">
													<div class="modalscroll">
														<div class="deskripsi-lapak bg-white">
															<div class="margin-min10">
																<div class="deskripsi-left">
																	<div class="pd-lr-10">
																		<h1><?= htmlspecialchars_decode($pro->nama); ?></h1>
																		<p><?= nl2br(e($pro->deskripsi)); ?></p>
																		<h3>Kategori : <b><?= $pro->kategori ?></b></h3>
																		<h3>Pelapak : <b><?= htmlspecialchars_decode($pro->pelapak) ?? 'ADMIN'; ?></b></h3>
																		<h3>Harga : <b><?= rupiah($pro->harga - $harga_potongan); ?></b></h3>
																		<div class="flexleft" style="margin-top:20px;">
																			<a data-remote="false" data-toggle="modal" data-target="#map-modal" title="Lokasi" data-lat="<?= $pro->lat?>" data-lng="<?= $pro->lng?>" data-zoom="<?= $pro->zoom?>" data-title="Lokasi <?= $pro->pelapak?>">	
																				<div class="flexleft" style="margin-top:10px;">
																				<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/location.svg") ?>" alt=""/>
																				<h2>Lokasi Penjual</h2>
																				</div>
																			</a>
																		</div>
																		<?php if ($pro->telepon): ?>
																			<?php $pesan = strReplaceArrayRecursive(['[nama_produk]' => $pro->nama, '[link_web]' => base_url('lapak'), '<br />' => "%0A"], nl2br($this->setting->pesan_singkat_wa)); ?>
																			<a href="https://api.whatsapp.com/send?phone=<?=format_telpon($pro->telepon);?>&amp;text=<?= htmlspecialchars_decode($pesan); ?>" rel="noopener noreferrer" target="_blank" title="WhatsApp">
																				<div class="flexleft" style="margin-top:10px;">
																					<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/shop.svg") ?>" alt=""/>
																					<h2>Hubungi Penjual</h2>
																				</div>
																			</a>
																		<?php endif; ?>
																	</div>
																</div>
																<div class="deskripsi-right">
																	<div class="pd-lr-10">
																		<?php if ($pro->foto): ?>
																			<div id="foto-produk-<?= $in; ?>" class="carousel slide" data-ride="carousel">
																				<?php for ($i = 0; $i < $this->setting->banyak_foto_tiap_produk; $i++): ?>
																					<?php if ($foto[$i]): ?>
																						<div class="item <?= jecho($i, 0, 'active'); ?>">
																							<?php if (is_file(LOKASI_PRODUK . $foto[$i])): ?>
																								<img style="width:100%;height:auto;margin:0 0 5px;" class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= base_url(LOKASI_PRODUK . $foto[$i]); ?>" alt="Foto <?= ($i+1); ?>">
																							<?php else: ?>
																								<div class="image-lapak">
																									<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $latar_website ? $latar_website : base_url('assets/front/css/images/latar_website.jpg'); ?>" alt=""/>
																								</div>
																							<?php endif; ?>
																						</div>
																					<?php endif; ?>
																				<?php endfor; ?>
																			</div>	
																		<?php else: ?>
																			<div class="image-lapak">
																				<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $latar_website ? $latar_website : base_url('assets/front/css/images/latar_website.jpg'); ?>" alt=""/>
																			</div>
																		<?php endif; ?>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<style type="text/css">
							#map{height:92vh !important;}
						</style>
						<div class='modal-lokasi'>
							<div class='modal fade' id="map-modal" tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' data-backdrop="false">
								<div class='modal-dialog'>
									<div class="modal-container-big bg-white">
										<div class="topmodal bg-gradient-hor flexleft" data-dismiss="modal" aria-hidden="true">Lokasi Produk/Lapak<div class="close-button"></div></div>
										<div class="modal-container-area">
											<div class="modal-body" style="padding:0;">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php $this->load->view($folder_themes . "/commons/paging"); ?>
					<?php else: ?>
						<div class="no-found">
							<div class="margin-min10 flexcenter">
								<div class="no-found-title">
									<div class="pd-lr-10">
										<h2>404! PRODUK BELUM TERSEDIA</h2>
										<h3>Maaf, belum tersedia produk pada halaman ini.</h3>
									</div>
								</div>
								<div class="no-found-image">
									<div class="pd-lr-10">
										<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nodata.png") ?>" alt=""/>
									</div>
								</div>
							</div>
						</div>
					<?php endif;?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var token = "<?= setting('mapbox_key') ?>";
	var jenis_peta = "<?= setting('jenis_peta') ?>";
	
	$(document).ready(function() {
		$(document).on('shown.bs.modal','#map-modal', function(event) {
			var link = $(event.relatedTarget);
			var title = link.data('title');
			var modal = $(this);
			modal.find('.modal-title').text(title);
			modal.find('.modal-body').html("<div id='map' style='width: 100%;'></div>");

			var posisi = [link.data('lat'), link.data('lng')];
			var zoom = link.data('zoom');
			let logo = L.icon({
			iconUrl: '<?= setting('icon_lapak_peta') ?>',
			});
			$("#lat").val(link.data('lat'));
			$("#lng").val(link.data('lng'));

			// Inisialisasi tampilan peta
			pelapak = L.map('map').setView(posisi, zoom);

			// Menampilkan BaseLayers Peta
			getBaseLayers(pelapak, token, jenis_peta);

			// Tampilkan Posisi Pelapak
			marker = new L.Marker(posisi, {
				draggable: false,
          		icon: logo
			});
			pelapak.addLayer(marker);
			L.marker(posisi, {
				icon: logo
			}).addTo(pelapak).bindPopup(popup);
			L.control.scale().addTo(pelapak);
        	pelapak.invalidateSize();
		});
	});
</script>
