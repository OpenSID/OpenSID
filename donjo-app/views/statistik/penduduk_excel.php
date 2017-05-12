<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Statistik_penduduk.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Laporan Data Statistik Kependudukan menurut</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">

<!-- Print Body -->
<div id="body">

<table>
    <tbody>
    <tr>
        <td style="padding: 5px 20px;">

		<br>
		<table class="border thick data">
		<thead>
            <tr class="thick">
                <th class="thick">No</th>
				<th class="thick">Jenis Kelompok</th>
				<th class="thick">Jumlah</th>
				<th class="thick" width="60">Laki-laki</th>
				<th class="thick" width="60">Perempuan</th>
			</tr>
		</thead>
		<tbody>
        <?php  foreach($main as $data): ?>
		<tr>
          <td class="thick" align="center" width="2"><?php echo $data['no']?></td>
          <td class="thick"><?php echo strtoupper($data['nama'])?></td>
          <td class="thick"><?php echo $data['jumlah']?></td>
		  <td class="thick"><?php echo $data['laki']?></td>
          <td class="thick"><?php echo $data['perempuan']?></td>
		  </tr>
        <?php  endforeach; ?>
		</tbody>
        </table>


            <br>


        </td>
    </tr>
</tbody></table>
</div>
   <label>Tanggal cetak : &nbsp; </label><?php echo tgl_indo(date("Y m d"))?>
</div>

</body></html>
