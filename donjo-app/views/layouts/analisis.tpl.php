<?php $this->load->view('layouts/header.php');?>
			<div id="contentwrapper">
				<div id="contentcolumn">
					<div class="innertube" style="padding-left:10px;">
						<?php
						if($list_jawab){
							$this->load->view('partials/analisis.php');
						}else{ ?>
						<h2 class="judul">DAFTAR DATA STATISTIK ANALISIS DI TINGKAT DESA</h2>
						<h3>Klik judul Analisis untuk melihat tampilan detail data statistik</h3><br>
						<ul >
						<?php foreach($list_indikator AS $data){?>							
							<div class="box box-primary">
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
							</div>
						<?php 
							}
						} ?>
					</div>
				</div>
			</div>
			<div id="rightcolumn">
				<div class="innertube">
					<?php $this->load->view('partials/side.right.stat.php');?>
				</div>
			</div>
			
			<div id="footer">
				<?php 
				$this->load->view('partials/copywright.tpl.php');
				?>
			</div>
		</div>
	</body>
</html>