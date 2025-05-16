<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="article-single">
	<div class="gallerystyle">
		<div class="container-page">
			<div class="box-article bg-white mb-20" style="border-radius:5px;">
				<div class="relative-border p-15 border-grey-soft" style="border-radius:5px;">
					<div class="album-head">
						<div class="album-head-image">
							<?php if(is_file(LOKASI_GALERI."sedang_{$parent['gambar']}")) : ?>
								<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= AmbilGaleri($parent['gambar'], 'sedang') ?>" alt="">
							<?php else: ?>
								<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $latar_website ? $latar_website : base_url('assets/front/css/images/latar_website.jpg'); ?>" alt="">
							<?php endif;?>
						</div>
						<div class="album-head-title">
							<?php if(is_file(LOKASI_GALERI."sedang_{$parent['gambar']}")) : ?>
								<div class="flexcenter">
									<a data-fancybox="gallery" href="<?= AmbilGaleri($parent['gambar'], 'sedang') ?>">
										<div class="album-headtitle-image flexcenter">
											<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/fullscreen.svg") ?>" alt=""/>
										</div>
									</a>
								</div>
							<?php endif;?>
							<h2>Galeri Foto</h2>
							<h1><?= $parent['nama']; ?></h1>
						</div>
					</div>
					<div class="subfoto">
						<?php if(count($gallery ?? [])) : ?>
							<div class="margin-min10">
								<?php $i = 1 ?>
								<?php $jumlah = 0; ?>
								<?php foreach ($gallery as $data) : ?>
									<a href="<?= AmbilGaleri($data['gambar'],'sedang') ?>" data-fancybox="images" data-caption="<?= ($data['nama']); ?>">
										<div class="subfoto-inner">
											<div class="pd-lr-10">
												<div class="framephoto">
													<div class="inner-frame">
														<div class="mat">
															<?php if (file_exists(LOKASI_GALERI . "sedang_" . $data['gambar']) || $data['jenis'] == 2): ?>
																<?php
																$gambar = $data['jenis'] == 2 ? $data['gambar'] : AmbilGaleri($data['gambar'], 'kecil'); 
																$jumlah++;
																?>
																<div class="picture" style="background-image:url('<?= $gambar ?>');"></div>
															<?php else: ?>
																<div class="picture" style="background-image:url('<?= base_url("$this->theme_folder/$this->theme/assets/images/album.jpg") ?>');"></div>
															<?php endif;?>
															<div class="bottom-gradient"></div>
															<div class="title-foto">
																<div class="big-screen"><h3><?= $data["nama"] ?></h3></div>
																<div class="small-screen"><h3><?= potong_teks($data['nama'], 60); ?>...</h3></div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</a>
								<?php endforeach ?>
							</div>
							<?php $this->load->view($folder_themes . "/commons/paging"); ?>
						<?php else: ?>
							<div class="no-photo mb-20 flexcenter">
								<img class="big-screen" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nofdata1.png") ?>"/>
								<p style="margin:0 10px;">Untuk sementara<br/>foto album <b><?= $parent['nama']; ?></b> belum tersedia.</p>
								<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nofoto.png") ?>" alt=""/>
							</div>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
