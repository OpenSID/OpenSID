<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">

<head>
	<?php $this->load->view("$folder_themes/commons/meta.php"); ?>
</head>

<body>
	<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
	<div class="container" style="background-color: #f6f6f6;">
		<header id="header">
			<?php $this->load->view("$folder_themes/partials/header.php"); ?>
		</header>
		<div id="navarea">
			<?php $this->load->view("$folder_themes/partials/menu_head.php"); ?>
		</div>
		<div class="row">
			<section>
				<div class="content_middle"></div>
				<div class="content_bottom">
					<div class="col-lg-9 col-md-9">
						<div class="content_bottom_left">
							<div class="single_page_area">
								<?php if ($list_jawab) : ?>
									<div class='box'>";
										<?php $this->load->view($folder_themes . '/partials/analisis.php'); ?>
									</div>
								<?php else : ?>
									<div class="">
										<div class="single_page_area">
											<h2 class="post_titile">DAFTAR AGREGASI DATA ANALISIS DESA</h2>
											<div class="single_bottom_rightbar wow fadeInDown animated">
												<h2>Klik untuk melihat lebih detail</h2>
											</div>
										</div>
										<?php foreach ($list_indikator as $data) : ?>
											<div class="box-header">
												<a href="<?= site_url('first/data_analisis/' . $data['id'] . '/' . $data['subjek_tipe'] . '/' . $data['id_periode']); ?>">
													<h4><?= $data['indikator'] ?></h4>
												</a>
											</div>
											<div class="box-body" style="font-size:12px;">
												<table>
													<tr>
														<td width="100">Pendataan </td>
														<td width="20"> :</td>
														<td> <?= $data['master'] ?></td>
													</tr>
													<tr>
														<td>Subjek </td>
														<td> : </td>
														<td> <?= $data['subjek'] ?></td>
													</tr>
													<tr>
														<td>Tahun </td>
														<td> :</td>
														<td> <?= $data['tahun'] ?></td>
													</tr>
												</table>
											</div>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
						<div class="col-lg-3 col-md-3">
							<?php $this->load->view("$folder_themes/partials/bottom_content_right.php"); ?>
						</div>
					</div>
			</section>
		</div>
	</div>
	<footer id="footer">
		<?php $this->load->view("$folder_themes/partials/footer_top.php"); ?>
		<?php $this->load->view("$folder_themes/partials/footer_bottom.php"); ?>
	</footer>
	<?php $this->load->view("$folder_themes/commons/meta_footer.php"); ?>
</body>

</html>