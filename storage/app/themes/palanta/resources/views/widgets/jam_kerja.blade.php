
@if ($jam_kerja) 
<div class="box-def">
	<div class="head-widget l-flex">
		<div class="head-widget-title l-flex">
		<i class="fa fa-clock-o"></i><h1>{{ $judul_widget }}</h1>
		</div>
	</div>
	<div class="widgetbox widget-cat">
		<table class="tableagenda" style="width: 100%;" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th>Hari</th>
								<th>Mulai</th>
								<th>Selesai</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($jam_kerja as $value) 
							<tr>
								<td>{{ $value->nama_hari }}</td>
								@if ($value->status) 
									<td>{{ $value->jam_masuk }}</td>
									<td>{{ $value->jam_keluar }}</td>
								@else 
									<td colspan="2"><span class="label label-danger"> Libur </span></td>
								@endif
							</tr>
							@endforeach
						</tbody>
					</table>
	</div>
</div>
@endif