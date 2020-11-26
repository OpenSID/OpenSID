<script>
	$( function() {
		$( "#cari" ).autocomplete( {
			source: function( request, response ) {
				$.ajax( {
					type: "POST",
					url: '<?= site_url("penduduk/autocomplete"); ?>',
					dataType: "json",
					data: {
						cari: request.term
					},
					success: function( data ) {
						response( JSON.parse( data ));
					}
				} );
			},
			minLength: 2,
		} );
	} );
</script>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Data Penduduk
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fas fa-home"></i> Home</a></li>
						<li class="breadcrumb-item active">Data Penduduk</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-outline card-info">
					<div class="card-header with-border">
						<a href="<?= site_url('penduduk/form'); ?>" class="btn btn-flat btn-success btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left visible-xl-inline-block text-left" title="Tambah Data"><i class="fa fa-plus"></i> Penduduk Domisili</a>
						<?php if ($this->CI->cek_hak_akses('h')): ?>
							<a href="#confirm-delete" title="Hapus Data Terpilih" onclick="deleteAllBox('mainform', '<?= site_url("penduduk/delete_all/$p/$o"); ?>')" class="btn  btn-flat btn-danger btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left visible-xl-inline-block text-left hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
						<?php endif; ?>
						<div class="btn-group-vertical visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left visible-xl-inline-block">
							<a class="btn btn-flat btn-info btn-xs text-left" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Pilih Aksi Lainnya</a>
							<ul class="dropdown-menu text-xs" role="menu">
								<li>
									<a class="dropdown-item" href="<?= site_url("penduduk/ajax_cetak/$o/cetak"); ?>" class="btn btn-flat btn-block btn-xs" title="Cetak Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data"><i class="fa fa-print"></i> Cetak</a>
								</li>
								<li>
									<a class="dropdown-item" href="<?= site_url("penduduk/ajax_cetak/$o/unduh"); ?>" class="btn  btn-flat btn-block btn-xs" title="Unduh Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data"><i class="fa fa-download"></i> Unduh</a>
								</li>
								<li>
									<a class="dropdown-item" href="<?= site_url("penduduk/ajax_adv_search"); ?>" class="btn  btn-flat btn-block btn-xs" title="Pencarian Spesifik" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pencarian Spesifik"><i class="fa fa-search"></i> Pencarian Spesifik</a>
								</li>
								<li>
									<a class="dropdown-item" href="<?= site_url("penduduk/search_kumpulan_nik"); ?>" class="btn  btn-flat btn-block btn-xs" title="Pilihan Kumpulan NIK" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pilihan Kumpulan NIK"><i class="fa fa-users"></i> Pilihan Kumpulan NIK</a>
								</li>
								<li>
									<a class="dropdown-item" href="<?= site_url("penduduk_log/clear"); ?>" class="btn  btn-flat btn-block btn-xs" title="Log Data Penduduk"><i class="fa fa-book"></i> Log Penduduk</a>
								</li>
							</ul>
						</div>
						<a href="<?= site_url("{$this->controller}/clear"); ?>" class="btn  btn-flat bg-purple btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left visible-xl-inline-block text-left"><i class="fa fa-refresh"></i> Bersihkan</a>
					</div>
					<div class="card-body">
						<div class="dataTables_wrapper dt-bootstrap no-footer">
							<form class="form-inline" id="mainform" name="mainform" action="" method="post">
								<div class="container-fluid">
									<div class="row mb-2">
										<div class="col-sm-9">
											<select class="form-control form-control-sm" name="filter" onchange="formAction('mainform', '<?= site_url('penduduk/filter/filter'); ?>')">
												<option value="">Status Penduduk</option>
												<?php foreach ($list_status_penduduk AS $data): ?>
													<option value="<?= $data['id']; ?>" <?= selected($filter, $data['id']); ?>><?= set_ucwords($data['nama']); ?></option>
												<?php endforeach; ?>
											</select>
											<select class="form-control form-control-sm" name="status_dasar" onchange="formAction('mainform', '<?= site_url('penduduk/filter/status_dasar'); ?>')">
												<option value="">Status Dasar</option>
												<?php foreach ($list_status_dasar AS $data): ?>
													<option value="<?= $data['id']; ?>" <?= selected($status_dasar, $data['id']); ?>><?= set_ucwords($data['nama']); ?></option>
												<?php endforeach; ?>
											</select>
											<select class="form-control form-control-sm" name="sex" onchange="formAction('mainform', '<?= site_url('penduduk/filter/sex'); ?>')">
												<option value="">Jenis Kelamin</option>
												<?php foreach ($list_jenis_kelamin AS $data): ?>
													<option value="<?= $data['id']; ?>" <?= selected($sex, $data['id']); ?>><?= set_ucwords($data['nama']); ?></option>
												<?php endforeach; ?>
											</select>
											<?php $this->load->view('global/filter_wilayah', ['form' => 'mainform']); ?>
										</div>
										<div class="col-sm-3">
											<div class="input-group input-group-sm pull-right">
												<input name="cari" id="cari" class="form-control form-control-sm" placeholder="Cari..." type="text" title="Pencarian berdasarkan nama penduduk" value="<?=html_escape($cari); ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("penduduk/filter/cari"); ?>');$('#'+'mainform').submit();}">
												<div class="input-group-btn">
													<button type="submit" class="btn btn-default btn-xs" onclick="$('#'+'mainform').attr('action', '<?= site_url("penduduk/filter/cari"); ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="table-responsive table-min-height">
									<?php if ($judul_statistik): ?>
										<h5 class="card-title text-center"><b><?= $judul_statistik; ?></b></h5>
									<?php endif; ?>
									<table class="table table-bordered dataTable table-striped table-hover tabel-daftar dt-responsive">
										<thead class="bg-gray disabled color-palette">
											<tr>
												<th><input type="checkbox" id="checkall"/></th>
												<th>No</th>
												<th>Aksi</th>
												<th>Foto</th>
												<th><?= url_order($o, "{$this->controller}/{$func}/$p", 1, 'NIK'); ?></th>
												<th>Tag ID Card</th>
												<th><?= url_order($o, "{$this->controller}/{$func}/$p", 3, 'Nama'); ?></th>
												<th><?= url_order($o, "{$this->controller}/{$func}/$p", 5, 'No. KK'); ?></th>
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
												<th><?= url_order($o, "{$this->controller}/{$func}/$p", 7, 'Umur'); ?></th>
												<th >Pekerjaan</th>
												<th>Kawin</th>
												<th><?= url_order($o, "{$this->controller}/{$func}/$p", 9, 'Tgl Terdaftar'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php if($main): ?>
												<?php foreach ($main as $key => $data): ?>
													<tr>
														<td class="padat"><input type="checkbox" name="id_cb[]" value="<?= $data['id']; ?>" /></td>
														<td class="padat"><?= ($key + $paging->offset + 1); ?></td>
														<td class="aksi">
															<div class="btn-group">
																<button type="button" class="btn btn-flat btn-info btn-xs" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Pilih Aksi</button>
																<ul class="dropdown-menu text-xs" role="menu">
																	<li>
																		<a class="dropdown-item" href="<?= site_url("penduduk/detail/$p/$o/$data[id]"); ?>" class="btn  btn-flat btn-block btn-xs"><i class="fa fa-list-ol"></i> Lihat Detail Biodata Penduduk</a>
																	</li>
																	<?php if ($data['status_dasar']==9): ?>
																		<li>
																			<a class="dropdown-item" href="#" data-href="<?= site_url("penduduk/kembalikan_status/$p/$o/$data[id]"); ?>" class="btn  btn-flat btn-block btn-xs" data-remote="false" data-toggle="modal" data-target="#confirm-status"><i class="fa fa-undo"></i> Kembalikan ke Status HIDUP</a>
																		</li>
																	<?php endif; ?>
																	<?php if ($data['status_dasar']==1): ?>
																		<li>
																			<a class="dropdown-item" href="<?= site_url("penduduk/form/$p/$o/$data[id]"); ?>" class="btn  btn-flat btn-block btn-xs"><i class="fa fa-edit"></i> Ubah Biodata Penduduk</a>
																		</li>
																		<li>
																			<a class="dropdown-item" href="<?= site_url("penduduk/ajax_penduduk_maps/$p/$o/$data[id]/0"); ?>" class="btn  btn-flat btn-block btn-xs"><i class='fa fa-map-marker'></i> Lihat Lokasi Tempat Tinggal</a>
																		</li>
																		<li>
																			<a class="dropdown-item" href="<?= site_url("penduduk/edit_status_dasar/$p/$o/$data[id]"); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Status Dasar" class="btn  btn-flat btn-block btn-xs"><i class='fa fa-sign-out'></i> Ubah Status Dasar</a>
																		</li>
																		<li>
																			<a class="dropdown-item" href="<?= site_url("penduduk/dokumen/$data[id]"); ?>" class="btn  btn-flat btn-block btn-xs"><i class="fa fa-upload"></i> Upload Dokumen Penduduk</a>
																		</li>
																		<li>
																			<a class="dropdown-item" href="<?= site_url("penduduk/cetak_biodata/$data[id]"); ?>" target="_blank" class="btn  btn-flat btn-block btn-xs"><i class="fa fa-print"></i> Cetak Biodata Penduduk</a>
																		</li>
																		<?php if ($this->CI->cek_hak_akses('h')): ?>
																			<li>
																				<a class="dropdown-item" href="#" data-href="<?= site_url("penduduk/delete/$p/$o/$data[id]"); ?>" class="btn  btn-flat btn-block btn-xs" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i> Hapus</a>
																			</li>
																		<?php endif; ?>
																	<?php endif; ?>
																</ul>
															</div>
														</td>
														<td class="padat">
															<div class="user-panel">
																<div class="image2">
																	<img src="<?= ! empty($data['foto']) ? AmbilFoto($data['foto']) : base_url('assets/files/user_pict/kuser.png') ?>" class="rounded-circle" alt="Foto Penduduk"/>
																</div>
															</div>
														</td>
														<td>
															<a href="<?= site_url("penduduk/detail/$p/$o/$data[id]"); ?>" id="test" name="<?= $data['id']; ?>"><?= $data['nik']; ?></a>
														</td>
														<td nowrap><?= $data['tag_id_card']; ?></td>
														<td nowrap><?= strtoupper($data['nama']); ?></td>
														<td><a href="<?= site_url("keluarga/kartu_keluarga/$p/$o/$data[id_kk]"); ?>"><?= $data['no_kk']; ?> </a></td>
														<!-- tambah kolom orang tua-->
														<td nowrap><?= $data['nama_ayah']; ?></td>
														<td nowrap><?= $data['nama_ibu']; ?></td>
														<!-- tambah kolom orang tua-->
														<td><a href="<?= site_url("rtm/anggota/$data[id_rtm]"); ?>"><?= $data['no_rtm']; ?></a></td>
														<td><?= strtoupper($data['alamat']); ?></td>
														<td nowrap><?= strtoupper($data['dusun']); ?></td>
														<td><?= $data['rw']; ?></td>
														<td><?= $data['rt']; ?></td>
														<td><?= $data['pendidikan']; ?></td>
														<td><?= $data['umur']; ?></td>
														<td><?= $data['pekerjaan']; ?></td>
														<td nowrap><?= $data['kawin']; ?></td>
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
<div class='modal fade' id='confirm-status' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
				<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
			</div>
			<div class='modal-body btn-info'>
				Apakah Anda yakin ingin mengembalikan status data penduduk ini?
			</div>
			<div class='modal-footer'>
				<button type="button" class="btn  btn-flat btn-danger btn-xs" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
				<a class='btn-ok'>
					<button type="button" class="btn  btn-flat btn-info btn-xs" id="ok-status"><i class='fa fa-check'></i> Simpan</button>
				</a>
			</div>
		</div>
	</div>
</div>
