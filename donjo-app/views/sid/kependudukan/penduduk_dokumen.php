<div class="content-wrapper">
	<section class="content-header">
		<h1>Dokumen / Kelengkapan Penduduk</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('penduduk/clear')?>"> Daftar Penduduk</a></li>
			<li class="active">Dokumen / Kelengkapan Penduduk</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<?php if ($this->CI->cek_hak_akses('u')): ?>
							<a href="<?= site_url("penduduk/dokumen_form/{$penduduk['id']}")?>" title="Tambah Dokumen" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Dokumen" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-plus'></i>Tambah Dokumen</a>
						<?php endif; ?>
						<?php if ($this->CI->cek_hak_akses('h')): ?>
							<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?= site_url("penduduk/delete_all_dokumen/{$penduduk['id']}")?>')" class="btn btn-social btn-flat	btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
						<?php endif; ?>
						<a href="<?= (preg_match('/bumindes_arsip/i', $_SERVER['HTTP_REFERER'])) ? site_url('bumindes_arsip/clear') : site_url("penduduk/detail/1/0/{$penduduk['id']}") ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali ke Halaman <?= $_SERVER['HTTP_REFERER'] == site_url('bumindes_arsip') ? 'Bumindes Arsip' : 'Biodata Penduduk' ?></a>
					</div>
					<div class="box-body ">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover">
								<tbody>
									<tr>
										<td nowrap style="padding-top : 10px;padding-bottom : 10px; width:15%;" >Nama Penduduk</td><td nowrap > : <?= $penduduk['nama']?></td>
									</tr>
									<tr>
										<td nowrap style="padding-top : 10px;padding-bottom : 10px;" >NIK</td><td nowrap > :  <?= $penduduk['nik']?></td>
									</tr>
									<tr>
										<td nowrap style="padding-top : 10px;padding-bottom : 10px;" >Alamat</td><td nowrap > : <?= $penduduk['alamat']?> RT/RW :  <?= $penduduk['rt']?>/<?= $penduduk['rw']?> <?= strtoupper($this->setting->sebutan_dusun)?> :  <?= $penduduk['dusun']?> </td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="box-body">
						<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
							<form id="mainform" name="mainform" method="post">
								<div class="table-responsive">
									<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
										<thead class="bg-gray disabled color-palette">
											<tr>
												<th><input type="checkbox" id="checkall"></th>
												<th>No</th>
												<th >Aksi</th>
												<th>Nama Dokumen</th>
												<th>Jenis Dokumen</th>
												<th>Tanggal Upload</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($list_dokumen as $data): ?>
												<tr>
													<td class="padat"><input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" ></td>
													<td class="padat"><?= $key + 1?></td>
													<td class="aksi">
														<?php if (! $data['hidden']): ?>
															<?php if ($this->CI->cek_hak_akses('u')): ?>
																<a href="<?= site_url("penduduk/dokumen_form/{$penduduk['id']}/{$data['id']}")?>" class="btn bg-orange btn-flat btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data" title="Ubah Data"  title="Ubah Data"><i class="fa fa-edit"></i></a>
															<?php endif; ?>
															<?php if ($this->CI->cek_hak_akses('u')): ?>
																<a href="#" data-href="<?= site_url("penduduk/delete_dokumen/{$penduduk['id']}/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
															<?php endif; ?>
														<?php endif ?>
														<a href="<?= site_url("{$this->controller}/unduh_berkas/{$data['id']}"); ?>" class="btn bg-purple btn-flat btn-sm" title="Unduh Dokumen"><i class="fa fa-download"></i></a>
													</td>
													<td width="40%"><?= $data['nama']?></td>
													<td width="30%"><?= $jenis_syarat_surat[$data['id_syarat']]['ref_syarat_nama']?></a></td>
													<td class="padat"><?= tgl_indo2($data['tgl_upload'])?></td>
												</tr>
												<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</form>
						</div>
					</div>
				</div>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>