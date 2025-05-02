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
						<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/map.png") ?>"/>
						<div>
						<h1 class="color-1"><?= $heading ?></h1>
						</div>
					</div>
				<div class="padding-leftright-10">
					
					<div class="table-responsive">
					  <table class="table table-striped table-bordered">
						<thead>

						  <tr>
							<th>No</th>
							<th colspan="8">Wilayah / Ketua</th>
							<th class="text-center">KK</th>
							<th class="text-center">L+P</th>
							<th class="text-center">L</th>
							<th class="text-center">P</th>
						  </tr>

						</thead>

						<?php if (count($daftar_dusun ?? []) > 0) : ?>
						  <tbody>
							<?php foreach ($daftar_dusun as $key_dusun => $data_dusun) : ?>
							  <tr>
								<td class="text-center"><?= $key_dusun + 1; ?></td>
								<td colspan="8">
								  <?= ucwords($this->setting->sebutan_dusun . ' ' . $data_dusun['dusun']); ?>
								  <?php if ($data_dusun['nama_kadus']) : ?>
									, Ketua <?= $data_dusun['nama_kadus']; ?>
								  <?php endif ?>
								</td>
								<td class="text-right"><?= $data_dusun['jumlah_kk']; ?></td>
								<td class="text-right"><?= $data_dusun['jumlah_warga']; ?></td>
								<td class="text-right"><?= $data_dusun['jumlah_warga_l']; ?></td>
								<td class="text-right"><?= $data_dusun['jumlah_warga_p']; ?></td>
							  </tr>

							  <?php
							  $no_rw = 1;
							  foreach ($data_dusun['daftar_rw'] as $data_rw) :
							  ?>
								<?php if ($data_rw['rw'] != '-') : ?>
								  <tr>
									<td></td>
									<td class="text-center"><?= $no_rw++; ?></td>
									<td colspan="7">
									  RW <?= $data_rw['rw']; ?>
									  <?php if ($data_rw['nama_ketua']) : ?>
										, Ketua <?= $data_rw['nama_ketua']; ?>
									  <?php endif ?>
									</td>
									<td class="text-right"><?= $data_rw['jumlah_kk']; ?></td>
									<td class="text-right"><?= $data_rw['jumlah_warga']; ?></td>
									<td class="text-right"><?= $data_rw['jumlah_warga_l']; ?></td>
									<td class="text-right"><?= $data_rw['jumlah_warga_p']; ?></td>
								  </tr>
								<?php endif ?>

								<?php
								$no_rt = 1;
								foreach ($data_rw['daftar_rt'] as $data_rt) :
								?>
								  <?php if ($data_rt['rt'] != '-') : ?>
									<tr>
									  <td></td>
									  <td></td>
									  <td class="text-center"><?= $no_rt++; ?></td>
									  <td colspan="6">
										RT <?= $data_rt['rt']; ?>
										<?php if ($data_rt['nama_ketua']) : ?>
										  , Ketua <?= $data_rt['nama_ketua']; ?>
										<?php endif ?>
									  </td>
									  <td class="text-right"><?= $data_rt['jumlah_kk']; ?></td>
									  <td class="text-right"><?= $data_rt['jumlah_warga']; ?></td>
									  <td class="text-right"><?= $data_rt['jumlah_warga_l']; ?></td>
									  <td class="text-right"><?= $data_rt['jumlah_warga_p']; ?></td>
									</tr>
								  <?php endif ?>
								<?php endforeach; ?>
							  <?php endforeach; ?>
							<?php endforeach; ?>

						  </tbody>
						  <tfoot>
							<tr>
							  <th colspan="9">TOTAL</th>
							  <th class="text-right"><?= $total['total_kk'] ?></th>
							  <th class="text-right"><?= $total['total_warga'] ?></th>
							  <th class="text-right"><?= $total['total_warga_l'] ?></th>
							  <th class="text-right"><?= $total['total_warga_p'] ?></th>
							</tr>
						  </tfoot>
						<?php else : ?>
						  <div class="no-photo mb-20 flexcenter">
							<p>Untuk sementara<br />Data <?= $heading ?> belum tersedia...</p>
							<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nofdata1.png") ?>" />
						  </div>
						<?php endif; ?>

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