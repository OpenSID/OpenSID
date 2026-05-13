
<div class="box-def">
	<div class="head-widget l-flex">
		<div class="head-widget-title l-flex">
		<i class="fa fa-bar-chart"></i><h1>{{ $judul_widget }}</h1>
		</div>
	</div>
	<div class="widgetbox widget-cat">
		<table width="100%" class="tableagenda">
			<tr>
				<td>Hari ini</td><td width="20px">:</td><td>{{ ribuan($statistik_pengunjung['hari_ini']) }}</td>
			</tr>
			<tr>
				<td>Kemarin</td><td width="20px">:</td><td>{{ ribuan($statistik_pengunjung['kemarin']) }}</td>
			</tr>
			<tr>
				<td>Total</td><td width="20px">:</td><td>{{ ribuan($statistik_pengunjung['total']) }}</td>
			</tr>
			<tr>
				<td>Sistem Operasi</td><td width="20px">:</td><td>{{ $statistik_pengunjung['os'] }}</td>
			</tr>
			<tr>
				<td>IP Address</td><td width="20px">:</td><td>{{ $statistik_pengunjung['ip_address'] }}</td>
			</tr>
			<tr>
				<td>Browser</td><td width="20px">:</td><td>{{ $statistik_pengunjung['browser'] }}</td>
			</tr>
		</table>
	</div>
</div>

