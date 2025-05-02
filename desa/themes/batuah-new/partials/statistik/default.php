<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
	<script type="text/javascript">
    let chart;
    const rawData = Object.values(<?= json_encode($stat) ?>);
    const type = '<?= $tipe == 1 ? 'column' : 'pie' ?>';
    const legend = Boolean(<?= (bool)$tipe ?>);
    let categories = [];
    let data = [];
    let i = 1;
    let status_tampilkan = true;
    for (const stat of rawData) {
        if (stat.nama !== 'TOTAL' && stat.nama !== 'JUMLAH' && stat.nama != 'PENERIMA') {
            let filteredData = [stat.nama, parseInt(stat.jumlah)];
            categories.push(i);
            data.push(filteredData);
            i++;
        }
    }

    function tampilkan_nol(tampilkan = false) {
        if (tampilkan) {
            $(".nol").parent().show();
        } else {
            $(".nol").parent().hide();
        }
    }

    function toggle_tampilkan() {
        $('#showData').click();
        tampilkan_nol(status_tampilkan);
        status_tampilkan = !status_tampilkan;
        if (status_tampilkan) $('#tampilkan').text('Tampilkan Nol');
        else $('#tampilkan').text('Sembunyikan Nol');
    }

    function switchType(){
        var chartType = chart_penduduk.series[0].type;
        chart_penduduk.series[0].update({
            type: (chartType === 'pie') ? 'column' : 'pie'
        });
    }

    $(document).ready(function () {
        tampilkan_nol(false);
        if (<?=$this->setting->statistik_chart_3d?>) {
            chart_penduduk = new Highcharts.Chart({
                chart: {
                    renderTo: 'container',
                    options3d: {
                        enabled: true,
                        alpha: 45
                    }
                },
                title: 0,
                yAxis: {
                    showEmpty: false,
                },
                xAxis: {
                    categories: categories,
                },
                plotOptions: {
                    series: {
                        colorByPoint: true
                    },
                    column: {
                        pointPadding: -0.1,
                        borderWidth: 0,
                        showInLegend: false,
                        depth: 45
                    },
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        showInLegend: true,
                        depth: 45,
                        innerSize: 70
                    }
                },
                legend: {
                    enabled: legend
                },
                series: [{
                    type: type,
                    name: 'Jumlah Populasi',
                    shadow: 1,
                    border: 1,
                    data: data
                }]
            });
        } else {
            chart_penduduk = new Highcharts.Chart({
                chart: {
                    renderTo: 'container'
                },
                title: 0,
                yAxis: {
                    showEmpty: false,
                },
                xAxis: {
                    categories: categories,
                },
                plotOptions: {
                    series: {
                        colorByPoint: true
                    },
                    column: {
                        pointPadding: -0.1,
                        borderWidth: 0,
                        showInLegend: false,
                    },
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        showInLegend: true,
                    }
                },
                legend: {
                    enabled: legend
                },
                series: [{
                    type: type,
                    name: 'Jumlah Populasi',
                    shadow: 1,
                    border: 1,
                    data: data
                }]
            });
        }

        $('#showData').click(function () {
            $('tr.lebih').show();
            $('#showData').hide();
            tampilkan_nol(false);
        });

    });
