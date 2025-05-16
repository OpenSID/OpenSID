<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php $this->load->view("$folder_themes/plus/header_side"); ?>
<div class="mainpage">
	<div class="margin-side-10">
	<div class="mainbodyarea">
		<div class="bigarea hiddenrelative">
		<div class="bigarea-margin">
			<div class="col-default margin-top-10">
				<div class="bodypage bgwhite bordergrey">
					<div class="center-head bggrad-color2 flexcenter">
						<div class="icon-titlepage bgcolor-1 flexcenter"><svg viewBox="0 0 24 24">
								<path d="M19,20H4C2.89,20 2,19.1 2,18V6C2,4.89 2.89,4 4,4H10L12,6H19A2,2 0 0,1 21,8H21L4,8V18L6.14,10H23.21L20.93,18.5C20.7,19.37 19.92,20 19,20Z" />
							</svg></div>
						<h1>Arsip</h1>
					</div>
					<div class="pagebox gridstyle artikelpage">
						<?php if (count($farsip ?? []) > 0) : ?>
							<div class="rowsame margin-minlr-5">
								<?php foreach ($farsip as $data) : ?>
									<div class="articlerow-box bggrad-grey1 bordergrey forhover">
										<div class="artikelhome-image">
											<div class="image-artikelhome">
												<?php if (is_file(LOKASI_FOTO_ARTIKEL . "kecil_" . $data['gambar'])) : ?>
													<img src="<?= AmbilFotoArtikel($data['gambar'], 'kecil') ?>">
												<?php else : ?>
													<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pengganti.jpg") ?>" />
													<div class="logo-no-image"><img src="<?= gambar_desa($desa['logo']); ?>" /></div>
												<?php endif; ?>
												<div class="hiderow artikel-date flexleft">
													<div class="metadate bgorange flexcenter"><?php echo date('d', strtotime($data['tgl_upload'])); ?></div>
													<div class="metanext flexleft"><?php echo date('M Y', strtotime($data['tgl_upload'])); ?></div>
												</div>
												<div class="hoverimage flexcenter">
													<div class="hoverimage-left bgcolor-1 hover-width"></div>
													<div class="hoverimage-right bgcolor-1 hover-width"></div>
													<div>
														<?php if (is_file(LOKASI_FOTO_ARTIKEL . "kecil_" . $data['gambar'])) : ?>
															<a href="<?= AmbilFotoArtikel($data['gambar'], 'kecil') ?>" data-fancybox="images" data-caption="<?= $data["judul"] ?>">
																<div class="hoverimage-icon flexcenter hover-height">
																	<svg viewBox="0 0 24 24">
																		<path d="M9.5,13.09L10.91,14.5L6.41,19H10V21H3V14H5V17.59L9.5,13.09M10.91,9.5L9.5,10.91L5,6.41V10H3V3H10V5H6.41L10.91,9.5M14.5,13.09L19,17.59V14H21V21H14V19H17.59L13.09,14.5L14.5,13.09M13.09,9.5L17.59,5H14V3H21V10H19V6.41L14.5,10.91L13.09,9.5Z" />
																	</svg>
																</div>
															</a>
														<?php endif; ?>
														<a href="<?= site_url('artikel/' . buat_slug($data)) ?>">
															<div class="hoverimage-icon flexcenter hover-height">
																<svg viewBox="0 0 24 24">
																	<path d="M20,18H22V6H20M11.59,7.41L15.17,11H1V13H15.17L11.59,16.58L13,18L19,12L13,6L11.59,7.41Z" />
																</svg>
															</div>
														</a>
													</div>
												</div>
											</div>
										</div>
										<div class="artikelhome-text">
											<a href="<?= site_url('artikel/' . buat_slug($data)) ?>">
												<h3><?= $data["judul"] ?></h3>
											</a>
											<div class="desktop-only">
												<div class="artikelhome-info flexleft">
													<div class="artikelmeta flexleft"><i class="fa fa-user flexleft"></i>
														<p>Admin : <?= $data['owner'] ?></p>
													</div>
												</div>
											</div>
											<div class="artikelhome-info flexleft">
												<div class="artikelmeta flexleft"><i class="fa fa-eye flexleft"></i>
													<p><?= hit($data['hit']) ?> dibuka</p>
												</div>
												<div class="artikelmeta flexleft"><i class="fa fa-comments flexleft"></i>
													<p><?php $baca_komentar = $this->db->query("SELECT * FROM komentar WHERE id_artikel = '" . $data['id'] . "'");
														$komentarku = $baca_komentar->num_rows();
														echo number_format($komentarku, 0, ',', '.'); ?> Komentar</p>
												</div>
											</div>
										</div>
										<div class="artikelhome-link bgwhite bordergrey">
											<div class="rowsame">
												<div class="artikelhome-link-col" style="width:100%;">
													<a href="<?= site_url('artikel/' . buat_slug($data)) ?>">
														<div class="artikelhome-link-item2 flexcenter">
															<i class="fa fa-external-link flexleft"></i>
															<p>Buka Halaman</p>
														</div>
													</a>
												</div>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
							<?php
							$data['paging_page'] = 'first/arsip';
							$this->load->view("$folder_themes/commons/page", $data);
							?>
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