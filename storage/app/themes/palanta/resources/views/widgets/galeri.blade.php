
<div class="box-def">
	<div class="head-widget l-flex">
		<div class="head-widget-title l-flex">
		<i class="fa fa-picture-o"></i><h1>{{ $judul_widget }}</h1>
		</div>
	</div>
	<div class="widgetbox widget-gal">
		<div class="carousel js-flickity" data-flickity='{ "autoPlay": false, "cellAlign": "left"}'>
			@foreach ($w_gal As $data)
				<div class="carousel-col">
					<a class="slider_tittle" href="{{ ci_route('galeri.'.$data['id']) }}">
					<div class="image-slider">
						@if (is_file(LOKASI_GALERI . "sedang_" . $data['gambar']))
						<img src="{{ AmbilGaleri($data['gambar'],'sedang')}}">
						@endif
					</div>
					<p>{{ "$data[nama]" }}</p>
					</a>
				</div>
			@endforeach
		</div>
	</div>
</div>
