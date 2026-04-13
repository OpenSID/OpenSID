@php $abstrak_headline = potong_teks($headline['isi'], 160) @endphp

@if ($headline)
<div class="section-module headline">
<div class="margin-page">
	<div class="hover-effect">
	<a href="{{ $headline->url_slug }}">
	<div class="row">
		<div class="col-md-6 col-sm-12 mt-20">
			<div class="image-headline trans-def imagefull brd-10">
				@if ($headline['gambar'] != '')
				@if (is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $headline['gambar']))	
					<img src="{{ AmbilFotoArtikel($headline['gambar'], 'sedang') }}">
				@else
					<img src="{{ theme_asset('images/no-image.jpg') }}"/>
				@endif
				@endif
			</div>
		</div>
		<div class="col-md-6 col-sm-12 mt-20">
			<div class="head-module mb-20">
				<h1 class="head-border">Headline</h1>
			</div>
			<div class="meta">
				<div class="meta-item flex-left"><svg viewBox="0 0 24 24"><path d="M19 3H18V1H16V3H8V1H6V3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H9V19H5V9H19V19H15V21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3M5 7V5H19V7H5M12 12L8 16H11V22H13V16H16L12 12Z" /></svg><p>{{ tgl_indo($headline['tgl_upload']) }}</p></div>
			</div>
			<h2>{{ $headline['judul'] }}</h2>
			<div class="meta flex-left" style="padding:0;">
				<div class="meta-item flex-left"><svg viewBox="0 0 24 24"><path d="M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C12.36,19.5 12.72,19.5 13.08,19.45C13.03,19.13 13,18.82 13,18.5C13,17.94 13.08,17.38 13.24,16.84C12.83,16.94 12.42,17 12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12C17,12.29 16.97,12.59 16.92,12.88C17.58,12.63 18.29,12.5 19,12.5C20.17,12.5 21.31,12.84 22.29,13.5C22.56,13 22.8,12.5 23,12C21.27,7.61 17,4.5 12,4.5M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M18,14.5V17.5H15V19.5H18V22.5H20V19.5H23V17.5H20V14.5H18Z" /></svg><p>{{ hit($headline['hit']) }} dibuka</p></div>
			</div>
			<div class="intro mt-20"><p>{!! $abstrak_headline !!} ...</p></div>
		</div>
	</div>
	</a>
	</div>
</div>
</div>	
@endif
