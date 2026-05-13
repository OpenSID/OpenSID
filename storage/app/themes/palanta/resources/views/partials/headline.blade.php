@if($headline)
<div class="headline bg-primary">
	<a href="{{ $headline['url_slug'] }}">
	<div class="box-def-inner">
		<div class="row-custom mlr-min5">
			<div class="image-headline">
				<div class="image-slider2">
				@if (is_file(LOKASI_FOTO_ARTIKEL."sedang_".$headline['gambar']))
					<img src="{{ AmbilFotoArtikel($headline['gambar'],'sedang') }}">
				@else
					<img src="{{ theme_asset('images/pengganti.jpg') }}"/>
					<div class="small-image"><img src="{{ gambar_desa($desa['logo']) }}"/></div>
				@endif
				</div>
			</div>
			<div class="headline-inner">
				<h2>Headline</h2>
				<h3>{{ $headline['judul'] }}</h3>
				<div class="artikel-meta" style="margin-bottom:5px;">
					<div class="meta-item l-flex"><i class="fa fa-user"></i><p>{{ tgl_indo($headline['tgl_upload']);}}</p></div>
					<div class="meta-item l-flex"><i class="fa fa-eye"></i><p>{{ hit($headline['hit']) }} dibaca</p></div>
					<div class="meta-item l-flex"><i class="fa fa-comment"></i><p>{{ $headline->comments->count() }}</p></div>
					<div class="meta-item l-flex"><i class="fa fa-user"></i><p>{{ $headline->author?->nama ?? '' }}</p></div>
				</div>
				<p>{!! potong_teks ($headline['isi'], 120); !!}...</p>
				
			</div>
		</div>
	</div>
	</a>
</div>
@endif