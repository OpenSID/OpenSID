<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div style="margin-left:.5em;">
	<div class="box box-primary box-solid">
		<div class="box-header">
			<h3 class="box-title">Arsip Konten Situs Web <?=$desa["nama_desa"]?></h3>
		</div>
		<div class="box-body">
			<?php if(count($farsip)>0): ?>
				<table class="table table-striped">
					<thead>
						<tr>
							<td width="3%"><b>No.</b></td>
							<td width="20%"><b>Tanggal Artikel</b></td>
							<td><b>Judul Artikel</b></td>
							<td width="20%"><b>Penulis</b></td>
							<td width="10%"><b>Dibaca</b></td>
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
								<a href="<?= site_url('artikel/'.buat_slug($data))?>"><?= $data["judul"]?></a>
							</td>
							<td style="text-align:center;">
								<?= $data["owner"]?>
							</td>
							<td style="text-align:center;">
								<?= hit($data['hit']) ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			<?php else: ?>
				Belum ada arsip konten web.
			<?php endif; ?>
		</div>
		<?php if(count($farsip)>0 AND $paging->num_rows > $paging->per_page): ?>
			<div class="box-footer">
				<ul class="pagination pagination-sm no-margin">
					<?php if($paging->start_link): ?>
						<li><a href="<?= site_url("arsip/$paging->start_link")?>" title="Halaman Pertama"><i class="fa fa-fast-backward"></i>&nbsp;</a></li>
					<?php endif; ?>
					<?php if($paging->prev): ?>
						<li><a href="<?= site_url("arsip/$paging->prev")?>" title="Halaman Sebelumnya"><i class="fa fa-backward"></i>&nbsp;</a></li>
					<?php endif; ?>
					<?php for ($i=$paging->start_link; $i<=$paging->end_link; $i++): ?>
						<li class="<?php ($p != $i) or print('active');?>"><a href="<?=site_url('arsip/'.$i)?>" title="<?= 'Halaman '.$i ?>"><?= $i ?></a></li>
					<?php endfor; ?>
					<?php if($paging->next): ?>
						<li><a href="<?= site_url("arsip/$paging->next")?>" title="Halaman Selanjutnya"><i class="fa fa-forward"></i>&nbsp;</a></li>
					<?php endif; ?>
					<?php if($paging->end_link): ?>
						<li><a href="<?= site_url("arsip/$paging->end_link")?>" title="Halaman Terakhir"><i class="fa fa-fast-forward"></i>&nbsp;</a></li>
					<?php endif; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
</div>
