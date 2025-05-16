<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if ($artikel && $this->setting->covid_desa): ?>
	<div class="relative-row ptb-5" style="padding-top:10px;">
		<div class="covidstyle container-page">
			<div class="flexrow">
				<div class="covid-left border-grey-soft bg-white">
					<div class="p-10">
						<div class="flexrow2">
							<div class="flex-covid bg-grey-medium border-grey-soft">
								<div class="covid-box">
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/covid/masker.png") ?>" alt=""/>
									<h3>Selalu Pakai Masker</h3>
								</div>
							</div>
							<div class="flex-covid bg-grey-medium border-grey-soft">
								<div class="covid-box bg-grey-medium border-grey-soft">
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/covid/cucitangan.png") ?>" alt=""/>
									<h3>Rajin Mencuci Tangan</h3>
								</div>
							</div>
							<div class="flex-covid bg-grey-medium border-grey-soft">
								<div class="covid-box bg-grey-medium border-grey-soft">
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/covid/jagajarak.png") ?>" alt=""/>
									<h3>Tetap Jaga Jarak</h3>
								</div>
							</div>
							<div class="flex-covid bg-grey-medium border-grey-soft">
								<div class="covid-box bg-grey-medium border-grey-soft">
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/covid/kerumunan.png") ?>" alt=""/>
									<h3>Hindari Kerumunan</h3>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="relative-row covid-right border-grey-soft bg-white flexcenter">
					<div class="covid-backg1"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/covid/corona.svg") ?>" alt=""/></div>
					<div class="covid-backg2"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/covid/corona.svg") ?>" alt=""/></div>
					<div class="relative-row ptb-10 pd-lr-20 opencovid">
						Perkembangan Covid 19<br/>di <?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?>
						<div class="flexcenter">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapsecovid">
								<button type="submit" class="layanan-masuk bg-color3 flexcenter">Buka Data</button>
							</a>
						</div>
					</div>
				</div>
			</div>
			<div id="collapsecovid" class="panel-collapse collapse">
				<?= view('admin.layouts.components.token') ?>
				<?php
				$panel = [
					'default',
					'info',
					'primary',
					'secondary',
					'warning',
					'danger',
					'success',
				];
				?>
				<div class="relative-row pt-10">
					<div class="covid-data bg-white border-grey-soft">
						<div class="samecovid">
							<?php foreach ($covid as $key => $val):
								if ($key >= 7) break;
								?>
								<div class="covid-item bg-grey-medium">
									<div class="<?= $panel[$key]; ?>">
										<div class="head-covid bg-color3 flexcenter"><?= $val['nama']; ?></div>
										<h2><?= number_format($val['jumlah']); ?></h2>
										<p>Orang</p>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
<?php endif ?>
