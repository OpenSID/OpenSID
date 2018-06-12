<div class="content-wrapper">
	<section class="content-header">
		<h1>Manajemen modul</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Manajemen Modul</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<div class="row">
								<div class="col-sm-2">
									<select class="form-control input-sm" name="filter" onchange="formAction('mainform','<?=site_url('modul/filter')?>')">
										<option value="">Pilih Semua</option>
										<option value="1" <?php if($filter==1 ) :?>selected<?php endif?>>Aktif</option>
										<option value="2" <?php if($filter==2 ) :?>selected<?php endif?>>Tidak Aktif</option>
									</select>
								</div>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="table table-bordered dataTable table-hover">
											<thead class="bg-gray disabled color-palette">
												<tr>
													<th><input type="checkbox" id="checkall"/></th>
													<th>No</th>
													<th>Aksi</th>
													<th width="50%">Nama Modul</th>
													<th width="30%">URL</th>
													<th>Aktif</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach($main as $data): ?>
													<tr>
														<td><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
														<td><?=$data['no']?></td>
														<td nowrap>
															<a href="<?=site_url("modul/form/$data[id]")?>" class="btn bg-orange btn-flat btn-sm" title="Ubah Data" ><i class="fa fa-edit"></i></a>
															<a href="<?=site_url("modul/sub_modul/$data[id]")?>" class="btn bg-olive btn-flat btn-sm" title="Lihat Sub Modul" ><i class="fa fa-list"></i></a>
														</td>
														<td><?=$data['modul']?></td>
														<td><?=$data['url']?></td>
														<td><?php	if($data['aktif']==1):?>Aktif<?php else:?>Tidak Aktif <?php endif?>	</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

