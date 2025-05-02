<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$title = (!empty($judul_kategori))? $judul_kategori: 'Artikel Terkini';
$slug = 'terkini';
if(is_array($title)){
	$slug = $title['slug'];
	$title = $title['kategori'];
}
?>
<div class="articlehome">
	<div class="relative-row ptb-10">
		<div class="container-page">
			<div class="articlehome-inner bg-gradient-hor pd-lr-20 p-tb-10">
				<div class="articlehome-head bg-gradient-black flexleft">
					<div class="big-screen"><h1><?= htmlspecialchars_decode(htmlspecialchars_decode(htmlspecialchars_decode($title))) ?></h1></div>
					<div class="small-screen"><h1>Berita</h1></div>
				</div>
				<div class="headmodule-right flexright">
					<div class="headmodule-right-inner flexcenter" onclick="openkategori()">
						<svg viewBox="0 0 28 28">
							<path d="M8 7.29805C8 6.85622 8.35817 6.49805 8.8 6.49805H25.2C25.6418 6.49805 26 6.85622 26 7.29805V7.69805C26 8.13987 25.6418 8.49805 25.2 8.49805H8.8C8.35817 8.49805 8 8.13987 8 7.69805V7.29805Z"/><path d="M8 21.298C8 20.8562 8.35817 20.498 8.8 20.498H25.2C25.6418 20.498 26 20.8562 26 21.298V21.698C26 22.1399 25.6418 22.498 25.2 22.498H8.8C8.35817 22.498 8 22.1399 8 21.698V21.298Z"/><path d="M8 14.298C8 13.8562 8.35817 13.498 8.8 13.498H25.2C25.6418 13.498 26 13.8562 26 14.298V14.698C26 15.1399 25.6418 15.498 25.2 15.498H8.8C8.35817 15.498 8 15.1399 8 14.698V14.298Z"/><path d="M5 7.5C5 8.32843 4.32843 9 3.5 9C2.67157 9 2 8.32843 2 7.5C2 6.67157 2.67157 6 3.5 6C4.32843 6 5 6.67157 5 7.5Z"/><path d="M5 21.498C5 22.3265 4.32843 22.998 3.5 22.998C2.67157 22.998 2 22.3265 2 21.498C2 20.6696 2.67157 19.998 3.5 19.998C4.32843 19.998 5 20.6696 5 21.498Z"/><path d="M5 14.498C5 15.3265 4.32843 15.998 3.5 15.998C2.67157 15.998 2 15.3265 2 14.498C2 13.6696 2.67157 12.998 3.5 12.998C4.32843 12.998 5 13.6696 5 14.498Z"/>
						</svg>
						<p>Kategori</p>
					</div>
					<a href="<?= site_url('arsip') ?>">
						<div class="headmodule-right-inner flexcenter" style="margin:0 3px 0 3px;">
							<svg viewBox="0 0 32 32">
								<path d="M4,28a3,3,0,0,1-3-3V7A3,3,0,0,1,4,4h7a1,1,0,0,1,.77.36L14.8,8H27a1,1,0,0,1,0,2H14.33a1,1,0,0,1-.76-.36L10.53,6H4A1,1,0,0,0,3,7V25a1,1,0,0,0,1,1,1,1,0,0,1,0,2Z M25.38,28H4a1,1,0,0,1-1-1.21l3-14A1,1,0,0,1,7,12H30a1,1,0,0,1,1,1.21L28.32,25.63A3,3,0,0,1,25.38,28ZM5.24,26H25.38a1,1,0,0,0,1-.79L28.76,14h-21Z"/>
							</svg>
							<p>Arsip</p>
						</div>
					</a>
				</div>	
				<div id="kategori" class="panel-small">
					<div class="panel-small-box bg-grey-medium">
						<div class="head-module-center border-grey-soft flexleft" style="margin-bottom:20px;">
							<h2>Kategori Artikel</h2>
						</div>
						<div class="withscroll">
							<ul id="ul-menu">
								<?php foreach($menu_kiri as $data):?>
									<li>
										<div class="single-link"><a href="<?= site_url("artikel/kategori/$data[slug]"); ?>"><?= $data['kategori']; ?><?php (count($data['submenu'] ?? []) > 0) and print('<span class="caret"></span>'); ?></a></div>
										<?php if(count($data['submenu'] ?? []) > 0): ?>
											<ul>
												<?php foreach($data['submenu'] as $submenu):?>
													<div class="subsingle-link"><a href="<?= site_url("artikel/kategori/$submenu[slug]"); ?>"><?= $submenu['kategori']?></a></div>
												<?php endforeach; ?>
											</ul>
										<?php endif; ?>
									</li>
								<?php endforeach;?>
							</ul>
						</div>
					</div>
					<a href="javascript:void(0)" onclick="closekategori()">
						<div class="close-area flexcenter">
							<div class="close-button bg-grey-dark2"></div>
						</div>
					</a>
				</div>
				<?php if ($artikel): ?>
					<div class="article-inner">
						<div class="flexrow">
							<?php foreach ($artikel as $data): ?>
								<div class="flex-column4 bg-grey-medium">
									<div class="latest-article relative-hid">
										<?php if (is_file(LOKASI_FOTO_ARTIKEL."sedang_".$data['gambar'])): ?>
											<div class="latest-image">
												<div class="carouselcustom js-flickity" data-flickity='{ "autoPlay": false, "groupCells": true, "groupCells": 1, "wrapAround": true, "cellAlign": "left" }'>
													<?php if (is_file(LOKASI_FOTO_ARTIKEL."sedang_".$data['gambar'])): ?>
														<div class="carouselcustom-item">
															<div class="image-article" style="border-radius:0;">
																<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= AmbilFotoArtikel($data['gambar'],'sedang') ?>" alt="">
																<a href="<?= AmbilFotoArtikel($data['gambar'],'sedang') ?>"  data-fancybox="images" data-caption="<?= $data["judul"] ?>"><div class="image-zoom"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/fullscreen.svg") ?>" alt=""/></div></a>
															</div>
														</div>
													<?php endif; ?>
													<?php if (is_file(LOKASI_FOTO_ARTIKEL."sedang_".$data['gambar1'])): ?>
														<div class="carouselcustom-item">
															<div class="image-article" style="border-radius:0;">
																<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= AmbilFotoArtikel($data['gambar1'],'sedang') ?>" alt="">
																<a href="<?= AmbilFotoArtikel($data['gambar1'],'sedang') ?>"  data-fancybox="images" data-caption="<?= $data["judul"] ?>"><div class="image-zoom"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/fullscreen.svg") ?>" alt=""/></div></a>
															</div>
														</div>
													<?php endif; ?>
													<?php if (is_file(LOKASI_FOTO_ARTIKEL."sedang_".$data['gambar2'])): ?>
														<div class="carouselcustom-item">
															<div class="image-article" style="border-radius:0;">
																<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= AmbilFotoArtikel($data['gambar2'],'sedang') ?>" alt="">
																<a href="<?= AmbilFotoArtikel($data['gambar2'],'sedang') ?>"  data-fancybox="images" data-caption="<?= $data["judul"] ?>"><div class="image-zoom"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/fullscreen.svg") ?>" alt=""/></div></a>
															</div>
														</div>
													<?php endif; ?>
													<?php if (is_file(LOKASI_FOTO_ARTIKEL."sedang_".$data['gambar3'])): ?>
														<div class="carouselcustom-item">
															<div class="image-article" style="border-radius:0;">
																<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= AmbilFotoArtikel($data['gambar3'],'sedang') ?>" alt="">
																<a href="<?= AmbilFotoArtikel($data['gambar3'],'sedang') ?>"  data-fancybox="images" data-caption="<?= $data["judul"] ?>"><div class="image-zoom"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/fullscreen.svg") ?>" alt=""/></div></a>
															</div>
														</div>
													<?php endif; ?>
												</div>	
											</div>
										<?php else: ?>	
											<div class="latest-image">
												<div class="image-article" style="border-radius:0;">
													<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= gambar_desa($desa['kantor_desa'], TRUE)?>" alt=""/>
													<div class="logo-no-image"><img src="<?= gambar_desa($desa['logo']);?>" alt=""/></div>
												</div>
											</div>
										<?php endif; ?>
										<div class="latest-date"><?= tgl_indo($data['tgl_upload']);?> <i class="fa fa-heart"></i> <?= hit($data['hit']) ?></div>
										<a title="Selengkapnya" href="<?= site_url('artikel/'.buat_slug($data))?>"><h2><?= htmlspecialchars_decode($data["judul"]) ?></h2></a>
									</div>
									<div class="latest-box-bottom bg-white border-grey-soft flexcenter" style="border-radius:0 0 5px 5px;">
										<div class="flexcenter">
											<div class="latest-info flexcenter border-grey-soft small">
												<a title="Detail" data-remote="true" data-toggle="modal" data-target="#openartikel<?= $data['id']; ?>">
													<div class="article-link flexleft">
														<svg viewBox="0 0 128 128">
															<path d="M109,55c0-29.8-24.2-54-54-54C25.2,1,1,25.2,1,55s24.2,54,54,54c13.5,0,25.8-5,35.2-13.1l25.4,25.4l5.7-5.7L95.9,90.2   C104,80.8,109,68.5,109,55z M55,101C29.6,101,9,80.4,9,55S29.6,9,55,9s46,20.6,46,46S80.4,101,55,101z"/><path d="M25.6,30.9l6.2,5.1C37.5,29,46,25,55,25v-8C43.6,17,32.9,22.1,25.6,30.9z"/><path d="M17,55h8c0-2.1,0.2-4.1,0.6-6.1l-7.8-1.6C17.3,49.8,17,52.4,17,55z"/>
														</svg>
														Baca
													</div>
												</a>
											</div>
											<div class="latest-info flexcenter" style="border:none;">
												<a title="Selengkapnya" href="<?= site_url('artikel/'.buat_slug($data))?>">
													<div class="article-link flexleft">
														<svg viewBox="0 0 128 128">
															<polygon points="37,9 119,9 119,119 37,119 37,127 127,127 127,1 37,1  "/><rect height="8" width="8" x="1" y="60"/><polygon points="46.2,91.2 51.8,96.8 84.7,64 51.8,31.2 46.2,36.8 69.3,60 19,60 19,68 69.3,68  "/>
														</svg>
														Selengkapnya
													</div>
												</a>
											</div>
										</div>
									</div>	
								</div>
								<div class="modal fade" id="openartikel<?= $data['id']; ?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="true">
									<div class="modal-dialog" style="margin:0 !important;">
										<div class="modal-container-big">
											<div class="modal-article">
												<div class="topmodal bg-grey-dark2 flexleft" data-dismiss="modal" aria-hidden="true">Detail Artikel<div class="close-button"></div></div>
												<div class="modal-article-inner">
													<div class="modalscroll">
														<div class="relative-hid2 modal-article-content bgwhite">
															<h2><?= htmlspecialchars_decode($data["judul"]) ?></h2>
															<div class="article-info flexleft">
																<div class="article-info-inner flexleft"><i class="fa fa-calendar flexleft"></i><p><?= tgl_indo2($data['tgl_upload']);?></p></div>
																<div class="article-info-inner flexleft"><i class="fa fa-heart flexleft"></i><p><?= hit($data['hit']) ?></p></div>
																<div class="article-info-inner flexleft"><i class="fa fa-user flexleft"></i><p><?= $data['owner'] ?></p></div>
															</div>
															<?php if (is_file(LOKASI_FOTO_ARTIKEL."sedang_".$data['gambar'])): ?>
																<div class="image-small"><img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= AmbilFotoArtikel($data['gambar'],'sedang') ?>" alt=""></div>
															<?php endif; ?>
															<?php if ($data['isi']): ?>
																<?= potong_teks ($data['isi'], 300); ?>...
															<?php endif; ?>
															<a href="<?= site_url('artikel/'.buat_slug($data))?>">
																<div class="article-link flexleft" style="margin:15px 0;">
																	<svg viewBox="0 0 128 128">
																		<polygon points="37,9 119,9 119,119 37,119 37,127 127,127 127,1 37,1  "/><rect height="8" width="8" x="1" y="60"/><polygon points="46.2,91.2 51.8,96.8 84.7,64 51.8,31.2 46.2,36.8 69.3,60 19,60 19,68 69.3,68  "/>
																	</svg>
																	<b>Selengkapnya</b>
																</div>
															</a>
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
					<?php $this->load->view($folder_themes . "/commons/paging", $data); ?>
				<?php else: ?>
					<div class="no-found">
						<div class="margin-min10 flexcenter">
							<div class="no-found-title">
								<div class="pd-lr-10">
									<h2>404! ARTIKEL BELUM TERSEDIA</h2>
									<h3>Maaf, Artikel belum tersedia atau sedang dalam perbaikan.</h3>
								</div>
							</div>
							<div class="no-found-image">
								<div class="pd-lr-10">
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nodata.png") ?>" alt=""/>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>	
		</div>
	</div>
</div>
<script>
	function openkategori() {
		document.getElementById("kategori").style.height = "100%";
	}
	function closekategori() {
		document.getElementById("kategori").style.height = "0";
	}
</script>
