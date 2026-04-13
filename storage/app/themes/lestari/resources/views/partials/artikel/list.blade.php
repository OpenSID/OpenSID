@push('styles')
<style>  
	@if ($cari)
		.homepage{position:relative;}
		.head-home .header{background:var(--color1);}
	@elseif (!empty($judul_kategori))
		.homepage{position:relative;}
		.head-home .header{background:var(--color1);}
	@endif
</style>	
@endpush

<div class="section-module article-home">
<div class="margin-page">
	<div class="head-module align-center mt-20">
		<h1>
		@if ($cari)
			Pencarian
		@elseif (!empty($judul_kategori))
			{{ $title }}
		@else
			<font class="desk-v">Berita & Artikel</font> Terbaru
		@endif
		</h1>
	</div>
	@if ($artikel->count() > 0)
	<div class="article-grid">
		@foreach ($artikel as $post)
		<div class="articlecol mt-20">
		<div class="hover-effect">
			<a href="{{ $post->url_slug }}">
			<div class="box-shadow brd-10">
				<div class="image-article imagefull brd-10 trans-def">
					@if (is_file(LOKASI_FOTO_ARTIKEL.'kecil_'.$post['gambar']))
						<img src="{{ AmbilFotoArtikel($post['gambar'], 'sedang') }}">
					@else
						<img src="{{ theme_asset('images/no-image.jpg') }}"/>
					@endif
				</div>
			</div>
			<div class="article-title">
				<div class="meta">
					<div class="meta-item flex-left"><svg viewBox="0 0 24 24"><path d="M19 3H18V1H16V3H8V1H6V3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H9V19H5V9H19V19H15V21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3M5 7V5H19V7H5M12 12L8 16H11V22H13V16H16L12 12Z" /></svg><p>{{ tgl_indo($post['tgl_upload']) }}</p></div>
				</div>
				<h2>{{ $post['judul'] }}</h2>
				<div class="meta">
					<div class="meta-item flex-left"><svg viewBox="0 0 24 24"><path d="M12 3C14.21 3 16 4.79 16 7S14.21 11 12 11 8 9.21 8 7 9.79 3 12 3M16 13.54C16 14.6 15.72 17.07 13.81 19.83L13 15L13.94 13.12C13.32 13.05 12.67 13 12 13S10.68 13.05 10.06 13.12L11 15L10.19 19.83C8.28 17.07 8 14.6 8 13.54C5.61 14.24 4 15.5 4 17V21H20V17C20 15.5 18.4 14.24 16 13.54Z" /></svg><p>{{ $post['owner'] }}</p></div>
				</div>
				<div class="meta flex-left" style="padding:0;">
					<div class="meta-item flex-left"><svg viewBox="0 0 24 24"><path d="M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C12.36,19.5 12.72,19.5 13.08,19.45C13.03,19.13 13,18.82 13,18.5C13,17.94 13.08,17.38 13.24,16.84C12.83,16.94 12.42,17 12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12C17,12.29 16.97,12.59 16.92,12.88C17.58,12.63 18.29,12.5 19,12.5C20.17,12.5 21.31,12.84 22.29,13.5C22.56,13 22.8,12.5 23,12C21.27,7.61 17,4.5 12,4.5M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M18,14.5V17.5H15V19.5H18V22.5H20V19.5H23V17.5H20V14.5H18Z" /></svg><p>{{ hit($post['hit']) }} dibuka</p></div>
					<div class="meta-item flex-left"><svg viewBox="0 0 24 24" style="margin-top:3px;"><path d="M9,22A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V4C2,2.89 2.9,2 4,2H20A2,2 0 0,1 22,4V16A2,2 0 0,1 20,18H13.9L10.2,21.71C10,21.9 9.75,22 9.5,22V22H9M17,11V9H15V11H17M13,11V9H11V11H13M9,11V9H7V11H9Z" /></svg><p>{{ $post->jumlah_komentar }}</p></div>
				</div>
			</div>
			</a>
		</div>	
		</div>	
		@endforeach
		<div class="col-sm-12 mt-20">
			<div class="flex-center">@include('theme::commons.page')</div>
		</div>
	</div>
	@else
		@include('theme::partials.artikel.empty', ['title' => $title])
	@endif
</div>	
</div>	
