<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<link rel="stylesheet" href="<?= base_url()?>assets/css/peta.css">
<script>
	const KODE_PROVINSI = "<?= $this->setting->provinsi_covid ?: 'undefined' ?>";
</script>

	<section id="covid-nasional">
		<p class="font-weight-bold line line-short shimmer" data-name="wilayah"></p>
		<div class="row">
			<div class="col-lg-2 col-12 px-2 py-1">
				<div class="square covid positif shimmer">
					<span>Total Positif</span>
					<span data-name="positif"></span>
					<span class="small">orang</span>
				</div>
			</div>
			<div class="col-lg-2 col-12 px-2 py-1">
				<div class="square covid sembuh shimmer">
					<span>Total Sembuh</span>
					<span data-name="sembuh"></span>
					<span class="small">orang</span>
				</div>
			</div>
			<div class="col-lg-2 col-12 px-2 py-1">
				<div class="square covid meninggal shimmer">
					<span>Total Meninggal</span>
					<span data-name="meninggal"></span>
					<span class="small">orang</span>
				</div>
			</div>
		</div>
	</section>

	<?php if($this->setting->provinsi_covid) : ?>
	<section id="covid-provinsi">
		<p class="font-weight-bold line line-short shimmer" data-name="wilayah"></p>
		<div class="row">
			<div class="col-lg-2 col-12 px-2 py-1">
				<div class="square covid positif shimmer">
					<span>Total Positif</span>
					<span data-name="positif"></span>
					<span class="small">orang</span>
				</div>
			</div>
			<div class="col-lg-2 col-12 px-2 py-1">
				<div class="square covid sembuh shimmer">
					<span>Total Sembuh</span>
					<span data-name="sembuh"></span>
					<span class="small">orang</span>
				</div>
			</div>
			<div class="col-lg-2 col-12 px-2 py-1">
				<div class="square covid meninggal shimmer">
					<span>Total Meninggal</span>
					<span data-name="meninggal"></span>
					<span class="small">orang</span>
				</div>
			</div>
		</div>
	</section>
	<?php endif ?>
