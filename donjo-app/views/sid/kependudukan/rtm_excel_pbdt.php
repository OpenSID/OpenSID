<?php
$tgl =  date('d_m_Y');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=data_rumah_tangga_$tgl.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<style>
td{
	mso-number-format:"\@";
	vertical-align:top;
}
td,th{
	font-size:9pt;
	line-height:9px;
	cell-padding:-2px;
	margin:0px;
}
th{
background-color:#cccccc;
font-weight:normal;
horizontal-align:left;
}
</style>
<link href="<?php echo base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
<div id="body">
<div class="header" align="center">
<label>PEMERINTAH DAERAH KABUPATEN GUNUNGKIDUL</label><br>
<label>FORUM KONSULTASI PUBLIK PEMUTAKHIRAN BASIS DATA TERPADU 2016</label>
</div>
<table border="0">
		<tr>
			<td colspan="16" align="right">PBDK2016.FKP.01</td>
		</tr>
</table>
 <table border="1">
		<tr style="background:#cccccc;">
  <td colspan=16 height=16 class=xl94 width=1076 style='border-right:.5pt solid black;
  height:12.0pt;width:808pt'>BLOK I. IDENTITAS WILAYAH SLS</td>
 </tr>
 <tr height=17 style='mso-height-source:userset;height:12.95pt'>
  <td height=17 class=xl67 width=58 style='height:12.95pt;width:44pt'>1</td>
  <td class=xl68 width=189 style='width:142pt'>Kabupaten</td>
  <td colspan=2 class=xl97 width=206 style='border-right:.5pt solid black;border-left:none;width:154pt'>GUNUNGKIDUL</td>
  <td class=xl76 width=13 style='width:10pt'><?php echo $config['kode_kabupaten']?></td>
  <td class=xl71 width=22 style='width:17pt'>&nbsp;</td>
  <td class=xl71 width=19 style='width:14pt'>&nbsp;</td>
  <td class=xl72 width=33 style='width:25pt'>4</td>
  <td class=xl73 width=147 style='width:110pt'>Nama SLS lengkap dibawah Desa</td>
  <td class=xl76 width=13 style='width:10pt'>&nbsp;</td>
  <td class=xl76 width=13 style='width:10pt'>&nbsp;</td>
  <td class=xl75 width=68 style='width:51pt'>&nbsp;</td>
  <td class=xl76 width=91 style='width:68pt'>Padukuhan:</td>
  <td class=xl76 width=100 style='width:75pt'>:</td>
  <td class=xl76 width=15 style='width:11pt'><?php echo $_SESSION['dusun']?></td>
  <td class=xl76 width=13 style='width:10pt'>&nbsp;</td>
 </tr>
 <tr height=18 style='mso-height-source:userset;height:14.1pt'>
  <td height=18 class=xl67 width=58 style='height:14.1pt;width:44pt'>2</td>
  <td class=xl68 width=189 style='width:142pt'>Kecamatan</td>
  <td colspan=2 class=xl100 width=206 style='border-right:.5pt solid black; border-left:none;width:154pt'><?php echo strtoupper($config['nama_kecamatan'])?></td>
  <td class=xl76 width=13 style='width:10pt'><?php echo $config['kode_kecamatan']?></td>
  <td class=xl76 width=13 style='width:10pt'>&nbsp;</td>
  <td class=xl76 width=13 style='width:10pt'>&nbsp;</td>
  <td class=xl70 width=22 style='width:17pt'>&nbsp;</td>
  <td class=xl70 width=19 style='width:14pt'>&nbsp;</td>
  <td class=xl81 width=33 style='width:25pt'>&nbsp;</td>
  <td class=xl73 width=147 style='width:110pt'></td>
  <td class=xl75 width=68 style='width:51pt'>&nbsp;</td>
  <td class=xl76 width=91 style='width:68pt'>RT / RW:</td>
  <td class=xl76 width=100 style='width:75pt'>:</td>
  <td class=xl76 width=15 style='width:11pt'><?php echo $_SESSION['rt']?></td>
  <td class=xl76 width=13 style='width:10pt'><?php echo $_SESSION['rw']?></td>
 </tr>
 <tr height=18 style='mso-height-source:userset;height:14.1pt'>
  <td height=18 class=xl67 width=58 style='height:14.1pt;width:44pt'>3</td>
  <td class=xl68 width=189 style='width:142pt'>Desa</td>
  <td colspan=2 class=xl102 style='border-right:.5pt solid black;border-left: none'><?php echo strtoupper($config['nama_desa'])?></td>
  <td class=xl76 width=13 style='width:10pt'><?php echo $config['kode_desa']?></td>
  <td class=xl76 width=13 style='width:10pt'>&nbsp;</td>
  <td class=xl76 width=13 style='width:10pt'>&nbsp;</td>
  <td class=xl82 width=33 style='width:25pt'>5</td>
  <td colspan=2 class=xl104 width=215 style='border-right:.5pt solid black;border-left:none;width:161pt'>Jumlah RTS pada kolom (5) yg berkode 1 di SLS</td>
  <td colspan=2 class=xl100 width=191 style='border-left:none;width:143pt'>&nbsp;</td>
  <td class=xl84 width=40 style='width:30pt'>
  </table>
