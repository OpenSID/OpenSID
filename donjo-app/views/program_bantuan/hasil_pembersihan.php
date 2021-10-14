<div class="content-wrapper">
	<section class="content-header">
		<h1>Pembersihan Data Peserta Program Bantuan</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?=site_url('program_bantuan')?>"> Daftar Program Bantuan</a></li>
			<li class="active"> Pembersihan Data Peserta Program Bantuan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?=site_url('program_bantuan')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Program Bantuan</a>
					</div>
					<div class="box-body">
						<h4>Hasil Pembersihan Data Peserta Tidak Valid</h4>
						<p>Sasaran (penduduk, keluarga, rumah tangga, kelompok) peserta tidak valid berikut telah dihapus dari program bantuannya.</p>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered  table-striped table-hover tabel-rincian" >
							<tbody>
								<tr>
									<th>No.</td>
									<th>Program Bantuan</td>
									<th>Sasaran</td>
									<th>ID Peserta (NIK/No KK/Kode RTM/Kode Kelompok)</td>
									<th>Nama Kartu Peserta</td>
								</tr>
								<?php if (count($invalid) > 0): ?>
									<?php foreach ($invalid as $key => $peserta): ?>
										<tr>
											<td><?= $key + 1 ?></td>
											<td><?= $peserta['nama'] ?></td>
											<td><?= $ref_sasaran[$peserta['sasaran']] ?></td>
											<td><?= $peserta['peserta'] ?></td>
											<td><?= $peserta['kartu_nama'] ?></td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="5">Tidak ada peserta yang tidak valid</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
					<div class="box-body">
						<h4>Hasil Pembersihan Data Peserta Duplikat</h4>
						<p>Peserta duplikat berikut telah dihapus dari program bantuannya.</p>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered  table-striped table-hover tabel-rincian" >
							<tbody>
								<tr>
									<th>No.</td>
									<th>Program Bantuan</td>
									<th>Sasaran</td>
									<th>ID Peserta (NIK/No KK/Kode RTM/Kode Kelompok)</td>
									<th>Nama Kartu Peserta</td>
									<th>Jumlah Duplikat</th>
								</tr>
								<?php if (count($duplikat) > 0): ?>
									<?php foreach ($duplikat as $key => $peserta): ?>
										<tr>
											<td><?= $key + 1 ?></td>
											<td><?= $peserta['nama'] ?></td>
											<td><?= $ref_sasaran[$peserta['sasaran']] ?></td>
											<td><?= $peserta['peserta'] ?></td>
											<td><?= $peserta['kartu_nama'] ?></td>
											<td><?= $peserta['jumlah'] ?></td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="6">Tidak ada peserta duplikat</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
