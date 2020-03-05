<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view($folder_themes.'/layouts/header.php');?>
	<div id="contentwrapper">
		<div id="contentcolumn">
			<div class="innertube">
				<?php
					if($list_jawab){
							echo "<div class='box'>";
							$this->load->view($folder_themes.'/partials/analisis.php');
							echo "</div>";
					}else{ ?>
						<div class="box box-primary">
							<div class="box-header">
								<h2 class="judul">DAFTAR AGREGASI DATA ANALISIS DESA</h2>
								<h3>Klik untuk melihat lebih detail</h3>
							</div>
							<?php foreach($list_indikator AS $data){?>
								<div class="box-header">
									<a href="<?php echo site_url()?>first/data_analisis/<?php echo $data['id']?>/<?php echo $data['subjek_tipe']?>/<?php echo $data['id_periode']?>">
									<h4><?php echo $data['indikator']?></h4>
									</a>
								</div>
								<div class="box-body" style="font-size:12px;">
									<table>
										<tr>
											<td width="100">Pendataan </td>
											<td width="20"> :</td>
											<td> <?php echo $data['master']?></td>
										</tr>
										<tr>
											<td>Subjek </td>
											<td> : </td>
											<td> <?php echo $data['subjek']?></td>
										</tr>
										<tr>
											<td>Tahun </td>
											<td> :</td>
											<td> <?php echo $data['tahun']?></td>
										</tr>
									</table>
								</div>
							<?php
							}
						} ?>
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
			<?php
			$this->load->view($folder_themes.'/partials/copywright.tpl.php');
			?>
		</div>
	</div>
