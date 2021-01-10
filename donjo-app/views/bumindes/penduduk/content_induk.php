<script>
	$( function() {
		$( "#cari" ).autocomplete( {
			source: function( request, response ) {
				$.ajax( {
					type: "POST",
					url: '<?= site_url("bumindes_penduduk/autocomplete"); ?>',
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
<div class="box box-info">
	<div class="box-header with-border">
		<a href="<?= site_url("bumindes_penduduk/ajax_cetak/induk/$o/cetak"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Buku Induk Penduduk" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Buku Induk Penduduk"><i class="fa fa-print "></i> Cetak</a>
		<a href="<?= site_url("bumindes_penduduk/ajax_cetak/induk/$o/unduh"); ?>?>" title="Unduh Buku Induk Penduduk" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Buku Ekspedisi" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Buku Induk Penduduk"><i class="fa fa-download"></i> Unduh</a>
	</div>
	<div class="box-body">
		<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
			<form id="mainform" name="mainform" action="" method="post">
				<div class="row">
					<div class="col-sm-12">
						<div class="input-group input-group-sm pull-right">
							<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" title="Pencarian berdasarkan nama penduduk" value="<?=html_escape($cari); ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("penduduk/filter/cari"); ?>');$('#'+'mainform').submit();}">
							<div class="input-group-btn">
								<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("bumindes_penduduk/filter/cari"); ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive table-min-height">
					<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
						<thead class="bg-gray color-palette">
							<tr>
								<th rowspan="2">Nomor Urut</th>
								<th rowspan="2">Foto</th>
								<th rowspan="2" style="width: 5px;"><?= url_order($o, "{$this->controller}/{$func}/$p", 3, 'Nama Lengkap / Panggilan'); ?></th>
								<th rowspan="2">Jenis Kelamin</th>
								<th rowspan="2">Status Perkawinan</th>
								<th colspan="2">Tempate & Tanggal Lahir</th>
								<th rowspan="2">Agama</th>
								<th rowspan="2">Pendidikan Terakhir</th>
								<th rowspan="2">Pekerjaan</th>
								<th rowspan="2">Dapat Membaca Huruf</th>
								<th rowspan="2">Kewarganegaraan</th>
								<th rowspan="2">Alamat Lengkap</th>
								<th rowspan="2">Kedudukan Dlm Keluarga</th>
								<th rowspan="2"><?= url_order($o, "{$this->controller}/{$func}/$p", 1, 'NIK'); ?></th>
								<th rowspan="2"><?= url_order($o, "{$this->controller}/{$func}/$p", 5, 'No. KK'); ?></th>
								<th rowspan="2">Ket</th>								
							</tr>
							<tr>
								<th>Tempat Lahir</th>
								<th>Tgl</th>
							</tr>
						</thead>
						<tbody>
							<?php if($main): ?>
								<?php foreach ($main as $key => $data): ?>
									<tr>
										<td class="padat"><?= ($key + $paging->offset + 1); ?></td>
										<td class="padat">
											<div class="user-panel">
												<div class="image2">
													<img src="<?= ! empty($data['foto']) ? AmbilFoto($data['foto']) : base_url('assets/files/user_pict/kuser.png') ?>" class="img-circle" alt="Foto Penduduk"/>
												</div>
											</div>
										</td>
										<td><?= strtoupper($data['nama'])?></td>
										<td><?= $data['sex'] == 'LAKI-LAKI' ? 'L':'P' ?></td>
										<td>
											<?php
											if( strpos( $data['kawin'],'KAWIN' ) !== false) {
												if( strpos( $data['kawin'],'BELUM KAWIN' ) !== false) {
													echo "BK";
												} else echo "K";
											} else {
												// untuk sekarang masih menampilkan status cerai, karena tidak ada janda dan duda
												echo "C";
											}
											?>
										</td>
										<td><?= $data['tempatlahir']?></td>
										<td><?= tgl_indo($data['tanggallahir'])?></td>
										<td><?= $data['agama']?></td>
										<td><?= $data['pendidikan']?></td>
										<td><?= $data['pekerjaan']?></td>
										<td><?= $data['bahasa']?></td>
										<td><?= $data['warganegara']?></td>
										<td><?= strtoupper($data['alamat'])?></td>
										<td><?= $data['hubungan']?></td>
										<td><a href="<?= site_url("penduduk/detail/$p/$o/$data[id]"); ?>" id="test" name="<?= $data['id']; ?>"><?= $data['nik']; ?></a></td>
										<td><a href="<?= site_url("keluarga/kartu_keluarga/$p/$o/$data[id_kk]"); ?>"><?= $data['no_kk']; ?></a></td>
										<td><?= $data['attr']['keterangan']?></td>	
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
				<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
				<a class='btn-ok'>
					<button type="button" class="btn btn-social btn-flat btn-info btn-sm" id="ok-status"><i class='fa fa-check'></i> Simpan</button>
				</a>
			</div>
		</div>
	</div>
</div>