<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style type="text/css">
	#covid {
		margin-right: 8px;
		margin-left: 8px;
	}

	#covid .panel {
		background-color: inherit;
		margin-bottom: 0px;
	}

	#covid .panel-body.text-center {
		background-color: white;
		font-weight: bold;
		font-size: 20px;
	}

	#covid .panel-body span.i {
		font-size: inherit;
	}

	#covid .panel-body span {
		font-size: initial;
		font-weight: normal;
	}

	#covid .panel-body.sub {
		background-color: inherit;
		padding-top: 10px;
	}

	#covid .row .panel-heading {
		height: 40px;
		padding: 1px;
	}

	span.text-small {
		font-size: 14px !important;
	}
</style>
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

		$('.nama-wilayah').html(`di ${wilayah}`);
		attributes.forEach(function (attr) {
			$(`[data-status=${attr}]`).html(numberFormat(eval(attr)) + ' <span>orang</span>');
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
	})
</script>
<div id="covid">
	<div class="panel">
		<div class="box-header with-border">
			<h3 class="box-title">
				<span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Status COVID-19
					<span class="nama-wilayah"><i class="fa fa-spinner fa-pulse"></i></span></span>
			</h3>
		</div>
		<div class="panel-body sub">
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="panel panel-danger">
						<div style="padding:1px" class="panel-heading text-center">
							<h4>Positif<br>&nbsp;</h4>
						</div>
						<div class="panel-body text-center" data-status="positif">
							<span><i class="fa fa-spinner fa-lg fa-pulse"></i></span>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="panel panel-warning">
						<div style="padding:1px" class="panel-heading text-center">
							<h4>Dalam Perawatan</h4>
						</div>
						<div class="panel-body text-center" data-status="perawatan">
							<span><i class="fa fa-spinner fa-lg fa-pulse"></i></span>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="panel panel-info">
						<div style="padding:1px" class="panel-heading text-center">
							<h4>Sembuh</h4>
						</div>
						<div class="panel-body text-center" data-status="sembuh">
							<span><i class="fa fa-spinner fa-lg fa-pulse"></i></span>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="panel panel-success">
						<div style="padding:1px" class="panel-heading text-center">
							<h4>Meninggal</h4>
						</div>
						<div class="panel-body text-center" data-status="meninggal">
							<span><i class="fa fa-spinner fa-lg fa-pulse"></i></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="progress-group">
				<a href="https://kawalcorona.com/" rel="noopener noreferrer" target="_blank">
					<button type="button" class="btn btn-info btn-block">Sumber : kawalcorona.com</button>
				</a>
			</div>
		</div>
	</div>
</div>
