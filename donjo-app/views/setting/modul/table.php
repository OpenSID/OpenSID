<link rel="stylesheet" href="<?= base_url()?>assets/css/alert-bootstrap.css">

<?php if (! $sub_modul): ?>
	<script type="text/javascript">
		function ubah_jenis_server(jenis_server) {
			$('#offline_saja select').val('');
			if (jenis_server == 3) {
				$('#offline_saja').hide();
				$('#offline_saja select').removeClass('required');
				$('#offline_online_hosting select').val('');
				$('#offline_online_hosting select').addClass('required');
				$('#offline_online_hosting').show();
			} else {
				$('#offline_online_hosting select').val('');
				$('#offline_online_hosting select').change();
				$('#offline_online_hosting').hide();
				$('#offline_online_hosting select').removeClass('required');
				$('#offline_saja select').removeClass('required');
				$('#offline_saja').hide();
				if (jenis_server == 1)
				{
					$('#offline_saja select').addClass('required');
					$('#offline_saja').show();
				}
			}
		}

		function ubah_server(server) {
			$('#offline_saja select').val('');
			$('#offline_ada_hosting select').val('');

			if (server == 5) {
				$('#offline_ada_hosting select').addClass('required');
				$('#offline_ada_hosting').show();
			} else {
				$('#offline_ada_hosting select').removeClass('required');
				$('#offline_ada_hosting').hide();
			}
		}

		$(function() {
			var keyword = <?= $keyword?> ;
			$( "#cari" ).autocomplete({
				source: keyword,
				maxShowItems: 10,
			});
		});
	</script>
