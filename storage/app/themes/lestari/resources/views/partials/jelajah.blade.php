	
<div class="section-module jelajah">
<div class="margin-page">
	<div class="jelajah-grid">
		<div class="jelajah-left mt-20 flex-left">
			<div>
			<h1>Jelajahi {{ ucwords(setting('sebutan_desa')) }}</h1>
			<div class="jelajah-search">
				<img src="{{ theme_asset('images/jelajah.png') }}"/>
			</div>
			<p>Melalui website ini Anda dapat menjelajahi segala hal yang terkait dengan {{ ucwords(setting('sebutan_desa')) }} {{ ucwords($desa['nama_desa']) }}, {{ ucwords(setting('sebutan_kecamatan')." ".$desa['nama_kecamatan']) }}, {{ ucwords(setting('sebutan_kabupaten')." ".$desa['nama_kabupaten']) }}. Seperti Lapak {{ ucwords(setting('sebutan_desa')) }}, Informasi Publik, Produk Hukum, Daftar Pemilih, Aspek Pemerintahan, Statistik Penduduk, Demografi, Data Pembangunan, Indek Desa Membangun, SDGs {{ ucwords(setting('sebutan_desa')) }}, Data Bantuan, Artikel & Berita, Layanan Kependudukan & Pencatatan Sipil, Layanan Pengaduan, dan lainnya tentang {{ ucwords(setting('sebutan_desa')) }}.</p>
			</div>
		</div>
		<div class="jelajah-right mt-20">
			<div class="row">
				<div class="col-lg-6 col-sm-6 col-xs-6">
					<div class="hover-effect">
					<a href="{{ site_url('lapak') }}">
					<div class="jelajah-box align-center box-shadow">
						<img src="{{ theme_asset('images/lapak.png') }}"/>
						<h3>Lapak {{ ucwords(setting('sebutan_desa')) }}</h3>
					</div>
					</a>
					</div>
				</div>
				<div class="col-lg-6 col-sm-6 col-xs-6">
					<div class="hover-effect">
					<a href="{{ site_url('first/dpt') }}">
					<div class="jelajah-box jelajah2 align-center box-shadow">
						<img src="{{ theme_asset('images/dpt.png') }}"/>
						<h3>Cek DPT Online</h3>
					</div>
					</a>
					</div>
				</div>
				<div class="col-lg-6 col-sm-6 col-xs-6">
					<div class="hover-effect">
					<a href="{{ site_url('peraturan-desa') }}">
					<div class="jelajah-box jelajah3 align-center box-shadow">
						<img src="{{ theme_asset('images/produk-hukum.png') }}"/>
						<h3>Produk Hukum</h3>
					</div>
					</a>
					</div>
				</div>
				<div class="col-lg-6 col-sm-6 col-xs-6">
					<div class="hover-effect">
					<a href="{{ site_url('informasi-publik') }}">
					<div class="jelajah-box jelajah4 align-center box-shadow">
						<img src="{{ theme_asset('images/informasi-publik.png') }}"/>
						<h3>Informasi Publik</h3>
					</div>
					</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>