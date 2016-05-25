<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Cetak Laporan Bulanan</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">

<!-- Print Body -->
<div id="body">

	   <table  width="100%">
				<tbody><tr>	<?php foreach($config as $data){?>				
				<td width="37%"><h4>PEMERINTAH KABUPATEN/KOTA  <?php echo $data['nama_kabupaten']?></h4></td>
				<td align= "right" width="17%"><h4>LAMPIRAN A - 9</h4></td>		</tr>	
				<tr>				
					<td></td>
					<td width="100%"><h3>LAPORAN BULANAN KELURAHAN</h3></td>
					
									
				</tr>
				</tbody>
		</table>
		<br>
				<table>
				<tbody><tr>						
					<td>Kelurahan</td>
					<td width="3%">:</td>
					<td width="38.5%"><?php echo $data['nama_desa']?></h4></td>
					<td></td>	

				</tr>
				<tr>					
					<td>Kecamatan</td>
					<td width="3%">:</td>
					<td width="38.5%"><?php echo $data['nama_kecamatan']?></td>
					<td></td>	
					<?php  } ?>
				</tr>
				<tr>						
					<td>Laporan Bulan</td>
					<td width="3%">:</td>
					<td><?php echo $bln?> tahun <?php echo $tahun?> </td>
					<td width="40%"></td>	

				</tr>
				 
		</tbody></table>
	
		<br>
	<table class="border thick">
			<thead>
				<tr class="border thick">
					<th  width="4%"><div align="center">No.</div></th>
					<th  width="18%"><div align="center">PERINCIAN </div></th>
				   <th colspan="2" ><div align="center">
      			<table  width="100%">
          			<tbody><tr>
            		<th colspan="2" ><div align="center">Warga Negara Indonesia </div> </th>
          			</tr>
          			<tr>
            		<th width="50%"><div align="center">Laki-laki</div></th>
            		<th width="100%"><div align="center">Perempuan</div></th>
          			</tr>
        			</tbody></table></div>
        			</th>
				   <th colspan="2" ><div align="center">
      			<table  width="100%">
          			<tbody><tr>
            		<th colspan="2" ><div align="center">Orang Asing</div> </th>
          			</tr>
          			<tr>
            		<th width="50%"><div align="center">Laki-laki</div></th>
            		<th width="100%"><div align="center">Perempuan</div></th>
          			</tr>
        			</tbody></table></div>
        			</th>
				   <th colspan="3" ><div align="center">
      			<table  width="100%">
          			<tbody><tr>
            		<th colspan="3" ><div align="center">Jumlah</div> </th>
          			</tr>
          			<tr>
            		<th width="50"><div align="center">Laki-laki</div></th>
            		<th width="50"><div align="center">Perempuan</div></th>
            		<th width="50"><div align="center">L + P</div></th>            		
          			</tr>
        			</tbody></table></div>
        			</th>


				</tr>
				<tr class="border thick">
					<th><div align="center">1</div></th>
					<th><div align="center">2</div></th>
					<th width="13%"><div align="center">3</div></th>
        			<th width="13%"><div align="center">4</div></th>
			      <th width="13%"><div align="center">5</div></th>
        			<th width="13%"><div align="center">6</div></th>
			      <th width="50"><div align="center">7</div></th>
        			<th width="50"><div align="center">8</div></th>
        			<th width="50"><div align="center">9</div></th>


				</tr>
			</thead>
			<tbody>
    

     <tr>
		</tr><tr>
    	<td><div align="center">1</div></td>
    	<td>Penduduk awal bulan ini</td>
    	<td><?php echo $penduduk_awal['WNI_L']+0 ?></td>
    	<td><?php echo $penduduk_awal['WNI_P']+0 ?></td> 
    	<td><?php echo $penduduk_awal['WNA_L']+0 ?></td>
    	<td><?php echo $penduduk_awal['WNA_P']+0 ?></td>
      <td><?php echo ($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L'])?></td>
      <td><?php echo ($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P'])?></td>
      <td><?php echo ($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L'])+($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P'])?></td>

    	</tr>
		<tr>
    	<td><div align="center">2</div></td>
    	<td>Kelahiran bulan ini</td>

    	<td><?php echo $kelahiran['WNI_L']+0 ?></td>
    	<td><?php echo $kelahiran['WNI_P']+0 ?></td> 
    	<td><?php echo $kelahiran['WNA_L']+0 ?></td>
    	<td><?php echo $kelahiran['WNA_P']+0 ?></td>
      <td><?php echo ($kelahiran['WNI_L']+$kelahiran['WNA_L'])?></td>
      <td><?php echo ($kelahiran['WNI_P']+$kelahiran['WNA_P'])?></td>
      <td><?php echo ($kelahiran['WNI_L']+$kelahiran['WNA_L'])+($kelahiran['WNI_P']+$kelahiran['WNA_P'])?></td>
 
    	</tr>
		<tr>
    	<td><div align="center">3</div></td>
    	<td>Kematian bulan ini</td>

    	<td><?php echo $kematian['WNI_L']+0 ?></td>
    	<td><?php echo $kematian['WNI_P']+0 ?></td> 
    	<td><?php echo $kematian['WNA_L']+0 ?></td>
    	<td><?php echo $kematian['WNA_P']+0 ?></td>
      <td><?php echo ($kematian['WNI_L']+$kematian['WNA_L'])?></td>
      <td><?php echo ($kematian['WNI_P']+$kematian['WNA_P'])?></td>
      <td><?php echo ($kematian['WNI_L']+$kematian['WNA_L'])+($kematian['WNI_P']+$kematian['WNA_P'])?></td>

    	</tr>
		<tr>
    	<td><div align="center">4</div></td>
    	<td>Pendatang bulan ini</td>
 
    	<td><?php echo $pendatang['WNI_L']+0 ?></td>
    	<td><?php echo $pendatang['WNI_P']+0 ?></td> 
    	<td><?php echo $pendatang['WNA_L']+0 ?></td>
    	<td><?php echo $pendatang['WNA_P']+0 ?></td>
      <td><?php echo ($pendatang['WNI_L']+$pendatang['WNA_L'])?></td>
      <td><?php echo ($pendatang['WNI_P']+$pendatang['WNA_P'])?></td>
      <td><?php echo ($pendatang['WNI_L']+$pendatang['WNA_L'])+($pendatang['WNI_P']+$pendatang['WNA_P'])?></td>

    	</tr>
		<tr>
    	<td><div align="center">5</div></td>
    	<td>Pindah bulan ini</td>

    	<td><?php echo $pindah['WNI_L']+0 ?></td>
    	<td><?php echo $pindah['WNI_P']+0 ?></td> 
    	<td><?php echo $pindah['WNA_L']+0 ?></td>
    	<td><?php echo $pindah['WNA_P']+0 ?></td>
      <td><?php echo ($pindah['WNI_L']+$pindah['WNA_L'])?></td>
      <td><?php echo ($pindah['WNI_P']+$pindah['WNA_P'])?></td>
      <td><?php echo ($pindah['WNI_L']+$pindah['WNA_L'])+($pindah['WNI_P']+$pindah['WNA_P'])?></td>

    	</tr>

		<tr>
    	<td><div align="center">6</div></td>
    	<td>Penduduk akhir bulan ini</td>

    	<td><?php echo $penduduk_akhir['WNI_L']+0 ?></td>
    	<td><?php echo $penduduk_akhir['WNI_P']+0 ?></td> 
    	<td><?php echo $penduduk_akhir['WNA_L']+0 ?></td>
    	<td><?php echo $penduduk_akhir['WNA_P']+0 ?></td>
      <td><?php echo ($penduduk_akhir['WNI_L']+$penduduk_akhir['WNA_L'])?></td>
      <td><?php echo ($penduduk_akhir['WNI_P']+$penduduk_akhir['WNA_P'])?></td>
      <td><?php echo ($penduduk_akhir['WNI_L']+$penduduk_akhir['WNA_L'])+($penduduk_akhir['WNI_P']+$penduduk_akhir['WNA_P'])?></td>

    	</tr>
    
    <tr>
    	<td><div align="center">7</div></td>
    	<td>Penduduk hilang bulan ini</td>

    	<td><?php echo $hilang['WNI_L']+0 ?></td>
    	<td><?php echo $hilang['WNI_P']+0 ?></td> 
    	<td><?php echo $hilang['WNA_L']+0 ?></td>
    	<td><?php echo $hilang['WNA_P']+0 ?></td>
      <td><?php echo ($hilang['WNI_L']+$hilang['WNA_L'])?></td>
      <td><?php echo ($hilang['WNI_P']+$hilang['WNA_P'])?></td>
      <td><?php echo ($hilang['WNI_L']+$hilang['WNA_L'])+($hilang['WNI_P']+$hilang['WNA_P'])?></td>

    	</tr>
    	
  </tbody>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>

</div>
   <label>Tanggal cetak : &nbsp; </label><?php echo tgl_indo(date("Y m d"))?>
</div>

</body></html>
