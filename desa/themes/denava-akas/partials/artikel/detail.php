<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="article-single">
	<div class="container-page mb-20">
		<div class="relative">
			<div class="headingpage border-grey-soft bg-white flexleft" style="border-radius:5px 5px 0 0;">
				<div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/arsip.svg") ?>" alt=""/></div>
				<h2><?= (IS_PREMIUM || (!IS_PREMIUM && IS_240810) ? $single_artikel['tipe'] == 'agenda' : $single_artikel['id_kategori'] == 1000) ? 'Agenda' : 'Berita' ?> <?= ucwords($this->setting->sebutan_desa); ?></h2>
			</div>
          	<div class="big-screen">
				<div class="righthead flexright">
					<?php $this->load->view($folder_themes . "/partials/artikel/button"); ?>
				</div>
			</div>
		</div>
		<div class="box-article mb-30 bg-white" style="border-radius:0 0 5px 5px;">
			<div class="relative-border p-15 border-grey-soft" style="border-radius:0 0 5px 5px;">
				<?php if ($single_artikel["id"]) : ?>
					<nav class="main-nav stick-fixed">
						<div class="full-wrapper relative clearfix">
							<div class="head-content"><h1 class="transition-height"><?= htmlspecialchars_decode($single_artikel["judul"]) ?></h1></div>
						</div>
					</nav>
					<div class="gridview">
						<div class="sidebarright">
							<img style="width:100%;height:1px;display:block;" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/empty-pic.png") ?>" alt=""/>
							<ul>
								<div class="article-info">
									<?php if (trim($single_artikel['kategori']) != '') : ?>
										<a href="<?= site_url('artikel/kategori/'.$single_artikel['id_kategori'])?>">
											<div class="category-content color-1 flexleft">
												<div class="category-content-image bg-grey-dark flexcenter">
													<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/list.svg") ?>" alt=""/>
												</div>
												<b><?= $single_artikel['kategori']?></b>
											</div>
										</a>
									<?php endif; ?>
									<div class="article-info-inner flexleft"><i class="fa fa-calendar flexcenter"></i><p><?= tgl_indo2($single_artikel['tgl_upload']);?></p></div>
									<div class="article-info-inner flexleft"><i class="fa fa-heart flexcenter"></i><p><?= hit($single_artikel['hit']) ?></p></div>
									<div class="article-info-inner flexleft"><i class="fa fa-user flexcenter"></i><p><?= $single_artikel['owner'] ?></p></div>
                                  	<div class="fb-like" data-href="<?= site_url('artikel/'.buat_slug($single_artikel))?>" data-width="" data-layout="button_count" data-action="like" data-size="small" data-share="true"></div>				
									<div class="big-screen">
										<nav class="main-nav stick-fixed">
											<div class="full-wrapper relative clearfix">
												<div class="hide-judul transition-height"><?= htmlspecialchars_decode($single_artikel["judul"]) ?></div>
											</div>
										</nav>
									</div>
									<div class="desktop-view">
										<div class="share-top">
											<div class="flexleft">
												<?php
												$share = ['link' => site_url('artikel/' . buat_slug($single_artikel)), 'judul' => $single_artikel["judul"], ];
												$this->load->view($folder_themes . "/commons/share", $share);
												?>
											</div>
										</div>
									</div>
								</div>
							</ul>
						</div>
						<div class="">
							<div style="width:100% !important;max-width:100% !important;position:relative;overflow:hidden;"><?php $this->load->view($folder_themes . "/partials/artikel/isi"); ?></div>	
						</div>
					</div>
				<?php else: ?>
					<div class="no-found">
						<div class="margin-min10 flexcenter">
							<div class="no-found-title">
								<div class="pd-lr-10">
									<h2>404! ARTIKEL TIDAK DITEMUKAN</h2>
									<h3>Anda telah terdampar di halaman yang datanya tidak ada lagi di web ini.<br>Mohon periksa kembali, atau laporkan kepada kami</h3>
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
