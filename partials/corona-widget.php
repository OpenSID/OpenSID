<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script>
	const COVID_API_URL = 'https://api.kawalcorona.com/';
	const KODE_PROVINSI = <?= config_item('covid_provinsi') ? : 'undefined' ?> ;
	const ENDPOINT = KODE_PROVINSI ? 'indonesia/provinsi/' : 'indonesia/';

	function numberFormat(num)
	{
		return new Intl.NumberFormat('id-ID').format(num);
	}
	function parseToNum(data)
	{
		return parseFloat(data.toString().replace(/,/g, ''));
	}
	function showData(result)
	{
		const data = result[0];
		const wilayah = KODE_PROVINSI ? data.attributes.Provinsi : data.name;
		const meninggal = parseToNum(KODE_PROVINSI ? data.attributes.Kasus_Meni : data.meninggal);
		const sembuh = parseToNum(KODE_PROVINSI ? data.attributes.Kasus_Semb : data.sembuh);
		const positif = parseToNum(KODE_PROVINSI ? data.attributes.Kasus_Posi : data.positif);
		const perawatan = positif - (sembuh + meninggal);

		const attributes = ['positif', 'perawatan', 'sembuh', 'meninggal'];

		$('.nama-wilayah').html(`di ${wilayah}`);
		attributes.forEach(function (attr)
		{
			$(`[data-status=${attr}]`).html(numberFormat(eval(attr)));
		})
	}
	function showError()
	{
		$('.nama-wilayah').html('');
		$('#covid .panel-body.text-center').html('<span class="text-small">Gagal mengambil data</span>');
	}

	$(document).ready(function ()
	{
		try
		{
			$.ajax(
			{
				async: true,
				cache: true,
				url: COVID_API_URL + ENDPOINT,
				success: function (response) {
					const result = response.filter(data => KODE_PROVINSI ? data.attributes.Kode_Provi == KODE_PROVINSI :
						data);
					showData(result);
				},
				error: function (err)
				{
					showError();
				}
			});
		} catch (error)
		{
			showError()
		}
	})
</script>
<script>
	const COVID_API = 'https://api.kawalcorona.com/';
	const KODE_NEGARA = <?= config_item('covid_negara') ? : 'undefined' ?> ;

	function numberFormat(num) {
		return new Intl.NumberFormat('id-ID').format(num);
	}
	function parseToNum(data) {
		return parseFloat(data.toString().replace(/,/g, ''));
	}
	function showData2(result) {
		const data = result[0];
		const wilayah2 = KODE_NEGARA ? data.attributes.Country_Region : data.name;
		const meninggal2 = parseToNum(KODE_NEGARA ? data.attributes.Deaths : data.meninggal2);
		const sembuh2 = parseToNum(KODE_NEGARA ? data.attributes.Recovered : data.sembuh2);
		const positif2 = parseToNum(KODE_NEGARA ? data.attributes.Confirmed : data.positif2);
		const perawatan2 = parseToNum(KODE_NEGARA ? data.attributes.Active : data.perawatan2);
		const Last_Update = parseToNum(KODE_NEGARA ? data.attributes.Last_Update : data.Last_Update);

		const attributes = ['positif2', 'perawatan2', 'sembuh2', 'meninggal2', 'Last_Update'];

		$('.nama-wilayah2').html(`di ${wilayah2}`);
		attributes.forEach(function (attr) {
			$(`[data-status=${attr}]`).html(numberFormat(eval(attr)));
		})
	}
	function showError()
	{
		$('.nama-wilayah2').html('');
		$('#covid .panel-body.text-center').html('<span class="text-small">Gagal mengambil data</span>');
	}

	$(document).ready(function ()
	{
		try
		{
			$.ajax(
			{
				async: true,
				cache: true,
				url: COVID_API,
				success: function (response)
				{
					const result = response.filter(data => KODE_NEGARA ? data.attributes.OBJECTID == KODE_NEGARA :
						data);
					showData2(result);
				},
				error: function (err)
				{
					showError();
				}
			});
		} catch (error)
		{
			showError()
		}
	})
</script>
<div class="archive_style_1" style="font-family: Oswald">
	<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Statistik COVID-19</span></h2>
	<div class="row">
		<div style="margin-top:10px;">
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<div class="panel panel-danger">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>Positif</h4></div>
					<?php if (!empty(config_item('covid_negara'))): ?>
						<div style="height: 35px;padding:1px" class="panel-body text-center">
							<h4><small><span class="nama-wilayah2"><i class="fa fa-spinner fa-pulse"></i></span></small> <span data-status="positif2"></span> <small>Jiwa</small></h4>
						</div>
					<?php endif; ?>
					<?php if (!empty(config_item('covid_provinsi'))): ?>
						<div style="height: 35px;padding:1px" class="panel-body text-center">
							<h4><small><span class="nama-wilayah"><i class="fa fa-spinner fa-pulse"></i></span></small> <span data-status="positif"></span> <small>Jiwa</small></h4>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<div class="panel panel-info">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>Sembuh</h4></div>
					<?php if (!empty(config_item('covid_negara'))): ?>
						<div style="height: 35px;padding:1px" class="panel-body text-center">
							<h4><small><span class="nama-wilayah2"><i class="fa fa-spinner fa-pulse"></i></span></small> <span data-status="sembuh2"></span> <small>Jiwa</small></h4>
						</div>
					<?php endif; ?>
					<?php if (!empty(config_item('covid_provinsi'))): ?>
						<div style="height: 35px;padding:1px" class="panel-body text-center">
							<h4><small><span class="nama-wilayah"><i class="fa fa-spinner fa-pulse"></i></span></small> <span data-status="sembuh"></span> <small>Jiwa</small></h4>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<div class="panel panel-success">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>Meninggal</h4></div>
					<?php if (!empty(config_item('covid_negara'))): ?>
						<div style="height: 35px;padding:1px" class="panel-body text-center">
							<h4><small><span class="nama-wilayah2"><i class="fa fa-spinner fa-pulse"></i></span></small> <span data-status="meninggal2"></span> <small>Jiwa</small></h4>
						</div>
					<?php endif; ?>
					<?php if (!empty(config_item('covid_provinsi'))): ?>
						<div style="height: 35px;padding:1px" class="panel-body text-center">
							<h4><small><span class="nama-wilayah"><i class="fa fa-spinner fa-pulse"></i></span></small> <span data-status="meninggal"></span> <small>Jiwa</small></h4>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="progress-group">
					<a href="https://kawalcorona.com/" rel="noopener noreferrer" target="_blank">
						<button type="button" class="btn btn-success btn-block">Sumber kawalcorona.com</button>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
