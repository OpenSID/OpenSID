<div class="content-wrapper">
	<section class="content-header">
		<h1>Laporan Kelompok Rentan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda') ?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li class="active">Laporan Kelompok Rentan</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<form id="mainform" name="mainform" action="<?= site_url('laporan/bulan') ?>" method="post" class="form-horizontal">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url('laporan_rentan/cetak') ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  target="_blank"><i class="fa fa-print "></i> Cetak</a>
							<a href="<?= site_url("laporan_rentan/excel/{$lap}") ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  target="_blank"><i class="fa  fa-download"></i> Unduh</a>
						</div>
						<div class="box-header  with-border">
							<h4 class="text-center"><strong>PEMERINTAH KABUPATEN/KOTA <?= strtoupper($config['nama_kabupaten']) ?></strong></h4>
							<h5 class="text-center"><strong>DATA PILAH KEPENDUDUKAN MENURUT UMUR DAN FAKTOR KERENTANAN (LAMPIRAN A - 9)</strong></h5>
						</div>
						<div class="box-header  with-border">
							<div class="form-group">
								<label class="col-sm-2 col-md-1 control-label" for="kelurahan"><?= ucwords($this->setting->sebutan_desa) ?>/Kel</label>
								<div class="col-sm-4 col-md-2">
									<input type="text" class="form-control input-sm" value="<?= $config['nama_desa'] ?>" disabled/></input>
								</div>
								<label class="col-sm-2 col-md-1 control-label" for="kecamatan"><?= ucwords($this->setting->sebutan_kecamatan) ?></label>
								<div class="col-sm-4 col-md-2">
									<input type="text" class="form-control input-sm" value="<?= $config['nama_kecamatan'] ?>" disabled/></input>
								</div>
								<label class="col-sm-2 col-md-1 control-label" for="laporan">Lap. Bulan</label>
								<div class="col-sm-4 col-md-2">
									<input type="text" class="form-control input-sm" value="<?= getBulan(date('m')) ?>" disabled/></input>
								</div>
								<label class="col-sm-2 col-md-1 control-label" for="filter"><?= ucwords($this->setting->sebutan_dusun) ?></label>
								<div class="col-sm-4 col-md-2">
									<select class="form-control input-sm" name="dusun" onchange="formAction('mainform','<?= site_url('laporan_rentan/dusun') ?>')">
										<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun) ?></option>
										<?php foreach ($list_dusun as $data): ?>
											<option value="<?= $data['dusun'] ?>" <?= selected($dusun, $data['dusun']) ?>><?= $data['dusun'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
						<div class="box-body">
							<?php if ($dusun != ''): ?>
								<h4>DATA PILAH <?= strtoupper($this->setting->sebutan_dusun) ?> <?= $dusun ?></h4>
							<?php endif; ?>
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover nowrap">
									<thead class="bg-gray">
										<tr>
											<th rowspan="2" class="text-center"><?= ucwords($this->setting->sebutan_dusun) ?></th>
											<th rowspan="2" class="text-center">RW</th>
											<th rowspan="2" class="text-center">RT</th>
											<th colspan="2" class="text-center">KK</th>
											<th colspan="6" class="text-center">Kondisi dan Kelompok Umur</th>
											<th colspan="7" class="text-center">Cacat</th>
											<th colspan="2" class="text-center">Sakit Menahun</th>
											<th rowspan="2" class="text-center" >Hamil</th>
										</tr>
										<tr>
											<th class="text-center">L</th>
											<th class="text-center">P</th>
											<th class="text-center">Dibawah 1 Tahun</th>
											<th class="text-center">1-5 Tahun</th>
											<th class="text-center">6-12 Tahun</th>
											<th class="text-center">13-15 Tahun</th>
											<th class="text-center">16-18 Tahun</th>
											<th class="text-center">Diatas 60 Tahun</th>
											<th class="text-center">Cacat Fisik</th>
											<th class="text-center">Cacat Netra/ Buta</th>
											<th class="text-center">Cacat Rungu/ Wicara</th>
											<th class="text-center">Cacat Mental/ Jiwa</th>
											<th class="text-center">Cacat Fisik dan Mental</th>
											<th class="text-center">Cacat Lainnya</th>
											<th class="text-center">Tidak Cacat</th>
											<th class="text-center">L</th>
											<th class="text-center">P</th>
										</tr>
									</thead>
									<tbody>
										<?php
                                            $bayi = 0;
            $balita                               = 0;
            $sd                                   = 0;
            $smp                                  = 0;
            $sma                                  = 0;
            $lansia                               = 0;
            $cacat                                = 0;
            $sakit_L                              = 0;
            $sakit_P                              = 0;
            $hamil                                = 0;
            $jenis_cacat                          = ['cacat_fisik', 'cacat_netra', 'cacat_rungu', 'cacat_mental', 'cacat_fisik_mental', 'cacat_lainnya', 'tidak_cacat'];
            $total_cacat                          = [];

            foreach ($main as $data): $id_cluster = $data['id_cluster']; ?>
											<tr>
												<td class="text-right"><?= $data['dusunnya'] ?></td>
												<td class="text-right"><?= $data['rw'] ?></td>
												<td class="text-right"><?= $data['rt'] ?></td>
												<td class="text-right"><a href="<?= site_url("penduduk/lap_statistik/{$id_cluster}/1") ?>"><?= $data['L'] ?></a></td>
												<td class="text-right"><a href="<?= site_url("penduduk/lap_statistik/{$id_cluster}/2") ?>"><?= $data['P'] ?></a></td>
												<td class="text-right"><a href="<?= site_url("penduduk/lap_statistik/{$id_cluster}/3") ?>"><?= $data['bayi'] ?></a></td>
												<td class="text-right"><a href="<?= site_url("penduduk/lap_statistik/{$id_cluster}/4") ?>"><?= $data['balita'] ?></a></td>
												<td class="text-right"><a href="<?= site_url("penduduk/lap_statistik/{$id_cluster}/5") ?>"><?= $data['sd'] ?></a></td>
												<td class="text-right"><a href="<?= site_url("penduduk/lap_statistik/{$id_cluster}/6") ?>"><?= $data['smp'] ?></a></td>
												<td class="text-right"><a href="<?= site_url("penduduk/lap_statistik/{$id_cluster}/7") ?>"><?= $data['sma'] ?></a></td>
												<td class="text-right"><a href="<?= site_url("penduduk/lap_statistik/{$id_cluster}/8") ?>"><?= $data['lansia'] ?></a></td>
												<?php foreach ($jenis_cacat as $key => $value): ?>
													<?php $kode_cacat = $key + 1; ?>
													<td class="text-right"><a href="<?= site_url("penduduk/lap_statistik/{$id_cluster}/9{$kode_cacat}") ?>"><?= $data[$value] ?></a></td>
												<?php endforeach; ?>
												<td class="text-right"><a href="<?= site_url("penduduk/lap_statistik/{$id_cluster}/10") ?>"><?= $data['sakit_L'] ?></a></td>
												<td class="text-right"><a href="<?= site_url("penduduk/lap_statistik/{$id_cluster}/11") ?>"><?= $data['sakit_P'] ?></a></td>
												<td class="text-right"><a href="<?= site_url("penduduk/lap_statistik/{$id_cluster}/12") ?>"><?= $data['hamil'] ?></a></td>
												<?php
                    $bayi += $data['bayi'];
                $balita += $data['balita'];
                $sd += $data['sd'];
                $smp += $data['smp'];
                $sma += $data['sma'];
                $lansia += $data['lansia'];
                $cacat += $data['cacat'];
                $sakit_L += $data['sakit_L'];
                $sakit_P += $data['sakit_P'];
                $hamil += $data['hamil'];

                foreach ($jenis_cacat as $key => $val):
                    $total_cacat[$key] += $data[$val];
                endforeach;
                ?>
											</tr>
										<?php endforeach; ?>
									</tbody>
									<tfoot class="bg-gray disabled color-palette">
										<tr>
											<th colspan="5" class="text-right">Total</th>
											<th class="text-right"><?= $bayi; ?></th>
											<th class="text-right"><?= $balita; ?></th>
											<th class="text-right"><?= $sd; ?></th>
											<th class="text-right"><?= $smp; ?></th>
											<th class="text-right"><?= $sma; ?></th>
											<th class="text-right"><?= $lansia; ?></th>
											<?php foreach ($total_cacat as $cacat): ?>
												<th class="total text-right"><?= $cacat; ?></th>
											<?php endforeach; ?>
											<th class="text-right"><?= $sakit_L; ?></th>
											<th class="text-right"><?= $sakit_P; ?></th>
											<th class="text-right"><?= $hamil; ?></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
</div>

