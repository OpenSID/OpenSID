@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="section-module footstyle">
<div class="margin-head">
	<div class="footer mt-20">
		<div class="footer-inner">
		<div class="row">
			<div class="col-lg-4 col-sm-12 footer-left">
			<div class="footer-column">
				<div class="footer-left-grid">
					<div class="footer-left-logo">
						<img class="trans-h" src="{{ gambar_desa($desa['logo']) }}"/>
					</div>
					<div class="footer-left-title">
						<h2>Pemerintah<br/>{{ ucwords(setting('sebutan_desa')) }} {{ ucwords($desa['nama_desa']) }}</h2>
					</div>
				</div>
				<p>Kantor :<br/>{{ $desa['alamat_kantor'] }}<br/>{{ ucwords(setting('sebutan_kecamatan')." ".$desa['nama_kecamatan']) }}<br/>{{ ucwords(setting('sebutan_kabupaten')." ".$desa['nama_kabupaten']) }}</p>
			</div>
			</div>
			<div class="col-lg-4 col-sm-12 footer-center">
			<div class="footer-column">
				<h2>Kontak :</h2>
				<a rel="nofollow" href="https://api.whatsapp.com/send?phone={{ $desa['telepon'] }}&text=Saya ingin bertanya sesuatu" target="blank">
				<div class="contact-foot flex-left">
					<svg viewBox="0 0 24 24"><path d="M15,12H17A5,5 0 0,0 12,7V9A3,3 0 0,1 15,12M19,12H21C21,7 16.97,3 12,3V5C15.86,5 19,8.13 19,12M20,15.5C18.75,15.5 17.55,15.3 16.43,14.93C16.08,14.82 15.69,14.9 15.41,15.18L13.21,17.38C10.38,15.94 8.06,13.62 6.62,10.79L8.82,8.59C9.1,8.31 9.18,7.92 9.07,7.57C8.7,6.45 8.5,5.25 8.5,4A1,1 0 0,0 7.5,3H4A1,1 0 0,0 3,4A17,17 0 0,0 20,21A1,1 0 0,0 21,20V16.5A1,1 0 0,0 20,15.5Z" /></svg>
					@if (!empty($desa['telepon']))
						<p style="margin:0 0 0 5px;">{{ $desa['telepon'] }}</p>
					@else
						<p style="margin:0 0 0 5px;">-</p>	
					@endif
				</div>
				</a>
				
				<div class="contact-foot flex-left">
					<svg viewBox="0 0 24 24"><path d="M21,12.13C20.85,12.14 20.71,12.19 20.61,12.3L19.61,13.3L21.66,15.3L22.66,14.3C22.88,14.09 22.88,13.74 22.66,13.53L21.42,12.3C21.32,12.19 21.18,12.14 21.04,12.13M19.04,13.88L13,19.94V22H15.06L21.12,15.93M20,4H4A2,2 0 0,0 2,6V18A2,2 0 0,0 4,20H11V19.11L19.24,10.89C19.71,10.4 20.36,10.13 21.04,10.13C21.38,10.13 21.72,10.19 22.04,10.32V6C22.04,4.88 21.12,4 20,4M20,8L12,13L4,8V6L12,11L20,6" /></svg>
					@if (!empty($desa['email_desa']))
						<p style="margin:0 0 0 5px;">{{ $desa['email_desa'] }}</p>
					@else
						<p style="margin:0 0 0 5px;">-</p>	
					@endif
				</div>
				
				<div class="flex-left">
				@foreach ($sosmed as $data)
					@if (!empty($data['link']))
						<div class="mt-20">
						<a href="{{ $data['link'] }}" rel="noopener noreferrer" target="_blank">
							<img src="{{ $data['icon'] }}" alt="{{ $data['nama'] }}" style="width:26px;height:26px;margin:0 5px 0 0;border:#bdbdbd 1px solid;padding:2px;background:rgba(0,0,0,0.2)" />
						</a>
						</div>
					@endif
				@endforeach
				</div>
			</div>
			</div>
			<div class="col-lg-4 col-sm-12 footer-right">
			<div class="footer-column">
				<h2>Pengunjung :</h2>
				@include('theme::widgets.statistik_pengunjung')
			</div>
			</div>
		</div>
		</div>
	</div>
	<div class="copyright align-center">
		<p><a href="https://github.com/OpenSID/OpenSID" rel="noopener noreferrer" target="_blank">OpenSID {{ AmbilVersi()
            }}</a> - Lestari</p>
	</div>
</div>
</div>