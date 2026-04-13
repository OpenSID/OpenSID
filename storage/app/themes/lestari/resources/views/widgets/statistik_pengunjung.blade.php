@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<table style="width: 100%;">
			<tr>
				<td style="width:45% !important;">Hari ini</td><td style="width:10px;text-align:center;">:</td><td>{{ number_format($statistik_pengunjung['hari_ini']) }}</td>
			</tr>
			<tr>
				<td style="width:45% !important;">Kemarin</td><td style="width:10px;text-align:center;">:</td><td>{{ number_format($statistik_pengunjung['kemarin']) }}</td>
			</tr>
			<tr>
				<td style="width:45% !important;">Total Pengunjung</td><td style="width:10px;text-align:center;">:</td><td>{{ number_format($statistik_pengunjung['total']) }}</td>
			</tr>
			<tr>
				<td style="width:45% !important;">OS Anda</td><td style="width:10px;text-align:center;">:</td><td>{{ $statistik_pengunjung['os'] }}</td>
			</tr>
			<tr>
				<td style="width:45% !important;">IP Address anda</td><td style="width:10px;text-align:center;">:</td><td>{{ $statistik_pengunjung['ip_address'] }}</td>
			</tr>
			<tr>
				<td style="width:45% !important;">Browser anda</td><td style="width:10px;text-align:center;">:</td><td>{{ $statistik_pengunjung['browser'] }}</td>
			</tr>
</table>

