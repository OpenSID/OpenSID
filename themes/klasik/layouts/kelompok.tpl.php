<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view($folder_themes.'/layouts/header.php');?>
	<div id="contentwrapper">
		<div id="contentcolumn">
			<div class="innertube">
				<div class="box box-danger">
					<div class="box-header with-border">
						<h3 class="box-title"> Kelompok <?= $detail['nama']; ?></h3>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover">
								<tbody>
									<tr>
										<td width="20%">Nama Kelompok</td>
										<td width="1"> : </td>
										<td><?= $detail['nama']?></td>
									</tr>
									<tr>
										<td>Ketua Kelompok</td>
										<td> : </td>
										<td><?= $detail['ketua']?></td>
									</tr>
									<tr>
										<td>Keterangan</td>
										<td> : </td>
										<td><?= $detail['keterangan']?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="box-header with-border">
						<h3 class="box-title"> Anggota <?= $detail['nama']; ?></h3>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-bordered dataTable table-hover nowrap" id="tabel-data">
								<thead class="bg-gray disabled color-palette">
									<tr>
										<th>No</th>
										<th>Nomor Anggota</th>
										<th>NIK</th>
										<th>Nama</th>
										<th>Alamat</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($anggota as $data): ?>
										<tr>
											<td><?= $data['no']?></td>
											<td><?= $data['no_anggota']?></td>
											<td><?= $data['nik']?></td>
											<td nowrap><?= $data['nama']?></td>
											<td><?= $data['alamat']?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="rightcolumn">
		<div class="innertube">
			<?php $this->load->view(Web_Controller::fallback_default($this->theme, '/partials/side.right.php'));?>
		</div>
	</div>
	<div id="footer">
		<?php $this->load->view($folder_themes.'/partials/copywright.tpl.php'); ?>
	</div>
	</div>
	<script>
		$(document).ready(function(){
			$('#tabel-data').DataTable({
				responsive: true
			});
		});
	</script>
</body>
</html>
