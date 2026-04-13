
<div class="section-module mandiri">
<div class="margin-page box-shadow brd-10">
	<div class="mandiri brd-10">
	<div class="mandiri-grid">
		<div class="mandiri-left">
			<div class="mandiri-top">
				<h1>Layanan<br/>Mandiri</h1>
			</div>
			<div class="mandiri-top">
				<h2>{{ ucwords(setting('sebutan_desa')) }} {{ ucwords($desa['nama_desa']) }}</h2>
				<p>{{ ucwords(setting('sebutan_kecamatan')." ".$desa['nama_kecamatan']) }}, {{ ucwords(setting('sebutan_kabupaten')." ".$desa['nama_kabupaten']) }}</p>
			</div>
		</div>
		<div class="mandiri-right">
			<div class="image-mandiri imagefull">
				<img src="{{ $latar_website }}"/>
			</div>
			<div class="mandiri-login flex-center">
				<div>
				<a href="{{ site_url('layanan-mandiri/masuk') }}" rel="noopener noreferrer" target="_blank"><div class="btn btn-warning">Login</div></a>
				<p>Hubungi Perangkat {{ ucwords(setting('sebutan_desa')) }}<br/>Untuk Mendapatkan PIN Layanan Mandiri</p>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
</div>	