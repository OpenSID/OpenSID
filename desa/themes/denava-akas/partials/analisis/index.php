<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if($list_jawab): ?>
	<?php $this->load->view($folder_themes .'/partials/analisis/detail') ?>
<?php else: ?>
	<div class="article-single">
		<div class="statistikstyle">
			<div class="container-page mb-20">
				<div class="headingpage border-grey-soft bg-white flexleft">
					<div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/check.svg") ?>" alt=""/></div>
					<h2>DATA ANALISIS</h2>
				</div>
				<div class="box-article mb-30 bg-white">
					<div class="relative-border p-15 border-grey-soft">
						<div class="head-content">
							<?php if ($list_indikator): ?>
								<?php if (count($master_indikator ?? []) > 1) : ?>
									<form action="<?=site_url('data_analisis');?>" method="get">
										<div class="row" style="margin-bottom: 20px;">
											<label style="padding-top: 5px;" class="col-sm-2 control-label" >Analisis: </label>
											<div class="col-sm-6">
												<select class="form-control select2" name="master" onchange="this.form.submit()" style="width: 100%;">
													<?php foreach ($master_indikator as $master): ?>
														<option value="<?= $master['id']?>" <?= selected($list_indikator['0']['id_master'], $master['id'])?> ><?= "{$master['master']} ({$master['tahun']})"?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
									</form>
								<?php endif; ?>
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover">
										<tbody>
											<tr>
												<td width="15%">Pendataan </td>
												<td width="1%">:</td>
												<td><?= $list_indikator['0']['master']; ?></td>
											</tr>
											<tr>
												<td>Subjek </td>
												<td>:</td>
												<td><?= $list_indikator['0']['subjek']; ?></td>
											</tr>
											<tr>
												<td>Tahun </td>
												<td>:</td>
												<td><?= $list_indikator['0']['tahun']; ?></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="table-responsive">
									<table id="analisis" class="table table-striped">
										<thead>
											<tr>
												<th>Kode</th>
												<th>Indikator</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($list_indikator as $data): ?>
												<tr>
													<td width="15%"><?= $data['nomor'].'.'; ?>
													<td><a href="<?= site_url("jawaban_analisis/$data[id]/$data[subjek_tipe]/$data[id_periode]"); ?>"><b><?= $data['indikator']?></b></a></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							<?php else: ?>
								<div class="font-weight-bold text-center mt-4 mb-4">
									<h5>Data tidak tersedia.</h5>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<script>
				$(document).ready(function() {
					var table = $('#analisis').DataTable( {
						'processing': true,
						'order': [],
						'pageLength': 25,
						'language': {
							'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
						}
					} )
					.columns.adjust()
					.responsive.recalc();
				} );
			</script>
		</div>
	</div>
<?php endif; ?>
