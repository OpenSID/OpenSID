
<div class="row">
	<div class="col-sm-12">
	<div class="imglist">	
		@if ($single_artikel['gambar2'] != '' and is_file(LOKASI_FOTO_ARTIKEL . "sedang_" . $single_artikel['gambar2']))
			<div class="carousel mt-20" data-flickity='{"pageDots": false, "autoPlay": false, "cellAlign": "left", "wrapAround": true }'>
				@if ($single_artikel['gambar'] != '' and is_file(LOKASI_FOTO_ARTIKEL . "sedang_" . $single_artikel['gambar']))
					<div class="carousel-cell">
						<a href="{{ AmbilFotoArtikel($single_artikel['gambar'], 'sedang') }}"  data-fancybox="images">
						<div class="image-box">
						<div class="image-article imagefull">
							<img src="{{ AmbilFotoArtikel($single_artikel['gambar'], 'sedang') }}"/>
						</div>
						</div>
						</a>
					</div> 
				@endif
				@if ($single_artikel['gambar1'] != '' and is_file(LOKASI_FOTO_ARTIKEL . "sedang_" . $single_artikel['gambar1']))
					<div class="carousel-cell">
						<a href="{{ AmbilFotoArtikel($single_artikel['gambar1'], 'sedang') }}"  data-fancybox="images">
						<div class="image-box">
						<div class="image-article imagefull">
							<img src="{{ AmbilFotoArtikel($single_artikel['gambar1'], 'sedang') }}"/>
						</div>
						</div>
						</a>
					</div> 
				@endif
				@if ($single_artikel['gambar2'] != '' and is_file(LOKASI_FOTO_ARTIKEL . "sedang_" . $single_artikel['gambar2']))
					<div class="carousel-cell">
						<a href="{{ AmbilFotoArtikel($single_artikel['gambar2'], 'sedang') }}"  data-fancybox="images">
						<div class="image-box">
						<div class="image-article imagefull">
							<img src="{{ AmbilFotoArtikel($single_artikel['gambar2'], 'sedang') }}"/>
						</div>
						</div>
						</a>
					</div> 
				@endif
				@if ($single_artikel['gambar3'] != '' and is_file(LOKASI_FOTO_ARTIKEL . "sedang_" . $single_artikel['gambar3']))
					<div class="carousel-cell">
						<a href="{{ AmbilFotoArtikel($single_artikel['gambar3'], 'sedang') }}"  data-fancybox="images">
						<div class="image-box">
						<div class="image-article imagefull">
							<img src="{{ AmbilFotoArtikel($single_artikel['gambar3'], 'sedang') }}"/>
						</div>
						</div>
						</a>
					</div> 
				@endif
			</div>
		@else
			@if ($single_artikel['gambar'] != '' and is_file(LOKASI_FOTO_ARTIKEL . "sedang_" . $single_artikel['gambar']))
				<div class="image-box image-default mt-20">
					<img src="{{ AmbilFotoArtikel($single_artikel['gambar'], 'sedang') }}"/>
				</div>		
			@endif
		@endif
	</div>
	</div>
</div>