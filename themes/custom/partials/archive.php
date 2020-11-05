<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h2 class="content__heading">Arsip Konten Situs Web Sukaraya</h2>
<hr class="--mt-2 --mb-2">

<section class="content__article">
	<div class="table">
		<?php if(count($farsip)>0): ?>
			<table class="archive__table">
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
						<td>
							<?= $data["no"]?>
						</td>
						<td>
							<?= tgl_indo($data["tgl_upload"])?>
						</td>
						<td>
							<a class="archive__link" href="<?= site_url('artikel/'.buat_slug($data))?>"><?= $data["judul"]?></a>
						</td>
						<td>
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
</section>