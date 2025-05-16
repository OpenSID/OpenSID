<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if ($w_cos): ?>
	<?php foreach ($w_cos as $data): ?>
	<?php $widget = trim($data['isi']) ?>
	<?php if ($data["jenis_widget"] == 1): ?>
	<?php if ($data["isi"] == "statistik.php"): ?>
	<div class="stat-container statistikstyle">
		<div class="relative-row ptb-10">
			<div class="container-page">
				<div class="box-container bg-grey-radial with-border2 border-grey-soft p-tb-20" style="border-radius:5px;">
					<div class="statistik">
						<div class="statistik-left1-backg flexcenter">
							<svg viewBox="0 0 514.000000 300.000000">
								<g transform="translate(0.000000,300.000000) scale(0.100000,-0.100000)">
									<path d="M1070 2995 c0 -2 268 -334 596 -737 328 -403 601 -738 606 -745 7 -8 -168 -230 -594 -753 -332 -407 -604 -745 -606 -750 -2 -6 101 -10 285 -10 l288 0 595 730 c327 402 601 741 609 753 14 23 -6 49 -594 770 l-608 747 -288 0 c-159 0 -289 -2 -289 -5z M2348 2733 c120 -148 388 -477 596 -733 208 -256 385 -472 393 -481 12 -15 -53 -98 -592 -760 -333 -409 -605 -747 -605 -751 0 -4 400 -8 889 -8 l888 0 605 742 c332 409 607 748 610 753 3 6 -267 345 -601 755 l-606 745 -898 3 -897 2 218 -267z"/>
								</g>
							</svg>
						</div>
						<div class="flexcenter">	
							<div class="statistik-left1">
								<div class="statistik-title">
									<svg viewBox="0 0 889.000000 221.000000">
										<g transform="translate(0.000000,221.000000) scale(0.100000,-0.100000)">
											<path d="M373 2189 c-151 -29 -274 -138 -323 -283 -43 -131 -43 -327 1 -458 45 -136 149 -270 355 -457 209 -190 264 -287 264 -466 -1 -130 -43 -194 -141 -210 -46 -8 -117 10 -148 38 -39 36 -53 84 -59 205 l-5 112 -153 0 -154 0 0 -112 c0 -195 39 -316 131 -413 60 -64 126 -99 224 -120 247 -52 478 34 573 214 77 144 90 386 31 565 -43 128 -88 188 -319 416 -197 195 -220 222 -255 292 -80 163 -54 335 58 377 42 16 97 13 145 -9 59 -27 82 -80 89 -206 l6 -94 155 0 155 0 -6 123 c-8 180 -47 288 -136 377 -52 52 -129 92 -209 109 -60 13 -216 13 -279 0z M5263 2189 c-151 -29 -274 -138 -323 -283 -43 -131 -43 -327 1 -458 45 -136 149 -270 355 -457 209 -190 264 -287 264 -466 -1 -130 -43 -194 -141 -210 -46 -8 -117 10 -148 38 -39 36 -53 84 -59 205 l-5 112 -153 0 -154 0 0 -112 c0 -195 39 -316 131 -413 60 -64 126 -99 224 -120 247 -52 478 34 573 214 77 144 90 386 31 565 -43 128 -88 188 -319 416 -197 195 -220 222 -255 292 -80 163 -54 335 58 377 42 16 97 13 145 -9 59 -27 82 -80 89 -206 l6 -94 155 0 155 0 -6 123 c-8 180 -47 288 -136 377 -52 52 -129 92 -209 109 -60 13 -216 13 -279 0z M1120 2020 l0 -150 175 0 175 0 0 -915 0 -915 165 0 165 0 0 915 0 915 175 0 175 0 0 150 0 150 -515 0 -515 0 0 -150z M2266 1133 c-93 -571 -171 -1050 -173 -1065 l-5 -28 154 0 155 0 27 192 c15 106 30 201 33 211 4 15 22 17 193 17 188 0 190 0 195 -22 5 -26 55 -375 55 -389 0 -5 76 -9 168 -9 l168 0 -173 1058 c-95 581 -173 1060 -173 1065 0 4 -102 7 -228 7 l-227 0 -169 -1037z m463 131 c39 -276 71 -504 71 -508 0 -3 -67 -6 -150 -6 -82 0 -150 4 -150 8 0 5 32 235 70 512 39 277 70 511 71 519 2 59 27 -86 88 -525z M3170 2020 l0 -150 175 0 175 0 0 -915 0 -915 165 0 165 0 0 915 0 915 175 0 175 0 0 150 0 150 -515 0 -515 0 0 -150z M4370 1105 l0 -1065 165 0 165 0 0 1065 0 1065 -165 0 -165 0 0 -1065z M6000 2020 l0 -150 175 0 175 0 0 -915 0 -915 165 0 165 0 0 915 0 915 175 0 175 0 0 150 0 150 -515 0 -515 0 0 -150z M7200 1105 l0 -1065 165 0 165 0 0 1065 0 1065 -165 0 -165 0 0 -1065z M7790 1105 l0 -1065 165 0 165 0 1 343 0 342 68 133 67 134 139 -466 c76 -256 141 -470 145 -476 4 -7 64 -10 177 -8 l170 3 -202 672 -202 672 199 391 199 390 -168 -2 -168 -3 -210 -442 -210 -442 -3 445 -2 444 -165 0 -165 0 0 -1065z"/>
										</g>
									</svg>
									<h2><?= ucwords($this->setting->sebutan_desa); ?></h2>
								</div>
							</div>
							<div class="statistik-left2">
								<div class="statistik-left2-margin">
									<?php $i=0; ?>
									<?php foreach ($stat_widget as $data): ?>
										<?php if ($data['jumlah'] != NULL AND $data['nama']!= "JUMLAH"): ?>
											<div class="population">
												<div class="stat-icon <?php if($i==0){ echo "stat-male"; } elseif($i==1){ echo "stat-female"; } elseif($i==2) { echo "stat-single"; } elseif($i==3) { echo "stat-single"; } ?>">
													<h3><span class="big-stat" id="count-<?= $i ?>"><?= ribuan($data['jumlah'])?></span></h3><p><font style="text-transform:lowercase !important;"><?= $data['nama']?></font></p>
													<h4 class="flexcenter border-grey-soft color-1"><font style="text-transform:lowercase !important; margin-right:5px;"><?= $data['nama']?></font><span class="big-stat" id="count-<?= $i ?>"><?= ribuan($data['jumlah'])?></span><font style="text-transform:lowercase !important;">penduduk</font></h4>
												</div>
											</div>	
											<?php $i++; ?>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							</div>
							<div class="statistik-right">
								<div class="margin-min10">
									<div class="stat-col">
										<div class="pd-lr-10">
											<div class="stat-box">
												<a href="<?= site_url(); ?>data-wilayah">
													<div class="stat-box-inner rotate-1">
														<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/circle-graph1.svg") ?>" alt=""/>
													</div>
													<div class="stat-box-data"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/location-white.svg") ?>" alt=""/><p>Data<br/>Wilayah</p></div>
												</a>
											</div>
										</div>
									</div>
									<div class="stat-col">
										<div class="pd-lr-10">
											<div class="stat-box">
												<a href="<?= site_url(); ?>data-statistik/pendidikan-dalam-kk">
													<div class="stat-box-inner rotate-2">
														<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/circle-graph2.svg") ?>" alt=""/>
													</div>
													<div class="stat-box-data"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/pendidikan.svg") ?>" alt=""/><p>Data<br/>Pendidikan</p></div>
												</a>
											</div>
										</div>
									</div>
									<div class="stat-col">
										<div class="pd-lr-10">
											<div class="stat-box">
												<a href="<?= site_url(); ?>data-statistik/pekerjaan">
													<div class="stat-box-inner rotate-1">
														<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/circle-graph3.svg") ?>" alt=""/>
													</div>
													<div class="stat-box-data"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/pekerjaan.svg") ?>" alt=""/><p>Data<br/>Pekerjaan</p></div>
												</a>
											</div>
										</div>
									</div>
									<div class="stat-col">
										<div class="pd-lr-10">
											<div class="stat-box">
												<a href="<?= site_url(); ?>data-statistik/rentang-umur">
													<div class="stat-box-inner rotate-2">
														<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/circle-graph4.svg") ?>" alt=""/>
													</div>
													<div class="stat-box-data"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/usia.svg") ?>" alt=""/><p>Data<br/>Usia</p></div>
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
		</div>
	</div>
	<?php endif; ?>
	<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
