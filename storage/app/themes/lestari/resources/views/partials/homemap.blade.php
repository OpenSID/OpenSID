<div class="section-module homemap">
<div class="margin-page">
	<div class="carousel map-nav" data-flickity='{ "asNavFor": ".map-main", "contain": true, "pageDots": false, "autoPlay": false, "wrapAround": false }'>
		<div class="carousel-cell">
			<h1>Kantor {{ ucwords(setting('sebutan_desa')) }}</h1>
		</div>
		<div class="carousel-cell">
			<h1>Wilayah {{ ucwords(setting('sebutan_desa')) }}</h1>
		</div>
	</div>
	<div class="box-shadow brd-10">
	<div class="carousel map-main" data-flickity='{"pageDots": false, "autoPlay": false, "wrapAround": false }'>
		<div class="carousel-cell">
			@include('theme::widgets.peta_lokasi_kantor')
		</div>
		<div class="carousel-cell">
			@include('theme::widgets.peta_wilayah_desa')
		</div>
	</div>
	</div>
</div>
</div>