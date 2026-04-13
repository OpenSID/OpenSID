
<div class="section-module">
<div class="margin-page">
	<div class="intro-area">
		<div class="intro">
		@foreach ($aparatur_desa['daftar_perangkat'] as $data)
			<div class="intro-inner">
			<div class="intro-grid">
				<div class="intro-left flex-center">
					<div class="image-intro imagefull box-shadow">
					<img src="{{ $data['foto'] }}" alt="{{ $data['nama'] }}">
					</div>
				</div>
				<div class="intro-right">
					<h1>Sambutan {{ $data['jabatan'] }}</h1>
					<h2>{{ $data['nama'] }}</h2>
					<h3>{{ $data['jabatan'] }} {{ ucwords($desa['nama_desa']) }}</h3>
					<div class="intro-desk">Assalamu'alaikum warahmatullahi wabarakatuh.<br/>Selamat Datang di Website {{ ucwords(setting('sebutan_desa')) }} {{ ucwords($desa['nama_desa']) }}. Melalui website ini, kami berupaya menghadirkan informasi secara aktual dan efektif terkait program dan kegiatan pembangunan Pemerintah {{ ucwords(setting('sebutan_desa')) }} {{ ucwords($desa['nama_desa']) }}, {{ ucwords(setting('sebutan_kecamatan')." ".$desa['nama_kecamatan']) }}, {{ ucwords(setting('sebutan_kabupaten')." ".$desa['nama_kabupaten']) }}, {{ ucwords('Prov. ' . $desa['nama_propinsi']) }}.</div>
				</div>
			</div>
			</div>
		@endforeach
		</div>
	</div>
</div>
</div>