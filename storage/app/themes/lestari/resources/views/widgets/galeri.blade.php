@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="section-module homegallery">
<div class="margin-page">
	<div class="head-module mt-20 mb-20">
		<h1 class="head-border">Galeri Foto</h1>
	</div>
	<div class="carousel" data-flickity='{"pageDots": false, "autoPlay": false, "cellAlign": "left", "wrapAround": true }'>
		@foreach ($w_gal as $data)
			@if (is_file(LOKASI_GALERI . 'sedang_' . $data['gambar']))
			<div class="carousel-cell box-shadow brd-10">
				<a href="{{ site_url("galeri/$data[id]") }}">
				<div class="image-article imagefull" style="border-radius:10px 10px 0 0;">
					<img src="{{ AmbilGaleri($data['gambar'], 'kecil') }}" alt="Album : {{ $data['nama'] }}">
				</div>
				<div class="article-title">
					<h2>{{ $data['nama'] }}</h2>
				</div>
				</a>
			</div>
			@endif
		@endforeach
	</div>
</div>
</div>
