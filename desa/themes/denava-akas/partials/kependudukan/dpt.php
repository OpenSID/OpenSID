<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="article-single">
	<div class="statistikstyle">
		<div class="container-page mb-20">
			<div class="headingpage border-grey-soft bg-gradient-hor flexleft" style="border-radius:5px 5px 0 0;">
				<div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/check.svg") ?>" alt=""/></div>
				<div class="articlehome-head flexleft"><h1>Daftar Calon Pemilih</h1></div>
          	</div>
			<div class="box-article mb-30 bg-white" style="border-radius:0 0 5px 5px;">
				<div class="relative-border p-15 border-grey-soft" style="border-radius:0 0 5px 5px;">
					<div class="gridview">
						<div class="sidebarright">
							<img style="width:100%;height:1px;display:block;" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/empty-pic.png") ?>"/>
							<ul>
								<?php $this->load->view($folder_themes .'/partials/kependudukan/navigasi') ?>
							</ul>
						</div>
						<div class="head-content">
							<div style="text-align:center;">
								<h1>
								<?php if (IS_PREMIUM && IS_240803) : ?>
									<?= $heading; ?>
								<?php else : ?>
									Jumlah Penduduk yang Memiliki Hak Suara <?= !empty($this->setting->sebutan_dusun) ? 'per ' . ucwords($this->setting->sebutan_dusun) : ''; ?><br>di <?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?><br>Tahun <?= date('Y') ?>
								<?php endif; ?>
								</h1>
							</div>
							<div style="text-align:center;"><h1>Tanggal Pemilihan <?= $tanggal_pemilihan ?></h1>
							</div>
							<?php if(count($main) > 0 ?? []) : ?>
							<div class="table-responsive">
								<table id="dpt" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th class="text-center">No</th>
											<th class="text-center">Nama <?= ucwords($this->setting->sebutan_dusun) ?></th>
											<th class="text-center">RW</th>
											<th class="text-center">Jiwa</th>
											<th class="text-center">L</th>
											<th class="text-center">P</th>
										</tr>
									</thead>
										<tbody>
											<?php foreach($main as $data) { ?>
												<tr>
													<td class="text-center"><?= $data["no"] ?></td>
													<td class="text-left"><?= strtoupper($data["dusun"]) ?></td>
													<td class="text-center"><?= strtoupper($data["rw"]) ?></td>
													<td class="text-right"><?= ribuan($data["jumlah_warga"]) ?></td>
													<td class="text-right"><?= ribuan($data["jumlah_warga_l"]) ?></td>
													<td class="text-right"><?= ribuan($data["jumlah_warga_p"]) ?></td>
												</tr>
											<?php } ?>
										</tbody>
										<tr>
											<th colspan="3" class="text-right">TOTAL</th>
											<th class="text-right"><?= ribuan($total["total_warga"]) ?></th>
											<th class="text-right"><?= ribuan($total["total_warga_l"]) ?></th>
											<th class="text-right"><?= ribuan($total["total_warga_p"]) ?></th>
										</tr>
								</table>
							</div>
							<?php else : ?>
								<div class="no-photo mb-20 flexcenter">
									<p>Untuk sementara<br/>data calon pemilih masih kosong.</p>
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nofdata1.png") ?>" alt=""/>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="small-screen"><?php $this->load->view($folder_themes .'/partials/kependudukan/navigasi') ?></div>
				</div>
			</div>
		</div>
	</div>
</div>	
