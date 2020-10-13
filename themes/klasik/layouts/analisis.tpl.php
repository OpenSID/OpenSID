<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

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
							<?php foreach ($list_indikator AS $data): ?>
								<a href="<?= site_url("data_analisis/$data[id]/$data[subjek_tipe]/$data[id_periode]"); ?>"><h5>&nbsp;<b><?= $data['indikator']?></b></h5></a>
								<div class="table-responsive">
									<table>
											<tr>
												<td width="20%">Pendataan </td>
												<td width="1%"> :</td>
												<td><?= $data['master']; ?></td>
											</tr>
											<tr>
												<td>Subjek </td>
												<td> : </td>
												<td><?= $data['subjek']; ?></td>
											</tr>
											<tr>
												<td>Tahun </td>
												<td> :</td>
												<td><?= $data['tahun']; ?></td>
											</tr>
									</table>
								</div>
								<hr>
							<?php endforeach; ?>
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
