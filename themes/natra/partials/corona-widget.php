<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script>
	const KODE_PROVINSI = <?= $this->setting->provinsi_covid ? : 'undefined' ?> ;
</script>
<div class="archive_style_1" style="font-family: Oswald">
	<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Statistik COVID-19</span></h2>
	<div class="row">
		<div style="margin-top:10px;">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div id="covid-provinsi" class="panel panel-info">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4><span data-name="wilayah"><i class="fa fa-spinner fa-pulse"></i></span></h4></div>
					<div style="height: 100px;padding:1px" class="panel-body text-center">
						<h4><small>Positif</small> <span data-name="positif"><i class="fa fa-spinner fa-pulse"></i></span> <small>Jiwa</small></h4>
						<h4><small>Sembuh</small> <span data-name="sembuh"><i class="fa fa-spinner fa-pulse"></i></span> <small>Jiwa</small></h4>
						<h4><small>Meninggal</small> <span data-name="meninggal"><i class="fa fa-spinner fa-pulse"></i></span> <small>Jiwa</small></h4>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div id="covid-nasional" class="panel panel-info">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4><span data-name="wilayah"><i class="fa fa-spinner fa-pulse"></i></span></h4></div>
					<div style="height: 100px;padding:1px" class="panel-body text-center">
						<h4><small>Positif</small> <span data-name="positif"><i class="fa fa-spinner fa-pulse"></i></span> <small>Jiwa</small></h4>
						<h4><small>Sembuh</small> <span data-name="sembuh"><i class="fa fa-spinner fa-pulse"></i></span> <small>Jiwa</small></h4>
						<h4><small>Meninggal</small> <span data-name="meninggal"><i class="fa fa-spinner fa-pulse"></i></span> <small>Jiwa</small></h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
const regions = {
	indonesia: {
		id: 1,
		attributes: {
			wilayah: 'name',
			positif: 'jumlahKasus',
			meninggal: 'meninggal',
			perawatan: 'perawatan',
			sembuh: 'sembuh'
		}
	},
	provinsi: {
		id: 2,
		attributes: {
			wilayah: 'provinsi',
			positif: 'kasusPosi',
			meninggal: 'kasusMeni',
			sembuh: 'kasusSemb',
		}
	}
}

function numberFormat(num) {
	return new Intl.NumberFormat('id-ID').format(num);
}

function parseToNum(data) {
	return parseFloat(data.toString().replace(/,/g, ''));
}

function showCovidData(data, region) {
	const elem = region.id === regions.indonesia.id ? '#covid-nasional' : '#covid-provinsi';
	Object.keys(region.attributes).forEach(function (prop) {
		let tempData = data[region.attributes[prop]];
		let finalData = prop === 'wilayah' ? tempData.toUpperCase() : numberFormat(parseToNum(tempData));
		$(elem).find(`[data-name=${prop}]`).html(`${finalData}`);
	});

	$(elem).find('.shimmer').removeClass('shimmer');
}

function showError(elem = '') {
	$(`${elem} .shimmer`).html('<span class="small"><i class="fa fa-exclamation-triangle"></i> Gagal memuat<i class="fa fa-spinner fa-pulse"></i></span>');
	$(`${elem} .shimmer`).removeClass('shimmer');
}

$(document).ready(function () {
	if ($('#covid-nasional').length) {
		const COVID_API_URL = 'https://indonesia-covid-19.mathdro.id/api/';
		const ENDPOINT_PROVINSI = 'provinsi/';

		try {
			$.ajax({
				async: true,
				cache: true,
				url: COVID_API_URL,
				success: function (response) {
					const data = response;
					data.name = 'Indonesia';
					showCovidData(data, regions.indonesia);
				},
				error: function (error) {
					showError('#covid-nasional');
				}
			})
		} catch (error) {
			showError('#covid-nasional');
		}

		if (KODE_PROVINSI) {
			try {
				$.ajax({
					async: true,
					cache: true,
					url: COVID_API_URL + ENDPOINT_PROVINSI,
					success: function (response) {
						const data = response.data.filter(data => data.kodeProvi == KODE_PROVINSI);
						data.length ? showCovidData(data[0], regions.provinsi) : showError('#covid-provinsi');
					},
					error: function (error) {
						showError('#covid-provinsi');
					}
				})
			} catch (error) {
				showError('#covid-provinsi')
			}
		}

	}
})
</script>
