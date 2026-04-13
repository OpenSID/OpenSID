@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="widget-column">
	<div class="box-shadow brd-10">
	<div class="widget-padding">
		<div class="head-module">
			<h1>{{ $judul_widget }}</h1>
		</div>
		<div class="w-arsip">
			<ul role="tablist" class="nav nav-tabs" style="border:none;">
				<li class="difle-c active" role="presentation" style="width:50%;"><a style="width:100%;" class="difle-c" data-toggle="tab" role="tab" aria-controls="home" href="#terkini">Terbaru</a></li>
				<li class="difle-c" role="presentation" style="width:50%;"><a style="width:100%;" class="difle-c" data-toggle="tab" role="tab" aria-controls="messages" href="#populer">Populer</a></li>
			</ul>
			<div class="tab-content">
				@foreach (['terkini' => 'arsip_terkini', 'populer' => 'arsip_populer', 'acak' => 'arsip_acak'] as $jenis => $jenis_arsip)
					<div id="{{ $jenis }}" class="tab-pane fade in @if ($jenis == 'terkini') active @endif" role="tabpanel">
						<div id="wrapper">
							<div class="colscroll">
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
							</div>
						</div>
					</div>
				@endforeach	
			</div>
		</div>
	</div>
	</div>
</div>