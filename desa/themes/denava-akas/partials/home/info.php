<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="withscroll">
	<div class="m-20">
		<div class="mb-5">
			<div class="headpanel bg-gradient-hor flexleft">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapsedua">Kehadiran di Kantor <?= ucwords($this->setting->sebutan_desa); ?></a>
			</div>
			<div id="collapsedua" class="panel-collapse collapse">
				<div class="panelopen bg-grey-medium">
					<div class="pd-lr-15 mt-20 mb-20" style="position:relative;overflow:hidden;">
						<?php foreach($aparatur_desa['daftar_perangkat'] as $data) : ?>
							<?php if ($data['status_kehadiran'] == 'hadir'): ?>
								<div class="arsip-right flexleft">
									<div class="arsip-right-image">
										<?php if ($data['foto']): ?>
											<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $data['foto'] ?>" alt="">
										<?php endif;?>
									</div>
									<div class="arsip-right-title flexleft">
										<div>
											<h2><?= $data['nama'] ?></h2>
											<h2><b><?= $data['jabatan'] ?></b></h2>
										</div>
									</div>
									<div class="ada flexcenter">
										<svg viewBox="0 0 18 18">
											<path d="M4.9,7.1 L3.5,8.5 L8,13 L18,3 L16.6,1.6 L8,10.2 L4.9,7.1 L4.9,7.1 Z M16,16 L2,16 L2,2 L12,2 L12,0 L2,0 C0.9,0 0,0.9 0,2 L0,16 C0,17.1 0.9,18 2,18 L16,18 C17.1,18 18,17.1 18,16 L18,8 L16,8 L16,16 L16,16 Z"/>
										</svg>
									</div>
								</div>
							<?php else: ?>
								<div class="arsip-right flexleft <?= ((setting('tampilkan_kehadiran') && $data['kehadiran'] == 1 && $data['tanggal'] != date('Y-m-d')) ? 'greystyle' : '') ?>">
									<div class="arsip-right-image">
										<?php if ($data['foto']): ?>
											<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $data['foto'] ?>" alt="">
										<?php endif;?>
									</div>
									<div class="arsip-right-title flexleft">
										<div>
											<h2><?= $data['nama'] ?></h2>
											<h2><b><?= $data['jabatan'] ?></b></h2>
										</div>
									</div>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="mb-5">
			<div class="panelopen bg-gradient-hor pb-20 ">
				<div class="pd-lr-15 mt-15">
					<div class="image-top">
						<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= gambar_desa($desa['kantor_desa'], TRUE)?>" alt=""/>
					</div>
					<table id="table-agenda" width="100%" style="color:#dbdbdb !important; margin-top:10px;">
						<tr>
							<td width="40%" style="color:#dbdbdb !important;vertical-align:top;"><?= ucwords($this->setting->sebutan_desa); ?></td>
							<td width="3%" style="color:#dbdbdb !important;vertical-align:top;">:</td>
							<td width="57%" style="color:#dbdbdb !important;text-align:left;vertical-align:top"><?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></td>
						</tr>
						<tr>
							<td width="40%" style="color:#dbdbdb !important;vertical-align:top;"><?= ucwords($this->setting->sebutan_kecamatan); ?></td>
							<td width="3%" style="color:#dbdbdb !important;vertical-align:top;">:</td>
							<td width="57%" style="color:#dbdbdb !important;text-align:left;vertical-align:top"><?= ucwords(($desa['nama_kecamatan']) ? ' ' . $desa['nama_kecamatan'] : ''); ?></td>
						</tr>
						<tr>
							<td width="40%" style="color:#dbdbdb !important;vertical-align:top;"><?= ucwords($this->setting->sebutan_kabupaten); ?></td>
							<td width="3%" style="color:#dbdbdb !important;vertical-align:top;">:</td>
							<td width="57%" style="color:#dbdbdb !important;text-align:left;vertical-align:top"><?= ucwords(($desa['nama_kabupaten']) ? ' ' . $desa['nama_kabupaten'] : ''); ?></td>
						</tr>
						<tr>
							<td width="40%" style="color:#dbdbdb !important;vertical-align:top;">Provinsi</td>
							<td width="3%" style="color:#dbdbdb !important;vertical-align:top;">:</td>
							<td width="57%" style="color:#dbdbdb !important;text-align:left;vertical-align:top"><?= ucwords(($desa['nama_propinsi']) ? ' ' . $desa['nama_propinsi'] : ''); ?></td>
						</tr>
						<tr>
							<td width="40%" style="color:#dbdbdb !important;vertical-align:top;">Kode Pos</td>
							<td width="3%" style="color:#dbdbdb !important;vertical-align:top;">:</td>
							<td width="57%" style="color:#dbdbdb !important;text-align:left;vertical-align:top"><?= ucwords(($desa['kode_pos']) ? ' ' . $desa['kode_pos'] : ''); ?></td>
						</tr>
						<tr>
							<td width="40%" style="color:#dbdbdb !important;vertical-align:top;">Alamat Kantor</td>
							<td width="3%" style="color:#dbdbdb !important;vertical-align:top;">:</td>
							<td width="57%" style="color:#dbdbdb !important;text-align:left;vertical-align:top"><?= ucwords(($desa['alamat_kantor']) ? ' ' . $desa['alamat_kantor'] : ''); ?></td>
						</tr>
					</table>
					<div class="sekilas" style="margin-top:10px;margin-bottom:15px;">
						<?php if ($desa['telepon']): ?> 
							<div style="width:100%;float:left;margin-top:5px;">
								<i class="fa fa-phone" style="margin-right:8px;"></i><?= ucwords(" ".$desa['telepon'])?>
							</div>
						<?php endif ?>
						<?php if ($desa['nomor_operator']): ?> 
							<div style="width:100%;float:left;margin-top:5px;">
								<i class="fa fa-mobile" style="margin-right:14px;"></i><?= ucwords(" ".$desa['nomor_operator'])?>
							</div>
						<?php endif ?>
						<?php if ($desa['email_desa']): ?> 
							<div class="text-break" style="width:100%;float:left;margin-top:5px;">
								<i class="fa fa-envelope" style="margin-right:5px;"></i><?= strtolower(" ".$desa['email_desa'])?>
							</div>
						<?php endif ?>
						<?php if ($data_config['lat'] != NULL && $data_config['lng'] != NULL): ?> 
						<div style="width:100%;float:left;margin-top:5px;">
							<a href="https://www.google.com/maps/dir//<?=$data_config['lat'].",".$data_config['lng']?>" rel="noopener noreferrer" target="_blank">
								<button class="btn btn-primary btn-block">Titik Lokasi Kantor <?= ucwords($this->setting->sebutan_desa); ?></button>
							</a>
						</div>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
