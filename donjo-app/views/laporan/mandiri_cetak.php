<?php $this->load->view('print/headjs.php');?>
<body>

  <table  width="100%;">
	<tr>
		<td align="center">PIN Pelayanan Mandiri</td>
		<td align="center">Lembar untuk Pemohon</td>
	</tr>
  </table>
  <table width="100%;">
	<tr>
		<td>Nama</td>
		<td>:</td>
		<td><?=$mandiri_penduduk['nama']?></td>
	</tr>
	<tr>
		<td>NIK</td>
		<td>:</td>
		<td><?=$mandiri_penduduk['nik']?></td>
	</tr>
	<tr>
		<td>PIN</td>
		<td>:</td>
		<td><?=$mandiri_pin['plain']?></td>
	</tr>
  </table>
  <tr>
		<td align="center">Mohon disimpan dengan baik PIN ini, kode pin ini sangat rahasia!</td>
	</tr>
  </table>
  
  
  <table  width="100%;">
	<tr>
		<td align="center">PIN Pelayanan Mandiri</td>
		<td align="center">Lembar untuk Arsip Desa</td>
	</tr>
  </table>
  <table width="100%;">
	<tr>
		<td>Nama</td>
		<td>:</td>
		<td><?=$mandiri_penduduk['nama']?></td>
	</tr>
	<tr>
		<td>NIK</td>
		<td>:</td>
		<td><?=$mandiri_penduduk['nik']?></td>
	</tr>
	<tr>
		<td>PIN</td>
		<td>:</td>
		<td><?=$mandiri_pin['plain']?></td>
	</tr>
  </table>
  <tr>
		<td align="center">Mohon disimpan dengan baik PIN ini, kode pin ini sangat rahasia!</td>
	</tr>
  </table>
</body>
</html>