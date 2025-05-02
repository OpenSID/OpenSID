<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style>
	.linklapak:hover .bgcolor-1 {
		background: #717171;
	}
</style>
<?php $file = __DIR__ . '/../lapakhome/lapak.json'; ?>
<?php $json = file_get_contents($file); ?>
<?php $array = json_decode($json, true); ?>

<?php if (is_file($file)) : ?>
	<?php if ($array['aktif']) : ?>
		<?php if(IS_PREMIUM) : ?>
		<?php if (theme_config('lapak_desa', true)) : ?>
		<div class="col-default">
			<div class="widget-head bggrad-color2 flexleft" style="border-radius:5px 5px 0 0;">
				<svg viewBox="0 0 24 24">
					<path d="M12,13A5,5 0 0,1 7,8H9A3,3 0 0,0 12,11A3,3 0 0,0 15,8H17A5,5 0 0,1 12,13M12,3A3,3 0 0,1 15,6H9A3,3 0 0,1 12,3M19,6H17A5,5 0 0,0 12,1A5,5 0 0,0 7,6H5C3.89,6 3,6.89 3,8V20A2,2 0 0,0 5,22H19A2,2 0 0,0 21,20V8C21,6.89 20.1,6 19,6Z" />
				</svg>
				<h1>Lapak <?= ucwords($this->setting->sebutan_desa); ?></h1>
			</div>
			<div class="lapakhome bgwhite bordergrey1">
				<?php if (theme_config('lapak_desa_contoh', true)) : ?>
					<div class="rowsame margin-minlr-5 lapakstyle">
						<?php ($array['lapak']);
						foreach ($array['lapak'] as $index => $product) : ?>
							<?php $hp = $array['multi'] ? $product['hp'] : $array['default_hp'] ?>
							<?php $link = $array['mode'] == 'hp' ? 'https://api.whatsapp.com/send?phone=' . $hp . '&text=Saya Mau Pesan ' . $product['produk'] : $product['link'] ?>
							<?php $gambar = base_url($this->theme_folder . '/' . $this->theme . '/lapakhome/foto/' . $product['gambar']) ?>
							<div class="lapak-col bordergrey1">
								<div class="hiddenrelative forhover">
									<?php
									$allowed = array('mp4', 'webm', 'ogg');
									$filename = pathinfo($gambar);
									$ext = $filename['extension'];
									$allowed_pic = array('jpg', 'png', 'jpeg');
									$filename_pic = pathinfo($gambar);
									$ext_pic = $filename['extension'];
									if (in_array($ext, $allowed)) : ?>
										<div class="col-12 p-3 text-center">
											<object class="mw-100" height="210">
												<param name="src" value="<?= $gambar ?>">
												<param name="autoplay" value="false">
												<param name="controller" value="true">
												<param name="bgcolor" value="#333333">
												<embed type="mp4" src="<?= $gambar ?>" autostart="false" loop="false" controller="true" bgcolor="#333333"></embed>
											</object>
										</div>
									<?php elseif (in_array($ext_pic, $allowed_pic)) : ?>
										<div class="image-lapak">
											<a data-fancybox="gallery" href="<?= $gambar ?>">
												<img src="<?= $gambar ?>" alt="<?= $product['produk'] ?>" loading="lazy">
											</a>
										</div>
									<?php else : ?>
										<div class="image-lapak">
											<img src="<?= base_url("$this->theme_folder/$this->theme/images/pengganti.jpg") ?>" />
											<div class="logo-no-image"><img src="<?= gambar_desa($desa['logo']); ?>" /></div>
										</div>
									<?php endif; ?>
									<div class="absolute-title">
										<h2 class="hover-height"><?= $product['produk'] ?></h2>
									</div>
								</div>
								<div class="hiddenrelative margin-topbottom-10">
									<div class="harga-produk flexcenter">
										<div>
											<?php
											if ($gambar) {
												$harga_produk = number_format($product['diskon']);
												$diskon = '<s>Rp. ' . number_format($product['harga']) . ',-</s></small>';
											} else {
												$harga_produk = number_format($product['harga']);
											}
											?>
											<p><span style="color:#ff0000;text-decoration: line-through red;"><?= $diskon; ?></span></p>
											<p class="color-2"><b>Rp. <?= $harga_produk; ?>,-</b></p>
										</div>
									</div>
								</div>
								<div class="lapak-open flexcenter">
									<a data-remote="false" data-toggle="modal" data-target="#deskripsi<?= $product['id']; ?>" title="Detail">
										<div class="lapak-open-item bordergrey1 flexcenter">
											<div class="lapak-icon bgorange flexcenter"><svg viewBox="0 0 24 24">
													<path d="M19,20H4C2.89,20 2,19.1 2,18V6C2,4.89 2.89,4 4,4H10L12,6H19A2,2 0 0,1 21,8H21L4,8V18L6.14,10H23.21L20.93,18.5C20.7,19.37 19.92,20 19,20Z" />
												</svg></div>
										</div>
									</a>
									<a href="<?= $link ?>" rel='noopener noreferrer' target='_blank' title='WhatsApp'>
										<div class="lapak-open-item bordergrey1 flexcenter">
											<div class="lapak-icon bghijau flexcenter"><svg viewBox="0 0 24 24">
													<path d="M12,13A5,5 0 0,1 7,8H9A3,3 0 0,0 12,11A3,3 0 0,0 15,8H17A5,5 0 0,1 12,13M12,3A3,3 0 0,1 15,6H9A3,3 0 0,1 12,3M19,6H17A5,5 0 0,0 12,1A5,5 0 0,0 7,6H5C3.89,6 3,6.89 3,8V20A2,2 0 0,0 5,22H19A2,2 0 0,0 21,20V8C21,6.89 20.1,6 19,6Z" />
												</svg></div>
										</div>
									</a>
									<a data-remote="false" data-toggle="modal" data-target="#modalBesar<?= $product['id']; ?>" title="Lokasi">
										<div class="lapak-open-item bordergrey1 flexcenter">
											<div class="lapak-icon bgbiru flexcenter"><svg viewBox="0 0 24 24">
													<path d="M12,2C15.31,2 18,4.66 18,7.95C18,12.41 12,19 12,19C12,19 6,12.41 6,7.95C6,4.66 8.69,2 12,2M12,6A2,2 0 0,0 10,8A2,2 0 0,0 12,10A2,2 0 0,0 14,8A2,2 0 0,0 12,6M20,19C20,21.21 16.42,23 12,23C7.58,23 4,21.21 4,19C4,17.71 5.22,16.56 7.11,15.83L7.75,16.74C6.67,17.19 6,17.81 6,18.5C6,19.88 8.69,21 12,21C15.31,21 18,19.88 18,18.5C18,17.81 17.33,17.19 16.25,16.74L16.89,15.83C18.78,16.56 20,17.71 20,19Z" />
												</svg></div>
										</div>
									</a>
								</div>
							</div>

							<div class="lapakdetail-modal">
								<div class="bigmodal">
									<div class="modal center fade bgmodalcolor1" id="deskripsi<?= $product['id']; ?>" role="dialog" aria-labelledby="lapor" aria-hidden="true" data-backdrop="false">
										<div class="modal-dialog bggrey1" role="document">
											<div class="modal-absolute bgwhite bordergrey1">
												<div class="modalhead bgcolor-1 flexcenter">
													<h1>Detail Produk</h1>
												</div>
												<div class="inner-modal" style="border-radius:0;">
													<div class="withscroll">
														<div class="lapakdetail" style="padding:10px;">
															<h1><?= $product['judul'] ?></h1>
															<div class="hiddenrelative margin-topbottom-10">
																<table style="width:100%;">
																	<?php if ($diskon) : ?>
																		<tr>
																			<td>Harga Sebelumnya</td>
																			<td style="width:15px;text-align:center;">:</td>
																			<td>
																				<font style="color:red; text-decoration: line-through red;"><?= $diskon; ?></font>
																			</td>
																		</tr>
																	<?php endif; ?>
																	<tr>
																		<td>Harga Terbaru</td>
																		<td style="width:15px;text-align:center;">:</td>
																		<td><b class="color-2">Rp. <?= $harga_produk; ?></b></td>
																	</tr>
																	<tr>
																		<td>Pelapak</td>
																		<td style="width:15px;text-align:center;">:</td>
																		<td><b><?= $pro->pelapak; ?></b></td>
																	</tr>
																</table>
															</div>
															<p style="margin:10px 0 5px;"><b>Deskripsi Produk</b></p>
															<p style="margin:0 0 15px;"><?= $product['deskripsi'] ?></p>
															<?php
															$allowed = array('mp4', 'webm', 'ogg');
															$filename = pathinfo($gambar);
															$ext = $filename['extension'];
															$allowed_pic = array('jpg', 'png', 'jpeg');
															$filename_pic = pathinfo($gambar);
															$ext_pic = $filename['extension'];
															if (in_array($ext, $allowed)) : ?>
																<div class="col-12 p-3 text-center">
																	<object class="mw-100" height="210">
																		<param name="src" value="<?= $gambar ?>">
																		<param name="autoplay" value="false">
																		<param name="controller" value="true">
																		<param name="bgcolor" value="#333333">
																		<embed type="mp4" src="<?= $gambar ?>" autostart="false" loop="false" controller="true" bgcolor="#333333"></embed>
																	</object>
																</div>
															<?php elseif (in_array($ext_pic, $allowed_pic)) : ?>
																<img style="width:100%;height:auto;margin:0 0 5px;" src="<?= $gambar ?>" alt="<?= $product['produk'] ?>" loading="lazy">
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
					</div>
				<?php endif ?>
				<a href="<?= site_url('lapak') ?>">
					<div class="linklapak flexcenter" style="margin:5px 0;">
						<div class="b-btn bgcolor-1 flexcenter">
							<p>Selengkapnya</p>
						</div>
					</div>
				</a>
			</div>
		</div>
		<?php endif ?>
		<?php else : ?>
		<div class="col-default">
			<div class="widget-head bggrad-color2 flexleft" style="border-radius:5px 5px 0 0;">
				<svg viewBox="0 0 24 24">
					<path d="M12,13A5,5 0 0,1 7,8H9A3,3 0 0,0 12,11A3,3 0 0,0 15,8H17A5,5 0 0,1 12,13M12,3A3,3 0 0,1 15,6H9A3,3 0 0,1 12,3M19,6H17A5,5 0 0,0 12,1A5,5 0 0,0 7,6H5C3.89,6 3,6.89 3,8V20A2,2 0 0,0 5,22H19A2,2 0 0,0 21,20V8C21,6.89 20.1,6 19,6Z" />
				</svg>
				<h1>Lapak <?= ucwords($this->setting->sebutan_desa); ?></h1>
			</div>
			<div class="lapakhome bgwhite bordergrey1">
				<?php if (theme_config('lapak_desa_contoh', true)) : ?>
					<div class="rowsame margin-minlr-5 lapakstyle">
						<?php ($array['lapak']);
						foreach ($array['lapak'] as $index => $product) : ?>
							<?php $hp = $array['multi'] ? $product['hp'] : $array['default_hp'] ?>
							<?php $link = $array['mode'] == 'hp' ? 'https://api.whatsapp.com/send?phone=' . $hp . '&text=Saya Mau Pesan ' . $product['produk'] : $product['link'] ?>
							<?php $gambar = base_url($this->theme_folder . '/' . $this->theme . '/lapakhome/foto/' . $product['gambar']) ?>
							<div class="lapak-col bordergrey1">
								<div class="hiddenrelative forhover">
									<?php
									$allowed = array('mp4', 'webm', 'ogg');
									$filename = pathinfo($gambar);
									$ext = $filename['extension'];
									$allowed_pic = array('jpg', 'png', 'jpeg');
									$filename_pic = pathinfo($gambar);
									$ext_pic = $filename['extension'];
									if (in_array($ext, $allowed)) : ?>
										<div class="col-12 p-3 text-center">
											<object class="mw-100" height="210">
												<param name="src" value="<?= $gambar ?>">
												<param name="autoplay" value="false">
												<param name="controller" value="true">
												<param name="bgcolor" value="#333333">
												<embed type="mp4" src="<?= $gambar ?>" autostart="false" loop="false" controller="true" bgcolor="#333333"></embed>
											</object>
										</div>
									<?php elseif (in_array($ext_pic, $allowed_pic)) : ?>
										<div class="image-lapak">
											<a data-fancybox="gallery" href="<?= $gambar ?>">
												<img src="<?= $gambar ?>" alt="<?= $product['produk'] ?>" loading="lazy">
											</a>
										</div>
									<?php else : ?>
										<div class="image-lapak">
											<img src="<?= base_url("$this->theme_folder/$this->theme/images/pengganti.jpg") ?>" />
											<div class="logo-no-image"><img src="<?= gambar_desa($desa['logo']); ?>" /></div>
										</div>
									<?php endif; ?>
									<div class="absolute-title">
										<h2 class="hover-height"><?= $product['produk'] ?></h2>
									</div>
								</div>
								<div class="hiddenrelative margin-topbottom-10">
									<div class="harga-produk flexcenter">
										<div>
											<?php
											if ($gambar) {
												$harga_produk = number_format($product['diskon']);
												$diskon = '<s>Rp. ' . number_format($product['harga']) . ',-</s></small>';
											} else {
												$harga_produk = number_format($product['harga']);
											}
											?>
											<p><span style="color:#ff0000;text-decoration: line-through red;"><?= $diskon; ?></span></p>
											<p class="color-2"><b>Rp. <?= $harga_produk; ?>,-</b></p>
										</div>
									</div>
								</div>
								<div class="lapak-open flexcenter">
									<a data-remote="false" data-toggle="modal" data-target="#deskripsi<?= $product['id']; ?>" title="Detail">
										<div class="lapak-open-item bordergrey1 flexcenter">
											<div class="lapak-icon bgorange flexcenter"><svg viewBox="0 0 24 24">
													<path d="M19,20H4C2.89,20 2,19.1 2,18V6C2,4.89 2.89,4 4,4H10L12,6H19A2,2 0 0,1 21,8H21L4,8V18L6.14,10H23.21L20.93,18.5C20.7,19.37 19.92,20 19,20Z" />
												</svg></div>
										</div>
									</a>
									<a href="<?= $link ?>" rel='noopener noreferrer' target='_blank' title='WhatsApp'>
										<div class="lapak-open-item bordergrey1 flexcenter">
											<div class="lapak-icon bghijau flexcenter"><svg viewBox="0 0 24 24">
													<path d="M12,13A5,5 0 0,1 7,8H9A3,3 0 0,0 12,11A3,3 0 0,0 15,8H17A5,5 0 0,1 12,13M12,3A3,3 0 0,1 15,6H9A3,3 0 0,1 12,3M19,6H17A5,5 0 0,0 12,1A5,5 0 0,0 7,6H5C3.89,6 3,6.89 3,8V20A2,2 0 0,0 5,22H19A2,2 0 0,0 21,20V8C21,6.89 20.1,6 19,6Z" />
												</svg></div>
										</div>
									</a>
									<a data-remote="false" data-toggle="modal" data-target="#modalBesar<?= $product['id']; ?>" title="Lokasi">
										<div class="lapak-open-item bordergrey1 flexcenter">
											<div class="lapak-icon bgbiru flexcenter"><svg viewBox="0 0 24 24">
													<path d="M12,2C15.31,2 18,4.66 18,7.95C18,12.41 12,19 12,19C12,19 6,12.41 6,7.95C6,4.66 8.69,2 12,2M12,6A2,2 0 0,0 10,8A2,2 0 0,0 12,10A2,2 0 0,0 14,8A2,2 0 0,0 12,6M20,19C20,21.21 16.42,23 12,23C7.58,23 4,21.21 4,19C4,17.71 5.22,16.56 7.11,15.83L7.75,16.74C6.67,17.19 6,17.81 6,18.5C6,19.88 8.69,21 12,21C15.31,21 18,19.88 18,18.5C18,17.81 17.33,17.19 16.25,16.74L16.89,15.83C18.78,16.56 20,17.71 20,19Z" />
												</svg></div>
										</div>
									</a>
								</div>
							</div>

							<div class="lapakdetail-modal">
								<div class="bigmodal">
									<div class="modal center fade bgmodalcolor1" id="deskripsi<?= $product['id']; ?>" role="dialog" aria-labelledby="lapor" aria-hidden="true" data-backdrop="false">
										<div class="modal-dialog bggrey1" role="document">
											<div class="modal-absolute bgwhite bordergrey1">
												<div class="modalhead bgcolor-1 flexcenter">
													<h1>Detail Produk</h1>
												</div>
												<div class="inner-modal" style="border-radius:0;">
													<div class="withscroll">
														<div class="lapakdetail" style="padding:10px;">
															<h1><?= $product['judul'] ?></h1>
															<div class="hiddenrelative margin-topbottom-10">
																<table style="width:100%;">
																	<?php if ($diskon) : ?>
																		<tr>
																			<td>Harga Sebelumnya</td>
																			<td style="width:15px;text-align:center;">:</td>
																			<td>
																				<font style="color:red; text-decoration: line-through red;"><?= $diskon; ?></font>
																			</td>
																		</tr>
																	<?php endif; ?>
																	<tr>
																		<td>Harga Terbaru</td>
																		<td style="width:15px;text-align:center;">:</td>
																		<td><b class="color-2">Rp. <?= $harga_produk; ?></b></td>
																	</tr>
																	<tr>
																		<td>Pelapak</td>
																		<td style="width:15px;text-align:center;">:</td>
																		<td><b><?= $pro->pelapak; ?></b></td>
																	</tr>
																</table>
															</div>
															<p style="margin:10px 0 5px;"><b>Deskripsi Produk</b></p>
															<p style="margin:0 0 15px;"><?= $product['deskripsi'] ?></p>
															<?php
															$allowed = array('mp4', 'webm', 'ogg');
															$filename = pathinfo($gambar);
															$ext = $filename['extension'];
															$allowed_pic = array('jpg', 'png', 'jpeg');
															$filename_pic = pathinfo($gambar);
															$ext_pic = $filename['extension'];
															if (in_array($ext, $allowed)) : ?>
																<div class="col-12 p-3 text-center">
																	<object class="mw-100" height="210">
																		<param name="src" value="<?= $gambar ?>">
																		<param name="autoplay" value="false">
																		<param name="controller" value="true">
																		<param name="bgcolor" value="#333333">
																		<embed type="mp4" src="<?= $gambar ?>" autostart="false" loop="false" controller="true" bgcolor="#333333"></embed>
																	</object>
																</div>
															<?php elseif (in_array($ext_pic, $allowed_pic)) : ?>
																<img style="width:100%;height:auto;margin:0 0 5px;" src="<?= $gambar ?>" alt="<?= $product['produk'] ?>" loading="lazy">
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
					</div>
				<?php endif ?>
				<a href="<?= site_url('lapak') ?>">
					<div class="linklapak flexcenter" style="margin:5px 0;">
						<div class="b-btn bgcolor-1 flexcenter">
							<p>Selengkapnya</p>
						</div>
					</div>
				</a>
			</div>
		</div>
		<?php endif ?>
		<?php foreach ($array['lapak'] as $index => $product) : ?>
			<div class="bigmodal">
				<div class="modal center fade bgmodalcolor1" id="modalBesar<?= $product['id']; ?>" role="dialog" aria-labelledby="lapak" aria-hidden="true" data-backdrop="false">
					<div class="modal-dialog bggrey1" role="document">
						<div class="modal-absolute bgwhite bordergrey1">
							<div class="modalhead bgcolor-1 flexcenter">
								<h1>Lokasi</h1>
							</div>
							<div class="inner-modal">
								<div class="map_lapakhome" id="map_canvas<?= $product['id']; ?>"></div>
							</div>
							<div class="modalfoot bggrey1 bordergrey1 flexcenter" data-dismiss="modal" aria-label="Close">
								<div class="close-btn bgcolor-1"></div>
							</div>
						</div>
					</div>
				</div>
			</div>


		<?php endforeach; ?>


		<script>
			$(document).ready(function() {
				<?php foreach ($array['lapak'] as $index => $product) : ?>
					<?php $gambar = base_url($this->theme_folder . '/' . $this->theme . '/lapakhome/gambar/' . $product['gambar']) ?>
					$('#modalBesar<?= $product['id']; ?>').on('shown.bs.modal', function() {
						var container = L.DomUtil.get('map<?= $product['id']; ?>');
						if (container != null) {
							container._leaflet_id = null;
						}
						var map<?= $product['id']; ?> = L.map('map_canvas<?= $product['id']; ?>').setView([<?= $product['lat']; ?>, <?= $product['lng']; ?>], 15);
						var logo = L.icon({
							iconUrl: '<?= BASE_URL() ?>assets/images/gis/point/fastfood.png',
							iconSize: [32, 32], // size of the icon
							iconAnchor: [16, 16], // point of the icon which will correspond to marker's location
							popupAnchor: [-1, 1] // point from which the popup should open relative to the iconAnchor
						});
						var foto = "<img src='<?= $gambar; ?>' class='mw-100' style='border-radius:1px;-moz-border-radius:3px;-webkit-border-radius:1px;width:100%;'/>";
						var info_tempat =
							'<div id="content">' +
							'<?php $allowed = array("mp4", "webm", "ogg");
								$filename = pathinfo($gambar);
								$ext = $filename["extension"];
								$allowed_pic = array('jpg', 'png', 'jpeg');
								$filename_pic = pathinfo($gambar);
								$ext_pic = $filename['extension'];
								if (in_array($ext, $allowed)) : ?>' +
							'<div class="col-12 p-3 text-center">' +
							'    <object class="mw-100">' +
							'      <param name="src" value="<?= $gambar; ?>">' +
							'      <param name="autoplay" value="false">' +
							'      <param name="controller" value="true">' +
							'      <param name="bgcolor" value="#333333">' +
							'      <embed type="mp4" src="<?= $gambar; ?>" autostart="false" loop="false" controller="true" bgcolor="#333333"></embed>' +
							'    </object>' +
							'</div>' +
							'<?php endif; ?>' +
							'<table>' +
							'<tr>' +
							'<td width="60px" valign="top">Produk</td>' +
							'<td width="10px" valign="top">:</td>' +
							'<td><b style="color:red">' + <?= json_encode($product['judul']) ?> + '</b></td>' +
							'</tr>' +
							'<tr>' +
							'<td width="60px">Harga</td>' +
							'<td width="10px">:</td>' +
							'<td><s>Rp. ' + <?= json_encode($product['harga']) ?> + ',-</s> Rp. ' + <?= json_encode($product['diskon']) ?> + ',-</td>' +
							'</tr>' +
							'<tr>' +
							'<td width="60px" valign="top">Alamat</td>' +
							'<td width="10px" valign="top">:</td>' +
							'<td>' + <?= json_encode($product['alamat']) ?> + '</td>' +
							'</tr>' +
							'<tr>' +
							'<td width="60px">Kontak</td>' +
							'<td width="10px">:</td>' +
							'<td>' + <?= json_encode(substr_replace($product['hp'], '0', 0, 2)) ?> + '</td>' +
							'</tr>' +
							'<tr>' +
							'<td width="60px">Tujuan</td>' +
							'<td width="10px">:</td>' +
							'<td><a style="color:#fff;" class="btn btn-sm btn-danger danger-gradient mt-0" target="_blank" rel="noopener noreferrer" href="https://www.google.com/maps/dir//' + <?= $product['lat']; ?> + ',' + <?= $product['lng']; ?> + '/"><i class="fa fa-map-marker"></i> Arah ke Lokasi</a></td>' +
							'</tr>' +
							'</table>' +
							'</div>';
						var lapakmark = L.marker([<?= $product['lat']; ?>, <?= $product['lng']; ?>], {
								icon: logo
							}).addTo(map<?= $product['id']; ?>)
							.bindPopup(info_tempat).openPopup();
						L.control.scale().addTo(map<?= $product['id']; ?>);
						var baseLayers = getBaseLayers(map<?= $product['id']; ?>, '<?= $this->setting->google_key ?>');
						L.control.layers(baseLayers, null, {
							position: 'topright',
							collapsed: true
						}).addTo(map<?= $product['id']; ?>);
						map<?= $product['id']; ?>.invalidateSize();
					});
				<?php endforeach; ?>
			});
		</script>
	<?php endif ?>
<?php endif ?>