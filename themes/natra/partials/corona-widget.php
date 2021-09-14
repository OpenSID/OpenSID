<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="archive_style_1" style="font-family: Oswald">
	<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Statistik COVID-19</span></h2>
	<div class="row">
		<div style="margin-top:10px;">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div id="covid-provinsi" class="panel panel-info">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4><span class="nama-wilayah1"><i class="fa fa-spinner fa-pulse"></i></span></h4></div>
					<div style="height: 100px;padding:1px" class="panel-body text-center">
						<h4><small>Positif</small> <span data-status="positif"><i class="fa fa-spinner fa-pulse"></i></span> <small>Jiwa</small></h4>
						<h4><small>Sembuh</small> <span data-status="sembuh"><i class="fa fa-spinner fa-pulse"></i></span> <small>Jiwa</small></h4>
						<h4><small>Meninggal</small> <span data-status="meninggal"><i class="fa fa-spinner fa-pulse"></i></span> <small>Jiwa</small></h4>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div id="covid-nasional" class="panel panel-info">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4><span class="nama-wilayah2"><i class="fa fa-spinner fa-pulse"></i></span></h4></div>
					<div style="height: 100px;padding:1px" class="panel-body text-center">
						<h4><small>Positif</small> <span data-status="positif1"><i class="fa fa-spinner fa-pulse"></i></span> <small>Jiwa</small></h4>
						<h4><small>Sembuh</small> <span data-status="sembuh1"><i class="fa fa-spinner fa-pulse"></i></span> <small>Jiwa</small></h4>
						<h4><small>Meninggal</small> <span data-status="meninggal1"><i class="fa fa-spinner fa-pulse"></i></span> <small>Jiwa</small></h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	const COVID_API_URL = 'https://api.kawalcorona.com/';
	const KODE_PROVINSI = <?= $this->setting->provinsi_covid ? : 'undefined' ?> ;
	const ENDPOINT = KODE_PROVINSI ? 'indonesia/provinsi/' : 'indonesia/';

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

	function showError() {
		$('.nama-wilayah').html('');
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
					showError();
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
					endpoint: 'https://api.kawalcorona.com/indonesia/'
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
			showError()
		}

	})
</script>
