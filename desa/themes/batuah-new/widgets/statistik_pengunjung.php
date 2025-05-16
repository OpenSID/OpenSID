<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="widget-box bgwhite bordergrey">
	<div class="widget-head bggrad-color2 flexleft">
	<svg viewBox="0 0 24 24">
		<path d="M3,21H6V18H3M8,21H11V14H8M13,21H16V9H13M18,21H21V3H18V21Z" />
	</svg>
	<h1><?= $judul_widget ?></h1>
	</div>
	<div class="widget-inner">
			<table style="width: 100%;" cellpadding="0" cellspacing="0" class="table-noline" >
				<tr>
					<td>Hari ini</td><td style="width:20px;text-align:center;">:</td><td><?= ribuan($statistik_pengunjung['hari_ini']); ?></td>
				</tr>
				<tr>
					<td>Kemarin</td><td style="width:20px;text-align:center;">:</td><td><?= ribuan($statistik_pengunjung['kemarin']); ?></td>
				</tr>
				<tr>
					<td>Total</td><td style="width:20px;text-align:center;">:</td><td><?= ribuan($statistik_pengunjung['total']); ?></td>
				</tr>
				<tr>
					<td>Sistem Operasi</td><td style="width:20px;text-align:center;">:</td><td><?= $statistik_pengunjung['os']; ?></td>
				</tr>
				<tr>
					<td>IP Address</td><td style="width:20px;text-align:center;">:</td><td><?= potong_teks ($statistik_pengunjung['ip_address'], 15); ?></td>
				</tr>
				<tr>
					<td>Browser</td><td style="width:20px;text-align:center;">:</td><td><?= $statistik_pengunjung['browser']; ?></td>
				</tr>
			</table>
	</div>
</div>