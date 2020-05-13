<style type="text/css">
  .disabled
	{
     pointer-events: none;
     cursor: default;
  }
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Statistik Kependudukan</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Statistik Kependudukan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-4">
          <?php $this->load->view('statistik/laporan/side-menu.php')?>
				</div>
				<div class="col-md-8">
					<div class="box box-info">
            <div class="box-header with-border">
							<a href="<?=site_url("statistik/dialog_cetak/$lap")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Laporan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Laporan"><i class="fa fa-print "></i>Cetak
            	</a>
							<a href="<?=site_url("statistik/dialog_unduh/$lap")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Laporan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Laporan"><i class="fa fa-print "></i>Unduh
            	</a>
							<a href="<?=site_url("statistik/graph/$lap")?>" class="btn btn-social btn-flat bg-orange btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Grafik Data">
								<i class="fa  fa-bar-chart"></i>Grafik Data
            	</a>
							<a href="<?=site_url("statistik/pie/$lap")?>" class="btn btn-social btn-flat btn-primary btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Pie Data">
								<i class="fa fa-pie-chart"></i>Pie Data
            	</a>
							<?php if ($lap=='13'): ?>
								<a href="<?=site_url("statistik/rentang_umur")?>" class="btn btn-social btn-flat bg-olive btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Rentang Umur">
									<i class="fa fa-arrows-h"></i>Rentang Umur
								</a>
							<?php endif; ?>
							<a href="<?= site_url("{$this->controller}/clear") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan Filter</a>
						</div>
						<div class="box-body">
							<div class="col-sm-12">
								<?php if ($lap < 50): ?>
									<h4 class="box-title"><b>Data Kependudukan menurut <?= ($stat);?></b></h4>
								<?php else: ?>
									<h4 class="box-title"><b>Data Peserta Program <?= ($program['nama'])?></b></h4>
								<?php endif; ?>
								<?php if (($lap <= 20 OR $lap == 'bantuan_penduduk') AND $lap <> 'kelas_sosial' AND $lap <> 'bantuan_keluarga') : ?>
									<div class="row">
										<div class="col-sm-12 form-inline">
											<form action="" id="mainform" method="post">
												<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('statistik/dusun/0/'.$lap)?>')">
													<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
													<?php foreach ($list_dusun AS $data): ?>
														<option value="<?= $data['dusun']?>" <?php $dusun == $data['dusun'] and print('selected') ?>><?= strtoupper($data['dusun'])?></option>
													<?php endforeach; ?>
												</select>
												<?php if ($dusun): ?>
													<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('statistik/rw/0/'.$lap)?>')" >
														<option value="">RW</option>
														<?php foreach ($list_rw AS $data): ?>
															<option value="<?= $data['rw']?>" <?php $rw == $data['rw'] and print('selected') ?>><?= $data['rw']?></option>
														<?php endforeach; ?>
													</select>
												<?php endif; ?>
												<?php if ($rw): ?>
													<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('statistik/rt/0/'.$lap)?>')">
														<option value="">RT</option>
														<?php foreach ($list_rt AS $data): ?>
															<option value="<?= $data['rt']?>" <?php $rt == $data['rt'] and print('selected') ?>><?= $data['rt']?></option>
														<?php endforeach; ?>
													</select>
												<?php endif; ?>
											</form>
										</div>
									</div>
								<?php endif ?>
								<div class="table-responsive">
									<table class="table table-bordered dataTable table-striped table-hover nowrap">
										<thead class="bg-gray color-palette">
											<tr>
												<th width='5%'>No</th>
												<?php if ($o==2): ?>
                          <th><a href="<?= site_url("statistik/index/$lap/1")?>"><?= $judul_kelompok ?> <i class='fa fa-sort-asc fa-sm'></i></a></th>
                        <?php elseif ($o==1): ?>
                          <th><a href="<?= site_url("statistik/index/$lap/2")?>"><?= $judul_kelompok ?> <i class='fa fa-sort-desc fa-sm'></i></a></th>
                        <?php else: ?>
                          <th><a href="<?= site_url("statistik/index/$lap/1")?>"><?= $judul_kelompok ?> <i class='fa fa-sort fa-sm'></i></a></th>
                        <?php endif; ?>
                        <?php if ($o==6): ?>
                          <th nowrap colspan="2"><a href="<?= site_url("statistik/index/$lap/5")?>">Jumlah <i class='fa fa-sort-asc fa-sm'></i></a></th>
                        <?php elseif ($o==5): ?>
                          <th nowrap colspan="2"><a href="<?= site_url("statistik/index/$lap/6")?>">Jumlah <i class='fa fa-sort-desc fa-sm'></i></a></th>
                        <?php else: ?>
                          <th nowrap colspan="2"><a href="<?= site_url("statistik/index/$lap/5")?>">Jumlah <i class='fa fa-sort fa-sm'></i></a></th>
                        <?php endif; ?>

												<?php if ($jenis_laporan == 'penduduk'): ?>
													<?php if ($o==4): ?>
                            <th nowrap colspan="2"><a href="<?= site_url("statistik/index/$lap/3")?>">Laki-Laki <i class='fa fa-sort-asc fa-sm'></i></a></th>
                          <?php elseif ($o==3): ?>
                            <th nowrap colspan="2"><a href="<?= site_url("statistik/index/$lap/4")?>">Laki-Laki <i class='fa fa-sort-desc fa-sm'></i></a></th>
                          <?php else: ?>
                            <th nowrap colspan="2"><a href="<?= site_url("statistik/index/$lap/3")?>">Laki-Laki <i class='fa fa-sort fa-sm'></i></a></th>
                          <?php endif; ?>
													<?php if ($o==8): ?>
                            <th nowrap colspan="2"><a href="<?= site_url("statistik/index/$lap/7")?>">Perempuan <i class='fa fa-sort-asc fa-sm'></i></a></th>
                          <?php elseif ($o==7): ?>
                            <th nowrap colspan="2"><a href="<?= site_url("statistik/index/$lap/8")?>">Perempuan <i class='fa fa-sort-desc fa-sm'></i></a></th>
                          <?php else: ?>
                            <th nowrap colspan="2"><a href="<?= site_url("statistik/index/$lap/7")?>">Perempuan <i class='fa fa-sort fa-sm'></i></a></th>
                          <?php endif; ?>
												<?php endif; ?>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($main as $data): ?>
												<?php if ($lap>50) $tautan_jumlah = site_url("program_bantuan/detail/1/$lap/1"); ?>
												<tr>
													<td><?= $data['no']?></td>
													<td><?= strtoupper($data['nama']);?></td>
													<td>
														<?php if ($lap==21 OR $lap==22 OR $lap==23 OR $lap==24 OR $lap==25 OR $lap==26 OR $lap==27 OR "$lap"=='kelas_sosial' OR "$lap"=='bantuan_keluarga'): ?>
															<a href="<?= site_url("keluarga/statistik/$lap/$data[id]")?>/0" <?php if ($data['id']=='JUMLAH'): ?>class="disabled"<?php endif; ?>><?= $data['jumlah']?></a>
														<?php else: ?>
															<?php if ($lap<50) $tautan_jumlah = site_url("penduduk/statistik/$lap/$data[id]"); ?>
															<a href="<?= $tautan_jumlah ?>/0" <?php if ($data['id']=='JUMLAH'): ?> class="disabled"<?php endif; ?>><?= $data['jumlah']?></a>
														<?php endif; ?>
													</td>
													<td><?= $data['persen'];?></td>
													<?php if ($lap==21 OR $lap==22 OR $lap==23 OR $lap==24 OR $lap==25 OR $lap==26 OR $lap==27 OR "$lap"=='kelas_sosial' OR "$lap"=='bantuan_keluarga'):
															$tautan_jumlah = site_url("keluarga/statistik/$lap/$data[id]");
															elseif ($lap<50): $tautan_jumlah = site_url("penduduk/statistik/$lap/$data[id]");endif;
													?>
													<?php if ($jenis_laporan == 'penduduk'): ?>
														<td><a href="<?= $tautan_jumlah?>/1" <?php if ($data['id']=='JUMLAH'): ?>class="disabled"<?php endif; ?>><?= $data['laki']?></a></td>
														<td><?= $data['persen1'];?></td>
														<td><a href="<?= $tautan_jumlah?>/2" <?php if ($data['id']=='JUMLAH'): ?>class="disabled"<?php endif; ?>><?= $data['perempuan']?></a></td>
														<td><?= $data['persen2'];?></td>
													<?php endif; ?>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>

                <?php if ($program_peserta):?>
                		<div class="row">
                			<div class="col-md-12">
                				<?php $detail = $program_peserta[0];?>
                				<div class="box box-info">
                					<div class="box-body">
                						<div class="row">
                							<div class="col-sm-12">
                								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                								<form id="mainform" name="mainform" action="" method="post">
                								<input type="hidden" name="id" value="<?php echo $this->uri->segment(4) ?>">
                									<div class="row">
                										<div class="col-sm-12">
                											<div class="row">
                												<div class="col-sm-9">
                													<div class="box-header with-border">
                														<h3 class="box-title">Daftar Peserta Program <?=$heading?></h3>
                													</div>
                												</div>
                											</div>
                										</div>
                										<div class="col-sm-12">
                										<?php $peserta = $program_peserta[1];?>
                											<div class="table-responsive">
                												<table class="table table-bordered table-striped dataTable table-hover">
                													<thead class="bg-gray disabled color-palette">
                														<tr>
                															<th rowspan="2" class="text-center">No</th>
                															<th rowspan="2" class="text-center">Program</th>
                															<th rowspan="2" nowrap class="text-center"><?= $detail["judul_peserta"]?></th>
                															<?php if (!empty($detail['judul_peserta_plus'])): ?>
                																<th rowspan="2" nowrap class="text-center"><?= $detail["judul_peserta_plus"]?></th>
                															<?php endif ;?>
                															<th rowspan="2" nowrap class="text-center"><?= $detail["judul_peserta_info"]?></th>
                															<th colspan="6" class="text-center">Identitas di Kartu Peserta</th>
                														</tr>
                														<tr>
                															<th class="text-center">Nama</th>
                															<th class="text-center">Alamat</th>
                														</tr>
                													</thead>
                													<tbody>
                														<?php $nomer = $paging->offset;?>
                														<?php if (is_array($peserta)): ?>
                															<?php foreach ($peserta as $key=>$item): $nomer++;?>
                																<tr>
                																	<td class="text-center"><?= $nomer?></td>
                																	<td nowrap><?= strtoupper($item['program_plus']);?></td>
                																	<?php $id_peserta = ($detail['sasaran'] == 4) ? $item['peserta'] : $item['nik'] ?>
                																	<td nowrap class="text-center"><a href="<?= site_url("program_bantuan/peserta/$detail[sasaran]/$id_peserta/")?>" title="Daftar program untuk peserta"><?= $item["peserta_nama"] ?></a></td>
                																	<?php if (!empty($item['peserta_plus'])): ?>
                																		<td nowrap><?= $item["peserta_plus"]?></td>
                																	<?php endif; ?>
                																	<td nowrap><?= $item["peserta_info"]?></td>
                																	<td><?= $item["kartu_nama"];?></td>
                																	<td><?= $item["kartu_alamat"];?></td>
                																</tr>
                															<?php endforeach; ?>
                														<?php endif; ?>
                													</tbody>
                												</table>
                											</div>
                										</div>

                									</div>
                                  <div class="row">
                                    <div class="col-sm-6">
                                      <div class="dataTables_length">
                                        <form id="paging" action="<?= site_url("program_bantuan/detail/1/$detail[id]")?>" method="post" class="form-horizontal">
                                         <label>
                                            Tampilkan
                                            <select name="per_page" class="form-control input-sm" onchange="$('#mainform').submit();" id="per_page_input">
                      	                      <option value="20" <?php selected($per_page, 20); ?> >20</option>
                                              <option value="50" <?php selected($per_page, 50); ?> >50</option>
                                              <option value="100" <?php selected($per_page, 100); ?> >100</option>
                                            </select>
                        	                  Dari
                                            <strong><?= $paging->num_rows?></strong>
                                            Total Data
                                          </label>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                								</form>
                								</div>
                							</div>
                						</div>
                					</div>
                				</div>
                			</div>
                		</div>
                <?php endif;?>

							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
  </div>
