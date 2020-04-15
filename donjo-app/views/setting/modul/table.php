<script type="text/javascript">
	function ubah_jenis_server(jenis_server)
	{
		$('#offline_saja select').val('');
		if (jenis_server == 3)
		{
			$('#offline_saja').hide();
			$('#offline_saja select').removeClass('required');
			$('#offline_online_hosting select').val('');
			$('#offline_online_hosting select').addClass('required');
			$('#offline_online_hosting').show();
		}
		else
		{
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
	function ubah_server(server)
	{
		$('#offline_saja select').val('');
		$('#offline_ada_hosting select').val('');;
		if (server == 5)
		{
			$('#offline_ada_hosting select').addClass('required');
			$('#offline_ada_hosting').show();
		}
		else
		{
			$('#offline_ada_hosting select').removeClass('required');
			$('#offline_ada_hosting').hide();
		}
	}
	
	$(function()
	{
		var keyword = <?= $keyword?> ;
		$( "#cari" ).autocomplete(
		{
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Manajemen modul</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Manajemen Modul</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<form id="validasi" action="<?= site_url("modul/ubah_server")?>" method="POST" class="form-horizontal">
						<div class="box-body">
							<h4>Penggunaan Server</h4>
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
										<option value="3" <?php in_array($this->setting->penggunaan_server, array('3', '5', '6')) and print('selected') ?>>
											Offline di kantor desa dan online di hosting
										</option>
										<option value="4" <?php selected($this->setting->penggunaan_server, '4')?>>
											Offline dan online di kantor desa
										</option>
									</select>
								</div>
							</div>
							<div class="form-group" id="offline_online_hosting" style="<?php !in_array($this->setting->penggunaan_server, array('3', '5', '6')) and print('display: none;') ?>">
								<label class="col-sm-3 control-label">Server ini digunakan sebagai</label>
								<div class="col-sm-9 col-lg-4">
									<select class="form-control input-sm" name="server_mana" onchange="ubah_server($(this).val())">
										<option value='' selected="selected">-- Pilih Server Ini --</option>
										<option value="5" <?php selected($this->setting->penggunaan_server, '5')?>>
											Offline di kantor desa
										</option>
										<option value="6" <?php selected($this->setting->penggunaan_server, '6')?>>
											Online di hosting
										</option>
									</select>
								</div>
							</div>
							<div class="form-group" id="offline_ada_hosting" style="<?php !in_array($this->setting->penggunaan_server, array('5')) and print('display: none;') ?>">
								<label class="col-sm-3 control-label">Akses web pada server offline ini</label>
								<div class="col-sm-6 col-lg-4">
									<select class="form-control input-sm" name="offline_mode">
										<option value='' selected="selected">-- Pilih Akses Web --</option>
										<option value="1" <?php ($this->setting->penggunaan_server == '5' and $this->setting->offline_mode =='1') and print('selected')?>>
											Web bisa diakses petugas web
										</option>
										<option value="2" <?php ($this->setting->penggunaan_server == '5' and $this->setting->offline_mode =='2') and print('selected')?>>
											Web non-aktif sama sekali
										</option>
									</select>
								</div>
							</div>
							<div class="form-group" id="offline_saja" style="<?php !in_array($this->setting->penggunaan_server, array('1')) and print('display: none;') ?>">
								<label class="col-sm-3 control-label">Akses web pada server offline ini</label>
								<div class="col-sm-9 col-lg-4">
									<select class="form-control input-sm" name="offline_mode_saja">
										<option value='' selected="selected">-- Pilih Akses Web --</option>
										<option value="0" <?php ($this->setting->penggunaan_server == '1' and $this->setting->offline_mode == '0') and print('selected')?>>
											Web bisa diakses publik
										</option>
										<option value="1" <?php ($this->setting->penggunaan_server == '1' and $this->setting->offline_mode == '1') and print('selected')?>>
											Web bisa diakses petugas web
										</option>
										<option value="2" <?php ($this->setting->penggunaan_server == '1' and $this->setting->offline_mode == '2') and print('selected')?>>
											Web non-aktif sama sekali
										</option>
									</select>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
	<section class="content" id="atur_modul">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-body">
							<h4>Pengaturan Modul</h4>
							<div class="row">
								<div class="col-xs-12 text-center">
									<a href="<?= site_url("modul/default_server")?>" class="btn btn-social btn-flat btn-success btn-sm" <?php $this->setting->penggunaan_server or print("disabled='disabled'")?>><i class="fa fa-refresh"></i>Kembalikan ke default penggunaan server</a>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-6">
													<select class="form-control input-sm " name="filter" onchange="formAction('mainform','<?=site_url('modul/filter')?>')">
														<option value="">Semua</option>
														<option value="1" <?php if ($filter==1): ?>selected<?php endif ?>>Aktif</option>
														<option value="2" <?php if ($filter==2): ?>selected<?php endif ?>>Tidak Aktif</option>
													</select>
												</div>
												<div class="col-sm-6">
													<div class="box-tools">
														<div class="input-group input-group-sm pull-right">
															<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13):$('#'+'mainform').attr('action','<?=site_url('modul/search')?>');$('#'+'mainform').submit();endif;">
															<div class="input-group-btn">
																<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action','<?= site_url("modul/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
																	<th width="10%">No</th>
																	<th>Aksi</th>
																	<th width="50%">Nama Modul</th>
																	<th>Aktif</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($main as $data): ?>
																	<tr>
																		<td><?=$data['no']?></td>
																		<td nowrap>
																			<a href="<?=site_url("modul/form/$data[id]")?>" class="btn bg-orange btn-flat btn-sm" title="Ubah Data" ><i class="fa fa-edit"></i></a>
																			<?php if (count($data['submodul'])>0): ?>
																				<a href="<?=site_url("modul/sub_modul/$data[id]")?>" class="btn bg-olive btn-flat btn-sm" title="Lihat Sub Modul" ><i class="fa fa-list"></i></a>
																			<?php endif; ?>
																		</td>
																		<td><?=$data['modul']?></td>
																		<td><?php	if ($data['aktif']==1): ?>Aktif<?php else: ?>Tidak Aktif <?php endif; ?></td>
																	</tr>
																<?php endforeach; ?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</form>
									</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

