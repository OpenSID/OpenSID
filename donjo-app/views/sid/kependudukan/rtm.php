<script>
	$(function() {
		var keyword = <?= $keyword?> ;
		$( "#cari" ).autocomplete( {
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<style>
	.input-sm {
		padding: 4px 4px;
	}

	.table-min-height {
		min-height: 350px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengelompokan Rumah Tangga</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Daftar Rumah Tangga</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url('rtm/form_old')?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Data Rumah Tangga Per Penduduk" title="Tambah Dari Data Penduduk" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-plus'></i>Tambah Rumah Tangga</a>
						<?php if ($this->CI->cek_hak_akses('h')): ?>
							<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?= site_url("rtm/delete_all/$p/$o")?>')" class="btn btn-social btn-flat	btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
						<?php endif; ?>
						<a href="<?= site_url("rtm/daftar/cetak")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank"><i class="fa fa-print "></i> Cetak</a>
						<a href="<?= site_url("rtm/daftar/unduh")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank"><i class="fa fa-download"></i> Unduh</a>
						<a href="<?= site_url("{$this->controller}/clear") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan Filter</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-8">
												<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('rtm/dusun')?>')">
													<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun); ?></option>
													<?php foreach ($list_dusun AS $data): ?>
														<option value="<?= $data['dusun']?>" <?= selected($dusun, $data['dusun']); ?>><?= set_ucwords($data['dusun'])?></option>
													<?php endforeach; ?>
												</select>
												<?php if ($dusun): ?>
													<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('rtm/rw')?>')" >
														<option value="">Pilih RW</option>
														<?php foreach ($list_rw AS $data): ?>
															<option value="<?= $data['rw']?>" <?= selected($rw, $data['rw']); ?>><?= set_ucwords($data['rw'])?></option>
														<?php endforeach; ?>
													</select>
												<?php endif; ?>
												<?php if ($rw): ?>
													<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('rtm/rt')?>')">
														<option value="">Pilih RT</option>
														<?php foreach ($list_rt AS $data): ?>
															<option value="<?= $data['rt']?>" <?= selected($rt, $data['rt']); ?>><?= set_ucwords($data['rt'])?></option>
														<?php endforeach; ?>
													</select>
												<?php endif; ?>
											</div>
											<div class="col-sm-4">
												<div class="input-group input-group-sm pull-right">
													<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url("rtm/filter/cari")?>');$('#'+'mainform').submit();}">
													<div class="input-group-btn">
														<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("rtm/filter/cari")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive table-min-height">
													<table class="table table-bordered table-striped dataTable table-hover nowrap">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th class="padat"><input type="checkbox" id="checkall"/></th>
																<th class="padat">No</th>
																<th class="padat">Aksi</th>
																<th class="padat">Foto</th>
																<?php if ($order_by==2): ?>
																	<th><a href="<?= site_url("rtm/filter/order_by/1")?>">Nomor Rumah Tangga <i class='fa fa-sort-asc fa-sm'></i></a></th>
																 <?php elseif ($order_by==1): ?>
																	<th><a href="<?= site_url("rtm/filter/order_by/2")?>">Nomor Rumah Tangga <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else: ?>
																	<th><a href="<?= site_url("rtm/filter/order_by/1")?>">Nomor Rumah Tangga <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<?php if ($order_by==4): ?>
																	<th nowrap><a href="<?= site_url("rtm/filter/order_by/3")?>">Kepala Rumah Tangga <i class='fa fa-sort-asc fa-sm'></i></a></th>
																<?php elseif ($order_by==3): ?>
																	<th nowrap><a href="<?= site_url("rtm/filter/order_by/4")?>">Kepala Rumah Tangga <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else: ?>
																	<th nowrap><a href="<?= site_url("rtm/filter/order_by/3")?>">Kepala Rumah Tangga <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<th width="10%">NIK</th>
																<th>Jumlah Anggota</th>
																<th>Alamat</th>
																<th><?= ucwords($this->setting->sebutan_dusun)?></th>
																<th>RW</th>
																<th>RT</th>
																<th nowrap>Tanggal Terdaftar</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($main as $key => $data): ?>
																<tr>
																	<td class="text-center"><input type="checkbox" name="id_cb[]" value="<?= $data['no_kk']?>" /></td>
																	<td class="text-center"><?= ($paging->offset + $key + 1) ; ?></td>
																	<td nowrap>
																		<a href="<?= site_url("rtm/anggota/$data[id]")?>" class="btn bg-purple btn-flat btn-sm" title="Rincian Anggota Rumah Tangga"><i class="fa fa-list-ol"></i></a>
																		<a href="<?= site_url("rtm/ajax_add_anggota/$data[id]")?>" title="Tambah Anggota Rumah Tangga" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Anggota Rumah Tangga" class="btn btn-success btn-flat btn-sm"><i class="fa fa-plus"></i></a>
																		<a href="<?= site_url("rtm/edit_nokk/$data[id]")?>" title="Ubah Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Rumah Tangga" class="btn bg-orange btn-flat btn-sm"><i class='fa fa-edit'></i></a>
																		<?php if ($this->CI->cek_hak_akses('h')): ?>
																			<a href="#" data-href="<?= site_url("rtm/delete/$data[no_kk]")?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																		<?php endif; ?>
																	</td>
																	<td nowrap>
																		<div class="user-panel">
																			<div class="image2">
																				<img src="<?= !empty($data['foto']) ? AmbilFoto($data['foto']) : base_url('assets/files/user_pict/kuser.png') ?>" class="img-circle" alt="Foto Penduduk"/>
																			</div>
																		</div>
																	</td>
																	<td>
																		<label><?= $data['no_kk']?></label>
																	</td>
																	<td nowrap><?= strtoupper($data['kepala_kk'])?></td>
																	<td><?= strtoupper($data['nik'])?></td>
																	<td class="text-center">
																		<a href="<?= site_url("rtm/anggota/$data[id]")?>"><?= $data['jumlah_anggota']?></a>
																	</td>
																	<td><?= strtoupper($data['alamat'])?></td>
																	<td><?= strtoupper($data['dusun'])?></td>
																	<td><?= strtoupper($data['rw'])?></td>
																	<td><?= strtoupper($data['rt'])?></td>
																	<td nowrap><?= tgl_indo($data['tgl_daftar'])?></td>
																</tr>
															<?php endforeach; ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</form>
									<?php $this->load->view('global/paging');?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete');?>
