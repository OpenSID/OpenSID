<div class="content-wrapper">
	<section class="content-header">
		<h1>Input Data Sensus - <?= $analisis_master['nama'] ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda') ?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li><a href="<?= site_url('analisis_master/clear') ?>"> Master Analisis</a></li>
			<li><a href="<?= site_url('analisis_respon/leave') ?>"><?= $analisis_master['nama'] ?></a></li>
			<li><a href="<?= site_url('analisis_respon') ?>">Data Sensus</a></li>
			<li class="active">Input Data</li>
		</ol>
	</section>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<div class="row">
				<div class="col-md-4 col-lg-3">
					<?php $this->load->view('analisis_master/left', $data); ?>
				</div>
				<div class="col-md-8 col-lg-9">
					<div id="box-full-screen" class="box box-info">
						<div class="box-header with-border">
							<?php if (isset($this->session->fullscreen)) : ?>
								<a id="toggle-btn" href="<?= current_url() ?>/2" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
									<i class="fa fa-search-minus"></i>Normal
								</a>
							<?php else : ?>
								<a id="toggle-expand-btn" href="<?= current_url() ?>/1" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
									<i class="fa fa-search-plus"></i>Full Screen
								</a>
							<?php endif; ?>
							<?php if (can('u')) : ?>
								<a href="<?= $perbaharui; ?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Perbaharui Data <?= $analisis_master['subjek_nama']; ?>"><i class="fa fa-refresh"></i> Pebaharui Data <?= $analisis_master['subjek_nama']; ?></a>
							<?php endif; ?>
							<a href="<?= site_url('analisis_respon'); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Data Sensus</a>
						</div>
						<div class="box-body">
							<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover">
												<tr>
													<td width="150">Form Pendataan</td>
													<td width="1">:</td>
													<td><a href="<?= site_url("analisis_master/menu/{$_SESSION['analisis_master']}") ?>"><?= $analisis_master['nama'] ?></a></td>
												</tr>
												<tr>
													<td>Nomor Identitas</td>
													<td>:</td>
													<td><?= $subjek['nid'] ?></td>
												</tr>
												<tr>
													<td>Nama Subjek</td>
													<td>:</td>
													<td><?= $subjek['nama'] ?></td>
												</tr>
											</table>
										</div>
										<?php if ($list_anggota) : ?>
											<div class="table-responsive">
												<table class="table table-bordered dataTable table-hover nowrap">
													<thead class="bg-gray disabled color-palette">
														<tr>
															<th>No</th>
															<?php if ($analisis_master['id_child'] != 0) : ?>
																<th>Aksi</th>
															<?php endif; ?>
															<th>NIK</th>
															<th>Nama</th>
															<th>Tanggal Lahir</th>
															<th>Jenis Kelamin</th>
														</tr>
													</thead>
													<tbody>
														<?php $i = 1;

                                            foreach ($list_anggota as $ang) : $idc = $ang['id']; ?>
															<tr>
																<td><?= $i ?></td>
																<?php if ($analisis_master['id_child'] != 0) : ?>
																	<td nowrap>
																		<a href="<?= site_url("analisis_respon/kuisioner_child/{$p}/{$o}/{$id}/{$idc}") ?>" class="btn bg-purple btn-flat btn-sm" title="Input Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="<?= $ang['nama'] ?> - [<?= $ang['nik'] ?>]"><i class='fa fa-check-square-o'></i></a>
																	</td>
																<?php endif; ?>
																<td><?= $ang['nik'] ?></td>
																<td nowrap><?= $ang['nama'] ?></td>
																<td nowrap><?= tgl_indo($ang['tanggallahir']) ?></td>
																<td><?php if ($ang['sex'] == 1) : ?>LAKI-LAKI<?php endif; ?><?php if ($ang['sex'] == 2) : ?> PEREMPUAN<?php endif; ?></td>
															</tr>
														<?php $i++;
                                            endforeach; ?>
													</tbody>
												</table>
											</div>
										<?php endif; ?>
										<div class="table-responsive">
											<table class="table">
												<?php
                                                $new = 1;
        $last                                        = 0;

        foreach ($list_jawab as $data) :
            $data['no'] = '';
            ?>
													<?php
                if ($data['id_kategori'] != $last || $last == 0) :
                    $new = 1;
                endif;
            if ($new == 1) : ?>
														<tr>
															<th colspan="2" class="bg-aqua"><strong><?= $data['kategori'] ?></strong></th>
														</tr>
													<?php
                $new  = 0;
                $last = $data['id_kategori'];
            endif;
            ?>
													<tr>
														<td colspan="2"><label><?= $data['nomor'] ?> ) <?= $data['pertanyaan'] ?></label></td>
													</tr>
													<?php if ($data['id_tipe'] == 1) : ?>
														<tr>
															<td width="35px;"></td>
															<td class="col-xs-12 col-sm-4 pull-left">
																<select class="form-control input-sm select2" name="rb[<?= $data['id'] ?>]" onchange="formAction('mainform', '<?= site_url('analisis_indikator/kategori') ?>')">
																	<option value="" <?= jecho(($data['referensi'] && $subjek[$data['referensi']]) && $subjek[$data['referensi']] != $data2['kode_jawaban'] || ($data2['cek'] == 1), true, 'disabled'); ?>>Pilih Jawaban</option>
																	<?php foreach ($data['parameter_respon'] as $data2) : ?>
																		<option <?= jecho(($data['referensi'] && $subjek[$data['referensi']]) && $subjek[$data['referensi']] != $data2['kode_jawaban'] || ($data2['cek'] == 1), true, ''); ?> value="<?= $data['id'] ?>.<?= $data2['id_parameter']; ?>" <?= selected(($data2['cek'] == 1) || ($subjek[$data['referensi']] == $data2['kode_jawaban']), true); ?>><?= $data2['kode_jawaban'] ?>. <?= $data2['jawaban'] ?></option>
																	<?php endforeach; ?>
																</select>
															</td>
														</tr>
													<?php elseif ($data['id_tipe'] == 2) : ?>
														<tr>
															<td></td>
															<td id="op_item">
																<?php foreach ($data['parameter_respon'] as $data2) : ?>
																	<div class="checkbox">
																		<label>
																			<input name="cb[<?= $data2['id_parameter'] ?>_<?= $data['id'] ?>]" value="<?= $data['id'] ?>.<?= $data2['id_parameter'] ?>" type="checkbox" <?= selected($data2['cek'], true, 1) ?>>
																			<?= $data2['kode_jawaban'] ?>. <?= $data2['jawaban'] ?>
																		</label>
																	</div>
																<?php endforeach; ?>
															</td>
														</tr>
													<?php elseif ($data['id_tipe'] == 3) : ?>
														<div class="form-group">
															<tr>
																<td></td>
																<td>
																	<input <?= jecho($data['referensi'] && $subjek[$data['referensi']], true, 'readonly'); ?> class="form-control input-sm" name="ia[<?= $data['id'] ?>]" value="<?= $data['parameter_respon']['jawaban'] ?? $subjek[$data['referensi']]; ?>" type="number" min=0>
																</td>
															</tr>
														</div>
													<?php else : ?>
														<div class="form-group">
															<tr>
																<td></td>
																<td>
																	<?php
                            if (preg_match('/tanggal/i', $data['referensi']) || preg_match('/tanggal/i', $data['referensi'])) {
                                $subjek[$data['referensi']] = tgl_indo_dari_str($subjek[$data['referensi']]);
                            }
                                                        ?>
																	<textarea <?= jecho($data['referensi'] && $subjek[$data['referensi']], true, 'readonly'); ?> id="it[<?= $data['id'] ?>]" name="it[<?= $data['id'] ?>]" class="form-control input-sm" style="width:100%" rows="5"><?= $data['parameter_respon']['jawaban'] ?? $subjek[$data['referensi']]; ?></textarea>
																</td>
															</tr>
														</div>
													<?php endif; ?>
													<tr>
														<td colspan="2" style="height:15px;"></td>
													</tr>
												<?php endforeach; ?>
											</table>
										</div>
										<div class="col-sm-12">
											<?php if (! empty($list_bukti)) : ?>
												<div class="form-group">
													<label class="col-sm-2 no-padding">Berkas Form Pendaftaran</label>
													<div class="col-sm-2">
														<input type="hidden" name="old_file" value="<?= $list_bukti[0]['pengesahan'] ?>">
														<img class="attachment-img img-responsive" src="<?= base_url(LOKASI_PENGESAHAN . $list_bukti[0]['pengesahan']) ?>" alt="Bukti Pengesahan">
													</div>
												</div>
											<?php endif; ?>
											<div class="form-group">
												<label class="control-label" for="upload">Unggah Berkas Form Pendataan</label>
												<div class="input-group input-group-sm">
													<input type="text" class="form-control" id="file_path">
													<input id="file" type="file" class="hidden" name="pengesahan">
													<span class="input-group-btn">
														<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
													</span>
												</div>
												<?php if (! empty($list_bukti)) : ?>
													<p class="help-block"><code>(Kosongkan jika tidak ingin mengubah berkas)</code></p>
												<?php endif; ?>
												<p><label class="control-label">*) Format file harus *.jpg</label></p>
												<p><label class="control-label">*) Berkas form pendataan digunakan sebagai penguat / bukti pendataan maupun untuk verifikasi data yang sudah terinput.</label></p>
												<p><label class="control-label">*) Berkas Bukti / pengesahan harus berupa file gambar dengan format .jpg, dengan ukuran maksimal 1 Mb (1 megabyte)</label></p>
											</div>
										</div>
									</div>
								</div>
								<?php if (can('u')) : ?>
									<div class="box-footer">
										<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm'><i class='fa fa-times'></i> Batal</button>
										<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
		</form>
	</section>
</div>
<script>
	$(document).ready(function() {
		var fullscreen = '<?= $this->session->fullscreen; ?>';

		if (fullscreen) {
			$('#box-full-screen').addClass("panel-fullscreen");
		} else {
			$('#box-full-screen').removeClass("panel-fullscreen");
		}

		var op_item_width = (parseInt($('#op_item').width()) / 2 - 10);
		var label_width = (parseInt($('#op_item').width()) / 2) - 42;
		// $('#op_item div').css('clear','both');
		// $('#op_item div').css('float','left');
		$('#op_item div').css('width', op_item_width);
		$('#op_item label').css('width', label_width);
	});
</script>