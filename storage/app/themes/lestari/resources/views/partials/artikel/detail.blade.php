@extends('theme::layouts.' . $layout)

@section('content')
	@include('theme::partials.header')
	<div class="contentpage">
		<div class="margin-page">
		    @if ($single_artikel["id"])
			@include('theme::commons.asset_highcharts')
				<div class="grid-column">
					<div class="grid-main">
						<div class="grid-main-inner">
							 <div class="breadcrumb-article">
								<div class="flex-left">
									<svg viewBox="0 0 24 24"><path d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z" /></svg>
									@if (trim($single_artikel['kategori']) != '')
										{{ $single_artikel['kategori'] }}
									@elseif ($single_artikel['tipe'] == 'agenda')
										Agenda
									@else
										{{ $title }}
									@endif
								</div>
							</div>
							<div class="head-module">
								<h1>{{ $single_artikel["judul"] }}</h1>
							</div>
							<div class="metapost flex-left mt-20">
								<div class="meta flex-left">
									<div class="meta-item flex-left"><svg viewBox="0 0 24 24"><path d="M19 3H18V1H16V3H8V1H6V3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H9V19H5V9H19V19H15V21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3M5 7V5H19V7H5M12 12L8 16H11V22H13V16H16L12 12Z" /></svg><p>{{ $single_artikel['tgl_upload_local'] }}</p></div>
									<div class="meta-item flex-left"><svg viewBox="0 0 24 24"><path d="M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C12.36,19.5 12.72,19.5 13.08,19.45C13.03,19.13 13,18.82 13,18.5C13,17.94 13.08,17.38 13.24,16.84C12.83,16.94 12.42,17 12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12C17,12.29 16.97,12.59 16.92,12.88C17.58,12.63 18.29,12.5 19,12.5C20.17,12.5 21.31,12.84 22.29,13.5C22.56,13 22.8,12.5 23,12C21.27,7.61 17,4.5 12,4.5M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M18,14.5V17.5H15V19.5H18V22.5H20V19.5H23V17.5H20V14.5H18Z" /></svg><p>{{ hit($single_artikel['hit']) }} Dibaca</p></div>
								</div>	
								<div class="meta">	
									<div class="meta-item flex-left"><svg viewBox="0 0 24 24"><path d="M21.5 9H16.5L18.36 7.14C16.9 5.23 14.59 4 12 4C7.58 4 4 7.58 4 12C4 13.83 4.61 15.5 5.64 16.85C6.86 15.45 9.15 14.5 12 14.5C14.85 14.5 17.15 15.45 18.36 16.85C19.39 15.5 20 13.83 20 12H22C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C15.14 2 17.95 3.45 19.78 5.72L21.5 4V9M12 20C13.9 20 15.64 19.34 17 18.24C16.36 17.23 14.45 16.5 12 16.5C9.55 16.5 7.64 17.23 7 18.24C8.36 19.34 10.1 20 12 20M12 6C13.93 6 15.5 7.57 15.5 9.5C15.5 11.43 13.93 13 12 13C10.07 13 8.5 11.43 8.5 9.5C8.5 7.57 10.07 6 12 6M12 8C11.17 8 10.5 8.67 10.5 9.5C10.5 10.33 11.17 11 12 11C12.83 11 13.5 10.33 13.5 9.5C13.5 8.67 12.83 8 12 8Z" /></svg><p>{{ $single_artikel['owner'] }}</p></div>
								</div>
							</div>
							@include('theme::partials.artikel.image')

							@if ($single_artikel['tipe'] == 'agenda')
								<div class="mt-20">
									<table class="table table-agenda table-striped" style="width:100%;">
										<div class="head-sub">Detail Agenda</div>
										<tr>
											<td class="table-label"><p>Tanggal</p></td><td style="text-align:center;width:20px;"><p>:</p></td><td><p><b>{{ date('d M Y',strtotime($detail_agenda['tgl_agenda'])) }}</b></p></td>
										</tr>
										<tr>
											<td><p>Waktu</p></td><td style="text-align:center;width:20px;"><p>:</p></td><td><p><b>{{ date('H:i:s',strtotime($detail_agenda['tgl_agenda'])) }}</b></p></td>
										</tr>
										<tr>
											<td><p>Koordinator</p></td><td style="text-align:center;width:20px;"><p>:</p></td><td><p><b>{{ $detail_agenda['koordinator_kegiatan'] }}</b></p></td>
										</tr>
										<tr>
											<td><p>Tempat</p></td><td style="text-align:center;width:20px;"><p>:</p></td><td><p><b>{{ $detail_agenda['lokasi_kegiatan'] }}</b></p></td>
										</tr>
									</table>	
								</div>
							@endif
							@if ($single_artikel["isi"])
								<div class="content-isi mt-20">
									{!! $single_artikel["isi"] !!}
								</div>
							@endif
							<div class="flex-center mt-20" style="width:100%;">
								@php
								$share = [
								'link' => $single_artikel['url_slug'],
								'judul' => htmlspecialchars($single_artikel["judul"]),
								];
								@endphp

								@include('theme::commons.share', $share)
							</div>
							@include('theme::partials.artikel.comment')
						</div>
					</div>	
				
					<div class="grid-side">
						<div class="grid-fix">
							@include('theme::partials.sidebar')
						</div>
					</div>
				</div>
			@else
				@include('theme::partials.artikel.empty')
			@endif	
		</div>
		@include('theme::partials.modulepage')
		@include('theme::partials.footer')
	</div>
@endsection
