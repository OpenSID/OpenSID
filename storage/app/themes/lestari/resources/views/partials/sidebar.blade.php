@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="sidebar">
	<div class="head-module">
		<h2>Terbaru</h2>
	</div>
	@foreach (['terkini' => 'arsip_terkini'] as $jenis => $jenis_arsip)
		@foreach ($$jenis_arsip as $arsip)
			<a href="{{ site_url('artikel/' . buat_slug($arsip)) }}">
			<div class="arsipsmall">
				<div class="arsip-grid">
					<div class="arsipsmall-image">
						<div class="image-small imagefull">
						@if (is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip['gambar']))
							<img src="{{ base_url(LOKASI_FOTO_ARTIKEL.'sedang_'.$arsip['gambar']) }}"/>
						@else
							<img src="{{ theme_asset('images/no-image.jpg') }}"/>
						@endif
						</div>
					</div>
					<div class="arsipsmall-text">
						<p><font class="color1">{{ hit($arsip['hit']) }} dibuka</font><br/>{{ $arsip['judul'] }}</p>
					</div>
				</div>	
			</div>
			</a>
		@endforeach	
	@endforeach		
</div>							
