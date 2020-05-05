<script type="text/javascript">
	function kosongkan(key)
	{
		$("[name='setting["+key+"][baris]']").val('');
		$("[name='setting["+key+"][kolom]']").val('');
		$("[name='setting["+key+"][judul]']").val('');
		$("[name='setting["+key+"][tautan]']").val('');
		$("[name='setting["+key+"][gambar]']").val('');
		$("[name='setting["+key+"][old_gambar]']").val('');
	}
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Sinergi Program</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pengaturan Sinergi Program</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?=site_url("web_widget")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Widget
							</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered dataTable table-hover">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<th>Aksi</th>
																	<th>Baris</th>
																	<th>Kolom</th>
																	<th>Judul</th>
																	<th>Gambar</th>
																	<th>Tautan</th>
																</tr>
															</thead>
															<tbody>
																<?php $kosong = 20 - count($setting); $s = 0;?>
																<?php foreach ($setting as $program): ?>
																	<?php $s++; ?>
																	<tr>
																		<td>
																			<a href="#" class="btn bg-olive btn-flat btn-sm" title="Kosongkan" onclick="kosongkan(<?= $s; ?>)"><i class='fa fa-refresh'></i> </a>
																		</td>
																		<td>
																			<div class="form-group">
																				<select class="form-control input-sm" name="setting[<?= $s?>][baris]">
																					<option value="">-- Pilih Baris --</option>
																					<?php for ($i=1; $i<11; $i++): ?>
																						<option value="<?= $i;?>" <?php if ($program['baris']==$i): ?>selected<?php endif ?>><?= $i;?></option>
																					<?php endfor?>
																				</select>
																			</div>
																		</td>
																		<td>
																			<div class="form-group">
																				<select class="form-control input-sm" name="setting[<?= $s?>][kolom]">
																					<option value="">-- Pilih Kolom --</option>
																					<?php for ($i=1; $i<4; $i++): ?>
																						<option value="<?= $i;?>" <?php if ($program['kolom']==$i): ?>selected<?php endif ?>><?= $i;?></option>
																					<?php endfor?>
																				</select>
																			</div>
																		</td>
																		<td>
																			<input class="form-control input-sm" type="text" placeholder="Judul" name="setting[<?= $s?>][judul]" value="<?= $program['judul']?>" size="40">
																		</td>
																		<td>
																			<input type="hidden" name="setting[<?= $s?>][old_gambar]" value="<?= $program['gambar']?>"/>
																			<img class="profile-user-img img-responsive img-circle" src="<?= base_url().LOKASI_GAMBAR_WIDGET.$program['gambar']?>" alt="Gambar">
																			<input type="file" name="setting[<?= $s?>][gambar]"/>
																			<p class="help-block">(Kosongkan jika tidak ingin mengubah gambar)</p>
																		</td>
																		<td>
																			<input class="form-control input-sm" type="text" placeholder="Tautan" name="setting[<?= $s?>][tautan]" value="<?= $program['tautan']?>" size="40">
																		</td>
																	</tr>
																<?php endforeach; ?>
																<?php for ($s=count($setting)+1; $s <count($setting)+$kosong; $s++): ?>
																	<tr>
																		<td>
																			<a href="#" class="btn bg-olive btn-flat btn-sm" title="Kosongkan" onclick="kosongkan(<?= $s; ?>)"><i class='fa fa-refresh'></i> </a>
																		</td>
																		<td>
																			<div class="form-group">
																				<select class="form-control input-sm" name="setting[<?= $s?>][baris]">
																					<option value="">-- Pilih Baris --</option>
																					<?php for ($i=1; $i<11; $i++): ?>
																						<option value="<?= $i;?>"><?= $i;?></option>
																					<?php endfor?>
																				</select>
																			</div>
																		</td>
																		<td>
																			<div class="form-group">
																				<select class="form-control input-sm" name="setting[<?= $s?>][kolom]">
																					<option value="">-- Pilih Kolom --</option>
																					<?php for ($i=1; $i<4; $i++): ?>
																						<option value="<?= $i;?>"><?= $i;?></option>
																					<?php endfor?>
																				</select>
																			</div>
																		</td>
																		<td>
																			<input class="form-control input-sm" type="text" placeholder="Judul" name="setting[<?= $s?>][judul]" value="" size="40">
																		</td>
																		<td>
																			<input type="file" name="setting[<?= $s?>][gambar]"/>
																		</td>
																		<td>
																			<input class="form-control input-sm" type="text" placeholder="Tautan" name="setting[<?= $s?>][tautan]" value="" size="40">
																		</td>
																	</tr>
																<?php endfor; ?>
															</tbody>
															</table>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<div class="col-xs-12">
								<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
								<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

