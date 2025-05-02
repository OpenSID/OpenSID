<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php $this->load->view("$folder_themes/plus/header_side"); ?>
<div class="mainpage">
	<div class="margin-side-10">
	<div class="mainbodyarea">
		<div class="bigarea hiddenrelative">
		<div class="bigarea-margin">
			<div class="col-default margin-top-10">
				<div class="bodypage bgwhite bordergrey">
					<div class="center-head bggrad-color2 flexcenter">
						<div class="icon-titlepage bgcolor-1 flexcenter"><svg viewBox="0 0 24 24"><path d="M21 10V9L15 3H5C3.89 3 3 3.89 3 5V19C3 20.11 3.9 21 5 21H11V19.13L19.39 10.74C19.83 10.3 20.39 10.06 21 10M14 4.5L19.5 10H14V4.5M22.85 14.19L21.87 15.17L19.83 13.13L20.81 12.15C21 11.95 21.33 11.95 21.53 12.15L22.85 13.47C23.05 13.67 23.05 14 22.85 14.19M19.13 13.83L21.17 15.87L15.04 22H13V19.96L19.13 13.83Z" /></svg></div>
						<h1>Inventaris</h1>
					</div>
					<div class="pagebox gridstyle artikelpage">
						<div class="statishead"><h1>Data Inventaris <?= $judul ?></h1></div>
						<div class="box-body">
						<div class="table-responsive">
							<table id="inventaris" class="table table-bordered dataTable table-hover">
								<thead class="bg-gray">
									<tr>
										<th class="text-center">No</th>
										<th class="text-center">Nama Barang</th>
										<th class="text-center">Kode Barang / Nomor Registrasi</th>
										<th class="text-center">Jumlah</th>
										<th class="text-center">Tahun Pembelian</th>
										<th class="text-center">Asal Usul</th>
										<th class="text-center">Harga (Rp)</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($main as $data): ?>
										<tr>
											<td></td>
											<td><?= $data->nama_barang; ?></td>
											<td><?= $data->kode_barang; ?><br><?= $data->register; ?></td>
											<td><?= $data->jumlah; ?></td>
											<td><?= $data->tahun_pengadaan; ?></td>
											<td><?= $data->asal; ?></td>
											<td><?= number_format($data->harga, 0, '.', '.'); ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
								<?php if (count($main) > 0): ?>
									<tfoot>
										<tr>
											<th colspan="6" class="text-right">Total:</th>
											<th><?= number_format($total, 0, '.', '.'); ?></th>
										</tr>
									</tfoot>
								<?php endif; ?>
							</table>
						</div>
					</div>
					</div>
					<?php $this->load->view("$folder_themes/partials/inventaris/script") ?>
				</div>
			</div>
			<?php $this->load->view("$folder_themes/plus/middle_page"); ?>
		</div>
		</div>
		<div class="rightsidearea">
			<?php $this->load->view("$folder_themes/plus/side"); ?>			
		</div>
	</div>
	</div>
</div>