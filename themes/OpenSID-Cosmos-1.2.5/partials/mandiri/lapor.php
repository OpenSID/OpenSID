<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="stat">
	<h2 class="judul-artikel mb-3">LAPORAN PENDUDUK</h2>

	<!-- Tampilkan hanya jika 'flash_message' ada -->
	<?php $label = !empty($_SESSION['validation_error']) ? 'alert-danger' : 'alert-success'; ?>
	<?php if ($flash_message): ?>
		<div class="alert <?= $label?>"><?= $flash_message?></div>
	<?php endif; ?>

	<p class='text-danger'><i class='fa fa-comments'></i> Silahkan laporkan perubahan data kependudukan anda.</p>

	<form id="validasi" action="<?= site_url('lapor/insert') ?>" method="POST" onSubmit="return validasi(this);">
	<div class='form-group'>
		<label>Pengirim</label>
		<input class="form-control" type="text" readonly="readonly" name="owner" value="<?= $_SESSION['nama']?>"/>
	</div>
	<div class='form-group'>
		<label>NIK</label>
		<input class="form-control" type="text" readonly="readonly" name="email" value="<?= $_SESSION['nik']?>"/>
	</div>
	<div class='form-group'>
		<label>Laporan</label>
		<textarea name="komentar" rows="10" class='form-control'></textarea>
	</div>
		<button class='btn btn-success' type="submit">Kirim Laporan</button>
	</form>
</div>
