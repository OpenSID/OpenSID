<script>
	$(document).ready(function() {
		$('#cari').focus();
	});

	$( function() {
		$( "#cari" ).autocomplete( {
			source: function( request, response ) {
				$.ajax( {
					type: "POST",
					url: '<?= site_url('penduduk/autocomplete'); ?>',
					dataType: "json",
					data: {
						cari: request.term
					},
					success: function( data ) {
						response( JSON.parse( data ));
					}
				});
			},
			minLength: 2,
		} );
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Data Penduduk</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Data Penduduk</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<?php if (can('u')): ?>
							<div class="btn-group btn-group-vertical">
								<a class="btn btn-social btn-flat btn-success btn-sm" data-toggle="dropdown"><i class='fa fa-plus'></i> Tambah Penduduk</a>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="<?= site_url('penduduk/form_peristiwa/1'); ?>" class="btn btn-social btn-flat btn-block btn-sm" title="Tambah Data Penduduk Lahir"><i class="fa fa-plus"></i>  Penduduk Lahir</a>
									</li>
									<li>
										<a href="<?= site_url('penduduk/form_peristiwa/5'); ?>" class="btn btn-social btn-flat btn-block btn-sm" title="Tambah Data Penduduk Masuk"><i class="fa fa-plus"></i>  Penduduk Masuk</a>
									</li>
								</ul>
							</div>
						<?php endif; ?>
						<?php if (can('h')): ?>
							<a href="#confirm-delete" title="Hapus Data Terpilih" onclick="deleteAllBox('mainform', '<?= site_url("penduduk/delete_all/{$p}/{$o}"); ?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
						<?php endif; ?>
						<div class="btn-group-vertical">
							<a class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Pilih Aksi Lainnya</a>
							<ul class="dropdown-menu" role="menu">
								<li>
									<a href="<?= site_url("penduduk/ajax_cetak/{$o}/cetak"); ?>" class="btn btn-social btn-flat btn-block btn-sm" title="Cetak Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data"><i class="fa fa-print"></i> Cetak</a>
								</li>
								<li>
									<a href="<?= site_url("penduduk/ajax_cetak/{$o}/unduh"); ?>" class="btn btn-social btn-flat btn-block btn-sm" title="Unduh Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data"><i class="fa fa-download"></i> Unduh</a>
								</li>
								<li>
									<a href="<?= site_url('penduduk/ajax_adv_search'); ?>" class="btn btn-social btn-flat btn-block btn-sm" title="Pencarian Spesifik" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pencarian Spesifik"><i class="fa fa-search"></i> Pencarian Spesifik</a>
								</li>
								<li>
									<a href="<?= site_url('penduduk/program_bantuan')?>" class="btn btn-social btn-flat btn-block btn-sm" title="Pencarian Program Bantuan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pencarian Program Bantuan"><i class="fa fa-search"></i> Pencarian Program Bantuan</a>
								</li>
								<li>
									<a href="<?= site_url('penduduk/search_kumpulan_nik'); ?>" class="btn btn-social btn-flat btn-block btn-sm" title="Pilihan Kumpulan NIK" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pilihan Kumpulan NIK"><i class="fa fa-users"></i> Pilihan Kumpulan NIK</a>
								</li>
								<li>
									<a href="<?= site_url("{$this->controller}/nik_sementara"); ?>" class="btn btn-social btn-flat btn-block btn-sm" title="NIK Sementara"><i class="fa fa-search"></i> NIK Sementara</a>
								</li>
								<li>
									<a href="<?= site_url('penduduk_log/clear'); ?>" class="btn btn-social btn-flat btn-block btn-sm" title="Log Data Penduduk"><i class="fa fa-book"></i> Log Penduduk</a>
								</li>
							</ul>
						</div>
						<div class="btn-group-vertical">
							<a class="btn btn-social btn-flat bg-navy btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Impor / Ekspor</a>
							<ul class="dropdown-menu" role="menu">
								<?php if (! config_item('demo_mode')): ?>
									<li>
										<a href="<?= route('penduduk.impor') ?>" class="btn btn-social btn-flat btn-block btn-sm" title="Impor Penduduk"><i class="fa fa-upload"></i> Impor Penduduk</a>
									</li>
									<li>
										<a href="<?= route('penduduk.impor_bip') ?>" class="btn btn-social btn-flat btn-block btn-sm" title="Impor BIP"><i class="fa fa-upload"></i> Impor BIP</a>
									</li>
								<?php endif ?>
								<li>
									<a href="<?= route('penduduk.ekspor') ?>" target="_blank" class="btn btn-social btn-flat btn-block btn-sm" title="Ekspor Penduduk"><i class="fa fa-download"></i> Ekspor Penduduk</a>
								</li>
							</ul>
						</div>
						<a href="<?= site_url("{$this->controller}/clear"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
					</div>
					<div class="box-body">
						<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
							<form id="mainform" name="mainform" method="post">
								<div class="row">
									<div class="col-sm-9">
										<select class="form-control input-sm" name="filter" onchange="formAction('mainform', '<?= site_url('penduduk/filter/filter'); ?>')">
											<option value="">Status Penduduk</option>
											<?php foreach ($list_status_penduduk as $data): ?>
												<option value="<?= $data['id']; ?>" <?= selected($filter, $data['id']); ?>><?= set_ucwords($data['nama']); ?></option>
											<?php endforeach; ?>
										</select>
										<select class="form-control input-sm" name="status_dasar" onchange="formAction('mainform', '<?= site_url('penduduk/filter/status_dasar'); ?>')">
											<option value="">Status Dasar</option>
											<?php foreach ($list_status_dasar as $data): ?>
												<option value="<?= $data['id']; ?>" <?= selected($status_dasar, $data['id']); ?>><?= set_ucwords($data['nama']); ?></option>
											<?php endforeach; ?>
										</select>
										<select class="form-control input-sm" name="sex" onchange="formAction('mainform', '<?= site_url('penduduk/filter/sex'); ?>')">
											<option value="">Jenis Kelamin</option>
											<?php foreach ($list_jenis_kelamin as $data): ?>
												<option value="<?= $data['id']; ?>" <?= selected($sex, $data['id']); ?>><?= set_ucwords($data['nama']); ?></option>
											<?php endforeach; ?>
										</select>
										<?php $this->load->view('global/filter_wilayah', ['form' => 'mainform']); ?>
									</div>
									<div class="col-sm-3">
										<div class="input-group input-group-sm pull-right">
											<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" title="Pencarian berdasarkan nama penduduk" value="<?=html_escape($cari); ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url('penduduk/filter/cari'); ?>');$('#'+'mainform').submit();}">
											<div class="input-group-btn">
												<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url('penduduk/filter/cari'); ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
											</div>
										</div>
									</div>
								</div>
								<div class="table-responsive table-min-height">
									<?php if ($judul_statistik): ?>
										<h5 class="box-title text-center"><b><?= $judul_statistik; ?></b></h5>
									<?php endif; ?>
									<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
										<thead class="bg-gray disabled color-palette">
											<tr>
												<th><input type="checkbox" id="checkall"/></th>
												<th>No</th>
												<th>Aksi</th>
												<th>Foto</th>
												<th><?= url_order($o, "{$this->controller}/{$func}/1", 1, 'NIK'); ?></th>
												<th>Tag ID Card</th>
												<th><?= url_order($o, "{$this->controller}/{$func}/1", 3, 'Nama'); ?></th>
												<th><?= url_order($o, "{$this->controller}/{$func}/1", 5, 'No. KK'); ?></th>
												<!-- tambah kolom orang tua-->
												<th>Nama Ayah</th>
												<th>Nama Ibu</th>
												<!-- tambah kolom orang tua-->
												<th>No. Rumah Tangga</th>
												<th>Alamat</th>
												<th><?= ucwords($this->setting->sebutan_dusun); ?></th>
												<th>RW</th>
												<th>RT</th>
												<th>Pendidikan dalam KK</th>
												<th><?= url_order($o, "{$this->controller}/{$func}/1", 7, 'Umur'); ?></th>
												<th >Pekerjaan</th>
												<th>Kawin</th>
												<th><?= url_order($o, "{$this->controller}/{$func}/1", 11, 'Tgl Peristiwa'); ?></th>
												<th><?= url_order($o, "{$this->controller}/{$func}/1", 9, 'Tgl Terdaftar'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php if ($main): ?>
												<?php foreach ($main as $key => $data): ?>
													<tr <?= jecho(get_nik($data['nik']), '0', 'class="danger"') ?>>
														<td class="padat"><input type="checkbox" name="id_cb[]" value="<?= $data['id']; ?>" /></td>
														<td class="padat"><?= ($key + $paging->offset + 1); ?></td>
														<td class="aksi">
															<div class="btn-group">
																<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Pilih Aksi</button>
																<ul class="dropdown-menu" role="menu">
																	<li>
																		<a href="<?= site_url("penduduk/detail/{$p}/{$o}/{$data['id']}"); ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-list-ol"></i> Lihat Detail Biodata Penduduk</a>
																	</li>
																	<?php if ($data['status_dasar'] == 9 && can('u')): ?>
																		<li>
																			<a href="#" data-href="<?= site_url("penduduk/kembalikan_status/{$p}/{$o}/{$data['id']}"); ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#confirm-status" data-body="Apakah Anda yakin ingin mengembalikan status data penduduk ini?"><i class="fa fa-undo"></i> Kembalikan ke Status HIDUP</a>
																		</li>
																	<?php endif; ?>
																	<?php if ($data['status_dasar'] == 1): ?>
																		<?php if (can('u')): ?>
																			<li>
																				<a href="<?= site_url("penduduk/form/{$p}/{$o}/{$data['id']}"); ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-edit"></i> Ubah Biodata Penduduk</a>
																			</li>
																			<li>
																				<a href="<?= site_url("penduduk/ajax_penduduk_maps/{$p}/{$o}/{$data['id']}/0"); ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class='fa fa-map-marker'></i> Lihat Lokasi Tempat Tinggal</a>
																			</li>
																			<li>
																				<a href="<?= site_url("penduduk/edit_status_dasar/{$p}/{$o}/{$data['id']}"); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Status Dasar" class="btn btn-social btn-flat btn-block btn-sm"><i class='fa fa-sign-out'></i> Ubah Status Dasar</a>
																			</li>
																		<?php endif; ?>
																		<li>
																			<a href="<?= site_url("penduduk/dokumen/{$data['id']}"); ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-upload"></i> Upload Dokumen Penduduk</a>
																		</li>
																		<li>
																			<a href="<?= site_url("penduduk/cetak_biodata/{$data['id']}"); ?>" target="_blank" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-print"></i> Cetak Biodata Penduduk</a>
																		</li>
																		<?php if (can('h')): ?>
																			<li>
																				<a href="#" data-href="<?= site_url("penduduk/delete/{$p}/{$o}/{$data['id']}"); ?>" class="btn btn-social btn-flat btn-block btn-sm" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i> Hapus</a>
																			</li>
																		<?php endif; ?>
																	<?php endif; ?>
																</ul>
															</div>
														</td>
														<td class="padat">
															<img class="penduduk_kecil" src="<?= AmbilFoto($data['foto'], '', $data['id_sex']); ?>" alt="Foto Penduduk"/>
														</td>
														<td>
															<a href="<?= site_url("penduduk/detail/{$p}/{$o}/{$data['id']}"); ?>" id="test" name="<?= $data['id']; ?>"><?= $data['nik']; ?></a>
														</td>
														<td nowrap><?= $data['tag_id_card']; ?></td>
														<td nowrap><?= strtoupper($data['nama']); ?></td>
														<td class="padat"><a href="<?= site_url("keluarga/kartu_keluarga/{$p}/{$o}/{$data['id_kk']}"); ?>"><?= $data['no_kk']; ?> </a></td>
														<!-- tambah kolom orang tua-->
														<td nowrap><?= $data['nama_ayah']; ?></td>
														<td nowrap><?= $data['nama_ibu']; ?></td>
														<!-- tambah kolom orang tua-->
														<td><a href="<?= site_url("rtm/anggota/{$data['id_rtm']}"); ?>"><?= $data['no_rtm']; ?></a></td>
														<td><?= strtoupper($data['alamat']); ?></td>
														<td nowrap><?= strtoupper($data['dusun']); ?></td>
														<td><?= $data['rw']; ?></td>
														<td><?= $data['rt']; ?></td>
														<td><?= $data['pendidikan']; ?></td>
														<td><?= $data['umur']; ?></td>
														<td><?= $data['pekerjaan']; ?></td>
														<td nowrap><?= $data['kawin']; ?></td>
														<td><?= tgl_indo($data['tgl_peristiwa']); ?></td>
														<td><?= tgl_indo($data['created_at']); ?></td>
													</tr>
												<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td class="text-center" colspan="20">Data Tidak Tersedia</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</form>
							<?php $this->load->view('global/paging'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
<?php $this->load->view('global/konfirmasi'); ?>
