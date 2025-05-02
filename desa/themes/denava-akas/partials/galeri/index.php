<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="article-single">
	<div class="gallerystyle">
		<div class="container-page mb-20">
			<div class="headingpage border-grey-soft bg-white flexleft" style="border-radius:5px 5px 0 0;">
				<div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/sekilas.png") ?>" alt=""/></div>
				<h2>ALBUM GALERI</h2>
			</div>
			<div class="box-article mb-30 bg-white" style="border-radius:0 0 5px 5px;">
				<div class="relative-border p-15 border-grey-soft" style="border-radius:0 0 5px 5px;">
					<?php if(count($gallery ?? [])) : ?>
						<div class="subfoto">
							<div class="margin-min10">
								<?php $i = 1; ?>
								<?php foreach($gallery AS $data): ?>
									<a href="<?= site_url("galeri/{$data['id']}") ?>">
										<div class="subfoto-inner">
											<div class="pd-lr-10">
												<div class="framephoto">
													<div class="inner-frame">
														<div class="mat">
															<div class="picture" style="background-image:url('<?= is_file(LOKASI_GALERI."sedang_{$data['gambar']}") == TRUE ? AmbilGaleri($data['gambar'],'sedang') : base_url("$this->theme_folder/$this->theme/assets/images/album.jpg") ?>');"></div>
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
						</div>
						<?php $this->load->view($folder_themes . "/commons/paging"); ?>
					<?php else: ?>
						<div class="no-found">
							<div class="margin-min10 flexcenter">
								<div class="no-found-title">
									<div class="pd-lr-10">
										<h2>404! ALBUM GALERI BELUM TERSEDIA</h2>
										<h3>Maaf, belum tersedia album galeri pada halaman ini.</h3>
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
