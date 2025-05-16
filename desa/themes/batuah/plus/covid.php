<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if ($this->setting->covid_desa): ?>
<div class="col-default">
	<div class="statistik-row bggrad-grey1 bordergrey1">
	<div class="stathead flexcenter">
	<h1 class="bgcolor-1 flexcenter">Covid-19</h1>
	</div>
	<div class="covidcustom">
		<div class="covidcustom-inner">
		<h3>Data Pantauan Covid-19 di <?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></h3>
		<?php $this->load->view("$folder_themes/plus/head_tags_front"); ?>
		<?php defined('BASEPATH') || exit('No direct script access allowed');
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
		<div class="rowsame margin-minlr-3">
		<?php foreach ($covid as $key => $val):
		if ($key >= 7) break;
		?>
			<div class="covid-col bordergrey1">
			<div class="<?= $panel[$key]; ?>">
				<div class="covid-judul flexcenter"><?= $val['nama']; ?></div>
				<div class="covid-isi bgwhite flexcenter">
				<div>
				<h2><?= number_format($val['jumlah']); ?></h2>
				<p>Orang</p>
				</div>
				</div>
			</div>
			</div>
		<?php endforeach; ?>
		</div>
		</div>
	</div>
	</div>
</div>

<?php endif ?>