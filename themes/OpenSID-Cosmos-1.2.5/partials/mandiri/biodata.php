<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="stat">
	<div>
		<h2 class="judul-artikel">KARTU KELUARGA PENDUDUK</h2>
	</div>
	<hr class='divider'>
	<div class="mt-1 mb-3">
		<a href="<?php echo site_url("first/cetak_kk/$penduduk[id]/1"); ?>" target="_blank" class='btn btn-success'>
			<i class="fa fa-print"></i> CETAK KARTU KELUARGA
		</a>
	</div>
	<div class="table-responsive modul-mandiri">
	<table class="table table-striped">
	<tr>
		<th colspan="3"><b>BIODATA PENDUDUK</b></th>
	</tr>
	<tr>
		<td width="36%">Nama</td>
		<td width="2%">:</td>
		<td width="62%"><?php echo strtoupper(unpenetration($penduduk['nama']))?></td>
	</tr>
	<tr>
		<td>NIK</td>
		<td>:</td>
		<td><?php echo $penduduk['nik']?></td>
	</tr>
	<tr>
		<td>No KK</td>
		<td>:</td>
		<td><?php echo $penduduk['no_kk']?></td>
	</tr>
	<tr>
		<td>Akta Kelahiran</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['akta_lahir'])?></td>
	</tr>
	<tr>
		<td><?php echo ucwords($this->setting->sebutan_dusun)?></td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['dusun'])?></td>
	</tr>
	<tr>
		<td>RT/RW</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['rt'])?>/<?php echo $penduduk['rw']?></td>
	</tr>
	<tr>
		<td>Jenis Kelamin</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['sex'])?></td>
	</tr>
	<tr>
		<td>Tempat, Tanggal Lahir</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['tempatlahir'])?>, <?php echo strtoupper($penduduk['tanggallahir'])?></td>
	</tr>
	<tr>
		<td>Agama</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['agama'])?></td>
	</tr>
	<tr>
		<td>Pendidikan Dalam KK</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['pendidikan_kk'])?></td>
	</tr>
	<tr>
		<td>Pendidikan yang sedang ditempuh</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['pendidikan_sedang'])?></td>
	</tr>
	<tr>
		<td>Pekerjaan</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['pekerjaan'])?></td>
	</tr>
	<tr>
		<td>Status Perkawinan</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['kawin'])?></td>
	</tr>
	<tr>
		<td>Warga Negara</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['warganegara'])?></td>
	</tr>
	<tr>
		<td>Dokumen Paspor</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['dokumen_pasport'])?></td>
	</tr>
	<tr>
		<td>Dokumen Kitas</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['dokumen_kitas'])?></td>
	</tr>
	<tr>
		<td>Alamat Sebelumnya</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['alamat_sebelumnya'])?></td>
	</tr>
	<tr>
		<td>Alamat Sekarang</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['alamat'])?></td>
	</tr>
	<tr>
		<td>Akta Perkawinan</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['akta_perkawinan'])?></td>
	</tr>
	<tr class="shaded">
		<td>Tanggal Perkawinan</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['tanggalperkawinan'])?></td>
	</tr>
	<tr>
		<td>Akta Perceraian</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['akta_perceraian'])?></td>
	</tr>
	<tr>
		<td>Tanggal Perceraian</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['tanggalperceraian'])?></td>
	</tr>
	<tr>
		<td><b>Data Orang Tua</b></td>
		<td>&nbsp;</td>
		<td></td>
	</tr>
	<tr>
		<td>NIK Ayah</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['ayah_nik'])?></td>
	</tr>
	<tr>
		<td>Nama Ayah</td>
		<td>:</td>
		<td><?php echo strtoupper(unpenetration($penduduk['nama_ayah']))?></td>
	</tr>
	<tr>
		<td>NIK Ibu</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['ibu_nik'])?></td>
	</tr>
	<tr >
		<td>Nama Ibu</td>
		<td>:</td>
		<td><?php echo strtoupper(unpenetration($penduduk['nama_ibu']))?></td>
	</tr>
	<tr>
		<td>Cacat</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['cacat'])?></td>
	</tr>
	<tr>
		<td>Status</td>
		<td>:</td>
		<td><?php echo strtoupper($penduduk['status'])?></td>
	</tr>
	</table>
	</div>

	<div>
		<a href="<?php echo site_url("first/cetak_biodata/$penduduk[id]"); ?>" target="_blank" class="btn btn-success"><i class="fa fa-print"></i> CETAK BIODATA</a>
	</div>

	<hr class='divider'>

	<?php if (count($list_kelompok) > 0): ?>
		<div class='table-responsive'>
		<table class="table table-striped">
		<tr>
			<th colspan="4"><b>KEANGGOTAAN KELOMPOK</b></th>
		</tr>
		<tr>
			<th class='fit'>No</th>
			<th>Nama Kelompok</th>
			<th>Kategori Kelompok</th>
			<th></th>
		</tr>
		<?php
			$no=1;
			foreach($list_kelompok as $kel):?>
			<tr>
				<td class='text-center fit'><?php echo $no;?></td>
				<td><?php echo $kel['nama']?></td>
				<td><?php echo $kel['kategori']?></td>
				<td></td>
			</tr>
			<?php $no++; endforeach; ?>	
		</table>
		</div>
	<?php endif ?>

	<?php if (count($list_dokumen) > 0): ?>
		<div class="table-responsive modul-mandiri">
		<table class="table table-striped">
		<thead>
		<tr>
			<th colspan="5"><b>DOKUMEN / KELENGKAPAN PENDUDUK</b></th>
		</tr>
		<tr>
			<th class='fit'>No</th>
			<th>Nama Dokumen</th>
			<th>Berkas</th>
			<th>Tanggal Upload</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($list_dokumen as $data){?>
		<tr>
			<td class='text-center fit'><?php echo $data['no']?></td>
			<td><?php echo $data['nama']?></td>
			<td><a href="<?php echo base_url().LOKASI_DOKUMEN?><?php echo urlencode($data['satuan'])?>" ><?php echo $data['satuan']?></a></td>
			<td><?php echo tgl_indo2($data['tgl_upload'])?></td>
			<td></td>
		</tr>
		<?php }?>
		</tbody>
		</table>
		</div>
	<?php endif ?>

</div>
