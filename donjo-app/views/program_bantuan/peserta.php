<div class="content-wrapper">
	<?php $detail = $data[0]; ?>
	<section class="content-header">
		<h1>Profil Penerima Manfaat Program</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('program_bantuan')?>"> Daftar Program Bantuan</a></li>
			<li class="active">Profil Penerima Program Bantuan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-header with-border">
				<a href="<?= site_url('program_bantuan')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Program Bantuan</a>
			</div>
			<div class="box-body">
				<h5><b>Profil Penerima Manfaat Program Bantuan</b></h5>
				<div class="table-responsive">
					<table class="table table-bordered  table-striped table-hover tabel-rincian" >
						<tbody>
							<tr>
								<td width ="20%">Nama Penerima</td>
								<td width ="1">:</td>
								<td><?= strtoupper($profil['nama'])?></td>
							</tr>
							<tr>
								<td>Keterangan</td>
								<td>:</td>
								<td><?= $profil['ndesc']?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<br>

				<h5><b>Program Bantuan Yang Pernah Diikuti</b></h5>
				<div class="table-responsive">
					<table class="table table-bordered dataTable table-hover tabel-daftar">
						<thead class="bg-gray disabled color-palette">
							<tr>
								<th class="padat">No</th>
								<th width="15%">Waktu/Tanggal</th>
								<th width="15%">Nama Program</th>
								<th >Keterangan</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($programkerja as $key => $item): ?>
								<tr>
									<td class="padat"><?= ($key + 1); ?></td>
									<td nowrap><?= fTampilTgl($item['sdate'], $item['edate']); ?></td>
									<td nowrap><a href="<?= site_url("program_bantuan/detail/{$item['id']}")?>"><?= $item['nama'] ?></a></td>
									<td><?= $item['ndesc']; ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
</div>

