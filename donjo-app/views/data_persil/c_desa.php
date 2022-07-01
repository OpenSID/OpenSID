<script>
  $( function() {
	  $( "#cari" ).autocomplete({
	    source: function( request, response ) {
	      $.ajax( {
					type: "POST",
	        url: '<?= site_url('cdesa/autocomplete')?>',
	        dataType: "json",
	        data: {
	          cari: request.term
	        },
	        success: function( data ) {
	          response( JSON.parse( data ));
	        }
	      } );
	    },
	    minLength: 1,
	  } );
  } );
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Daftar C-DESA <?= ucwords($this->setting->sebutan_desa . ' ' . $this->header['desa']['nama_desa']); ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Daftar C-DESA</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-4 col-lg-3">
					<?php $this->load->view('data_persil/menu_kiri.php')?>
				</div>
				<div class="col-md-8 col-lg-9">
					<div class="box box-info">
						<div class="box-header">
							<h4 class="text-center"><strong>DAFTAR C-DESA</strong></h4>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<a href='<?= site_url('cdesa/cetak')?>' class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank">
											<i class="fa fa-print"></i>Cetak
										</a>
										<a href="<?= site_url('cdesa/unduh')?>" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" target="_blank">
											<i class="fa fa-download"></i>Unduh
										</a>
										<a href="<?= site_url('cdesa/clear')?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
										<form id="mainform" name="mainform" method="post">
											<div class="row">
												<div class="col-sm-12">
													<div class="box-tools">
														<div class="input-group input-group-sm pull-right">
															<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url('cdesa/search')?>');$('#'+'mainform').submit();}">
															<div class="input-group-btn">
																<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url('cdesa/search')?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped dataTable table-hover">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<th>No</th>
																	<th>Aksi</th>
																	<th nowrap>No. C-DESA</th>
																	<th>Nama di C-Desa</th>
																	<th>Nama Pemilik</th>
																	<th>NIK</th>
																	<th nowrap>Jumlah Persil</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($cdesa as $item): ?>
																	<tr>
																		<td><?= $item['no']?></td>
																		<td nowrap>
																			<a href="<?= site_url('cdesa/rincian/' . $item['id_cdesa'])?>" class="btn bg-purple btn-flat btn-sm" title="Rincian"><i class="fa fa-bars"></i></a>
																			<?php if ($this->CI->cek_hak_akses('u')): ?>
																				<a href="<?= site_url('cdesa/create_mutasi/' . $item['id_cdesa'])?>" class="btn bg-green btn-flat btn-sm" title="Tambah Data"><i class="fa fa-plus"></i></a>
																				<a href="<?= site_url('cdesa/create/edit/' . $item['id_cdesa'])?>" class="btn bg-yellow btn-flat btn-sm" title="Ubah Data"><i class="fa fa-edit"></i></a>
																			<?php endif; ?>
																			<?php if ($this->CI->cek_hak_akses('h')): ?>
																				<a href="#" data-href="<?= site_url('cdesa/hapus/' . $item['id_cdesa'])?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																			<?php endif; ?>
																		</td>
																		<td><?= sprintf('%04s', $item['nomor']) ?></td>
																		<td><?= $item['nama_kepemilikan'] ?>
																		<td><?= strtoupper($item['namapemilik']) ?></td>
																		<td><?= ($item['nik']) ? '<a href=' . site_url("penduduk/detail/1/0/{$item['id_pend']}") . '>' . $item['nik'] . '</a>' : '-'; ?></td>
																		<td><?= $item['jumlah'] ?></td>
																	</tr>
																<?php endforeach; ?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</form>
										<?php $this->load->view('global/paging'); ?>
									</div>
								</div>
							</div>
							<?php $this->load->view('global/confirm_delete'); ?>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

