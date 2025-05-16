<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if ($w_cos): ?>
	<div class="relative-row ptb-10">
		<div class="container-page">
			<div class="forwidget">
				<div class="margin-min5">
					<div class="carouselcustom js-flickity" data-flickity='{ "autoPlay": false, "cellAlign": "left", "groupCells": true, "groupCells": 1, "wrapAround": true }'>
						<?php foreach ($w_cos as $widget): ?>
							<?php
							$judul_widget = [
								'judul_widget' => str_replace('Desa', ucwords($this->setting->sebutan_desa), strip_tags($widget['judul']))
							];
							?>
							<?php if ($widget["jenis_widget"] == 1): ?>
								<?php if ($widget["isi"] == 'statistik.php' || $widget["isi"] == 'agenda.php' || $widget["isi"] == 'komentar.php' || $widget["isi"] == 'arsip_artikel.php' || $widget["isi"] == 'sinergi_program.php' || $widget["isi"] == 'menu_kategori.php' || $widget["isi"] == 'statistik_pengunjung.php' || $widget["isi"] == 'jam_kerja.php'): ?>	
									<div class="carouselcustom-item">
										<div class="mlr-5">
											<div class="widgetspace">
												<?php $this->load->view("{$folder_themes}/widgets/{$widget['isi']}", $judul_widget) ?>
											</div>
										</div>
									</div>
								<?php endif; ?>
							<?php elseif($widget['jenis_widget'] == 2) : ?>
								<div class="carouselcustom-item">
									<div class="mlr-5">
										<div class="widgetspace">
											<?php $this->load->view("../../{$widget['isi']}", $judul_widget) ?>
										</div>
									</div>
								</div>
							<?php else : ?>
								<?php if (!in_array(strtoupper(strip_tags($widget['judul'])), [strtoupper('event'),strtoupper('logo')])): ?>
								<div class="carouselcustom-item">
									<div class="mlr-5">
										<div class="widgetspace">
											<div class="head-widget flexleft bg-grey-dark2">
												<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/arsip.svg") ?>" alt=""/>
												<?= strip_tags($widget['judul']) ?>
											</div>
											<div class="widget-height bg-white border-grey-soft">
												<div class="widgetscroll">
													<div class="p-10">
														<img class="img-responsive mb-2 mw-100" src="<?= to_base64(LOKASI_GAMBAR_WIDGET . $widget['foto']); ?>" alt="">
														<?= htmlspecialchars_decode(html_entity_decode($widget['isi']), ENT_QUOTES) ?>
													</div>
													<div class="gradient-white-bottom"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php endif; ?>	
							<?php endif; ?>	
						<?php endforeach ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
