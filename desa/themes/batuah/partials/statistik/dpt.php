<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="stat-inner">
	<div class="stat-title flexcenter">
		<div>
			<h1 style="margin-bottom:10px;">Daftar Pemilih Tetap</h1>
			<h2>Per Hari Ini (<?= $tanggal_pemilihan ?>)</h2>
		</div>
	</div>
	<div class="table-responsive">
		<table id="dpt" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th class="text-center">No</th>
					<th class="text-center">Nama Dusun</th>
					<th class="text-center">RW</th>
					<th class="text-center">Jiwa</th>
					<th class="text-center">L</th>
					<th class="text-center">P</th>
				</tr>
			</thead>
			<?php
			if (count($main ?? []) > 0) { ?>
				<tbody>
					<?php foreach ($main as $data) { ?>
						<tr>
							<td class="text-center"><?= $data["no"] ?></td>
							<td class="text-left"><?= strtoupper($data["dusun"]) ?></td>
							<td class="text-center"><?= strtoupper($data["rw"]) ?></td>
							<td class="text-center"><?= $data["jumlah_warga"] ?></td>
							<td class="text-center"><?= $data["jumlah_warga_l"] ?></td>
							<td class="text-center"><?= $data["jumlah_warga_p"] ?></td>
						</tr>
					<?php
					} ?>
				</tbody>
				<tr>
					<th colspan="3" class="text-right">TOTAL</th>
					<th class="text-center"><?= $total["total_warga"] ?></th>
					<th class="text-center"><?= $total["total_warga_l"] ?></th>
					<th class="text-center"><?= $total["total_warga_p"] ?></th>
				</tr>
			<?php
			} else { ?>
				<div class="no-found-small flexcenter">
					<img src="<?= base_url("$this->theme_folder/$this->theme/images/nodata.png") ?>" />
					<p>Untuk sementara data Daftar Pemilih belum tersedia</p>
				</div>
			<?php
			} ?>
		</table>
	</div>
</div>