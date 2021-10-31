<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

	<style type="text/css">
		h5 { margin-top: 0px !important}
	</style>

	<?php $this->load->view("$folder_themes/layouts/header.php");?>
	<div id="contentwrapper">
		<div id="contentcolumn">
			<div class="innertube">
				<?php if($list_jawab): ?>
					<?php $this->load->view("$folder_themes/partials/analisis.php"); ?>
				<?php else: ?>
					<div class="box box-danger">
						<div class="box-header with-border">
							<h3 class="box-title">Daftar Agregasi Data Analisis Desa</h3>
						</div>
						<div class="box-body">
							<?php if ($list_indikator): ?>
								<?php if (count($master_indikator) > 1) : ?>
									<form action="<?=site_url('data_analisis');?>" method="get">
										<div class="row" style="margin-bottom: 20px;">
											<label style="padding-top: 5px;" class="col-sm-1 control-label" >Analisis: </label>
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
								
								<div class="table-responsive" style="border: solid">
									<table>
											<tr>
												<td width="20%">Pendataan </td>
												<td width="1%"> :</td>
												<td><?= $list_indikator['0']['master']; ?></td>
											</tr>
											<tr>
												<td>Subjek </td>
												<td> : </td>
												<td><?= $list_indikator['0']['subjek']; ?></td>
											</tr>
											<tr>
												<td>Tahun </td>
												<td> :</td>
												<td><?= $list_indikator['0']['tahun']; ?></td>
											</tr>
									</table>
								</div>

								<h4 style="margin-top: 15px; margin-bottom: 10px;">Indikator</h4>

								<div class="table-responsive">

									<table>
										<?php foreach ($list_indikator AS $data): ?>
											<tr>
												<td><?= $data['nomor'].'.'; ?>
												<td><a href="<?= site_url("jawaban_analisis/$data[id]/$data[subjek_tipe]/$data[id_periode]"); ?>"><h5><b><?= $data['indikator']?></b></h5></a></td>
											</tr>
										<?php endforeach; ?>
									</table>
								</div>
							<?php else: ?>
								<p>Data tidak tersedia</p>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div id="rightcolumn">
		<div class="innertube">
			<?php $this->load->view(Web_Controller::fallback_default($this->theme, "/partials/side.right.php"));?>
		</div>
	</div>

	<div id="footer">
		<?php $this->load->view("$folder_themes/partials/copywright.tpl.php"); ?>
	</div>
</div>
