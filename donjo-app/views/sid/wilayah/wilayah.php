<script>
	$(function() {
		var keyword = <?= $keyword?> ;
		$( "#cari" ).autocomplete( {
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Wilayah Administratif <?= ucwords($this->setting->sebutan_dusun) ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Daftar <?= ucwords($this->setting->sebutan_dusun) ?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<?php if ($this->CI->cek_hak_akses('u')): ?>
							<a href="<?= site_url('sid_core/form')?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Data"><i class="fa fa-plus"></i> Tambah <?= ucwords($this->setting->sebutan_dusun) ?></a>
						<?php endif; ?>
						<a href="<?= site_url("{$this->controller}/dialog/cetak")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data"><i class="fa fa-print "></i> Cetak</a>
						<a href="<?= site_url("{$this->controller}/dialog/unduh")?>" title="Unduh Data" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data"><i class="fa fa-download"></i> Unduh</a>
						<a href="<?= site_url("{$this->controller}/clear"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" method="post">
										<div class="row">
											<div class="col-sm-12">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action','<?= site_url('sid_core/search')?>');$('#'+'mainform').submit();};">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action','<?= site_url('sid_core/search')?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
																<th class="padat">No</th>
																<th wlass="padat">Aksi</th>
																<th width="25%"> <?= ucwords($this->setting->sebutan_dusun) ?></th>
																<th width="35%">Kepala <?= ucwords($this->setting->sebutan_dusun) ?></th>
																<th>RW</th>
																<th>RT</th>
																<th>KK</th>
																<th>L+P</th>
																<th>L</th>
																<th>P</th>
															</tr>
														</thead>
														<tbody>
															<?php
                                                                $total = [];
		$total['total_rw']                                                   = 0;
		$total['total_rt']                                                   = 0;
		$total['total_kk']                                                   = 0;
		$total['total_warga']                                                = 0;
		$total['total_warga_l']                                              = 0;
		$total['total_warga_p']                                              = 0;

		foreach ($main as $data):
		    ?>
															<tr>
																<td class="no_urut"><?= $data['no']?></td>
																<td nowrap>
																	<?php if ($this->CI->cek_hak_akses('u')): ?>
																		<a href="<?= site_url("sid_core/urut/dusun/{$paging->page}/{$data['id']}/1"); ?>" class="btn bg-olive btn-flat btn-sm <?php ($data['no'] == $paging->num_rows) && print 'disabled'; ?>" title="Pindah Posisi Ke Bawah"><i class="fa fa-arrow-down"></i></a>
																		<a href="<?= site_url("sid_core/urut/dusun/{$paging->page}/{$data['id']}/2"); ?>" class="btn bg-olive btn-flat btn-sm <?php ($data['no'] == 1 && $paging->page == $paging->start_link) && print 'disabled'; ?>" title="Pindah Posisi Ke Atas"><i class="fa fa-arrow-up"></i></a>
																	<?php endif; ?>
																	<a href="<?= site_url("sid_core/sub_rw/{$data['id']}")?>" class="btn bg-purple btn-flat btn-sm" title="Rincian Sub Wilayah"><i class="fa fa-list"></i></a>
																	<?php if ($this->CI->cek_hak_akses('u')): ?>
																		<a href="<?= site_url("sid_core/form/{$data['id']}")?>" class="btn bg-orange btn-flat btn-sm" title="Ubah"><i class="fa fa-edit"></i></a>
																	<?php endif; ?>
																	<?php if ($this->CI->cek_hak_akses('h')): ?>
																		<a href="#" data-href="<?= site_url("sid_core/delete/dusun/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																	<?php endif; ?>
																	<div class="btn-group">
																		<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Peta</button>
																		<ul class="dropdown-menu" role="menu">
																			<li>
																				<a href="<?= site_url("sid_core/ajax_kantor_dusun_maps/{$data['id']}")?>" class="btn btn-social btn-flat btn-block btn-sm"><i class='fa fa-map-marker'></i> Lokasi Kantor <?=ucwords($this->setting->sebutan_dusun)?></a>
																			</li>
																			<li>
																				<a href="<?= site_url("sid_core/ajax_wilayah_dusun_maps/{$data['id']}")?>" class="btn btn-social btn-flat btn-block btn-sm"><i class='fa fa-map'></i> Peta Wilayah <?=ucwords($this->setting->sebutan_dusun)?></a>
																			</li>
																		</ul>
																	</div>
																</td>
																<td><?= strtoupper($data['dusun'])?></td>
																<td nowrap><strong><?= strtoupper($data['nama_kadus'])?></strong> - <?= $data['nik_kadus']?></td>
																<td class="bilangan"><a href="<?= site_url("sid_core/sub_rw/{$data['id']}")?>" title="Rincian Sub Wilayah"><?= $data['jumlah_rw']?></a></td>
																<td class="bilangan"><?= $data['jumlah_rt']?></td>
																<td class="bilangan"><a href="<?= site_url("sid_core/warga_kk/{$data['id']}")?>"><?= $data['jumlah_kk']?></a></td>
																<td class="bilangan"><a href="<?= site_url("sid_core/warga/{$data['id']}")?>"><?= $data['jumlah_warga']?></a></td>
																<td class="bilangan"><a href="<?= site_url("sid_core/warga_l/{$data['id']}")?>"><?= $data['jumlah_warga_l']?></a></td>
																<td class="bilangan"><a href="<?= site_url("sid_core/warga_p/{$data['id']}")?>"><?= $data['jumlah_warga_p']?></a></td>
															</tr>
															<?php
		        $total['total_rw'] += $data['jumlah_rw'];
		    $total['total_rt'] += $data['jumlah_rt'];
		    $total['total_kk'] += $data['jumlah_kk'];
		    $total['total_warga'] += $data['jumlah_warga'];
		    $total['total_warga_l'] += $data['jumlah_warga_l'];
		    $total['total_warga_p'] += $data['jumlah_warga_p'];
		endforeach;
		?>
														</tbody>
														<tfoot>
															<tr>
																<th colspan="4"><label>TOTAL</label></th>
																<th class="bilangan"><?= $total['total_rw']?></th>
																<th class="bilangan"><?= $total['total_rt']?></th>
																<th class="bilangan"><?= $total['total_kk']?></th>
																<th class="bilangan"><?= $total['total_warga']?></th>
																<th class="bilangan"><?= $total['total_warga_l']?></th>
																<th class="bilangan"><?= $total['total_warga_p']?></th>
															</tr>
														</tfoot>
													</table>
												</div>
											</div>
										</div>
									</form>
									<?php $this->load->view('global/paging'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script src="<?= base_url()?>assets/js/validasi.js"></script>
<script src="<?= base_url()?>assets/js/localization/messages_id.js"></script>


