@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="section-module widget">
<div class="margin-page mt-20">
	<div class="widget-grid">
		<div class="widget-area">
		<div class="widget-margin">
			<div class="carousel" data-flickity='{"pageDots": false, "autoPlay": false, "cellAlign": "left", "wrapAround": true }'>
			@if ($widgetAktif)
				@foreach ($widgetAktif as $widget)
					@php
					$judul_widget = [
						'judul_widget' => str_replace('Desa', ucwords(setting('sebutan_desa')), strip_tags($widget['judul']))
						];
					@endphp
					@if ($widget['jenis_widget'] == 3)
						<div class="carousel-cell">
							<div class="widget-column">
							<div class="box-shadow brd-10">
							<div class="widget-padding">
								<div class="head-module flex-center">
									<h1>{{ $judul_widget['judul_widget'] }}</h1>
								</div>
								<div class="colscroll">
								{!! html_entity_decode($widget['isi']) !!}
								</div>
							</div>
							</div>
							</div>
						</div>
					@else
						@if ($widget['isi'] == 'keuangan' || $widget['isi'] == 'galeri' || $widget['isi'] == 'statistik' || $widget['isi'] == 'aparatur_desa' || $widget['isi'] == 'peta_lokasi_kantor' || $widget['isi'] == 'peta_wilayah_desa'  || $widget['isi'] == 'statistik_pengunjung')
						@else
							<div class="carousel-cell">	
							@includeIf("theme::widgets.{$widget['isi']}", $judul_widget)
							</div>
						@endif
					@endif
				@endforeach
			@endif		
			</div>
		</div>
		</div>
	</div>
</div>
</div>