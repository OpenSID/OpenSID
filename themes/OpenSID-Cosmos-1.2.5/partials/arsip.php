<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h2 class="judul-artikel">
	Arsip Konten Situs Web <?=$desa["nama_desa"]?>
</h2>
<div class="mt-3 arsip stat">
	<div class="table-responsive">
		<?php if(count($farsip)>0): ?>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<td width="3%"><b>No.</b></td>
						<td width="20%"><b>Tanggal Artikel</b></td>
						<td><b>Judul Artikel</b></td>
						<td width="20%"><b>Penulis</b></td>
					</tr>
				</thead>
				<tbody>
				<?php foreach($farsip AS $data): ?>
					<tr>
						<td style="text-align:center;">
							<?= $data["no"]?>
						</td>
						<td>
							<?= tgl_indo($data["tgl_upload"])?>
						</td>
						<td>
							<a href="<?= site_url('first/artikel/'.$data[id])?>"><?= $data["judul"]?></a>
						</td>
						<td style="text-align:center;">
							<?= $data["owner"]?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			Belum ada arsip konten web.
		<?php endif; ?>
	</div>
</div>