</script>
<?php $this->load->view("$folder_themes/plus/header_side"); ?>
<div class="mainpage">
	<div class="margin-side-10">
	<div class="mainbodyarea">
		<div class="bigarea hiddenrelative">
		<div class="bigarea-margin">
			<div class="col-default margin-top-10">
				<div class="bodypage bgwhite bordergrey" style="padding-bottom:20px;">
				<div class="center-head bggrad-color2 flexleft">
						<div class="icon-titlepage bgcolor-1 flexcenter"><svg viewBox="0 0 24 24">
								<path d="M3,4H7V8H3V4M9,5V7H21V5H9M3,10H7V14H3V10M9,11V13H21V11H9M3,16H7V20H3V16M9,17V19H21V17H9" />
							</svg></div>
						<h1>Statistik</h1>
					</div>
				<div class="padding-leftright-10">
					
					<div class="stat-title flexcenter">
						<h1>Data Demografi Berdasar <?= $heading ?></h1>
					</div>
					<div class="noprint">
						<?php if(isset($list_tahun)): ?>
						<div class="hiddenrelative flexcenter" style="width:100%;text-align:center;margin:0 auto;">
						<div class="flexcenter" style="margin-bottom: 20px;">
							<label style="padding-top: 5px;margin:0 10px 0 0;" class="control-label">Tahun: </label>
								<select class="form-control input-sm" id="tahun" name="tahun">
									<option selected="" value="">Semua</option>
									<?php foreach ($list_tahun as $item_tahun): ?>
									<option <?= $item_tahun == ($selected_tahun ?? NULL) ? 'selected' : '' ?> value="<?= $item_tahun ?>">
										<?= $item_tahun ?></option>
									<?php endforeach ?>
								</select>
						</div>
						</div>
						<?php endif ?>
						<div class="flexcenter mb-15">
							<a class="b-btn bgcolor-1 flexcenter <?= ($tipe == 0) ? 'bgcolor2' : 'bgcolor2' ?> btn-xs" onclick="switchType();" style="cursor:pointer;margin:0 3px;">
								<p>Pie Chart</p>
							</a>
							<a class="b-btn bgcolor-3 flexcenter <?= ($tipe == 1) ? 'bgcolor1' : 'bgcolor1' ?> btn-xs" onclick="switchType();" style="cursor:pointer;margin:0 3px;">
								<p>Bar Chart</p>
							</a>
						</div>
					</div>
					<div id="container" style="margin:0 auto;"></div>
					<div id="contentpane">
						<div class="ui-layout-north panel top"></div>
					</div>
					<div class="width-default mt-20">
						<div class="table-responsive">
							<table class="table table-striped">
						<thead>
							<tr>
								<th rowspan="2">Kode</th>
								<th rowspan="2" style='text-align:left;'>Kelompok</th>
								<th colspan="2">Jumlah</th>
								<th colspan="2">Laki-laki</th>
								<th colspan="2">Perempuan</th>
							</tr>
							<tr>
								<th style='text-align:right'>n</th><th style='text-align:right'>%</th>
								<th style='text-align:right'>n</th><th style='text-align:right'>%</th>
								<th style='text-align:right'>n</th><th style='text-align:right'>%</th>
							</tr>
						</thead>
						<tbody>
							<?php $i=0; $l=0; $p=0; $hide=""; $h=0; $jm1=1; $jm = count($stat ?? []); ?>
							<?php foreach ($stat as $data):?>
								<?php $jm1++; if (1):?>
									<?php $h++; if ($h > 12 AND $jm > 10): $hide="lebih"; ?>
									<?php endif;?>
									<tr class="<?=$hide?>">
										<td class="angka">
											<?php if ($jm1 > $jm - 2):?>
												<?=$data['no']?>
											<?php else:?>
												<?=$h?>
											<?php endif;?>
										</td>
										<td><?=$data['nama']?></td>
										<td class="angka <?php ($jm1 <= $jm - 2) and ($data['jumlah'] == 0) and print('nol')?>"><?=$data['jumlah']?></td>
										<td class="angka"><?=$data['persen']?></td>
										<td class="angka"><?=$data['laki']?></td>
										<td class="angka"><?=$data['persen1']?></td>
										<td class="angka"><?=$data['perempuan']?></td>
										<td class="angka"><?=$data['persen2']?></td>
									</tr>
									<?php $i += $data['jumlah'];?>
									<?php $l += $data['laki']; $p += $data['perempuan'];?>
								<?php endif;?>
							<?php endforeach;?>
						</tbody>
					</table>
							<div class="noprint">
								<div class="flexcenter mt-20 ">
									<?php if ($hide == "lebih") : ?>
										<button style='margin:0 5px' class='tombol-default bg-style3 borderradius-5 flexcenter' id='showData'>Selengkapnya...</button>
									<?php endif; ?>
									<button style='margin:0 5px' id='tampilkan' onclick="toggle_tampilkan();" class="tombol-default bg-style3 borderradius-5 flexcenter">Tampilkan Nol</button>
								</div>
							</div>
						</div>
						<div class="flexcenter mb-15">
							<p>Diperbarui pada : <?= tgl_indo($last_update); ?></p>
						</div>
					</div>
					<?php if ($this->setting->daftar_penerima_bantuan && $bantuan): ?>
							<div class="width-default mt-30">
								<input id="stat" type="hidden" value="<?=$st?>">
								<div class="headborder bordergrey1 flexcenter" style="margin:30px 0 15px;">
									<h2 class="bordercolor-1">Daftar <?= $heading ?></h2>
								</div>
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
							<script type="text/javascript">
				$(document).ready(function() {
					$('#tahun').change(function(){
						const current_url = window.location.href.split('?')[0]
						window.location.href = `${current_url}?tahun=${$(this).val()}`;
					})

					var url = "<?= site_url('first/ajax_peserta_program_bantuan') ?>?tahun=<?= $selected_tahun ?? '' ?>";
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
					<?php endif; ?>
				</div>
				</div>
			</div>
			<?php $this->load->view("$folder_themes/plus/middle_page"); ?>
		</div>
		</div>
		<div class="rightsidearea">
			<?php $this->load->view("$folder_themes/partials/statistik/statistik_nav"); ?>
			<?php $this->load->view("$folder_themes/plus/side"); ?>			
		</div>
	</div>
	</div>
</div>