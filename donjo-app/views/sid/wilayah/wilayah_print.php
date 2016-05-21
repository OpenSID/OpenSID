<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Data Wilayah</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?=base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">

<!-- Print Body --><div id="body"><div class="header" align="center"><label align="left"><?=get_identitas()?></label>
<h3> Tabel Data Kependudukan berdasarkan Populasi Per Wilayah </h3>
<h4> Kabupaten <?=$desa['desa']['nama_kabupaten']?>, Kecamatan <?=$desa['desa']['nama_kecamatan']?>, Desa <?=$desa['desa']['nama_desa']?></h4> 
</div>
<br>
    <table class="border thick">
	<thead>
		<tr class="border thick">
                <th>No</th>
				<th width="100">Nama Dusun</th>
				<th width="100">Nama Kadus</th>
				<th width="50">RW</th>
				<th width="50">RT</th>
				<th width="50">KK</th>
				<th width="50">Jiwa</th>
				<th width="50">LK</th>
				<th width="50">PR</th>
			</tr>
		</thead>
		<tbody>
        <? foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?=$data['no']?></td>
			
			<td><?=strtoupper(unpenetration(ununderscore($data['dusun'])))?></td>
			<td><?=$data['nama_kadus']?></td> 
	
			<td align="right"><?=$data['jumlah_rw']?></td>
			<td align="right"><?=$data['jumlah_rt']?></td>
			<td align="right"><?=$data['jumlah_kk']?></td>
			<td align="right"><?=$data['jumlah_warga']?></td>
			<td align="right"><?=$data['jumlah_warga_l']?></td>
			<td align="right"><?=$data['jumlah_warga_p']?></td>
		</tr>
        <? endforeach; ?>
		</tbody>
		
            <tr style="background-color:#BDD498;font-weight:bold;">
                <td colspan="3" align="left"><label>TOTAL</label></td>
				<td align="right"><?=$total['total_rw']?></td>
				<td align="right"><?=$total['total_rt']?></td>
				<td align="right"><?=$total['total_kk']?></td>
				<td align="right"><?=$total['total_warga']?></td>
				<td align="right"><?=$total['total_warga_l']?></td>
				<td align="right"><?=$total['total_warga_p']?></td>
			</tr>
	</tbody>
</table>
</div>
<label>Tanggal cetak : &nbsp; </label><?=tgl_indo(date("Y m d"))?>
   <label>Tanggal cetak : &nbsp; </label><?=tgl_indo(date("Y m d"))?>
</div>

</body></html>
