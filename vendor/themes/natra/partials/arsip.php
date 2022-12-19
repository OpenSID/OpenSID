<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="single_page_area">
	<div style="margin-top:0px;">
		<?php if (!empty($teks_berjalan)): ?>
			<marquee onmouseover="this.stop()" onmouseout="this.start()">
				<?php $this->load->view("$folder_themes/layouts/teks_berjalan"); ?>
			</marquee>
		<?php endif; ?>
	</div>
	<div class="single_category wow fadeInDown">
		<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Arsip Konten Situs Web <?=$desa["nama_desa"]?></span> </h2>
	</div>
	<div style="margin-top:50px;">
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
