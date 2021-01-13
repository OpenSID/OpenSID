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
		<a href="<?= site_url("bumindes_penduduk/ajax_cetak/rekapitulasi/$o/cetak"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Buku Rekapitulasi Penduduk Desa" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Buku Rekapitulasi Penduduk Desa"><i class="fa fa-print "></i> Cetak</a>
		<a href="<?= site_url("bumindes_penduduk/ajax_cetak/rekapitulasi/$o/unduh"); ?>?>" title="Unduh Buku Rekapitulasi Penduduk Desa" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Buku Rekapitulasi Penduduk Desa" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Buku Rekapitulasi Penduduk Desa"><i class="fa fa-download"></i> Unduh</a>
	</div>
	<div class="box-body">
		<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
			<form id="mainform" name="mainform" action="" method="post">
				<div class="row">
					<div class="col-sm-12">
						<div class="input-group input-group-sm pull-right">
							<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" title="Pencarian berdasarkan nama penduduk" value="<?=html_escape($cari); ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("penduduk/filter/cari/rekapitulasi"); ?>');$('#'+'mainform').submit();}">
							<div class="input-group-btn">
								<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("bumindes_penduduk/filter/cari/rekapitulasi"); ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive table-min-height">
					<table class="table table-condensed table-bordered dataTable table-striped table-hover tabel-daftar">
						<thead class="bg-gray color-palette">
							<tr>
								<th rowspan="4">Nomor Urut</th>
								<th rowspan="4">Nama Dusun / Lingkungan</th>
								<th colspan="7">Jumlah Penduduk Awal Bulan</th>
								<th colspan="8">Tambahan Bulan Ini</th>
								<th colspan="8">Pengurangan Bulan Ini</th>
								<th rowspan="2" colspan="7">Jml Penduduk Akhir Bulan</th>
								<th rowspan="4">Ket</th>								
							</tr>
							<tr>
								<th colspan="2">WNA</th>
								<th colspan="2">WNI</th>
								<th rowspan="3">Jlh KK</th>
								<th rowspan="3">Jml Anggota Keluarga</th>
								<th rowspan="3">Jml Jiwa (7+8)</th>
								<th colspan="4">Lahir</th>
								<th colspan="4">Datang</th>
								<th colspan="4">Meninggal</th>
								<th colspan="4">Pindah</th>
							</tr>
							<tr>
								<th rowspan="2">L</th>
								<th rowspan="2">P</th>
								<th rowspan="2">L</th>
								<th rowspan="2">P</th>
								<th colspan="2">WNA</th>
								<th colspan="2">WNI</th>
								<th colspan="2">WNA</th>
								<th colspan="2">WNI</th>
								<th colspan="2">WNA</th>
								<th colspan="2">WNI</th>
								<th colspan="2">WNA</th>
								<th colspan="2">WNI</th>
								<th colspan="2">WNA</th>
								<th colspan="2">WNI</th>
								<th rowspan="2">Jml KK</th>
								<th rowspan="2">Jml Anggota Keluarga</th>
								<th rowspan="2">Jml Jiwa (31+32)</th>
							</tr>
							<tr>
								<th>L</th>
								<th>P</th>
								<th>L</th>
								<th>P</th>
								<th>L</th>
								<th>P</th>
								<th>L</th>
								<th>P</th>
								<th>L</th>
								<th>P</th>
								<th>L</th>
								<th>P</th>
								<th>L</th>
								<th>P</th>
								<th>L</th>
								<th>P</th>
								<th>L</th>
								<th>P</th>
								<th>L</th>
								<th>P</th>
							</tr>
						</thead>
						<tbody>
						<!-- 
							""" 
							Menunggu detil informasi data tiap attributnya sudah atau belum, 
							jika sudah ada bagaimana proses menuju flow tersebut 
							""" 
						-->
							<?php if(!$main): ?>
								<?php foreach ($main as $key => $data): ?>
									<tr>
										<td class="padat"><?= ($key + $paging->offset + 1); ?></td>
										<td><?= strtoupper($data['nama'])?></td>
										<td><?= strtoupper($data['sex']) ?></td>
										<td><?= (strpos($data['kawin'],'KAWIN') !== false) ? $data['kawin'] : (($data['sex'] == 'LAKI-LAKI') ? 'DUDA':'JANDA') ?></td>
										<td><?= $data['tempatlahir']?></td>
										<td><?= tgl_indo_out($data['tanggallahir'])?></td>
										<td><?= $data['agama']?></td>
										<td><?= $data['pendidikan']?></td>
										<td><?= $data['pekerjaan']?></td>
										<td><?= $data['pekerjaan']?></td>
										<td><?= $data['pekerjaan']?></td>
										<td><?= strtoupper($data['bahasa_nama'])?></td>
										<td><?= $data['warganegara']?></td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td class="text-center" colspan="33">Data Tidak Tersedia</td>
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