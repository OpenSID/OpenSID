<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style type="text/css">
	table.form
	{
		margin-bottom: 10px;
		margin-top: 10px;
	}
</style>
<!-- Tampilkan hanya jika 'flash_message' ada -->
<?php $label = !empty($_SESSION['validation_error']) ? 'label-danger' : 'label-info'; ?>
<?php if ($flash_message): ?>
	<div class="box-header <?= $label?>"><?= $flash_message?></div>
<?php endif; ?>
<div class="artikel">
	<form id="validasi" action="<?= site_url()?>lapor_web/insert" method="POST" enctype="multipart/form-data" onSubmit="return validasi(this);">
		<p>Silakan laporkan perubahan data kependudukan anda.</p>
		<table class="form">
			<tr>
				<th>Pengirim </th>
				<td> <input class="inputbox" type="text" readonly="readonly" name="owner" value="<?= $_SESSION['nama']?>" size="30"/></td>
			</tr>
			<tr>
				<th>NIK</th>
				<td> <input class="inputbox" type="text" readonly="readonly" name="email" value="<?= $_SESSION['nik']?>" size="30"/></td>
			</tr>
			<tr>
				<td>Laporan </td>
				<td>
					<textarea name="komentar" rows="15" cols="80" style="width: 90%; height: 100%" placeholder="Isi laporan anda"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" value="Kirim"> </td>
			</tr>
		</table>
	</form>
</div>
