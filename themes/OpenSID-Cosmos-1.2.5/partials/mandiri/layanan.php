<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="stat">
	<h2 class="judul-artikel">DAFTAR REKAM CETAK SURAT</h2>

	<div class="table-responsive mt-3 modul-mandiri">
		<table class="table table-bordered table-striped">
			<thead>
			<tr>
				<th class='fit'>NO</th>
				<th>NOMOR SURAT</th>
				<th>JENIS SURAT</th>
				<th>NAMA STAF</th>
				<th>TANGGAL</th>
			</tr>
			</thead>
			<tbody>
			<?php
			if (count($surat_keluar) > 0) {
				foreach($surat_keluar as $data): ?>
				<tr>
					<td class='text-center fit'><?php echo $data['no']?></td>
					<td><?php echo $data['no_surat']?></td>
					<td><?php echo $data['format']?></td>
					<td><?php echo $data['pamong']?></td>
					<td><?php echo tgl_indo2($data['tanggal'])?></td>
				</tr>
			<?php
				endforeach;
			} else {?>
				<tr><td colspan='5' class='text-center'>Daftar masih kosong</td></tr>
			<?php
			} ?>
			</tbody>
		</table>
	</div>
</div>