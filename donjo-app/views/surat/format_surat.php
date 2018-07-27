<div class="content-wrapper">
	<section class="content-header">
		<h1>Cetak Layanan Surat</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Cetak Layanan Surat</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<form id="main" name="main" action="<?= site_url()?>surat/search" method="post">
							<div class="row">
								<div class="col-sm-6">
									<select class="form-control select2 " id="nik" name="nik" onchange="formAction('main')">
										<option selected="selected">-- Cari Judul Surat--</option>
										<?php foreach ($menu_surat2 as $data):?>
											<option value="<?= $data['url_surat']?>"><?= strtoupper($data['nama'])?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
						</form>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<table class="table table-bordered dataTable table-hover">
													<thead class="bg-gray disabled color-palette">
														<tr>
															<th width="50%">Layanan Administrasi Surat</th>
															<th>Kode Surat</th>
															<th>Link</th>
															<th width="7%">Aksi</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($menu_surat2 AS $data):?>
															<?php
																if ($data['url_surat'] == 'surat_persetujuan_mempelai'):
																	$surat_url = site_url()."surat/form/".$data['url_surat']."/clear";
																else:
																	$surat_url = site_url()."surat/form/".$data['url_surat'];
																endif;
															?>
															<tr>
																<td><?= $data['nama']?></td>
																<td><?= $data['kode_surat']?></td>
																<td><?= $data['url_surat']?></td>
																<td>
																	<a href="<?= $surat_url?>" class="btn btn-social btn-flat bg-purple btn-sm"  title="Kode Isian"><i class="fa fa-file-word-o"></i>Buat Surat</a>
																</td>
															</tr>
														<?php endforeach;?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

