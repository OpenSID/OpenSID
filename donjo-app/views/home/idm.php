<style type="text/css">
	tr.judul {background-color: lightgrey !important;}
	thead tr, th {height: auto !important;}
	th.horizontal {
		white-space: nowrap;
		width: 20%;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Status IDM <?= ucwords($this->setting->sebutan_desa)?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Status IDM <?= ucwords($this->setting->sebutan_desa)?></li>
		</ol>
	</section>
	<section class="content">
		<div class="box box-info">
			<div class="box-body">
				<div class="row">
					<div class="col-sm-12">
						<?php if ($idm->error_msg): ?>
							<div class="alert alert-danger">
								<?= $idm->error_msg ?>
							</div>
						<?php endif; ?>
						<p><strong>
							STATUS <?= strtoupper($this->setting->sebutan_desa . ' ' . $idm->IDENTITAS[0]->nama_desa . ', ' .
							$this->setting->sebutan_kecamatan . ' ' . $idm->IDENTITAS[0]->nama_kecamatan . ', ' .
							$idm->IDENTITAS[0]->nama_kab_kota . ', PROVINSI ' . $idm->IDENTITAS[0]->nama_provinsi); ?>
						</strong></p>
						<div class="table-responsive">
							<table class="table table-bordered table-striped dataTable table-hover">
								<tbody>
									<tr>
										<th class="horizontal">TAHUN</th>
										<td> : <?= $idm->SUMMARIES->TAHUN ?></td>
									</tr>
									<tr>
										<th class="horizontal">SKOR IDM SAAT INI</th>
										<td> : <?= $idm->SUMMARIES->SKOR_SAAT_INI ?></td>
									</tr>
									<tr>
										<th class="horizontal">STATUS IDM</th>
										<td> : <?= $idm->SUMMARIES->STATUS ?></td>
									</tr>
									<tr>
										<th class="horizontal">TARGET_STATUS</th>
										<td> : <?= $idm->SUMMARIES->TARGET_STATUS ?></td>
									</tr>
									<tr>
										<th class="horizontal">SKOR IDM MINIMAL</th>
										<td> : <?= $idm->SUMMARIES->SKOR_MINIMAL ?></td>
									</tr>
									<tr>
										<th class="horizontal">PENAMBAHAN YANG DIBUTUHKAN</th>
										<td> : <?= $idm->SUMMARIES->PENAMBAHAN ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-bordered table-striped dataTable table-hover">
								<thead class="bg-gray color-palette">
									<tr>
										<th rowspan="2" class="padat">NO</th>
										<th rowspan="2" >INDIKATOR IDM</th>
										<th rowspan="2" >SKOR</th>
										<th rowspan="2" >KETERANGAN</th>
										<th rowspan="2" nowrap>KEGIATAN YANG DAPAT DILAKUKAN</th>
										<th rowspan="2" >+NILAI</th>
										<th colspan="6" class="text-center">YANG DAPAT MELAKSANAKAN KEGIATAN</th>
									</tr>
									<tr>
										<th>PUSAT</th>
										<th>PROVINSI</th>
										<th>KABUPATEN</th>
										<th>DESA</th>
										<th>CSR</th>
										<th>LAINNYA</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($idm->ROW as $data): ?>
										<tr class="<?php empty($data->NO) and print('judul'); ?> ">
											<td class="text-center"><?= $data->NO ?></td>
											<td style="min-width: 150px;"><?= $data->INDIKATOR ?></td>
											<td class="padat"><?= $data->SKOR ?></td>
											<td style="min-width: 250px;"><?= $data->KETERANGAN ?></td>
											<td><?= $data->KEGIATAN ?></td>
											<td><?= $data->NILAI ?></td>
											<td><?= $data->PUSAT ?></td>
											<td><?= $data->PROV ?></td>
											<td><?= $data->KAB ?></td>
											<td><?= $data->DESA ?></td>
											<td><?= $data->CSR ?></td>
											<td><?= $data->LAINNYA ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