<?php endif; ?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan <?= $sub_modul ? 'Submodul' : 'Modul'; ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<?php if (! $sub_modul): ?>
				<li class="active">Pengaturan Modul</li>
			<?php else: ?>
				<li><a href="<?= site_url('modul/clear')?>"> Daftar Modul</a></li>
				<li class="active">Pengaturan Submodul</li>
			<?php endif; ?>
		</ol>
	</section>
	<?php if (! $sub_modul): ?>
		<section class="content" id="maincontent">
			<div class="box box-info">
				<form id="validasi" action="<?= site_url('modul/ubah_server')?>" method="POST" class="form-horizontal">
					<div class="box-body">
						<h4>Pengaturan Server</h4>
						<div class="form-group" >
							<label class="col-sm-3 control-label">Penggunaan OpenSID di <?= ucwords($this->setting->sebutan_desa)?></label>
							<div class="col-sm-9 col-lg-4">
								<select class="form-control required input-sm" name="jenis_server" onchange="ubah_jenis_server($(this).val())">
									<option value='' selected="selected">-- Pilih Penggunaan OpenSID --</option>
									<option value="1" <?php selected($this->setting->penggunaan_server, '1')?>>
										Offline saja di kantor desa
									</option>
									<option value="2" <?php selected($this->setting->penggunaan_server, '2')?>>
										Online saja di hosting
									</option>
									<option value="3" <?php in_array($this->setting->penggunaan_server, ['3', '5', '6']) && print 'selected' ?>>
										Offline di kantor desa dan online di hosting
									</option>
									<option value="4" <?php selected($this->setting->penggunaan_server, '4')?>>
										Offline dan online di kantor desa
									</option>
								</select>
							</div>
						</div>
						<div class="form-group" id="offline_online_hosting" style="<?php ! in_array($this->setting->penggunaan_server, ['3', '5', '6']) && print 'display: none;' ?>">
							<label class="col-sm-3 control-label">Server ini digunakan sebagai</label>
							<div class="col-sm-9 col-lg-4">
								<select class="form-control input-sm" name="server_mana" onchange="ubah_server($(this).val())">
									<option value='' selected="selected">-- Pilih Server Ini --</option>
									<option value="5" <?php selected($this->setting->penggunaan_server, '5')?>>
										Offline admin saja di kantor desa
									</option>
									<option value="6" <?php selected($this->setting->penggunaan_server, '6')?>>
										Online web publik saja di hosting
									</option>
								</select>
							</div>
						</div>
						<div class="form-group" id="offline_ada_hosting" style="<?php ! in_array($this->setting->penggunaan_server, ['5']) && print 'display: none;' ?>">
							<label class="col-sm-3 control-label">Akses web pada server offline ini</label>
							<div class="col-sm-6 col-lg-4">
								<select class="form-control input-sm" name="offline_mode">
									<option value='' selected="selected">-- Pilih Akses Web --</option>
									<option value="1" <?php ($this->setting->penggunaan_server == '5' && $this->setting->offline_mode == '1') && print 'selected'?>>
										Web bisa diakses petugas web
									</option>
									<option value="2" <?php ($this->setting->penggunaan_server == '5' && $this->setting->offline_mode == '2') && print 'selected'?>>
										Web non-aktif sama sekali
									</option>
								</select>
							</div>
						</div>
						<div class="form-group" id="offline_saja" style="<?php ! in_array($this->setting->penggunaan_server, ['1']) && print 'display: none;' ?>">
							<label class="col-sm-3 control-label">Akses web pada server offline ini</label>
							<div class="col-sm-9 col-lg-4">
								<select class="form-control input-sm" name="offline_mode_saja">
									<option value='' selected="selected">-- Pilih Akses Web --</option>
									<option value="0" <?php ($this->setting->penggunaan_server == '1' && $this->setting->offline_mode == '0') && print 'selected'?>>
										Web bisa diakses publik
									</option>
									<option value="1" <?php ($this->setting->penggunaan_server == '1' && $this->setting->offline_mode == '1') && print 'selected'?>>
										Web bisa diakses petugas web
									</option>
									<option value="2" <?php ($this->setting->penggunaan_server == '1' && $this->setting->offline_mode == '2') && print 'selected'?>>
										Web non-aktif sama sekali
									</option>
								</select>
							</div>
						</div>
					</div>
					<?php if ($this->CI->cek_hak_akses('u')): ?>
						<div class="box-footer">
							<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
							<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
						</div>
					<?php endif; ?>
				</form>
				<?php if ($this->CI->cek_hak_akses('u') && $this->setting->penggunaan_server == 6): ?>
					<div class="box-body">
						<div class="alert alert-info">
							<p>Server ini hanya digunakan untuk menampilkan data bagi publik. Secara default, semua modul dinon-aktifkan kecuali menu Pengaturan dan Admin Web. Pengelolaan data penduduk dan lain-lain dilakukan di server terpisah, secara offline di Kantor Desa. Untuk memutakhirkan data di server ini, unggah data secara berkala dari server yang digunakan untuk pengelolaan data.</p>
							<p>Sebaiknya data di server ini diacak atau disensor untuk menjaga privasi data penduduk dan data lain.</p>
						</div>
						<a href="#" data-title="Acak Data" class="btn btn-social btn-flat btn-danger btn-sm" data-toggle="modal" data-target="#confirm-acak"><i class='fa fa-trash-o'></i>Acak Data</a>
						<a href="<?= site_url('database/mutakhirkan_data_server')?>" title="Sinkronkan Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Sinkronkan Data" class="btn btn-social btn-flat btn-info btn-sm"><i class="fa fa-refresh"></i>Impor Data Mutakhir</a>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<?php if (! $sub_modul): ?>
				<div class="box-body">
					<h4>Pengaturan Modul</h4>
					<?php if ($this->CI->cek_hak_akses('u')): ?>
						<div class="row">
							<div class="col-xs-12 text-center">
								<a href="<?= site_url('modul/default_server')?>" class="btn btn-social btn-flat btn-success btn-sm" <?php $this->setting->penggunaan_server || print "disabled='disabled'"?>><i class="fa fa-refresh"></i>Kembalikan ke default penggunaan server</a>
							</div>
						</div>
					<?php endif; ?>
					<div class="row">
						<div class="col-sm-12">
							<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<form id="mainform" name="mainform" method="post">
									<div class="row">
										<div class="col-sm-6">
											<select class="form-control input-sm " name="status" onchange="formAction('mainform','<?=site_url('modul/filter/status/')?>')">
												<option value="">Semua</option>
												<option value="1" <?php selected($status, 1); ?>>Aktif</option>
												<option value="2" <?php selected($status, 2); ?>>Tidak Aktif</option>
											</select>
										</div>
										<div class="col-sm-6">
											<div class="box-tools">
												<div class="input-group input-group-sm pull-right">
													<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13):$('#'+'mainform').attr('action','<?=site_url('modul/search')?>');$('#'+'mainform').submit();endif;">
													<div class="input-group-btn">
														<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action','<?= site_url('modul/filter/cari')?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
			<?php else: ?>
				<div class="box-header with-border">
					<a href="<?= site_url('modul/clear')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Modul</a>
				</div>
				<div class="box-header with-border">
				 <strong> Modul Utama : <?=$sub_modul['modul']?></strong>
				</div>
				<div class="box-body">
			<?php endif; ?>
			<div class="row">
				<div class="col-sm-12">
					<div class="table-responsive">
						<table class="table table-bordered table-striped dataTable table-hover tabel-daftar">
							<thead class="bg-gray disabled color-palette">
								<tr>
									<th>No</th>
									<?php if ($this->CI->cek_hak_akses('u')): ?>
										<th>Aksi</th>
									<?php endif; ?>
									<th>Nama Modul</th>
									<th>Icon</th>
									<th>Tampil</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($main as $data): ?>
									<tr>
										<td class="padat"><?=$data['no']?></td>
										<?php if ($this->CI->cek_hak_akses('u')): ?>
											<td class="aksi">
												<?php if ($this->CI->cek_hak_akses('u')): ?>
													<a href="<?=site_url("modul/form/{$data['id']}")?>" class="btn bg-orange btn-flat btn-sm" title="Ubah Data" ><i class="fa fa-edit"></i></a>
													<?php if ($data['aktif'] == '1'): ?>
														<a href="<?= site_url("modul/lock/{$data['id']}/2")?>" class="btn bg-navy btn-flat btn-sm"  title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
													<?php elseif ($sub_modul && $sub_modul['aktif'] != '1'): ?>
														<!-- Jika parrent menu tdk aktif, maka tdk ada aksi lock -->
													<?php else: ?>
														<a href="<?= site_url("modul/lock/{$data['id']}/1")?>" class="btn bg-navy btn-flat btn-sm"  title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
													<?php endif ?>
												<?php endif; ?>
												<?php if (count($data['submodul']) > 0): ?>
													<a href="<?=site_url("modul/sub_modul/{$data['id']}")?>" class="btn bg-olive btn-flat btn-sm" title="Lihat Sub Modul" ><i class="fa fa-list"></i></a>
												<?php endif; ?>
											</td>
										<?php endif; ?>
										<td><?=$data['modul']?></td>
										<td class="padat"><?=$data['ikon']?></td>
										<td class="padat"><i class="fa <?=$data['ikon']?> fa-lg"></i></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<div class='modal fade' id='confirm-acak' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
					<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
			</div>
			<div class='modal-body btn-info'>
				Apakah Anda yakin ingin mengacak data di server ini?
				<br><br>
				Data yang telah diacak tidak bisa dikembalikan. Pastikan data sudah dibackup.
			</div>
			<div class='modal-footer'>
				<button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
				<a class='btn-ok' href="<?= site_url('database/acak'); ?>" \>
					<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" id="ok-delete"><i class='fa fa-trash-o'></i> Acak</button>
				</a>
			</div>
		</div>
	</div>
</div>
