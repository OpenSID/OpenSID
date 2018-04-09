<?php
$tgl =  date('d_m_Y');
$subjek = $_SESSION['subjek_tipe'];
switch($subjek){
	case 1: $sql = $nama="Nama"; $nomor="NIK";$asubjek="Penduduk"; break;
	case 2: $sql = $nama="Kepala Keluarga"; $nomor="Nomor KK";$asubjek="Keluarga"; break;
	case 3: $sql = $nama="Kepala Rumahtangga"; $nomor="Nomor Rumahtangga";$asubjek="Rumahtangga"; break;
	case 4: $sql = $nama="Nama Kelompok"; $nomor="ID Kelompok";$asubjek="Kelompok"; break;
	default: return null;
}
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=statistik_analisis_jawaban_$tgl.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<style>
td{
 mso-number-format:"\@";
}
td,th{
	font-size:8pt;
}
</style>
<!-- Print Body -->
<div id="body">
<div class="header">
<label><?php echo get_identitas()?></label>
<h3> DATA STATISTIK ANALISIS JAWABAN</h3>
</div>
 <table>
	<tr>
		<th>No</th>
		<th><?php echo $nomor ?></th>
		<th><?php echo $nama ?></th>
		<th>L/P</th>
		<th>Dusun</th>
		<th>RW</th>
		<th>RT</th>
		<th>Nilai</th>
		<th>Klasifikasi</th>			
	</tr>
	<?php foreach($main as $data): ?>
	<tr>
		<td><?php echo $data['no']?></td>
		<td><?php echo $data['uid']?></td>
		<td><?php echo $data['nama']?></td>
		<td><?php echo $data['jk']?></td>
		<td><?php echo $data['dusun']?></td>
		<td><?php echo $data['rw']?></td>
		<td><?php echo $data['rt']?></td>
		<td><?php echo $data['nilai']?></td>
		<td><?php echo $data['klasifikasi']?></td>
	</tr>
	<?php endforeach; ?>
</table>
</div>
<label>Tanggal cetak : &nbsp; </label><?php echo tgl_indo(date("Y m d"))?>