
<div class="box-def">
	<div class="head-widget l-flex">
		<div class="head-widget-title l-flex">
		<i class="fa fa-map-marker"></i><h1>{{ $judul_widget }}</h1>
		</div>
	</div>
	<div class="widgetbox widget-cat">
		<div id="map_canvas" class="maphome"></div>
		<div class="c-flex" style="margin-top:5px;">
			<button class="btn btn-primary btn-sm" style="margin:0 2px;"><a href="https://www.openstreetmap.org/#map=15/{{$data_config['lat']."/".$data_config['lng'] }}" style="color:#fff;" rel="noopener noreferrer" target="_blank">Buka Peta</a></button>
			<div class="panelarrow btn btn-danger btn-sm" data-toggle="collapse" data-target="#collapse2" aria-expanded="false">Detail</div>
		</div>
		<div id="collapse2" class="panel-collapse collapse">
			<div class="panelopen">
				<table width="100%" class="tableagenda">
					<tr>
						<td>Alamat</td><td width="20px">:</td><td>{{$desa['alamat_kantor'] }}</td>
					</tr>
					<tr>
						<td>{{ucwords($setting->sebutan_desa)." "}}</td><td width="20px">:</td><td>{{$desa['nama_desa'] }}</td>
					</tr>
					<tr>
						<td>{{ucwords($setting->sebutan_kecamatan)}}</td><td width="20px">:</td><td>{{$desa['nama_kecamatan'] }}</td>
					</tr>
					<tr>
						<td>{{ucwords($setting->sebutan_kabupaten)}}</td><td width="20px">:</td><td>{{$desa['nama_kabupaten'] }}</td>
					</tr>
					<tr>
						<td>Kodepos</td><td width="20px">:</td><td>{{$desa['kode_pos'] }}</td>
					</tr>
					<tr>
						<td>Telepon</td><td width="20px">:</td><td>{{$desa['telepon'] }}</td>
					</tr>
					<tr>
						<td>Email</td><td width="20px">:</td><td>{{$desa['email_desa'] }}</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>


<script>
	//Jika posisi kantor desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
	@if (!empty($data_config['lat']) && !empty($data_config['lng']))
		var posisi = [{{$data_config['lat'].",".$data_config['lng'] }}];
		var zoom = {{$data_config['zoom'] ?: 10}};
	@else
		var posisi = [-1.0546279422758742,116.71875000000001];
		var zoom = 10;
	@endif

	var lokasi_kantor = L.map('map_canvas').setView(posisi, zoom);

	//Menampilkan BaseLayers Peta
	var baseLayers = getBaseLayers(lokasi_kantor, '{{ $setting->mapbox_key');

	L.control.layers(baseLayers, null, {position: 'topright', collapsed: true}).addTo(lokasi_kantor);

	//Jika posisi kantor desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
	@if (!empty($data_config['lat']) && !empty($data_config['lng']))
		var kantor_desa = L.marker(posisi).addTo(lokasi_kantor);
	@endif
</script>