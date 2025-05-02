<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="anggaran">
	<div class="relative-row ptb-10">
		<div class="container-page">
			<div class="box-container">
				<div class="head-module-center border-grey-soft flexcenter" style="margin-bottom:5px;"><h1>Transparansi Anggaran</h1></div>
				<div class="margin-min5">
					<?php foreach ($data_widget as $subdata_name => $subdatas): ?>
						<div class="column-3">
							<div class="pd-lr-5">
								<?php
								$sebutan_desa = ucfirst(setting('sebutan_desa'));
								$laporan = $subdatas['laporan'];
								$replacement = ($sebutan_desa === 'Desa') ? 'Desa' : (($sebutan_desa === 'Kalurahan') ? 'Kal' : substr($sebutan_desa, 0, 1));
								$laporan = str_replace('Des', $replacement, $laporan);
								?>
								<h2 class="flexcenter bg-gradient-hor"><?= $laporan; ?></h2>
								<div class="box-anggaran bg-white border-grey-soft">
									<div class="withscroll" style="padding:0 10px 40px;">
										<?php foreach ($subdatas as $key => $subdata): ?>
											<?php if (! is_array($subdata)) continue; ?>
											<?php if($subdata['judul'] != null and $key != 'laporan' and $subdata['realisasi'] != 0 or $subdata['anggaran'] != 0): ?>
												<div class="ptb-10">
													<h3>
													<?=
													\Illuminate\Support\Str::of($subdata['judul'])
														->title()
														->whenContains('Desa', function (\Illuminate\Support\Stringable $string) {
															if (! in_array($string, ['Dana Desa'])) {
																return $string->replace('Desa', setting('sebutan_desa'));
															}
														}, function (\Illuminate\Support\Stringable $string) {
															if (! in_array($string, [
																'Swadaya, Partisipasi dan Gotong Royong',
																'Bagi Hasil Pajak Dan Retribusi', 
																'Bantuan Keuangan Provinsi',
																'Bantuan Keuangan Kabupaten/Kota',
																'Penerimaan Dari Hasil Kerjasama Dengan Pihak Ketiga',
																'Koreksi Kesalahan Belanja Tahun-Tahun Sebelumnya',
																'Bunga Bank',
																'Hibah dan Sumbangan dari Pihak Ketiga',
																'Lain-Lain Pendapatan Desa Yang Sah',
																'Lain - Lain Pendapatan Asli Desa Yang Sah'
																])) {
																return $string->append(' ' . setting('sebutan_desa'));
															}
														})
														->title();
													?>
													</h3>
													<div class="flexcenter" style="opacity:0.7;margin:0 0 0;padding:0;line-height:1.1;">Realisasi | Anggaran</div>
													<p><?= rupiah24($subdata['realisasi'], 'Rp. ') ?><span style="float:right;"><?= rupiah24($subdata['anggaran'], 'Rp. ') ?></span></p>
													<div class="progress2 progress-moved">
														<div class="progress-bar2" role="progressbar" style="text-align:right;width: <?= $subdata['persen'] ?>%" aria-valuenow="<?= $subdata['persen'] ?>" aria-valuemin="0" aria-valuemax="100"><span class="persen-progress"><?= $subdata['persen'] ?>%</span></div>
														<style>
															@keyframes progress {
																10% {width: 0%;}
																100% {width:<?= $subdata['persen'] ?>;}
															}
														</style>
													</div>
												</div>
											<?php endif; ?>
										<?php endforeach; ?>
										<div class="gradient-white-bottom"></div>
									</div>	
								</div>	
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
