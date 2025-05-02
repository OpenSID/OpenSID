<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="arsipstyle">
	<div class="relative-row">
		<div class="container-page mb-20">
			<div class="box-page border-grey-soft bg-white">
				<div class="relative">
					<div class="headingpage border-grey-soft flexleft">
						<div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/arsip.svg") ?>" alt=""/></div>
						<h2>Arsip Artikel</h2>
					</div>
                  	<div class="big-screen">
						<div class="righthead flexright">
							<?php $this->load->view($folder_themes . "/partials/artikel/button"); ?>
						</div>
					</div>
				</div>	
				<div class="box-page-padding">
					<div class="article-inner">
						<?php if(count($farsip ?? []) > 0): ?>
							<div class="flexrow" style="padding:0 5px;">
								<?php foreach($farsip AS $data): ?>
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
														<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $latar_website ? $latar_website : base_url('assets/front/css/images/latar_website.jpg'); ?>" alt=""/>
														<div class="logo-no-image"><img src="<?= gambar_desa($desa['logo']);?>" alt=""/></div>
													</div>
												</div>
											<?php endif; ?>
											<div class="latest-date"><?= tgl_indo2($data['tgl_upload']);?> <i class="fa fa-heart"></i> <?= hit($data['hit']) ?></div>
											<a title="Selengkapnya" href="<?= site_url('artikel/'.buat_slug($data))?>"><h2><?= $data["judul"] ?></h2></a>
										</div>
										<div class="latest-box-bottom bg-white border-grey-soft flexcenter" style="border-radius:0 0 5px 5px;">
										<div class="flexcenter">
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
								<?php endforeach; ?>
							</div>
							<?php
							$data['paging_page'] = 'arsip';
							$this->load->view($folder_themes . "/commons/paging", $data);
							?>
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
	</div>
</div>
