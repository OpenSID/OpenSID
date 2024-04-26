<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">

<head>
	<?php $this->load->view("$folder_themes/commons/meta"); ?>
</head>

<body onLoad="renderDate()">
	<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
	<div class="container" style="background-color: #f6f6f6;">
		<header id="header">
			<?php $this->load->view("$folder_themes/partials/header"); ?>
		</header>
		<div id="navarea">
			<?php $this->load->view("$folder_themes/partials/menu_head"); ?>
		</div>
		<div class="row">
			<section>
				<div class="content_bottom">
					<div class="col-lg-9 col-md-9">
						<div class="content_left">
							<div class="single_page_area">
								<?php if ($list_jawab) : ?>
									<div class='box'>
										<?php $this->load->view("$folder_themes/partials/analisis"); ?>
									</div>
								<?php else : ?>
									<div class="">
										<div class="single_page_area">
											<h2 class="post_titile">DAFTAR AGREGASI DATA ANALISIS DESA</h2>
										</div>
										<?php if ($list_indikator): ?>
											<?php if (count($master_indikator ?? []) > 1) : ?>
												<form action="<?=site_url('data_analisis'); ?>" method="get">
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
											<div class="table-responsive" style="border: solid; font-size:12px;">
												<table>
														<tr>
															<td width="200">Pendataan </td>
															<td width="20"> :</td>
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
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3">
						<?php $this->load->view("$folder_themes/partials/bottom_content_right"); ?>
					</div>
				</div>
			</section>
		</div>
	</div>
	<footer id="footer">
		<?php $this->load->view("$folder_themes/partials/footer_top"); ?>
		<?php $this->load->view("$folder_themes/partials/footer_bottom"); ?>
	</footer>
	<?php $this->load->view("$folder_themes/commons/meta_footer"); ?>
</body>

</html>