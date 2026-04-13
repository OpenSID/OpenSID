
<div class="runtext">
<div class="margin-page">
	<div class="runtext-inner box-shadow">
		<div class="runtext-head flex-left">
			<span class="desk-v" style="margin-right:5px;">Sekilas</span>Info
		</div>
		@if (!empty($teks_berjalan))
			<marquee onmouseover="this.stop()" onmouseout="this.start()">
				@foreach ($teks_berjalan as $teks)
					<span class="run-isi">
					{{ $teks['teks'] }}
					@if ($teks['tautan'])
						<a href="{{ $teks['tautan'] }}" rel="noopener noreferrer" title="Baca Selengkapnya">{{ $teks['judul_tautan'] }}</a>
					@endif
					</span>
				@endforeach
			</marquee>
		@else
			<marquee onmouseover="this.stop()" onmouseout="this.start()">
				Selamat datang di {{ ucwords($setting->website_title) }} {{ ucwords(setting('sebutan_desa')) }} {{ ucwords($desa['nama_desa']) }}, {{ ucwords(setting('sebutan_kecamatan')." ".$desa['nama_kecamatan']) }}, {{ ucwords(setting('sebutan_kabupaten')." ".$desa['nama_kabupaten']) }}
			</marquee>
		@endif
	</div>	
</div>
</div>