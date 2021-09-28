<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<section id="covid-nasional">
	<p class="font-weight-bold line line-short nama-wilayah2"></p>
	<div class="row">
		<div class="col-lg-2 col-12 px-2 py-1">
			<div class="square covid positif">
				<span>Total Positif</span>
				<span data-status="positif1"></span>
				<span class="small">Orang</span>
			</div>
		</div>
		<div class="col-lg-2 col-12 px-2 py-1">
			<div class="square covid sembuh">
				<span>Total Sembuh</span>
				<span data-status="sembuh1"></span>
				<span class="small">Orang</span>
			</div>
		</div>
		<div class="col-lg-2 col-12 px-2 py-1">
			<div class="square covid meninggal">
				<span>Total Meninggal</span>
				<span data-status="meninggal1"></span>
				<span class="small">Orang</span>
			</div>
		</div>
	</div>
</section>

<?php if($this->setting->provinsi_covid) : ?>
	<section id="covid-provinsi">
		<p class="font-weight-bold line line-short nama-wilayah1"></p>
		<div class="row">
			<div class="col-lg-2 col-12 px-2 py-1">
				<div class="square covid positif">
					<span>Total Positif</span>
					<span data-status="positif"></span>
					<span class="small">Orang</span>
				</div>
			</div>
			<div class="col-lg-2 col-12 px-2 py-1">
				<div class="square covid sembuh">
					<span>Total Sembuh</span>
					<span data-status="sembuh"></span>
					<span class="small">Orang</span>
				</div>
			</div>
			<div class="col-lg-2 col-12 px-2 py-1">
				<div class="square covid meninggal">
					<span>Total Meninggal</span>
					<span data-status="meninggal"></span>
					<span class="small">Orang</span>
				</div>
			</div>
		</div>
	</section>
<?php endif ?>
<?php $this->load->view('head_tags_front') ?>

<script>
const COVID_API_URL = 'https://api.kawalcorona.com/';
const KODE_PROVINSI = <?= $this->setting->provinsi_covid ? : 'undefined' ?> ;
const ENDPOINT = KODE_PROVINSI ? 'indonesia/provinsi/' : 'indonesia/';
const ENDPOINT_NASIONAL = '/indonesia/';

function numberFormat(num) {
	return new Intl.NumberFormat('id-ID').format(num);
}

function parseToNum(data) {
	return parseFloat(data.toString().replace(/,/g, ''));
}

function showData(result) {
	const data = result[0];
	const wilayah = KODE_PROVINSI ? data.attributes.Provinsi : data.name;
	const meninggal = parseToNum(KODE_PROVINSI ? data.attributes.Kasus_Meni : data.meninggal);
	const sembuh = parseToNum(KODE_PROVINSI ? data.attributes.Kasus_Semb : data.sembuh);
	const positif = parseToNum(KODE_PROVINSI ? data.attributes.Kasus_Posi : data.positif);
	const perawatan = positif - (sembuh + meninggal);
	const attributes = ['positif', 'perawatan', 'sembuh', 'meninggal'];
	$('.nama-wilayah1').html(`${wilayah}`);
	attributes.forEach(function (attr) {
		$(`[data-status=${attr}]`).html(numberFormat(eval(attr)));
	})
}

function showData1(result) {
	const data = result[0];
	const wilayah = data.name;
	const meninggal1 = parseToNum(data.meninggal);
	const sembuh1 = parseToNum(data.sembuh);
	const positif1 = parseToNum(data.positif);
	const perawatan1 = positif1 - (sembuh1 + meninggal1);
	const attributes = ['positif1', 'perawatan1', 'sembuh1', 'meninggal1'];
	$('.nama-wilayah2').html(`${wilayah}`);
	attributes.forEach(function (attr) {
		$(`[data-status=${attr}]`).html(numberFormat(eval(attr)));
	})
}

function showError1() {
	$('.nama-wilayah1').html('');
	$('#covid .panel-body.text-center').html('<span class="text-small">Gagal mengambil data</span>');
}

function showError2() {
	$('.nama-wilayah2').html('');
	$('#covid .panel-body.text-center').html('<span class="text-small">Gagal mengambil data</span>');
}

$(document).ready(function () {
	try {
		$.ajax({
			type: "POST",
			dataType: 'json',
			async: true,
			cache: true,
			url: '<?= site_url("ambil_data_covid")?>',
			data: {
				endpoint: COVID_API_URL + ENDPOINT
			},
			success: function (response) {
				const result = response.filter(data => KODE_PROVINSI ? data.attributes.Kode_Provi == KODE_PROVINSI :
					data);
					showData(result);
				},
				error: function (err) {
					showError1();
				}
			});
		} catch (error) {
			showError()
		}

		try {
			$.ajax({
				type: "POST",
				dataType: 'json',
				async: true,
				cache: true,
				url: '<?= site_url("ambil_data_covid")?>',
				data: {
					endpoint: COVID_API_URL + ENDPOINT_NASIONAL
				},
				success: function (response) {
					const result = response.filter(data => data);
					showData1(result);
				},
				error: function (err) {
					showError();
				}
			});
		} catch (error) {
			showError2()
		}

	})
	</script>
