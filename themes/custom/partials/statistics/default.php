<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script>
	const dataStats = Object.values(<?= json_encode($stat) ?>);
</script>

<h2 class="content__heading">Demografi Berdasar <?= $heading ?></h2>
<hr class="--mt-2 --mb-2">
<section class="content__article">
	<div class="--flex --items-center --justify-between --mt-2 --mb-4">
		<h3 class="content__title">Grafik <?= $heading ?></h3>
		<div class="content__group">
			<button class="button button--primary button__switch" data-type="column">Bar Graph</button>
			<button class="button button--primary button__switch button__switch--active" data-type="pie">Pie Graph</button>
		</div>
	</div>
	<div id="statistics__graph"></div>
	<h3 class="content__title --mt-4 --mb-2">Tabel <?= $heading ?></h3>
	<div class="table">
		<table>
			<thead>
				<tr>
					<th rowspan="2">No</th>
					<th rowspan="2">Kelompok</th>
					<th colspan="2">Jumlah</th>
					<?php if($jenis_laporan == 'penduduk'): ?>
						<th colspan="2">Laki-laki</th>
						<th colspan="2">Perempuan</th>
					<?php endif; ?>
				</tr>
				<tr>
					<th>n</th>
					<th>%</th>
					<?php if($jenis_laporan == 'penduduk'):?>
						<th>n</th>
						<th>%</th>
						<th>n</th>
						<th>%</th>
					<?php endif ?>
				</tr>
			</thead>
			<tbody>
				<?php $i=0; $l=0; $p=0; $hide=""; $h=0; $jm1=1; $jm = count($stat);?>
				<?php foreach ($stat as $data):?>
					<?php $jm1++; if (1):?>
					<?php $h++; if ($h > 12 AND $jm > 10): $hide="lebih"; ?>
					<?php endif;?>
					<tr class="<?=$hide?>">
						<td>
							<?php if ($jm1 > $jm - 2):?>
								<?=$data['no']?>
							<?php else:?>
								<?=$h?>
							<?php endif;?>
						</td>
						<td><?=$data['nama']?></td>
						<td class="<?php ($jm1 <= $jm - 2) and ($data['jumlah'] == 0) and print('nol')?>"><?=$data['jumlah']?>
						</td>
						<td><?=$data['persen']?></td>
						<?php if ($jenis_laporan == 'penduduk'):?>
							<td><?=$data['laki']?></td>
							<td><?=$data['persen1']?></td>
							<td><?=$data['perempuan']?></td>
							<td><?=$data['persen2']?></td>
						<?php endif;?>
					</tr>
					<?php $i += $data['jumlah'];?>
					<?php $l += $data['laki']; $p += $data['perempuan'];?>
					<?php endif;?>
				<?php endforeach;?>
			</tbody>
		</table>
		<div class="--mt-2 --mb-2 --flex --justify-between">
			<button class="button button--primary" id='showData'>Selengkapnya...</button>
			<button id="tampilkan" onclick="showHideToggle();" class="button button--primary">Tampilkan Nol</button>
		</div>
	</div>
	<?php if (in_array($st, array('bantuan_keluarga', 'bantuan_penduduk'))):?>
		<script>
		const bantuanUrl = '<?= site_url('first/ajax_peserta_program_bantuan')?>';
		</script>
		<h3 class="content__title --mt-4 --mb-2">Daftar <?= $heading ?></h3>
		<input id="stat" type="hidden" value="<?=$st?>">
		<div class="table">
			<table class="table table-striped table-bordered" id="peserta_program">
				<thead>
					<tr>
						<th>No</th>
						<th>Program</th>
						<th>Nama Peserta</th>
						<th>Alamat</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	<?php endif;?>
</section>