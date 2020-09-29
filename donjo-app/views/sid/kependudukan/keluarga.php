<script>
	$( function() {
		$( "#cari" ).autocomplete({
			source: function( request, response ) {
				$.ajax( {
					type: "POST",
					url: '<?= site_url("keluarga/autocomplete")?>',
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
	<section class="content-header">
		<h1>Data Keluarga</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Data Keluarga</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-header with-border">
				<div class="btn-group btn-group-vertical">
					<a class="btn btn-social btn-flat btn-success btn-sm" data-toggle="dropdown"><i class='fa fa-plus'></i> Tambah KK Baru</a>
					<ul class="dropdown-menu" role="menu">
						<li>
							<a href="<?= site_url('keluarga/form')?>" class="btn btn-social btn-flat btn-block btn-sm" title="Tambah Data KK Baru"><i class="fa fa-plus"></i> Tambah Penduduk Baru</a>
						</li>
						<li>
							<a href="<?= site_url('keluarga/form_old')?>" class="btn btn-social btn-flat btn-block btn-sm" title="Tambah Data KK dari keluarga yang sudah ter-input" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Data Kepala Keluarga"><i class="fa fa-plus"></i> Dari Penduduk Sudah Ada</a>
						</li>
					</ul>
				</div>
				<a href="<?= site_url("keluarga/ajax_cetak/$o/cetak")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data"  data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data" target="_blank"><i class="fa fa-print"></i> Cetak</a>
				<a href="<?= site_url("keluarga/ajax_cetak/$o/unduh")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data" target="_blank"><i class="fa fa-download"></i> Unduh</a>
				<div class="btn-group btn-group-vertical">
					<a class="btn btn-social btn-flat bg-maroon btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Aksi Data Terpilih</a>
					<ul class="dropdown-menu" role="menu">
						<li>
							<a href="" class="btn btn-social btn-flat btn-block btn-sm aksi-terpilih" title="Cetak Kartu Keluarga" onclick="formAction('mainform','<?= site_url("keluarga/cetak_kk_all")?>', '_blank'); return false;"><i class="fa fa-print"></i> Cetak Kartu Keluarga</a>
						</li>
						<li>
							<a href="" class="btn btn-social btn-flat btn-block btn-sm aksi-terpilih" title="Unduh Kartu Keluarga" onclick="formAction('mainform','<?= site_url("keluarga/doc_kk_all")?>'); return false;"><i class="fa fa-download"></i> Unduh Kartu Keluarga</a>
						</li>
						<?php if ($this->CI->cek_hak_akses('h')): ?>
							<li>
								<a href="#confirm-delete" class="btn btn-social btn-flat btn-block btn-sm hapus-terpilih" title="Hapus Data" onclick="deleteAllBox('mainform', '<?=site_url("keluarga/delete_all")?>')"><i class="fa fa-trash-o"></i> Hapus Data Terpilih</a>
							</li>
						<?php endif; ?>
					</ul>
				</div>
				<div class="btn-group-vertical">
					<a class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Pilih Aksi Lainnya</a>
					<ul class="dropdown-menu" role="menu">
						<li>
							<a href="<?= site_url("keluarga/search_kumpulan_kk")?>" class="btn btn-social btn-flat btn-block btn-sm" title="Pilihan Kumpulan KK" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pilihan Kumpulan KK"><i class="fa fa-search"></i> Pilihan Kumpulan KK</a>
						</li>
					</ul>
				</div>
				<a href="<?= site_url("{$this->controller}/clear") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan Filter</a>
			</div>
			<div class="box-body">
				<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
					<form id="mainform" name="mainform" action="" method="post">
						<div class="row">
							<div class="col-sm-9">
								<select class="form-control input-sm" name="status_dasar" onchange="formAction('mainform', '<?=site_url('keluarga/filter/status_dasar')?>')">
									<option value="">Pilih Status KK</option>
									<option value="1" <?= selected($status_dasar, 1); ?>>KK Aktif</option>
									<option value="2" <?= selected($status_dasar, 2); ?>>KK Hilang/Pindah/Mati</option>
									<option value="3" <?= selected($status_dasar, 3); ?>>KK Kosong</option>
								</select>
								<select class="form-control input-sm" name="sex" onchange="formAction('mainform', '<?=site_url('keluarga/filter/sex')?>')">
									<option value="">Pilih Jenis Kelamin</option>
									<?php foreach ($list_sex AS $data): ?>
										<option value="<?= $data['id']?>" <?= selected($sex, $data['id']); ?>><?= set_ucwords($data['nama'])?></option>
									<?php endforeach; ?>
								</select>
								<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('keluarga/dusun')?>')">
									<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
									<?php foreach ($list_dusun AS $data): ?>
										<option value="<?= $data['dusun']?>" <?= selected($dusun, $data['dusun']); ?>><?= set_ucwords($data['dusun'])?></option>
									<?php endforeach; ?>
								</select>
								<?php if ($dusun): ?>
									<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('keluarga/rw')?>')" >
										<option value="">Pilih RW</option>
										<?php foreach ($list_rw AS $data): ?>
											<option value="<?= $data['rw']?>" <?= selected($rw, $data['rw']); ?>><?= set_ucwords($data['rw'])?></option>
										<?php endforeach; ?>
									</select>
								<?php endif; ?>
								<?php if ($rw): ?>
									<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('keluarga/rt')?>')">
										<option value="">Pilih RT</option>
										<?php foreach ($list_rt AS $data): ?>
											<option value="<?= $data['rt']?>" <?= selected($rt, $data['rt']); ?>><?= set_ucwords($data['rt'])?></option>
										<?php endforeach; ?>
									</select>
								<?php endif; ?>
							</div>
							<div class="col-sm-3">
								<div class="input-group input-group-sm pull-right">
									<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url("keluarga/filter/cari")?>');$('#'+'mainform').submit();}">
									<div class="input-group-btn">
										<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("keluarga/filter/cari")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
									</div>
								</div>
							</div>
						</div>
						<div class="table-responsive">
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
										<?php if ($o==2): ?>
											<th><a href="<?= site_url("keluarga/index/$p/1")?>">Nomor KK <i class='fa fa-sort-asc fa-sm'></i></a></th>
										 <?php elseif ($o==1): ?>
											<th><a href="<?= site_url("keluarga/index/$p/2")?>">Nomor KK <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else: ?>
											<th><a href="<?= site_url("keluarga/index/$p/1")?>">Nomor KK <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
										<?php if ($o==4): ?>
											<th nowrap><a href="<?= site_url("keluarga/index/$p/3")?>">Kepala Keluarga <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($o==3): ?>
											<th nowrap><a href="<?= site_url("keluarga/index/$p/4")?>">Kepala Keluarga <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else: ?>
											<th nowrap><a href="<?= site_url("keluarga/index/$p/3")?>">Kepala Keluarga <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
										<th>NIK</th>
										<th>Tag ID Card</th>
										<th>Jumlah Anggota</th>
										<th>Jenis Kelamin</th>
										<th>Alamat</th>
										<th><?= ucwords($this->setting->sebutan_dusun)?></th>
										<th>RW</th>
										<th>RT</th>
										<?php if ($o==6): ?>
											<th nowrap><a href="<?= site_url("keluarga/index/$p/5")?>">Tanggal Terdaftar <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($o==5): ?>
											<th nowrap><a href="<?= site_url("keluarga/index/$p/6")?>">Tanggal Terdaftar <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else: ?>
											<th nowrap><a href="<?= site_url("keluarga/index/$p/6")?>">Tanggal Terdaftar <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
										<th nowrap>Tanggal Cetak KK</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($main as $data): ?>
										<tr>
											<td class="padat"><input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" /></td>
											<td class="padat"><?= $data['no']?></td>
											<td class="aksi">
												<a href="<?= site_url("keluarga/anggota/$p/$o/$data[id]")?>" class="btn bg-purple btn-flat btn-sm" title="Rincian Anggota Keluarga (KK)"><i class="fa fa-list-ol"></i></a>
												<a href="<?= site_url("keluarga/form_a/$p/$o/$data[id]")?>" class="btn btn-success btn-flat btn-sm " title="Tambah Anggota Keluarga" ><i class="fa fa-plus"></i> </a>
												<a href="<?= site_url("keluarga/edit_nokk/$p/$o/$data[id]")?>" title="Ubah Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data KK" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
												<?php if ($this->CI->cek_hak_akses('h')): ?>
													<a href="#" data-href="<?= site_url("keluarga/delete/$p/$o/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus/Keluar Dari Daftar Keluarga" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
												<?php endif; ?>
											</td>
											<td class="padat">
												<div class="user-panel">
													<div class="image2">
														<img src="<?= AmbilFoto($data['foto']); ?>" class="img-circle" alt="Foto Penduduk"/>
													</div>
												</div>
											</td>
											<td><a href="<?= site_url("keluarga/kartu_keluarga/$p/$o/$data[id]")?>"><?= $data['no_kk']?></a></td>
											<td nowrap><?= strtoupper($data['kepala_kk'])?></td>
											<td><a href="<?= site_url("penduduk/detail/1/0/$data[id_pend]")?>"><?= strtoupper($data['nik'])?></a></td>
											<td><?= $data['tag_id_card']?></td>
											<td><a href="<?= site_url("keluarga/anggota/$p/$o/$data[id]")?>"><?= $data['jumlah_anggota']?></a></td>
											<td><?= strtoupper($data['sex'])?></td>
											<td><?= strtoupper($data['alamat'])?></td>
											<td><?= strtoupper($data['dusun'])?></td>
											<td><?= strtoupper($data['rw'])?></td>
											<td><?= strtoupper($data['rt'])?></td>
											<td><?= tgl_indo($data['tgl_daftar'])?></td>
											<td><?= tgl_indo($data['tgl_cetak_kk'])?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</form>
					<?php $this->load->view('global/paging');?>
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete');?>
