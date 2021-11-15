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

				<?php

					$data['paging_page'] = 'first/arsip';

					$this->load->view("$folder_themes/commons/page", $data);

				?>
				
			<?php else: ?>
				Belum ada arsip konten web.
			<?php endif; ?>
		</div>
	</div>
</div>
