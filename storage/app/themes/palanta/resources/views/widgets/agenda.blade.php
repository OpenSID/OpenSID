<div class="box-def">
	<div class="head-widget l-flex">
		<div class="head-widget-title l-flex">
		<i class="fa fa-calendar"></i><h1>{{ $judul_widget }}</h1>
		</div>
	</div>
	<div class="widgetbox">
		<div class="tabs3">
			<input type="radio" id="tabb1" name="tab-control3" checked>
			<input type="radio" id="tabb2" name="tab-control3">
			<ul>
				<li><label for="tabb1" role="button" class="c-flex"><span>Dijadwalkan</span></label></li>
				<li><label for="tabb2" role="button" class="c-flex"><span>Sebelumnya</span></label></li>
			</ul>
			<div class="content3">
				<section>
					@if (array_merge($hari_ini, $yad) !== [])
						@if (count($hari_ini ?? []) > 0)
							@foreach ($hari_ini as $agenda)
								<table width="100%" class="tableagenda">
									<tr>
										<th colspan="3" style="padding:10px 0 3px !important;"><a href="{{ $agenda['url_slug'] }}">{{ $agenda['judul'] }}</a></th>
									</tr>
									<tr>
										<td>Waktu</td><td width="20px">:</td><td>{{ tgl_indo2($agenda['tgl_agenda'])}}</td>
									</tr>
									<tr>
										<td>Lokasi</td><td width="20px">:</td><td>{{ $agenda['lokasi_kegiatan'] }}</td>
									</tr>
									<tr>
										<td>Koordinator</td><td width="20px">:</td><td>{{ $agenda['koordinator_kegiatan'] }}</td>
									</tr>
								</table>
							@endforeach
						@endif	
						@if (count($yad ?? []) > 0)
							@foreach ($yad as $agenda)
								<table width="100%" class="tableagenda">
									<tr>
										<th colspan="3" style="padding:10px 0 3px !important;"><a href="{{ ci_route('artikel/'.buat_slug($agenda))}}">{{ $agenda['judul'] }}</a></th>
									</tr>
									<tr>
										<td>Waktu</td><td width="20px">:</td><td>{{ tgl_indo2($agenda['tgl_agenda'])}}</td>
									</tr>
									<tr>
										<td>Lokasi</td><td width="20px">:</td><td>{{ $agenda['lokasi_kegiatan'] }}</td>
									</tr>
									<tr>
										<td>Koordinator</td><td width="20px">:</td><td>{{ $agenda['koordinator_kegiatan'] }}</td>
									</tr>
								</table>
							@endforeach
						@endif
						@else 
						<div class="l-flex" style="padding:15px 10px;text-align:center;font-size:95%;"><p>Untuk sementara belum ada Data Agenda yang dapat ditampilkan</p></div>
					@endif
				</section>
				<section>
					@if (count($lama ?? []) > 0)
						@foreach ($lama as $agenda)
							<table width="100%" class="tableagenda">
								<tr>
									<th colspan="3" style="padding:10px 0 3px !important;"><a href="{{ $agenda['url_slug'] }}">{{ $agenda['judul'] }}</a></th>
								</tr>
								<tr>
									<td>Waktu</td><td width="20px">:</td><td>{{ tgl_indo2($agenda['tgl_agenda'])}}</td>
								</tr>
								<tr>
									<td>Lokasi</td><td width="20px">:</td><td>{{ $agenda['lokasi_kegiatan'] }}</td>
								</tr>
								<tr>
									<td>Koordinator</td><td width="20px">:</td><td>{{ $agenda['koordinator_kegiatan'] }}</td>
								</tr>
							</table>
						@endforeach
						@else 
						<div class="l-flex" style="padding:15px 10px;text-align:center;font-size:95%;"><p>Untuk sementara belum ada Data Agenda yang dapat ditampilkan</p></div>
					@endif
				</section>
			</div>
		</div>
	</div>
</div>