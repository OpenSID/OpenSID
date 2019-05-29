<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="single_page_area">
<h2 class='post_titile'>Rekam Layanan Cetak Surat</h2>
	
	<div class="single_bottom_rightbar wow fadeInDown animated"> 
	<h2>DAFTAR RIWAYAT PELAYANAN</h2> </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>No</th>
				<th align="left">Nomor Surat</th>
				<th align="left">Jenis Surat</th>
				<th align="left">Nama Staf</th>
				<th align="left">Tanggal</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($surat_keluar as $data): ?>
				<tr>
					<td align="center" width="2"><?= $data['no']?></td>
					<td><?= $data['no_surat']?></td>
					<td><?= $data['format']?></td>
					<td><?= $data['pamong']?></td>
					<td><?= tgl_indo2($data['tanggal'])?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div class="teks">
		<?php //$this->load->view('surat/signature.php');?>
	</div>
</div>