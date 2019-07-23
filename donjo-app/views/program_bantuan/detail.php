<script>
	$(function()
	{
		var keyword = <?= $keyword != '' ? $keyword : '""' ?> ;
		$( "#cari" ).autocomplete(
			{
				source: keyword,
				maxShowItems: 10,
			});
	});

</script>
<style>
	.input-sm
	{
		padding: 4px 4px;
	}
	@media (max-width:780px)
	{
		.btn-group-vertical
		{
			display: block;
		}
	}
	.table-responsive
	{
		min-height:275px;
	}
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Rincian Program Bantuan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('program_bantuan')?>"> Daftar Program Bantuan</a></li>
			<li class="active">Rincian Program Bantuan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<?php $detail = $program[0];?>
				<div class="box box-info">
					<div class="box-header with-border">
						<?php if ($program[0]["status"] == 1): ?>
							<a href="<?=site_url("program_bantuan/form/".$program[0]['id'])?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Peserta Baru">
								<i class="fa fa-plus"></i>Tambah Peserta Baru
							</a>
						 <?php endif; ?>
						 <a href="<?=site_url("program_bantuan/unduhsheet/$detail[id]/")?>" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" target="_blank">
							<i class="fa fa-download"></i>Unduh
            </a>
						<a href="<?=site_url('program_bantuan')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Program Bantuan</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<form id="mainform" name="mainform" action="" method="post">
								<input type="hidden" name="id" value="<?php echo $this->uri->segment(4) ?>">
									<div class="row">
										<div class="col-sm-12">
											<div class="box-header with-border">
												<h3 class="box-title">Rincian Program</h3>
											</div>
											<div class="box-body">
												<table class="table table-bordered  table-striped table-hover" >
													<tbody>
														<tr>
															<td style="padding-top : 10px;padding-bottom : 10px; width:15%;" nowrap>Nama Program</td>
															<td> : <?= strtoupper($detail["nama"])?></td>
														</tr>
														<tr>
															<td style="padding-top : 10px;padding-bottom : 10px;" nowrap>Sasaran Peserta</td>
															<td> :  <?= $sasaran[$detail["sasaran"]]?></td>
														</tr>
														<tr>
															<td style="padding-top : 10px;padding-bottom : 10px;" nowrap>Masa Berlaku</td>
															<td> : <?= fTampilTgl($detail["sdate"],$detail["edate"])?></td>
														</tr>
														<tr>
															<td style="padding-top : 10px;padding-bottom : 10px;" nowrap>Keterangan</td>
															<td> : <?= $detail["ndesc"]?></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="row">
												<div class="col-sm-9">
													<div class="box-header with-border">
														<h3 class="box-title">Daftar Peserta Program</h3>
													</div>
												</div>
												<div class="col-sm-3">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari_peserta)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url("program_bantuan/search_peserta")?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("program_bantuan/search_peserta")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-sm-12">
										<?php $peserta = $program[1];?>
											<div class="table-responsive">
												<table class="table table-bordered table-striped dataTable table-hover">
													<thead class="bg-gray disabled color-palette">
														<tr>
															<th rowspan="2" class="text-center">No</th>
															<th rowspan="2" class="text-center">Aksi</th>
															<th rowspan="2" nowrap class="text-center"><?= $detail["judul_peserta"]?></th>
															<?php if (!empty($detail['judul_peserta_plus'])): ?>
																<th rowspan="2" nowrap class="text-center"><?= $detail["judul_peserta_plus"]?></th>
															<?php endif ;?>
															<th rowspan="2" nowrap class="text-center"><?= $detail["judul_peserta_info"]?></th>
															<th rowspan="2" class="text-center">Alamat</th>
															<th colspan="6" class="text-center">Identitas di Kartu Peserta</th>
														</tr>
														<tr>
															<th rowspan="2" nowrap class="text-center">No. Kartu Peserta</th>
															<th class="text-center">NIK</th>
															<th class="text-center">Nama</th>
															<th class="text-center" nowrap>Tempat Lahir</th>
															<th class="text-center" nowrap>Tanggal Lahir</th>
															<th class="text-center">Alamat</th>
														</tr>
													</thead>
													<tbody>
														<?php $nomer = $paging->offset;?>
														<?php if (is_array($peserta)): ?>
															<?php foreach ($peserta as $key=>$item): $nomer++;?>
																<tr>
																	<td class="text-center"><?= $nomer?></td>
																	<td nowrap class="text-center">
																		<a href="<?= site_url("program_bantuan/edit_peserta_form/$item[id]")?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data Peserta"><i class="fa fa-edit"></i></a>
																		<a href="#" data-href="<?= site_url("program_bantuan/hapus_peserta/$detail[id]/$item[id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																	</td>
																	<?php $id_peserta = ($detail['sasaran'] == 4) ? $item['peserta'] : $item['nik'] ?>
																	<td nowrap class="text-center"><a href="<?= site_url("program_bantuan/peserta/$detail[sasaran]/$id_peserta/")?>" title="Daftar program untuk peserta"><?= $item["peserta_nama"] ?></a></td>
																	<?php if (!empty($item['peserta_plus'])): ?>
																		<td nowrap><?= $item["peserta_plus"]?></td>
																	<?php endif; ?>
																	<td nowrap><?= $item["peserta_info"]?></td>
																	<td nowrap><?= $item["info"];?></td>
																	<td nowrap class="text-center"><a href="<?= site_url("program_bantuan/data_peserta/$item[id]")?>" title="Data peserta"><?= $item['no_id_kartu'];?></a></td>
																	<td class="text-center"><?= $item["kartu_nik"];?></td>
																	<td><?= $item["kartu_nama"];?></td>
																	<td nowrap><?= $item["kartu_tempat_lahir"];?></td>
																	<td nowrap class="text-center"><?= tgl_indo_out($item["kartu_tanggal_lahir"]);?></td>
																	<td><?= $item["kartu_alamat"];?></td>
																</tr>
															<?php endforeach; ?>
														<?php endif; ?>
													</tbody>
												</table>
											</div>
										</div>

									</div>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="dataTables_length">
                        <form id="paging" action="<?= site_url("program_bantuan/detail/1/$detail[id]")?>" method="post" class="form-horizontal">
                         <label>
                            Tampilkan
                            <select name="per_page" class="form-control input-sm" onchange="$('#mainform').submit();" id="per_page_input">
      	                      <option value="20" <?php selected($per_page, 20); ?> >20</option>
                              <option value="50" <?php selected($per_page, 50); ?> >50</option>
                              <option value="100" <?php selected($per_page, 100); ?> >100</option>
                            </select>
        	                  Dari
                            <strong><?= $paging->num_rows?></strong>
                            Total Data
                          </label>
                        </form>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="dataTables_paginate paging_simple_numbers">
                        <ul class="pagination">
                          <?php if ($paging->start_link): ?>
                            <li><a href="<?=site_url("program_bantuan/detail/$paging->start_link/$detail[id]")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
                          <?php endif; ?>
                          <?php if ($paging->prev): ?>
                            <li><a href="<?=site_url("program_bantuan/detail/$paging->prev/$detail[id]")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                          <?php endif; ?>
                          <?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
                            <li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("program_bantuan/detail/$i/$detail[id]")?>"><?= $i?></a></li>
                          <?php endfor; ?>
                          <?php if ($paging->next): ?>
                            <li><a href="<?=site_url("program_bantuan/detail/$paging->next/$detail[id]")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                          <?php endif; ?>
                          <?php if ($paging->end_link): ?>
                            <li><a href="<?=site_url("program_bantuan/detail/$paging->end_link/$detail[id]")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
                          <?php endif; ?>
                        </ul>
                      </div>
                    </div>
                  </div>
								</form>
								</div>
							</div>
						</div>
						<div class='modal fade' id='confirm-delete' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog'>
								<div class='modal-content'>
									<div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
										<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
									</div>
									<div class='modal-body btn-info'>
										Apakah Anda yakin ingin menghapus data ini?
									</div>
									<div class='modal-footer'>
										<button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
										<a class='btn-ok'>
											<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" id="ok-delete"><i class='fa fa-trash-o'></i> Hapus</button>
										</a>
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

