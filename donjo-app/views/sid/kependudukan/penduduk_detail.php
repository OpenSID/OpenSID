<!-- TODO: Pindahkan ke external css -->
<style>
	.table {
		font-size: 12px;
	}
	.detail {
		margin-top: 5px;
		margin-bottom: 5px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Biodata Penduduk</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('penduduk/clear')?>"> Daftar Penduduk</a></li>
			<li class="active">Biodata Penduduk</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header">
							<a href="<?= site_url("penduduk/dokumen/{$penduduk['id']}")?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Manajemen Dokumen Penduduk" ><i class="fa fa-book"></i> Manajemen Dokumen</a>
							<?php if ($penduduk['status_dasar_id'] == 1): ?>
								<a href="<?= site_url("penduduk/form/{$p}/{$o}/{$penduduk['id']}")?>" class="btn btn-social btn-flat btn-warning btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Ubah Biodata" ><i class="fa fa-edit"></i> Ubah Biodata</a>
							<?php endif; ?>
							<a href="<?= site_url("penduduk/cetak_biodata/{$penduduk['id']}")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Biodata" target="_blank"><i class="fa fa-print"></i>Cetak Biodata</a>
							<?php if ($penduduk['status_dasar_id'] == 1 && ! empty($penduduk['id_kk'])): ?>
								<a href="<?= site_url("keluarga/anggota/{$p}/{$o}/{$penduduk['id_kk']}")?>" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Anggota Keluarga" ><i class="fa fa-users"></i> Anggota Keluarga</a>
							<?php endif; ?>
							<a href="<?= site_url('penduduk/clear')?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Penduduk">
								<i class="fa fa-arrow-circle-left"></i>Kembali Ke Daftar Penduduk
							</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<div class="box-header with-border">
										<h3 class="box-title">Biodata Penduduk (NIK : <?= $penduduk['nik']?>)</h3>
										<br>
										<?php if (! empty($penduduk['nama_pendaftar'])): ?>
											<p class="kecil">
												Terdaftar pada:
												<i class="fa fa-clock-o"></i><?= tgl_indo2($penduduk['created_at']); ?>
												<i class="fa fa-user"></i> <?= $penduduk['nama_pendaftar']?>
											</p>
										<?php else: ?>
											<p class="kecil">
												Terdaftar sebelum:
												<i class="fa fa-clock-o"></i><?= tgl_indo2($penduduk['created_at']); ?>
											</p>
										<?php endif; ?>
										<?php if (! empty($penduduk['nama_pengubah'])): ?>
											<p class="kecil">
												Terakhir diubah:
												<i class="fa fa-clock-o"></i><?= tgl_indo2($penduduk['updated_at']); ?>
												<i class="fa fa-user"></i> <?= $penduduk['nama_pengubah']?>
											</p>
										<?php endif; ?>
									</div>
								</div>
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover" >
											<tr>
												<td colspan="3">
													<img class="penduduk" src="<?= AmbilFoto($penduduk['foto'], '', $penduduk['id_sex'])?>" alt="Foto Penduduk">
												</td>
											</tr>
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover" >
													<tbody>
														<tr>
															<td>Status Dasar</td><td >:</td><td><span class="<?= ($penduduk['status_dasar_id'] != 1) ? 'label label-danger' : ''?>"><strong><?= strtoupper($penduduk['status_dasar'])?></strong></span></td>
														</tr>
														<tr>
															<td width="300">Nama</td>
															<td width="1">:</td>
															<td><?= strtoupper($penduduk['nama'])?></td>
														</tr>
														<tr>
															<td>Status Kepemilikan Identitas</td><td >:</td>
															<td>
																<table class="table table-bordered table-striped table-hover detail">
																	<tr>
																		<th>Wajib Identitas</th>
																		<th>Identitas-EL</th>
																		<th>Status Rekam</th>
																		<th>Tag ID Card</th>
																	</tr>
																	<tr>
																		<td><?= strtoupper($penduduk['wajib_ktp'])?></td>
																		<td><?= strtoupper($penduduk['ktp_el'])?></td>
																		<td><?= strtoupper($penduduk['status_rekam'])?></td>
																		<td><?= $penduduk['tag_id_card']?></td>
																	</tr>
																</table>
															</td>
														</tr>
														<tr>
															<td>Nomor Kartu Keluarga</td><td >:</td>
															<td>
																<?= $penduduk['no_kk']?>
																<?php if ($penduduk['status_dasar_id'] != '1' && $penduduk['no_kk'] != $penduduk['log_no_kk']): ?>
																	(waktu peristiwa {<?= $penduduk['status_dasar']?>}: {<?= $penduduk['log_no_kk']?>})
																<?php endif; ?>
															</td>
														</tr>
														<tr>
															<td>Nomor KK Sebelumnya</td><td >:</td><td><?= $penduduk['no_kk_sebelumnya']?></td>
														</tr>
														<tr>
															<td>Hubungan Dalam Keluarga</td><td >:</td><td><?= $penduduk['hubungan']?></td>
														</tr>
														<tr>
															<td>Jenis Kelamin</td><td >:</td><td><?= strtoupper($penduduk['sex'])?></td>
														</tr>
														<tr>
															<td>Agama</td><td >:</td><td><?= strtoupper($penduduk['agama'])?></td>
														</tr>
														<tr>
															<td>Status Penduduk</td><td >:</td><td><?= strtoupper($penduduk['status'])?></td>
														</tr>
														<tr>
															<th colspan="3" class="subtitle_head"><strong>DATA KELAHIRAN</strong></th>
														</tr>
														<tr>
															<td>Akta Kelahiran</td><td >:</td><td><?= strtoupper($penduduk['akta_lahir'])?></td>
														</tr>
														<tr>
															<td>Tempat / Tanggal Lahir</td><td >:</td><td><?= strtoupper($penduduk['tempatlahir'])?> / <?= strtoupper($penduduk['tanggallahir'])?></td>
														</tr>
														<tr>
															<td>Tempat Dilahirkan</td><td >:</td><td><?= $penduduk['tempat_dilahirkan_nama'] ?></td>
														</tr>
														<tr>
															<td>Jenis Kelahiran</td><td >:</td><td><?= $penduduk['jenis_kelahiran_nama'] ?></td>
														</tr>
														<tr>
															<td>Kelahiran Anak Ke</td><td >:</td><td><?= $penduduk['kelahiran_anak_ke'] ?></td>
														</tr>
														<tr>
															<td>Penolong Kelahiran</td><td >:</td><td><?= $penduduk['penolong_kelahiran_nama'] ?></td>
														</tr>
														<tr>
															<td>Berat Lahir</td><td >:</td><td><?= $penduduk['berat_lahir']?> Gram</td>
														</tr>
														<tr>
															<td>Panjang Lahir</td><td >:</td><td><?= $penduduk['panjang_lahir']?> cm</td>
														</tr>
														<tr>
															<th colspan="3" class="subtitle_head"><strong>PENDIDIKAN DAN PEKERJAAN</strong></th>
														</tr>
														<tr>
															<td>Pendidikan dalam KK</td><td >:</td><td><?= strtoupper($penduduk['pendidikan_kk'])?></td>
														</tr>
														<tr>
															<td>Pendidikan sedang ditempuh</td><td >:</td><td><?= strtoupper($penduduk['pendidikan_sedang'])?></td>
														</tr>
														<tr>
															<td>Pekerjaan</td><td >:</td><td><?= strtoupper($penduduk['pekerjaan'])?></td>
														</tr>
														<tr>
															<th colspan="3" class="subtitle_head"><strong>DATA KEWARGANEGARAAN</strong></th>
														</tr>
														<tr>
															<td>Suku/Etnis</td><td >:</td><td><?= strtoupper($penduduk['suku'])?></td>
														</tr>
														<tr>
															<td>Warga Negara</td><td >:</td><td><?= strtoupper($penduduk['warganegara'])?></td>
														</tr>
														<tr>
															<td>Nomor Paspor</td><td >:</td><td><?= strtoupper($penduduk['dokumen_pasport'])?></td>
														</tr>
														<tr>
															<td>Tanggal Berakhir Paspor</td><td >:</td><td><?= strtoupper($penduduk['tanggal_akhir_paspor'])?></td>
														</tr>
														<tr>
															<td>Nomor KITAS/KITAP</td><td >:</td><td><?= strtoupper($penduduk['dokumen_kitas'])?></td>
														</tr>
														<tr>
															<th colspan="3" class="subtitle_head"><strong>ORANG TUA</strong></th>
														</tr>
														<tr>
															<td>NIK Ayah</td><td >:</td><td><?= strtoupper($penduduk['ayah_nik'])?></td>
														</tr>
														<tr>
															<td>Nama Ayah</td><td >:</td><td><?= strtoupper($penduduk['nama_ayah'])?></td>
														</tr>
														<tr>
															<td>NIK Ibu</td><td >:</td><td><?= strtoupper($penduduk['ibu_nik'])?></td>
														</tr>
														<tr>
															<td>Nama Ibu</td><td >:</td><td><?= strtoupper($penduduk['nama_ibu'])?></td>
														</tr>
														<tr>
															<th colspan="3" class="subtitle_head"><strong>ALAMAT</strong></th>
														</tr>
														<tr>
															<td>Alamat</td><td >:</td><td><?= strtoupper($penduduk['alamat'])?></td>
														</tr>
														<tr>
															<td>Dusun</td><td >:</td><td><?= strtoupper($penduduk['dusun'])?></td>
														</tr>
														<tr>
															<td>RT/ RW</td><td >:</td><td><?= strtoupper($penduduk['rt'])?> / <?= $penduduk['rw']?></td>
														</tr>
														<tr>
															<td>Alamat Sebelumnya</td><td >:</td><td><?= strtoupper($penduduk['alamat_sebelumnya'])?></td>
														</tr>
														<tr>
															<td>Nomor Telepon</td><td >:</td><td><?= strtoupper($penduduk['telepon'])?></td>
														</tr>
														<tr>
															<td>Alamat Email</td><td >:</td><td><?= strtoupper($penduduk['email'])?></td>
														</tr>
														<tr>
															<td>Telegram</td><td >:</td><td><?= $penduduk['telegram']?></td>
														</tr>
														<tr>
															<td>Cara Hubung Warga</td><td >:</td><td><?= $penduduk['hubung_warga']?></td>
														</tr>
														<tr>
															<th colspan="3" class="subtitle_head"><strong>STATUS KAWIN</strong></th>
														</tr>
														<tr>
															<td>Status Kawin</td><td >:</td><td><?= strtoupper($penduduk['kawin'])?></td>
														</tr>
														<?php if ($penduduk['status_kawin'] != 1): ?>
															<tr>
																<td>Akta perkawinan</td><td >:</td><td><?= strtoupper($penduduk['akta_perkawinan'])?></td>
															</tr>
															<tr>
																<td>Tanggal perkawinan</td><td >:</td><td><?= strtoupper($penduduk['tanggalperkawinan'])?></td>
															</tr>
														<?php endif ?>
														<?php if ($penduduk['status_kawin'] != 1 && $penduduk['status_kawin'] != 2): ?>
															<tr>
																<td>Akta perceraian</td><td >:</td><td><?= strtoupper($penduduk['akta_perceraian'])?></td>
															</tr>
															<tr>
																<td>Akta perceraian</td><td >:</td><td><?= strtoupper($penduduk['tanggalperceraian'])?></td>
															</tr>
														<?php endif ?>
														<tr>
															<th colspan="3" class="subtitle_head"><strong>DATA KESEHATAN</strong></th>
														</tr>
														<tr>
															<td>Golongan Darah</td><td >:</td><td><?= $penduduk['golongan_darah']?></td>
														</tr>
														<tr>
															<td>Cacat</td><td >:</td><td><?= strtoupper($penduduk['cacat'])?></td>
														</tr>
														<tr>
															<td>Sakit Menahun</td><td >:</td><td><?= strtoupper($penduduk['sakit_menahun'])?></td>
														</tr>
														<?php if ($penduduk['status_kawin'] == 2): ?>
															<tr>
																<td>Akseptor KB</td><td >:</td><td><?= strtoupper($penduduk['cara_kb'])?></td>
															</tr>
														<?php endif ?>
														<?php if ($penduduk['id_sex'] == 2): ?>
															<tr>
																<td>Status Kehamilan</td><td >:</td><td><?= $penduduk['hamil'] == '1' ? 'HAMIL' : 'TIDAK HAMIL'?></td>
															</tr>
														<?php endif; ?>
														<tr>
															<td>Nama/Nomor Asuransi Kesehatan</td><td >:</td><td><?= $penduduk['asuransi'] . ' / ' . strtoupper($penduduk['no_asuransi']) ?></td>
														</tr>
														<tr>
															<td>Nomor BPJS Ketenagakerjaan</td><td >:</td><td><?= $penduduk['bpjs_ketenagakerjaan'] ?></td>
														</tr>

														<tr>
															<th colspan="3" class="subtitle_head"><strong>DATA LAINNYA</strong></th>
														</tr>
														<tr>
															<td>Bahasa</td><td >:</td><td><?= $penduduk['bahasa_nama']?></td>
														</tr>
														<tr>
															<td>Keterangan</td><td >:</td><td><?= $penduduk['ket']?></td>
														</tr>
													</tbody>
												</table>
											</div>
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover">
													<tbody>
														<tr>
															<th colspan="3" class="subtitle_head"><strong>PROGRAM BANTUAN</strong></th>
														</tr>
														<tr>
															<td>
																<div class="table-responsive">
																	<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
																		<thead class="bg-gray disabled color-palette">
																			<tr>
																				<th class="padat">No</th>
																				<th>Waktu / Tanggal</th>
																				<th>Nama Program</th>
																				<th>Keterangan</th>
																			</tr>
																		</thead>
																		<tbody>
																			<?php foreach ($program['programkerja'] as $key => $item): ?>
																				<tr>
																					<td class="text-center"><?= $key + 1?></td>
																					<td><?= fTampilTgl($item['sdate'], $item['edate']); ?></td>
																					<td><a href="<?= site_url("program_bantuan/data_peserta/{$item['peserta_id']}"); ?>"><?= $item['nama']; ?></a></td>
																					<td><?= $item['ndesc']; ?></td>
																				</tr>
																			<?php endforeach; ?>
																		</tbody>
																	</table>
																</div>
															</td>
														</tr>
														<tr>
															<th colspan="3" class="subtitle_head"><strong>DOKUMEN / KELENGKAPAN PENDUDUK</strong></th>
														</tr>
														<tr>
															<td>
																<div class="table-responsive">
																	<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
																		<thead class="bg-gray disabled color-palette">
																			<tr>
																				<th>No</th>
																				<th >Aksi</th>
																				<th>Nama Dokumen</th>
																				<th>Tanggal Upload</th>
																			</tr>
																		</thead>
																		<tbody>
																			<?php foreach ($list_dokumen as $key => $data): ?>
																				<tr>
																					<td class="padat"><?= $key + 1; ?></td>
																					<td class="aksi">
																						<a href="<?= site_url("{$this->controller}/unduh_berkas/{$data['id']}"); ?>" class="btn bg-purple btn-flat btn-sm" title="Unduh Dokumen"><i class="fa fa-download"></i></a>
																						<a href="<?= site_url("{$this->controller}/unduh_berkas/{$data['id']}/1"); ?>" class="btn bg-info btn-flat btn-sm" title="Lihat Dokumen"><i class="fa fa-eye"></i></a>
																					</td>
																					<td><?= $data['nama']?></td>
																					<td><?= tgl_indo2($data['tgl_upload']); ?></td>
																				</tr>
																			<?php endforeach; ?>
																		</tbody>
																	</table>
																</div>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

