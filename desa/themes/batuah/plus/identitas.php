<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="withscroll-padding identitas">
	<table class="table-noline" width="100%">
		<tr>
			<td>Nama <?= ucwords($this->setting->sebutan_desa); ?></td>
			<td style="width:10px;text-align:center;">:</td>
			<td><b><?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></b></td>
		</tr>
		<tr>
			<td>Kode <?= ucwords($this->setting->sebutan_desa); ?></td>
			<td style="width:10px;text-align:center;">:</td>
			<td><?= ucwords(($desa['kode_desa']) ? ' ' . $desa['kode_desa'] : ''); ?></td>
		</tr>
		<tr>
			<td><?= ucwords($this->setting->sebutan_kecamatan); ?></td>
			<td style="width:10px;text-align:center;">:</td>
			<td><?= ucwords(($desa['nama_kecamatan']) ? ' ' . $desa['nama_kecamatan'] : ''); ?></td>
		</tr>
		<tr>
			<td>Kode <?= ucwords($this->setting->sebutan_kecamatan); ?></td>
			<td style="width:10px;text-align:center;">:</td>
			<td><?= ucwords(($desa['kode_kecamatan']) ? ' ' . $desa['kode_kecamatan'] : ''); ?></td>
		</tr>
		<tr>
			<td><?= ucwords($this->setting->sebutan_kabupaten); ?></td>
			<td style="width:10px;text-align:center;">:</td>
			<td><?= ucwords(($desa['nama_kabupaten']) ? ' ' . $desa['nama_kabupaten'] : ''); ?></td>
		</tr>
		<tr>
			<td>Kode <?= ucwords($this->setting->sebutan_kabupaten); ?></td>
			<td style="width:10px;text-align:center;">:</td>
			<td><?= ucwords(($desa['kode_kabupaten']) ? ' ' . $desa['kode_kabupaten'] : ''); ?></td>
		</tr>
		<tr>
			<td>Provinsi</td>
			<td style="width:10px;text-align:center;">:</td>
			<td><?= ucwords(($desa['nama_propinsi']) ? ' ' . $desa['nama_propinsi'] : ''); ?></td>
		</tr>
		<tr>
			<td>Kode Provinsi</td>
			<td style="width:10px;text-align:center;">:</td>
			<td><?= ucwords(($desa['kode_propinsi']) ? ' ' . $desa['kode_propinsi'] : ''); ?></td>
		</tr>
		<tr>
			<td>Kode Pos</td>
			<td style="width:10px;text-align:center;">:</td>
			<td><?= ucwords(($desa['kode_pos']) ? ' ' . $desa['kode_pos'] : ''); ?></td>
		</tr>
	</table>
</div>	