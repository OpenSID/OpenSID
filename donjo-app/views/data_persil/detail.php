<div class="content-wrapper">
	<section class="content-header">
		<h1>Rincian Data Persil <?=ucwords($this->setting->sebutan_desa)?> <?= $desa["nama_desa"];?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?=site_url('data_persil/clear')?>"> Daftar Persil</a></li>
			<li class="active">Rincian Data Persil</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-3">
         <?php $this->load->view('data_persil/menu_kiri.php')?>
			</div>
			<div class="col-md-9">
				<div class="box box-info">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<div class="row">
										<div class="col-md-12">
											<div class="box-header with-border">
												<h3 class="box-title">Pengelolaan Data Persil <?= $desa['nama_desa'];?></h3>
											</div>
										</div>
										<div class="col-md-12">
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover" >
													<?php if ($persil_detail['jenis_pemilik'] != '2'): ?>
														<tr>
															<th colspan="3" class="bg-aqua"><strong>DATA PEMILIK</strong></th>
														</tr>
														<tr>
															<td width="300">Nama Penduduk</td>
															<td width="1">:</td>
															<td><?= strtoupper($persil_detail["namapemilik"])?></td>
														</tr>
														<tr>
															<td>NIK</td>
															<td >:</td>
															<td><?= $persil_detail["nik"]?></td>
														</tr>
														<tr>
															<td>Alamat</td>
															<td >:</td>
															<td>RT: <?= $persil_detail["rt"]?> RW: <?= $persil_detail["rw"]?> Dusun <?= strtoupper($persil_detail["dusun"])?></td>
														</tr>
													<?php else : ?>
														<tr>
															<th colspan="3" class="bg-aqua"><strong>DATA PEMILIK</strong></th>
														</tr>
														<tr>
															<td width="300">Nama Pemilik</td>
															<td width="1">:</td>
															<td><?= strtoupper($persil_detail["namapemilik"])?></td>
														</tr>
														<tr>
															<td>Alamat</td>
															<td >:</td>
															<td><?= $persil_detail["alamat_luar"]?></td>
														</tr>
													<?php endif; ?>
													<tr>
														<th colspan="3" class="bg-aqua"><strong>RINCIAN PERSIL</strong></th>
													</tr>
													<tr>
														<td>Nomor Persil</td>
														<td >:</td>
														<td><?= $persil_detail["nopersil"]?></td>
													</tr>
													<tr>
														<td>Keterangan Persil</td>
														<td >:</td>
														<td><?= $persil_jenis[$persil_detail["persil_jenis_id"]][0]?></br><?= $persil_jenis[$persil_detail["persil_jenis_id"]][1]?></td>
													</tr>
													<tr>
														<td>Luas Tanah</td>
														<td >:</td>
														<td><?= $persil_detail["luas"]?> m<sup>2</sup></td>
													</tr>
													<tr>
														<td>Kelas Tanah</td>
														<td >:</td>
														<td><?= $persil_detail["kelas"]?></td>
													</tr>
													<tr>
														<td>Peruntukan</td>
														<td >:</td>
														<td><?= $persil_peruntukan[$persil_detail["persil_peruntukan_id"]][0]?><br/><?= $persil_peruntukan[$persil_detail["persil_peruntukan_id"]][1]?></td>
													</tr>
													<tr>
														<td>Nomor SPPT PBB</td><td >:</td><td><?= $persil_detail["no_sppt_pbb"]?></td>
													</tr>
													<tr>
														<td>Lokasi</td><td >:</td><td>RT: <?= $persil_detail["rt"]?> RW: <?= $persil_detail["rw"]?> Dusun <?= strtoupper($persil_detail["dusun"])?></td>
													</tr>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

