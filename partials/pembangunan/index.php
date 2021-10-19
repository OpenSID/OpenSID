<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="single_category wow fadeInDown">
	<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Pembangunan</span></h2>
</div>

<div class="box box-primary">
	<div class="box-body">
		<?php if ($pembangunan): ?>
			<div class="row">
				<?php foreach ($pembangunan as $data): ?>
					<div class="col-md-4">
						<div class="card">
							<?php if (is_file(LOKASI_GALERI . $data->foto)): ?>
								<img width="auto" class="img-fluid img-thumbnail" src="<?= base_url() . LOKASI_GALERI . $data->foto ?>" alt="Foto Pembangunan"/>
							<?php else: ?>
								<img width="auto" class="img-fluid img-thumbnail" src="<?= base_url('assets/images/404-image-not-found.jpg') ?>" alt="Foto Pembangunan"/>
							<?php endif; ?>
							<div class="card-body">
								<table class="table">
									<tbody>
										<tr>
                      <th width="auto">Nama Kegiatan</th>
                      <td width="1%">:</td>
                      <td><?= $data->judul ?></td>
                    </tr>
										<tr>
                      <th>Alamat</th>
                      <td>:</td>
                      <td><?= $data->alamat ?></td>
                    </tr>
                    <tr>
                      <th>Tahun</th>
                      <td>:</td>
                      <td><?= $data->tahun_anggaran ?></td>
                    </tr>
                    <tr>
                      <th>Keterangan</th>
                      <td>:</td>
                      <td><?= $data->keterangan ?></td>
                    </tr>
									</tbody>
								</table>
								<a href="<?= site_url("pembangunan/detail/$data->id"); ?>" class="btn btn-primary">Selengkapnya</a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ($paging->num_rows > $paging->per_page): ?>
				<div class="box-footer text-center">
					<div>Halaman <?= $paging->page; ?> dari <?= $paging->end_link; ?></div>
					<ul class="pagination pagination-sm no-margin">
						<?php if ($paging->start_link): ?>
							<li><a href="<?= site_url($paging_page . "/$paging->start_link" . $paging->suffix); ?>" title="Halaman Pertama"><i class="fa fa-fast-backward"></i>&nbsp;</a></li>
						<?php endif; ?>
						<?php if ($paging->prev): ?>
							<li><a href="<?= site_url($paging_page . "/$paging->prev" . $paging->suffix); ?>" title="Halaman Sebelumnya"><i class="fa fa-backward"></i>&nbsp;</a></li>
						<?php endif; ?>

						<?php foreach ($pages as $i): ?>
							<li <?= jecho($paging->page, $i, 'class="active"'); ?>>
								<a href="<?= site_url($paging_page . "/$i" . $paging->suffix); ?>" title="Halaman <?= $i; ?>"><?= $i; ?></a>
							</li>
						<?php endforeach; ?>

						<?php if ($paging->next): ?>
							<li><a href="<?= site_url($paging_page . "/$paging->next" . $paging->suffix); ?>" title="Halaman Selanjutnya"><i class="fa fa-forward"></i>&nbsp;</a></li>
						<?php endif; ?>
						<?php if ($paging->end_link): ?>
							<li><a href="<?= site_url($paging_page . "/$paging->end_link" . $paging->suffix); ?>" title="Halaman Terakhir"><i class="fa fa-fast-forward"></i>&nbsp;</a></li>
						<?php endif; ?>
					</ul>
				</div>
			<?php endif; ?>
		<?php else: ?>
			<h5>Data pembangunan tidak tersedia.</h5>
		<?php endif; ?>
	</div>
</div>