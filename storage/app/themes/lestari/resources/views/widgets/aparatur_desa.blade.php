@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp


<div class="section-module aparatur">
<div class="margin-page">
	<div class="head-module mt-20 mb-20">
		<h1 class="head-border">Aparatur {{ ucwords(setting('sebutan_desa')) }}</h1>
	</div>
	<div class="carousel" data-flickity='{"pageDots": false, "autoPlay": false, "cellAlign": "left", "wrapAround": true }'>
		@foreach ($aparatur_desa['daftar_perangkat'] as $data)
			<div class="carousel-cell">
				<div class="image-aparatur imagefull">
					<img src="{{ $data['foto'] }}" alt="{{ $data['nama'] }}">
				</div>
				<div class="aparatur-title">
					<h2>{{ $data['nama'] }}</h2>
					<p>{{ $data['jabatan'] }}</p>
				</div>
			</div>
		@endforeach
	</div>
</div>
</div>
