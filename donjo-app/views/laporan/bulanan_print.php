<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Cetak Laporan Bulanan</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link href="<?php echo base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
</head>
<style type="text/css">
  td.judul {text-align: center; font-size: 14pt;};
</style>

<body>
<div id="container">

<!-- Print Body -->
<div id="body">
  <table>
    <tbody>
      <?php foreach($config as $data) : ?>
        <tr>
    			<td colspan="12" class="judul"><strong>PEMERINTAH <?php echo strtoupper($this->setting->sebutan_kabupaten)?> <?php echo strtoupper($data['nama_kabupaten'])?> <?php echo strtoupper($this->setting->sebutan_kecamatan)?> <?php echo strtoupper($data['nama_kecamatan'])?></strong></td>
        </tr>
  			<tr>
  				<td colspan="12" class="judul"><strong>LAPORAN PENDUDUK <?php echo strtoupper($this->setting->sebutan_desa)?> <?php echo strtoupper($data['nama_desa'])?></strong></td>
  			</tr>
      <?php endforeach; ?>
		</tbody>
	</table>
	<br>
	<table>
		<tbody>
			<tr>
				<td style="text-align: left;">Bulan : <?php echo $bln?> <?php echo $tahun?> </td>
				<td width="40%"></td>
			</tr>
		</tbody>
  </table>

  <?php include ("donjo-app/views/laporan/tabel_bulanan.php"); ?>

  <table>
    <col span="9" style="width: 8%">
    <col style="width: 28%">
    <tr><td colspan="10">&nbsp;</td>
    <tr><td colspan="10">&nbsp;</td>
    <tr>
      <td colspan="9">&nbsp;</td>
      <td><?php echo ucwords($this->setting->sebutan_desa)?> <?php echo unpenetration($data['nama_desa'])?>, <?php echo tgl_indo(date("Y m d"))?></td>
    </tr>
    <tr>
      <td colspan="9">&nbsp;</td>
      <td><?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($data['nama_desa'])?></td>
    </tr>
    <tr><td colspan="10">&nbsp;</td>
    <tr><td colspan="10">&nbsp;</td>
    <tr><td colspan="10">&nbsp;</td>
    <tr><td colspan="10">&nbsp;</td>
    <tr>
      <td colspan="9">&nbsp;</td>
      <td>( <?php echo unpenetration($input['pamong'])?> )</td>
    </tr>
  </table>

</div>
</div>

</body></html>
