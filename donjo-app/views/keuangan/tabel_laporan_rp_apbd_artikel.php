<table width="100%" style="border: solid 0px black; text-align: center;">
	<tr>
		</td>
		<td>
			<h4>LAPORAN REALISASI PELAKSANAAN</h4>
			<h4>ANGGARAN PENDAPATAN DAN BELANJA DESA</h4>
			<h4>PEMERINTAH <?= strtoupper(ucwords($this->setting->sebutan_desa))?> <?= strtoupper($desa['nama_desa'])?></h4>
			<?php if (! empty($sm)): ?>
			<h4>SEMESTER <?= $sm ?></h4>
			<?php endif; ?>
			<h4>TAHUN ANGGARAN <?= $ta ?></h4>
		</td>
	</tr>
</table>

<?php include 'donjo-app/views/keuangan/tabel_laporan_rp_apbd_isi.php'; ?>
