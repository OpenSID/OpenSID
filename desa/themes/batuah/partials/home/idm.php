<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<style>
.idm-box{position:relative;width:calc(25% - 10px);margin:5px;text-align:center;border-radius:5px;overflow:hidden;}
.idm-head{padding:0 10px;height:50px;font-size:100%;margin:0;line-height:1;text-transform:uppercase;color:#fff;background:rgba(0,0,0,0.4);text-align:center;}
.idm-inner{position:relative;overfloe:hidden;padding:25px 15px;}
.idm-inner p{font-size:100%;margin:0;line-height:1.2;padding:0;color:#fff;font-weight:bold;}
@media (max-width: 992px) {
    .idm-box{width:calc(50% - 10px);}
}
</style>
<div class="col-default">
<div class="bodypage bgwhite bordergrey1">
	<div class="center-head bggrad-color2 flexcenter">
		<div class="icon-titlepage bgcolor-1 flexcenter"><svg viewBox="0 0 24 24"><path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12,6A6,6 0 0,0 6,12A6,6 0 0,0 12,18A6,6 0 0,0 18,12A6,6 0 0,0 12,6M12,8A4,4 0 0,1 16,12A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8M12,10A2,2 0 0,0 10,12A2,2 0 0,0 12,14A2,2 0 0,0 14,12A2,2 0 0,0 12,10Z" /></svg></div>
		<h1>IDM</h1>
	</div>
	<div class="pagebox">
		<div class="statishead">
			<h1>Status IDM <?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></h1>
		</div>
		<div class="width-default">
			<?php if ($idm->error_msg): ?>
				<div class="alert alert-danger">
				<?= $idm->error_msg ?>
				</div>
			<?php else: ?>
				<div class="rowsame margin-minlr-5">
					<div class="idm-box bgbiru">
						<div class="idm-head flexcenter">SKOR IDM SAAT INI</div>
						<div class="idm-inner">
						<p><?= number_format($idm->SUMMARIES->SKOR_SAAT_INI, 4) ?></p>
						</div>
					</div>
					<div class="idm-box bgorange">
						<div class="idm-head flexcenter">STATUS IDM</div>
						<div class="idm-inner">
						<p><?= $idm->SUMMARIES->STATUS ?></p>
						</div>
					</div>
					<div class="idm-box bgtoska">
						<div class="idm-head flexcenter">SKOR IDM MINIMAL</div>
						<div class="idm-inner">
						<p><?= number_format($idm->SUMMARIES->SKOR_MINIMAL, 4) ?></p>
						</div>
					</div>
					<div class="idm-box bgpink">
						<div class="idm-head flexcenter">TARGET STATUS</div>
						<div class="idm-inner">
						<p><?= $idm->SUMMARIES->TARGET_STATUS ?></p>
						</div>
					</div>
				</div>
				<div class="row" style="margin-left:-5px;margin-right:-5px;">
					<div class="col-sm-12" style="padding-left:5px;padding-right:5px;">
					<table class="table table-bordered table-striped dataTable table-hover tablesmall-text">
						<tbody>
											<tr>
												<td width="30%">PROVINSI</td>
												<td width="1">:</td>
												<td><?= $idm->IDENTITAS[0]->nama_provinsi ?></td>
											</tr>
											<tr>
												<td>KABUPATEN</td>
												<td> : </td>
												<td><?= $idm->IDENTITAS[0]->nama_kab_kota ?></td>
											</tr>
											<tr>
												<td><?= strtoupper($this->setting->sebutan_kecamatan) ?></td>
												<td> : </td>
												<td><?= $idm->IDENTITAS[0]->nama_kecamatan ?></td>
											</tr>
											<tr>
												<td><?= strtoupper($this->setting->sebutan_desa) ?></td>
												<td> : </td>
												<td><?= $idm->IDENTITAS[0]->nama_desa ?></td>
											</tr>
							</tbody>
						</table>
					</div>
					<div class="col-lg-12 col-xs-12" style="padding-left:5px;padding-right:5px;">
						<figure class="highcharts-figure">
							<div id="container"></div>
						</figure>
					</div>
					
					<div class="col-md-12" style="padding-left:5px;padding-right:5px;">
									<figure class="highcharts-figure">
										<div id="container"></div>
									</figure>
								</div>
				</div>
				<div class="row" style="margin-left:-5px;margin-right:-5px;">
					<div class="col-sm-12" style="padding-left:5px;padding-right:5px;">
						<div class="table-responsive">
							<table class="table table-bordered dataTable table-striped table-hover tabel-daftar tablesmall-text">
								<thead>
									<tr>
										<th rowspan="2" class="padat">NO</th>
										<th rowspan="2" >INDIKATOR IDM</th>
										<th rowspan="2" >SKOR</th>
										<th rowspan="2" >KETERANGAN</th>
										<th rowspan="2" nowrap>KEGIATAN YANG DAPAT DILAKUKAN</th>
										<th rowspan="2" >+NILAI</th>
										<th colspan="6" class="text-center">YANG DAPAT MELAKSANAKAN KEGIATAN</th>
									</tr>
									<tr>
										<th>PUSAT</th>
										<th>PROVINSI</th>
										<th>KABUPATEN</th>
										<th>DESA</th>
										<th>CSR</th>
										<th>LAINNYA</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($idm->ROW as $data): ?>
										<tr class="<?php empty($data->NO) && print 'judul'; ?> ">
											<td class="text-center"><?= $data->NO ?></td>
											<td style="min-width: 150px;"><?= $data->INDIKATOR ?></td>
											<td class="padat"><?= $data->SKOR ?></td>
											<td style="min-width: 250px;"><?= $data->KETERANGAN ?></td>
											<td><?= $data->KEGIATAN ?></td>
											<td class="padat"><?= $data->NILAI ?></td>
											<td><?= $data->PUSAT ?></td>
											<td><?= $data->PROV ?></td>
											<td><?= $data->KAB ?></td>
											<td><?= $data->DESA ?></td>
											<td><?= $data->CSR ?></td>
											<td><?= $data->SKOR[INDIKATOR['IKS 2020']] ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
</div>

<?php if (! $idm->error_msg): ?>
	<script type="text/javascript">
		$(document).ready(function () {

			Highcharts.chart('container', {
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 45
				}
			},
			title: {
				text: 'Indeks Desa Membangun (IDM) <?= $tahun; ?>'
			},
			subtitle: {
				text: 'SKOR : IKS, IKE, IKL'
			},

			plotOptions: {
				series: {
					colorByPoint: true
				},
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					showInLegend: true,
					depth: 45,
					innerSize: 70,
					dataLabels: {
						enabled: true,
						format: '<b>{point.name}</b>: {point.y:,.2f} / {point.percentage:.1f} %'
					}
				}
			},
			series: [{
				name: 'SKOR',
						shadow: 1,
						border: 1,
				data: [
					['IKS', <?= $idm->ROW[35]->SKOR ?>],
					['IKE', <?= $idm->ROW[48]->SKOR ?>],
					['IKL', <?= $idm->ROW[52]->SKOR ?>]
				]
			}]
			});
		});
	</script>
<?php endif; ?>