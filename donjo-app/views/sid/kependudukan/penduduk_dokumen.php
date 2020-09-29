<div class="content-wrapper">
	<section class="content-header">
		<h1>Dokumen / Kelengkapan Penduduk</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('penduduk/clear'); ?>"> Daftar Penduduk</a></li>
			<li class="active">Dokumen / Kelengkapan Penduduk</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-header with-border">
				<a href="<?= site_url("penduduk/dokumen_form/$penduduk[id]"); ?>" title="Tambah Dokumen" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Dokumen" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-plus'></i>Tambah Dokumen</a>
				<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?= site_url("penduduk/delete_all_dokumen/$penduduk[id]")?>')" class="btn btn-social btn-flat	btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
				<a href="<?= site_url("penduduk/detail/1/0/$penduduk[id]"); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Biodata Penduduk</a>
			</div>
			<div class="box-body">
				<h5><b>Rincian Penduduk</b></h5>
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover tabel-rincian">
						<tbody>
							<tr>
								<td width="20%">Nama Penduduk</td>
								<td width="1%">:</td>
								<td><?= $penduduk['nama']; ?></td>
							</tr>
							<tr>
								<td>NIK</td>
								<td>:</td>
								<td><?= $penduduk['nik']; ?></td>
							</tr>
							<tr>
								<td>Alamat</td>
								<td>:</td>
								<td><?= $penduduk['alamat'] . ' RT/RW : ' . $penduduk['rt'] . ' ' . $penduduk['rw'] . ' ' . strtoupper($this->setting->sebutan_dusun) . ' : ' . $penduduk['dusun']; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="box-body">
				<h5><b>Daftar Dokumen</b></h5>
				<form id="mainform" name="mainform" action="" method="post">
					<div class="table-responsive">
						<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
							<thead class="bg-gray disabled color-palette">
								<tr>
									<th><input type="checkbox" id="checkall"></th>
									<th>No</th>
									<th>Aksi</th>
									<th>Nama Dokumen</th>
									<th>File</th>
									<th>Tanggal Upload</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($list_dokumen as $key => $data): ?>
									<tr>
										<td class="padat"><input type="checkbox" name="id_cb[]" value="<?= $data['id']; ?>" ></td>
										<td class="padat"><?= ($key + 1); ?></td>
										<td class="aksi">
											<?php if( ! $data['hidden']): ?>
												<a href="<?= site_url("penduduk/dokumen_form/$penduduk[id]/$data[id]"); ?>" class="btn bg-orange btn-flat btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data" title="Ubah Data"  title="Ubah Data"><i class="fa fa-edit"></i></a>
												<a href="#" data-href="<?= site_url("penduduk/delete_dokumen/$penduduk[id]/$data[id]"); ?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
											<?php endif; ?>
										</td>
										<td width="35%"><?= $data['nama']; ?></td>
										<td width="40%"><a href="<?= base_url() . LOKASI_DOKUMEN . '' . urlencode($data['satuan']); ?>" rel="noopener noreferrer" target="_blank" ><?= $data['satuan']; ?></a></td>
										<td nowrap><?= tgl_indo2($data['tgl_upload']); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete');?>
