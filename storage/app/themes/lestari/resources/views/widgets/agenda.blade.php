
<div class="widget-column">
	<div class="box-shadow brd-10">
	<div class="widget-padding">
		<div class="head-module">
			<h1>{{ $judul_widget }}</h1>
		</div>
		<div class="colscroll">
			@php
			$merge = array_merge($hari_ini, $yad, $lama);
			@endphp
			@if (count($merge ?? []) > 0)
					<div class="agenda-area mb-20">
					<h2>Terjadwal</h2>
					@if (count($hari_ini ?? []) > 0)
					@foreach ($hari_ini as $agenda)
						<div class="agenda-small">
							<a href="{{ site_url('artikel/'.buat_slug($agenda)) }}">
							<h3>{{ $agenda['judul'] }}</h3>
							<div class="agenda-title" style="margin:0!important;padding:0!important;">
								<table class="table-agenda-mini" style="margin:0!important;padding:0!important;">
									<tr>
										<td>Tanggal</td><td style="text-align:center;width:15px;">:</td><td>{{ tgl_indo2($agenda['tgl_agenda']) }}</td>
									</tr>
									<tr>
										<td>Jam</td><td style="text-align:center;width:15px;">:</td><td>{{ date('H:i:s',strtotime($agenda['tgl_agenda'])) }}</td>
									</tr>
									<tr>
										<td>Tempat</td><td style="text-align:center;width:15px;">:</td><td>{{ $agenda['lokasi_kegiatan'] }}</td>
									</tr>
								</table>
							</div>
							</a>
						</div>
					@endforeach
					@endif
					@if (count($yad ?? []) > 0)
					@foreach ($yad as $agenda)
						<div class="agenda-small forhover">
							<a href="{{ site_url('artikel/'.buat_slug($agenda)) }}">
							<h3>{{ $agenda['judul'] }}</h3>
							<div class="agenda-title" style="margin:0!important;padding:0!important;">
								<table class="table-agenda-mini" style="margin:0!important;padding:0!important;">
									<tr>
										<td>Tanggal</td><td style="text-align:center;width:15px;">:</td><td>{{ tgl_indo2($agenda['tgl_agenda']) }}</td>
									</tr>
									<tr>
										<td>Jam</td><td style="text-align:center;width:15px;">:</td><td>{{ date('H:i:s',strtotime($agenda['tgl_agenda'])) }}</td>
									</tr>
									<tr>
										<td>Tempat</td><td style="text-align:center;width:15px;">:</td><td>{{ $agenda['lokasi_kegiatan'] }}</td>
									</tr>
								</table>
							</div>
							</a>
						</div>
					@endforeach
					@endif
					</div>

				@if (count($lama ?? []) > 0)
					<div class="agenda-area">
					<h2>Sebelumnya</h2>
					@foreach ($lama as $agenda)
						<div class="agenda-small forhover">
							<a href="{{ site_url('artikel/'.buat_slug($agenda)) }}">
							<h3>{{ $agenda['judul'] }}</h3>
							<div class="agenda-title" style="margin:0!important;padding:0!important;">
								<table class="table-agenda-mini" style="margin:0!important;padding:0!important;">
									<tr>
										<td>Tanggal</td><td style="text-align:center;width:15px;">:</td><td>{{ tgl_indo2($agenda['tgl_agenda']) }}</td>
									</tr>
									<tr>
										<td>Jam</td><td style="text-align:center;width:15px;">:</td><td>{{ date('H:i:s',strtotime($agenda['tgl_agenda'])) }}</td>
									</tr>
									<tr>
										<td>Tempat</td><td style="text-align:center;width:15px;">:</td><td>{{ $agenda['lokasi_kegiatan'] }}</td>
									</tr>
								</table>
							</div>
							</a>
						</div>
					@endforeach
					</div>				
				@endif
			@else
				<div class="row">
							<div class="col-sm-12">
								<div class="empty-small flex-center align-center mt-20">
									<div>
									<img src="{{ theme_asset('images/empty-small.png') }}"/>
									<p>Untuk sementara belum ada Agenda yang tersedia</p>
									</div>
								</div>
							</div>
						</div>	
			@endif
		</div>
	</div>
	</div>
</div>
