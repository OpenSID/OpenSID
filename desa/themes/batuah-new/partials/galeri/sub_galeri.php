<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php $this->load->view("$folder_themes/plus/header_side"); ?>
<div class="mainpage">
	<div class="margin-side-10">
	<div class="mainbodyarea">
		<div class="bigarea hiddenrelative">
		<div class="bigarea-margin">
			<div class="col-default margin-top-10">
				<div class="bodypage bgwhite bordergrey">
					<div class="headgaleri bgcolor-2">
						<div class="rowsame">
							<div class="headgaleri-title flexcenter">
								<div>
									<p>Galeri Foto</p>
									<h3><?= $parent['nama']; ?></h3>
								</div>
							</div>
							<div class="headgaleri-image">
								<?php if (is_file(LOKASI_GALERI . "sedang_{$parent['gambar']}")) : ?>
									<img src="<?= AmbilGaleri($parent['gambar'], 'sedang') ?>">
								<?php else : ?>
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/kamera.jpg") ?>" />
								<?php endif; ?>
								<div class="headgaleri-cover"></div>
								<?php if (is_file(LOKASI_GALERI . "sedang_{$parent['gambar']}")) : ?>
									<a data-fancybox="gallery" href="<?= AmbilGaleri($parent['gambar'], 'sedang') ?>">
										<div class="headgaleri-link flexcenter">
											<svg viewBox="0 0 24 24">
												<path d="M9.5,13.09L10.91,14.5L6.41,19H10V21H3V14H5V17.59L9.5,13.09M10.91,9.5L9.5,10.91L5,6.41V10H3V3H10V5H6.41L10.91,9.5M14.5,13.09L19,17.59V14H21V21H14V19H17.59L13.09,14.5L14.5,13.09M13.09,9.5L17.59,5H14V3H21V10H19V6.41L14.5,10.91L13.09,9.5Z" />
											</svg>
										</div>
									</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="pagebox">
						<?php if ($gallery): ?>
							<div class="rowsame margin-minlr-5">
								<?php foreach ($gallery as $data): ?>
									<div class="galeri-box bordergrey1 forhover">
							<div class="image-galeri">
								<?php if (is_file(LOKASI_GALERI . "kecil_" . $data['gambar'])) : ?>
									<img src="<?= AmbilGaleri($data['gambar'], 'kecil') ?>" alt="Album : <?= "$data[nama]" ?>">
								<?php else : ?>
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pengganti.jpg") ?>" />
									<div class="logo-no-image"><img src="<?= gambar_desa($desa['logo']); ?>" /></div>
								<?php endif; ?>
								<?php if (is_file(LOKASI_GALERI . "kecil_" . $data['gambar'])) : ?>
									<div class="hoverimage flexcenter">
										<div class="hoverimage-left bgcolor-1 hover-width"></div>
										<div class="hoverimage-right bgcolor-1 hover-width"></div>
										<div>
											<a href="<?= AmbilGaleri($data['gambar'], 'sedang') ?>" data-fancybox="images" data-caption="<?= ($data['nama']); ?>">
												<div class="hoverimage-icon flexcenter hover-height">
													<svg viewBox="0 0 24 24">
														<path d="M9.5,13.09L10.91,14.5L6.41,19H10V21H3V14H5V17.59L9.5,13.09M10.91,9.5L9.5,10.91L5,6.41V10H3V3H10V5H6.41L10.91,9.5M14.5,13.09L19,17.59V14H21V21H14V19H17.59L13.09,14.5L14.5,13.09M13.09,9.5L17.59,5H14V3H21V10H19V6.41L14.5,10.91L13.09,9.5Z" />
													</svg>
												</div>
											</a>
										</div>
									</div>
								<?php endif; ?>
							</div>
							<h2><?= $data["nama"] ?></h2>
						</div>
								<?php endforeach; ?>
							</div>

							<?php $this->load->view("$folder_themes/commons/page"); ?>

						<?php else : ?>
							<div class="blankdata">
								<img style="width:100%;height:auto;display:block;border-radius:5px;" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nature.jpg") ?>" />
								<div class="blankdata-title">
									<h2>Mohon maaf,</h2>
									<h3>Data Album Galeri Foto belum tersedia untuk saat ini...</h3>
								</div>
								<div class="blankdata-bottom">
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/girl.png") ?>" />
								</div>
							</div>
						<?php endif; ?>
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