<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if($artikel) : ?>
	<?php $file = __DIR__ . '/lapak.json'; ?>
	<?php if(is_file($file)) : ?>
		<?php $json = file_get_contents($file); ?>
		<?php $array = json_decode($json, true); ?>
		<?php if($array['aktif']) : ?>
			<style>
				.map {
					width:100%;
					height:70vh;
				}
				#qrcode .panel-body-lg {
					margin-right: 5px;
					margin-bottom: 20px;
					pointer-events: visiblePainted;
					pointer-events: auto;
					position: relative;
					z-index: 800;
				}
				.leaflet-popup-content {
					height: auto;
					width: 225px;
					overflow-y: scroll;
				}
			</style>
			<div class="lapakplusstyle">
				<div class="relative-row ptb-10">
					<div class="container-page">
						<div class="lapakplus border-grey-soft bg-white">
							<div class="lapakplus-left bg-grey-medium border-grey-soft">
								<div class="padat lapakplus-head">
									<h2>Produk</h2>
									<h1>Warga</h1>
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/shop.svg") ?>" height="80px"; alt=""/>
								</div>
							</div>
							<div class="lapakplus-right">
								<div class="relative-hid lapakplus-right-inner">
									<div class="carouselcustom js-flickity" data-flickity='{ "autoPlay": false, "cellAlign": "left", "wrapAround": true }'>
										<?php shuffle($array['lapak']); foreach($array['lapak'] as $index => $product) : ?>
										<?php $hp = $array['multi'] ? $product['hp'] : $array['default_hp'] ?>
										<?php $link = $array['mode'] == 'hp' ? 'https://api.whatsapp.com/send?phone='.$hp.'&text=Saya Mau Pesan '.$product['produk'].' '.$product['link'] : $product['link'] ?>
										<?php $gambar = base_url($this->theme_folder.'/'.$this->theme .'/assets/lapak/' . $product['gambar']) ?>
										<?php $youtube = 'https://www.youtube.com/embed/'.$product['gambar'] ?>
										<div class="carouselcustom-item">
											<div class="lapakplus-column-2b">
												<?php
												$allowed = array('mp4', 'webm', 'ogg');
												$filename = pathinfo($gambar);
												$ext = $filename['extension'];
												$allowed_pic = array('jpg', 'png', 'jpeg');
												$filename_pic = pathinfo($gambar);
												$ext_pic = $filename['extension'];
												if (in_array($ext, $allowed)): ?>
													<div class="">
														<object class="mw-100" height="210">
															<param name="src" value="<?= $gambar ?>">
															<param name="autoplay" value="false">
															<param name="controller" value="true">
															<param name="bgcolor" value="#333333">
															<embed type="mp4" src="<?= $gambar ?>" autostart="false" loop="false" controller="true" bgcolor="#333333">
															</object>
														</div>
													<?php elseif (in_array($ext_pic, $allowed_pic)): ?>
														<div class="image-lapakplus">
															<a data-fancybox="gallery" href="<?= $gambar ?>">
																<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $gambar ?>" alt="<?= $product['produk'] ?>" loading="lazy">
															</a>
														</div>
													<?php else: ?>
														<div class="image-lapakplus">
															<iframe class="mw-100" height="210" src="<?= $youtube ?>" frameborder="0" loading="lazy"></iframe>
														</div>
													<?php endif; ?>
												</div>
												<div class="lapakplus-column-2b">
													<div class="lapakplus-title bg-white">
														<h2><?= $product['produk'] ?></h2>
														<div class="lapakplus-absolute">
															<div class="relative-hid mb-15">
																<h3>
																	<?php
																	if ($gambar) {
																		$harga_produk = number_format($product['diskon']);
																		$diskon = '<s>Rp. ' . number_format($product['harga']) . ',-</s>';
																	} else {
																		$harga_produk = number_format($product['harga']);
																	}
																	?>
																	<?= $diskon; ?><br/><b class="color-1">Rp. <?= $harga_produk; ?>,-</b>
																</h3>
															</div>
															<div class="flexcenter">	
																<div class="tombol-medium flexcenter" style="margin:0 2px;"><a href="<?= $link ?>" rel='noopener noreferrer' target='_blank' title='Pesan/Beli'><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/shop.svg") ?>" alt=""/></a></div>
																<div class="tombol-medium flexcenter" style="margin:0 2px;"><a title="Detail" data-remote="false" data-toggle="modal" data-target="#deskripsi<?= $product['id']; ?>"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/komentar.svg") ?>" alt=""/></a></div>
																<div class="tombol-medium flexcenter" style="margin:0 2px;"><a title="Lokasi" data-remote="false" data-toggle="modal" data-target="#modalBesar<?= $product['id']; ?>"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/location.svg") ?>" alt=""/></a></div>
															</div>
														</div>
													</div>
												</div>
											</div>			
										<?php endforeach; ?>			
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php shuffle($array['lapak']); foreach($array['lapak'] as $index => $product) : ?>
				<?php $link = $array['mode'] == 'hp' ? 'https://api.whatsapp.com/send?phone='.$hp.'&text=Saya Mau Pesan '.$product['produk'].' '.$product['link'] : $product['link'] ?>
				<div class="modal fade" id="deskripsi<?= $product['id']; ?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
					<div class="modal-dialog">
						<div class="modal-container-big bg-white">
							<div class="topmodal bg-gradient-hor flexleft" data-dismiss="modal" aria-hidden="true">Detail Produk<div class="close-button"></div></div>
							<div class="modal-container-area">
								<div class="modalscroll">
									<div class="deskripsi-lapak bg-white">
										<div class="margin-min10">
											<div class="deskripsi-left">
												<div class="pd-lr-10">
													<h1><?=$product['produk']?></h1>
													<p><?= $product['deskripsi'] ?></p>	
													<table style="width:100%;">
														<tr>
															<td style="vertical-align:top;">Penjual</td>
															<td style="vertical-align:top;width:20px;text-align:center;">:</td>
															<td style="vertical-align:top;"><b><?=$product['penjual']?></b></td>
														</tr>
														<tr>
															<td style="vertical-align:top;">Alamat</td>
															<td style="vertical-align:top;width:20px;text-align:center;">:</td>
															<td style="vertical-align:top;"><?= $product['alamat'] ?></td>
														</tr>
														<tr>
															<td style="vertical-align:top;">Kontak</td>
															<td style="vertical-align:top;width:20px;text-align:center;">:</td>
															<td style="vertical-align:top;"><?= substr_replace($product['hp'],'0',0,2) ?></td>
														</tr>
														<tr>
															<td style="vertical-align:top;">Harga</td>
															<td style="vertical-align:top;width:20px;text-align:center;">:</td>
															<td style="vertical-align:top;">															<?php
															if ($gambar) {
																$harga_produk = number_format($product['diskon']);
																$diskon = '<s>Rp. ' . number_format($product['harga']) . ',-</s>';
															} else {
																$harga_produk = number_format($product['harga']);
															}
															?>
															<?= $diskon; ?> <b>Rp. <?= $harga_produk; ?>,-</b>
															</td>
														</tr>														
													</table>
													<div class="flexleft" style="margin-top:20px;">
														<a href="<?= $link ?>" rel='noopener noreferrer' target='_blank' title='Pesan/Beli'>
														<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/shop.svg") ?>" alt=""/>
														<h2>Hubungi Penjual</h2></a>
														</div>
													<div class="flexleft" style="margin-top:20px;">
														<a title="Lokasi" data-remote="false" data-toggle="modal" data-target="#modalBesar<?= $product['id']; ?>">
														<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/location.svg") ?>" alt=""/>
														<h2>Lokasi Penjual</h2></a>
														</div>
													</div>
												</div>
												<div class="deskripsi-right">
													<div class="pd-lr-10">
														<?php $gambar = base_url($this->theme_folder.'/'.$this->theme .'/assets/lapak/' . $product['gambar']) ?>
														<?php $youtube = 'https://www.youtube.com/embed/'.$product['gambar'] ?>
														<?php
														$allowed = array('mp4', 'webm', 'ogg');
														$filename = pathinfo($gambar);
														$ext = $filename['extension'];
														$allowed_pic = array('jpg', 'png', 'jpeg');
														$filename_pic = pathinfo($gambar);
														$ext_pic = $filename['extension'];
														if (in_array($ext, $allowed)): ?>
															<div class="col-12 p-3 text-center">
																<object class="mw-100" height="210">
																	<param name="src" value="<?= $gambar ?>">
																	<param name="autoplay" value="false">
																	<param name="controller" value="true">
																	<param name="bgcolor" value="#333333">
																	<embed type="mp4" src="<?= $gambar ?>" autostart="false" loop="false" controller="true" bgcolor="#333333"></embed>
																</object>
															</div>
														<?php elseif (in_array($ext_pic, $allowed_pic)): ?>
															<img style="display:block;border-radius:5px;width:100% !important;min-width:100% !important;height:auto;" class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $gambar ?>" alt="<?= $product['produk'] ?>" loading="lazy">
														<?php else: ?>
															<iframe class="mw-100" height="210" src="<?= $youtube ?>" frameborder="0" loading="lazy"></iframe>
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
				<style type="text/css">
					.map{height:92vh !important;}
				</style>
				<?php foreach($array['lapak'] as $index => $product) : ?>
					<div class='modal-lokasi'>
						<div class="modal fade" id="modalBesar<?= $product['id']; ?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
							<div class="modal-dialog">
								<div class="modal-container-big bg-white">
									<div class="topmodal bg-gradient-hor flexleft" data-dismiss="modal" aria-hidden="true">Lokasi Penjual
										<div class="close-button"></div>
									</div>
									<div class="modal-container-area">
										<div class="map" id="map_canvas<?= $product['id']; ?>">
											<div class="leaflet-bottom leaflet-right">
												<div id="qrcode">
													<div class="panel-body-lg">
														<a href="https://github.com/OpenSID/OpenSID">
															<img src="<?= base_url()?>assets/images/opensid.png" alt="OpenSID">
														</a>
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
			<script>
				$(document).ready(function(){
					<?php foreach($array['lapak'] as $index => $product) : ?>
						<?php $gambar = base_url($this->theme_folder.'/'.$this->theme .'/assets/lapak/' . $product['gambar']) ?>
						<?php $youtube = 'https://www.youtube.com/embed/'.$product['gambar'] ?>
						$('#modalBesar<?= $product['id']; ?>').on('shown.bs.modal', function(){
							var container = L.DomUtil.get('map<?= $product['id']; ?>'); if(container != null){ container._leaflet_id = null; }
							var map<?= $product['id']; ?> = L.map('map_canvas<?= $product['id']; ?>').setView([<?= $product['lat']; ?>,<?= $product['lng']; ?>], 15);
							var logo = L.icon({
								iconUrl: '<?= setting('icon_lapak_peta') ?>',
								iconSize:     [32, 32], // size of the icon
								iconAnchor:   [16, 16], // point of the icon which will correspond to marker's location
								popupAnchor:  [-1,1] // point from which the popup should open relative to the iconAnchor
							});
							var foto = "<img src='<?= $gambar; ?>' class='mw-100' style='border-radius:1px;-moz-border-radius:3px;-webkit-border-radius:1px;width:100%;' alt=''/>";
							var info_tempat =
							'<div id="content">'
							+ '<h6><b style="color:red"><center>' + <?=json_encode($product['produk'])?> + '</center></b></h6>'
							+ '<?php $allowed = array("mp4", "webm", "ogg"); $filename = pathinfo($gambar); $ext = $filename["extension"]; $allowed_pic = array('jpg', 'png', 'jpeg');
							$filename_pic = pathinfo($gambar);
							$ext_pic = $filename['extension']; if (in_array($ext, $allowed)): ?>'
							+ '<div class="col-12 p-3 text-center">'
							+ '    <object class="mw-100">'
							+ '      <param name="src" value="<?= $gambar; ?>">'
							+ '      <param name="autoplay" value="false">'
							+ '      <param name="controller" value="true">'
							+ '      <param name="bgcolor" value="#333333">'
							+ '      <embed type="mp4" src="<?= $gambar; ?>" autostart="false" loop="false" controller="true" bgcolor="#333333"></embed>'
							+ '    </object>'
							+ '</div>'
							+ '<?php elseif (in_array($ext_pic, $allowed_pic)): ?>'
							+ '<div id="bodyContent" class="mb-2 text-center">'+ foto
							+ '</div>'
							+ '<?php else: ?>'
							+ '<div class="col-12 p-3 text-center">'
							+ '<iframe class="mw-100" src="<?= $youtube; ?>" frameborder="no" loading="lazy"></iframe>'
							+ '</div>'
							+ '<?php endif; ?>'
							+ '<table>'
							+ '<tr>'
							+ '<td width="60px" valign="top">Produksi</td>'
							+ '<td width="10px" valign="top">:</td>'
							+ '<td><b style="color:red">' + <?=json_encode($product['produk'])?> + '</b></td>'
							+ '</tr>'
							+ '<tr>'
							+ '<td width="60px">Harga</td>'
							+ '<td width="10px">:</td>'
							+ '<td><s>Rp. ' + <?=json_encode(number_format($product['harga']))?> + ',-</s> Rp. ' + <?=json_encode(number_format($product['diskon']))?> + ',-</td>'
							+ '</tr>'
							+ '<tr>'
							+ '<td width="60px" valign="top">Alamat</td>'
							+ '<td width="10px" valign="top">:</td>'
							+ '<td>' + <?=json_encode($product['alamat'])?> + '</td>'
							+ '</tr>'
							+ '<tr>'
							+ '<td width="60px">Kontak</td>'
							+ '<td width="10px">:</td>'
							+ '<td>' + <?=json_encode(substr_replace($product['hp'],'0',0,2))?> + '</td>'
							+ '</tr>'
							+ '<tr>'
							+ '<td width="60px">Tujuan</td>'
							+ '<td width="10px">:</td>'
							+ '<td><a style="color:#fff;" class="btn btn-sm btn-danger danger-gradient mt-0" target="_blank" rel="noopener noreferrer" href="https://www.google.com/maps/dir//'+<?= $product['lat']; ?> +','+<?= $product['lng']; ?>+'/"><i class="fa fa-map-marker"></i> Arah ke Lokasi</a></td>'
							+ '</tr>'
							+ '</table>'
							+ '</div>';
							var lapakmark = L.marker([<?= $product['lat']; ?>,<?= $product['lng']; ?>],{icon:logo}).addTo(map<?= $product['id']; ?>)
							.bindPopup(info_tempat).openPopup();
							L.control.scale().addTo(map<?= $product['id']; ?>);
							var baseLayers = getBaseLayers(map<?= $product['id']; ?>, "<?= setting('mapbox_key') ?>", "<?= setting('jenis_peta') ?>");
							L.control.layers(baseLayers, null, {position: 'topright', collapsed: true}).addTo(map<?= $product['id']; ?>);
							map<?= $product['id']; ?>.invalidateSize();
						});
					<?php endforeach; ?>
				});
			</script>
		<?php endif ?>
	<?php endif ?>
<?php endif ?>
