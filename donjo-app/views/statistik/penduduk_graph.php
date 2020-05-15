
<!-- Pengaturan Grafik (Graph) Data Statistik-->
<script type="text/javascript">
	var chart;
	$(document).ready(function()
	{
		chart = new Highcharts.Chart(
		{
			chart:
			{
				renderTo: 'chart',
				defaultSeriesType: 'column'
			},
			title:
			{
				text: 'Statistik <?= $stat?>'
			},
			xAxis:
			{
				title:
				{
					text: '<?= $stat?>'
				},
        categories: [
					<?php $i=0; foreach ($main as $data): $i++;?>
					  <?php if ($data['jumlah'] != "-"): ?><?= "'$i',";?><?php endif; ?>
					<?php endforeach;?>
				]
			},
			yAxis:
			{
				title:
				{
					text: 'Jumlah Populasi'
				}
			},
			legend:
			{
				layout: 'vertical',
        enabled:false
			},
			plotOptions:
			{
				series:
				{
          colorByPoint: true
        },
      column:
			{
				pointPadding: 0,
				borderWidth: 0
			}
		},
		series: [
		{
			shadow:1,
			border:1,
			data: [
				<?php foreach ($main as $data): ?>
				  <?php if ($data['nama'] != "BELUM MENGISI" and $data['nama'] != "TOTAL" and $data['nama'] != "JUMLAH" and $data['nama'] != "PENERIMA"): ?>
					  <?php if ($data['jumlah'] != "-"): ?>
							['<?= strtoupper($data['nama'])?>',<?= $data['jumlah']?>],
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach;?>]
			}]
		});
	});
</script>
<!-- Highcharts -->
<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Statistik Kependudukan (Grafik)</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Statistik Kependudukan (Grafik)</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-3">
          <?php $this->load->view('statistik/laporan/side-menu.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-body">
							<div id="chart"> </div>
							<div class="col-sm-12">
								<?php if ($lap < 50): ?>
									<h4 class="box-title"><b>Data Kependudukan menurut <?= ($stat);?></b></h4>
								<?php else: ?>
									<h4 class="box-title"><b>Data Peserta Program <?= ($program['nama'])?></b></h4>
								<?php endif; ?>
								<?php if($lap <= 20 AND $lap <> 'kelas_sosial' AND $lap <> 'bantuan_keluarga' AND $lap <> 'bantuan_penduduk') : ?>
									<div class="row">
										<div class="col-sm-12 form-inline">
											<form action="" id="mainform" method="post">
												<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('statistik/dusun/1/'.$lap)?>')">
													<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
													<?php foreach ($list_dusun AS $data): ?>
														<option value="<?= $data['dusun']?>" <?php $dusun == $data['dusun'] and print('selected') ?>><?= strtoupper($data['dusun'])?></option>
													<?php endforeach; ?>
												</select>
												<?php if ($dusun): ?>
													<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('statistik/rw/1/'.$lap)?>')" >
														<option value="">RW</option>
														<?php foreach ($list_rw AS $data): ?>
															<option value="<?= $data['rw']?>" <?php $rw == $data['rw'] and print('selected') ?>><?= $data['rw']?></option>
														<?php endforeach; ?>
													</select>
												<?php endif; ?>
												<?php if ($rw): ?>
													<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('statistik/rt/1/'.$lap)?>')">
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
									<table class="table table-bordered dataTable table-hover nowrap">
										<thead>
											<tr>
												<th width='5%'>No</th>
												<th width='50%'>Jenis Kelompok</th>
												<?php if ($jenis_laporan == 'penduduk'): ?>
													<th width='15%' colspan="2">Laki-Laki</th>
													<th width='15%' colspan="2">Perempuan</th>
												<?php endif; ?>
												<th width='15%'colspan="2">Jumlah</th>
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
							</div>

							<?php if (in_array($lap, array('bantuan_keluarga', 'bantuan_penduduk'))):?>
                <section class="content" id="maincontent">
                  <div class="row">
                    <div class="col-md-12">
                      <input id="stat" type="hidden" value="<?=$lap?>">
                      <div class="box box-info">
                        <div class="box-header with-border" style="margin-bottom: 15px;">
                          <h3 class="box-title"><?= $heading ?></h3>
                        </div>
                        <div style="margin-right: 1rem; margin-left: 1rem;">
                          <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="peserta_program">
                              <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Program</th>
                                  <th>Nama Peserta</th>
                                  <th>Alamat</th>
                                </tr>
                              </thead>
                              <tfoot>
                              </tfoot>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>

                <script type="text/javascript">
                  $(document).ready(function() {

                    var url = "<?= site_url('statistik/ajax_peserta_program_bantuan')?>";
                      table = $('#peserta_program').DataTable({
                        'processing': true,
                        'serverSide': true,
                        "pageLength": 10,
                        'order': [],
                        "ajax": {
                          "url": url,
                          "type": "POST",
                          "data": {stat: $('#stat').val()}
                        },
                        //Set column definition initialisation properties.
                        "columnDefs": [
                          {
                            "targets": [ 0, 3 ], //first column / numbering column
                            "orderable": false, //set not orderable
                          },
                        ],
                        'language': {
                          'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
                        },
                        'drawCallback': function (){
                            $('.dataTables_paginate > .pagination').addClass('pagination-sm no-margin');
                        }
                      });

                  } );
                </script>

              <?php endif;?>

						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
