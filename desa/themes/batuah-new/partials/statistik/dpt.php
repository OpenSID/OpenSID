<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php $this->load->view("$folder_themes/plus/header_side"); ?>
<div class="mainpage">
	<div class="margin-side-10">
	<div class="mainbodyarea">
		<div class="bigarea hiddenrelative">
		<div class="bigarea-margin">
			<div class="col-default margin-top-10">
				<div class="bodypage bgwhite bordergrey">
				
					<div class="headstat-lebar flexcenter" style="margin-bottom:10px;">
						<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pngfile/populasi.png") ?>"/>
						<div>
						<h1 class="color-1">Daftar Pemilih Tetap</h1>
						<p>Per Hari Ini (<?= $tanggal_pemilihan ?>)</p>
						</div>
					</div>
					<div class="padding-leftright-10">
					<div class="table-responsive">
						<table id="dpt" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th class="text-center">No</th>
									<th class="text-center">Nama Dusun</th>
									<th class="text-center">RW</th>
									<th class="text-center">Jiwa</th>
									<th class="text-center">L</th>
									<th class="text-center">P</th>
								</tr>
							</thead>
							<?php
							if (count($main ?? []) > 0) { ?>
								<tbody>
									<?php foreach ($main as $data) { ?>
										<tr>
											<td class="text-center"><?= $data["no"] ?></td>
											<td class="text-left"><?= strtoupper($data["dusun"]) ?></td>
											<td class="text-center"><?= strtoupper($data["rw"]) ?></td>
											<td class="text-center"><?= $data["jumlah_warga"] ?></td>
											<td class="text-center"><?= $data["jumlah_warga_l"] ?></td>
											<td class="text-center"><?= $data["jumlah_warga_p"] ?></td>
										</tr>
									<?php
									} ?>
								</tbody>
								<tr>
									<th colspan="3" class="text-right">TOTAL</th>
									<th class="text-center"><?= $total["total_warga"] ?></th>
									<th class="text-center"><?= $total["total_warga_l"] ?></th>
									<th class="text-center"><?= $total["total_warga_p"] ?></th>
								</tr>
							<?php
							} else { ?>
								<div class="no-found-small flexcenter">
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nodata.png") ?>" />
									<p>Untuk sementara data Daftar Pemilih belum tersedia</p>
								</div>
							<?php
							} ?>
						</table>
					</div>
				</div>
				</div>
			</div>
			<?php $this->load->view("$folder_themes/plus/middle_page"); ?>
		</div>
		</div>
		<div class="rightsidearea">
			<?php $this->load->view("$folder_themes/partials/statistik/statistik_nav"); ?>
			<?php $this->load->view("$folder_themes/plus/side"); ?>			
		</div>
	</div>
	</div>
</div>