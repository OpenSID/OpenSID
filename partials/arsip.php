<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="single_page_area">
	<div style="margin-top:0px;">
		<?php if (!empty($teks_berjalan)): ?>
			<marquee onmouseover="this.stop()" onmouseout="this.start()">
				<?php $this->load->view($folder_themes.'/layouts/teks_berjalan.php'); ?>
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
				<?php else: ?>
					Belum ada arsip konten web.
				<?php endif; ?>
			</div>
			<?php if(count($farsip)>0): ?>
				<div class="pagination_area">
					<ul class="pagination">
						<?php if($paging->start_link): ?>
							<li><a href="<?= site_url("first/arsip/$paging->start_link")?>" title="Halaman Pertama"><i class="fa fa-fast-backward"></i>&nbsp;</a></li>
						<?php endif; ?>
						<?php if($paging->prev): ?>
							<li><a href="<?= site_url("first/arsip/$paging->prev")?>" title="Halaman Sebelumnya"><i class="fa fa-backward"></i>&nbsp;</a></li>
						<?php endif; ?>
						<?php for ($i=$paging->start_link; $i<=$paging->end_link; $i++): ?>
							<li class="<?php ($p != $i) or print('active');?>"><a href="<?=site_url('first/arsip/'.$i)?>" title="<?= 'Halaman '.$i ?>"><?= $i ?></a></li>
						<?php endfor; ?>
						<?php if($paging->next): ?>
							<li><a href="<?= site_url("first/arsip/$paging->next")?>" title="Halaman Selanjutnya"><i class="fa fa-forward"></i>&nbsp;</a></li>
						<?php endif; ?>
						<?php if($paging->end_link): ?>
							<li><a href="<?= site_url("first/arsip/$paging->end_link")?>" title="Halaman Terakhir"><i class="fa fa-fast-forward"></i>&nbsp;</a></li>
						<?php endif; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</div>
