<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="single_category wow fadeInDown">
	<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Pembangunan</span></h2>
</div>

<div class="box box-primary">
	<div class="box-body">
		<?php if ($pembangunan): ?>
			<div class="row">
				<?php foreach ($pembangunan as $data): ?>
					<div class="col-sm-4">
						<div class="card">
							<?php if (is_file(LOKASI_GALERI . $data->foto)): ?>
								<img width="auto" class="img-fluid img-thumbnail card-img-top" src="<?= base_url(LOKASI_GALERI . $data->foto) ?>" alt="Foto Pembangunan"/>
							<?php else: ?>
								<img width="auto" class="img-fluid img-thumbnail card-img-top" src="<?= asset('images/404-image-not-found.jpg') ?>" alt="Foto Pembangunan"/>
							<?php endif; ?>
							<div class="card-body">
								<table class="table">
									<tbody>
										<tr>
                      <th width="auto">Nama Kegiatan</th>
                      <td width="1%">:</td>
                      <td><?= e($data->judul) ?></td>
                    </tr>
										<tr>
                      <th>Alamat</th>
                      <td>:</td>
											
                      <td><?= ($data->alamat == "=== Lokasi Tidak Ditemukan ===") ? 'Lokasi tidak diketahui' : e($data->alamat); ?></td>
                    </tr>
                    <tr>
                      <th>Tahun</th>
                      <td>:</td>
                      <td>
												<?= $data->tahun_anggaran ?></td>
                    </tr>
                    <tr>
                      <th>Keterangan</th>
                      <td>:</td>
                      <td><?= e($data->keterangan) ?></td>
                    </tr>
									</tbody>
								</table>
								<a href="<?= site_url("pembangunan/$data->slug"); ?>" class="btn btn-primary">Selengkapnya</a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php $this->load->view("$folder_themes/commons/page"); ?>

		<?php else: ?>
			<h5>Data pembangunan tidak tersedia.</h5>
		<?php endif; ?>
	</div>
</div>