<br>
 <table border="1">
		<tr style="background:#cccccc;">
			<td colspan="16" align="center">BLOK II. DAFTAR RTS</td>
		</tr>
		<tr style="background:#cccccc;">
			<th width="50">Nomor RTS</th>
			<th width="170" colspan="2">Nama Kepala Rumah Tangga(KRT)/Nomor Induk Kependudukan(NIK)</th>
			<th colspan="2" width="170">Nama Anggota Rumah Tangga (ART) Lainnya</th>
			<th colspan="2" width="50">Jumlah ART</th>
			<th colspan="3" width="180">Alamat Lengkap (nama jalan/gang/lorong/nomor, RT/RW/dusun)</th>
			<th colspan="2" align="left" width="250">Apakah rumah tangga masih ada? (Lingkar iKODE)<br>
							1. Ada, Status Kesejahteraan Tetap<br>
							2. Ada, Status Kesejahteraan Berubah<br>
							3. Ganti Kepala Rumah Tangga<br>
							4. Pindah<br>
							5. Meninggal<br>	
							6. Berubah Jml / Komposisi ART<br>	
							7. Baru<br>
							8. Tidak dikenal/diketahui	
			</th>
			<th colspan="2" align="center" width="65">Keterangan</th>
			<th colspan="2" align="center" width="120">Telah diperiksa oleh asisten fasilitator (v)</th>
		</tr>
		<tr style="background:#cccccc;">
			<td align="center">(01)</td>
			<td align="center" colspan="2">(02)</td>
			<td colspan="2" align="center">(03)</td>
			<td colspan="2" align="center">(04)</td>
			<td colspan="3" align="center">(05)</td>
			<td colspan="2" align="center">(06)</td>
			<td colspan="2" align="center">(07)</td>
			<td colspan="2" align="center">(08)</td>
		</tr>
		<?php foreach($main as $data): ?>
		<tr>
			<td><?php echo $data['no_kk']?></td>
			<td colspan="2"><?php echo strtoupper($data['kepala_kk'])?></td>
			<td colspan="2"><?php
				foreach($data['anggota'] AS $ang){
					echo $ang['nama'].",";
				}
				?>
			</td>
			<td colspan="2"><?php echo $data['jumlah_anggota']?></td>
			<td colspan="3"><?php echo strtoupper(ununderscore($data['dusun']))?> RT <?php echo strtoupper($data['rt'])?> RW <?php echo strtoupper($data['rw'])?></td>
			<td colspan="2"> &nbsp; 1 &nbsp;&nbsp;&nbsp; 2 &nbsp;&nbsp;&nbsp; 3 &nbsp;&nbsp;&nbsp; 4 &nbsp;&nbsp;&nbsp; 5 &nbsp;&nbsp;&nbsp; 6 &nbsp;&nbsp;&nbsp; 7 &nbsp;&nbsp;&nbsp; 8 &nbsp; </td>
			<td colspan="2"></td>
			<td colspan="2"></td>
		</tr>
		<?php endforeach; ?>
</table>
</div>