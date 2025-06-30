<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="archive_style_1">
	<div class="single_bottom_rightbar">
		<h2 class="box-title">
			<i class="fa fa-bar-chart-o"></i>&ensp;<?= $judul_widget ?>
		</h2>
		<div class="data-case-container">
			<ul class="ants-right-headline">
				<li class="info-case">
					<table style="width: 100%;" cellpadding="0" cellspacing="0" class="table table-striped table-inverse counter" >
						<tr>
							<td class="description">Hari ini</td><td class="dot">:</td><td class="case"><?= ribuan($statistik_pengunjung['hari_ini']); ?></td>
						</tr>
						<tr>
							<td class="description">Kemarin</td><td class="dot">:</td><td class="case"><?= ribuan($statistik_pengunjung['kemarin']); ?></td>
						</tr>
						<tr>
							<td class="description">Total Pengunjung</td><td class="dot">:</td><td class="case"><?= ribuan($statistik_pengunjung['total']); ?></td>
						</tr>
						<tr>
							<td class="description">Sistem Operasi</td><td class="dot">:</td><td class="case"><?= $statistik_pengunjung['os']; ?></td>
						</tr>
						<tr>
							<td class="description">IP Address</td><td class="dot">:</td><td class="case"><?= $statistik_pengunjung['ip_address']; ?></td>
						</tr>
						<tr>
							<td class="description">Browser</td><td class="dot">:</td><td class="case"><?= $statistik_pengunjung['browser']; ?></td>
						</tr>
					</table>
				</li>
			</ul>
		</div>
	</div>
</div>