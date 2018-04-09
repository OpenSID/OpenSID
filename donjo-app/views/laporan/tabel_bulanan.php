<style type="text/css">
  table.tftable {
    margin-top: 5px;
    font-size:12px;
    color:<?php echo (isset($warna_font) ? $warna_font : "");?>;
    width:100%;
    border-width: 1px;
    border-style: solid;
    border-color: <?php echo (isset($warna_border) ? $warna_border : "");?>;
    border-collapse: collapse;
  }
  table.tftable.lap-bulanan {border-width: 3px;}
  table.tftable tr.thick {border-width: 3px; border-style: solid;}
  table.tftable th.thick {border-width: 3px;}
  table.tftable th.thick-kiri {border-left: 3px solid <?php echo (isset($warna_border) ? $warna_border : "");?>;}
  table.tftable td.thick-kanan {border-right: 3px solid <?php echo (isset($warna_border) ? $warna_border : "");?>;}
  table.tftable td.angka {text-align: right;}
  table.tftable th {background-color:<?php echo (isset($warna_background) ? $warna_background : "");?>;padding: 3px;border: 1px solid <?php echo (isset($warna_border) ? $warna_border : "");?>;text-align:center;}
  /*table.tftable tr {background-color:#ffffff;}*/
  table.tftable td {padding: 8px;border: 1px solid <?php echo (isset($warna_border) ? $warna_border : "");?>;}
</style>


<table id="tfhover" class="tftable lap-bulanan">
  <thead>
    <tr class="thick">
      <th rowspan="2" scope="col" class="thick">No.</th>
      <th rowspan="2" scope="col" class="thick">PERINCIAN</th>
      <th colspan="2" scope="col" class="thick">WNI</th>
      <th colspan="2" scope="col" class="thick">WNA</th>
      <th colspan="3" scope="col" class="thick">Jumlah</th>
      <th colspan="3" scope="col" class="thick">Kepala Keluarga</th>
    </tr>
    <tr class="thick">
      <th>Laki-laki</th>
      <th>Perempuan</th>
      <th class="thick-kiri">Laki-laki</th>
      <th>Perempuan</th>
      <th class="thick-kiri">Laki-laki</th>
      <th>Perempuan</th>
      <th>L+P</th>
      <th class="thick-kiri">Laki-laki</th>
      <th>Perempuan</th>
      <th>L+P</th>
    </tr>
		<tr>
			<th>1</th>
			<th class="thick-kiri">2</th>
			<th class="kolom-data">3</th>
			<th class="kolom-data">4</th>
      <th class="kolom-data">5</th>
			<th class="kolom-data">6</th>
      <th class="kolom-data">7</th>
			<th class="kolom-data">8</th>
			<th class="kolom-data">9</th>
      <th class="kolom-data">10</th>
      <th class="kolom-data">11</th>
      <th class="kolom-data">12</th>
		</tr>
	</thead>
	<tbody>
    <tr></tr>
    <tr>
      <td class="thick-kanan"><div align="center">1</div></td>
      <td>Penduduk awal bulan ini</td>
      <td class="angka"><?php echo show_zero_as($penduduk_awal['WNI_L'],'-') ?></td>
      <td class="angka"><?php echo show_zero_as($penduduk_awal['WNI_P'],'-') ?></td>
      <td class="angka"><?php echo show_zero_as($penduduk_awal['WNA_L'],'-') ?></td>
      <td class="angka"><?php echo show_zero_as($penduduk_awal['WNA_P'],'-') ?></td>
      <td class="angka"><?php echo show_zero_as(($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L']),'-')?></td>
      <td class="angka"><?php echo show_zero_as(($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P']),'-')?></td>
      <td class="angka"><?php echo show_zero_as(($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L'])+($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P']),'-')?></td>
      <td class="angka"><?php echo show_zero_as($penduduk_awal['KK_L'],'-')?></td>
      <td class="angka"><?php echo show_zero_as($penduduk_awal['KK_P'],'-')?></td>
      <td class="angka"><?php echo show_zero_as($penduduk_awal['KK'],'-')?></td>
    </tr>
		<tr>
    	<td class="thick-kanan"><div align="center">2</div></td>
    	<td>Kelahiran/KK baru bulan ini</td>
    	<td class="angka"><?php echo show_zero_as($kelahiran['WNI_L'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($kelahiran['WNI_P'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($kelahiran['WNA_L'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($kelahiran['WNA_P'],'-') ?></td>
      <td class="angka"><?php echo show_zero_as(($kelahiran['WNI_L']+$kelahiran['WNA_L']),'-')?></td>
      <td class="angka"><?php echo show_zero_as(($kelahiran['WNI_P']+$kelahiran['WNA_P']),'-')?></td>
      <td class="angka"><?php echo show_zero_as(($kelahiran['WNI_L']+$kelahiran['WNA_L'])+($kelahiran['WNI_P']+$kelahiran['WNA_P']),'-')?></td>
      <td class="angka"><?php echo show_zero_as($kelahiran['KK_L'],'-')?></td>
      <td class="angka"><?php echo show_zero_as($kelahiran['KK_P'],'-')?></td>
      <td class="angka"><?php echo show_zero_as($kelahiran['KK'],'-')?></td>
  	</tr>
		<tr>
    	<td class="thick-kanan angka"><div align="center">3</div></td>
    	<td>Kematian bulan ini</td>
    	<td class="angka"><?php echo show_zero_as($kematian['WNI_L'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($kematian['WNI_P'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($kematian['WNA_L'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($kematian['WNA_P'],'-') ?></td>
      <td class="angka"><?php echo show_zero_as(($kematian['WNI_L']+$kematian['WNA_L']),'-')?></td>
      <td class="angka"><?php echo show_zero_as(($kematian['WNI_P']+$kematian['WNA_P']),'-')?></td>
      <td class="angka"><?php echo show_zero_as(($kematian['WNI_L']+$kematian['WNA_L'])+($kematian['WNI_P']+$kematian['WNA_P']),'-')?></td>
      <td class="angka"><?php echo show_zero_as($kematian['KK_L'],'-')?></td>
      <td class="angka"><?php echo show_zero_as($kematian['KK_P'],'-')?></td>
      <td class="angka"><?php echo show_zero_as($kematian['KK'],'-')?></td>
  	</tr>
		<tr>
    	<td class="thick-kanan angka"><div align="center">4</div></td>
    	<td>Pendatang bulan ini</td>
    	<td class="angka"><?php echo show_zero_as($pendatang['WNI_L'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($pendatang['WNI_P'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($pendatang['WNA_L'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($pendatang['WNA_P'],'-') ?></td>
      <td class="angka"><?php echo show_zero_as(($pendatang['WNI_L']+$pendatang['WNA_L']),'-')?></td>
      <td class="angka"><?php echo show_zero_as(($pendatang['WNI_P']+$pendatang['WNA_P']),'-')?></td>
      <td class="angka"><?php echo show_zero_as(($pendatang['WNI_L']+$pendatang['WNA_L'])+($pendatang['WNI_P']+$pendatang['WNA_P']),'-')?></td>
      <td class="angka"><?php echo show_zero_as($pendatang['KK_L'],'-')?></td>
      <td class="angka"><?php echo show_zero_as($pendatang['KK_P'],'-')?></td>
      <td class="angka"><?php echo show_zero_as($pendatang['KK'],'-')?></td>
  	</tr>
		<tr>
    	<td class="thick-kanan angka"><div align="center">5</div></td>
    	<td>Pindah bulan ini</td>
    	<td class="angka"><?php echo show_zero_as($pindah['WNI_L'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($pindah['WNI_P'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($pindah['WNA_L'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($pindah['WNA_P'],'-') ?></td>
      <td class="angka"><?php echo show_zero_as(($pindah['WNI_L']+$pindah['WNA_L']),'-')?></td>
      <td class="angka"><?php echo show_zero_as(($pindah['WNI_P']+$pindah['WNA_P']),'-')?></td>
      <td class="angka"><?php echo show_zero_as(($pindah['WNI_L']+$pindah['WNA_L'])+($pindah['WNI_P']+$pindah['WNA_P']),'-')?></td>
      <td class="angka"><?php echo show_zero_as($pindah['KK_L'],'-')?></td>
      <td class="angka"><?php echo show_zero_as($pindah['KK_P'],'-')?></td>
      <td class="angka"><?php echo show_zero_as($pindah['KK'],'-')?></td>
  	</tr>
    <tr>
    	<td class="thick-kanan angka"><div align="center">6</div></td>
    	<td>Penduduk hilang bulan ini</td>
    	<td class="angka"><?php echo show_zero_as($hilang['WNI_L'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($hilang['WNI_P'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($hilang['WNA_L'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($hilang['WNA_P'],'-') ?></td>
      <td class="angka"><?php echo show_zero_as(($hilang['WNI_L']+$hilang['WNA_L']),'-')?></td>
      <td class="angka"><?php echo show_zero_as(($hilang['WNI_P']+$hilang['WNA_P']),'-')?></td>
      <td class="angka"><?php echo show_zero_as(($hilang['WNI_L']+$hilang['WNA_L'])+($hilang['WNI_P']+$hilang['WNA_P']),'-')?></td>
      <td class="angka"><?php echo show_zero_as($hilang['KK_L'],'-')?></td>
      <td class="angka"><?php echo show_zero_as($hilang['KK_P'],'-')?></td>
      <td class="angka"><?php echo show_zero_as($hilang['KK'],'-')?></td>
  	</tr>
		<tr>
    	<td class="thick-kanan angka"><div align="center">7</div></td>
    	<td>Penduduk akhir bulan ini</td>
    	<td class="angka"><?php echo show_zero_as($penduduk_akhir['WNI_L'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($penduduk_akhir['WNI_P'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($penduduk_akhir['WNA_L'],'-') ?></td>
    	<td class="angka"><?php echo show_zero_as($penduduk_akhir['WNA_P'],'-') ?></td>
      <td class="angka"><?php echo show_zero_as(($penduduk_akhir['WNI_L']+$penduduk_akhir['WNA_L']),'-')?></td>
      <td class="angka"><?php echo show_zero_as(($penduduk_akhir['WNI_P']+$penduduk_akhir['WNA_P']),'-')?></td>
      <td class="angka"><?php echo show_zero_as(($penduduk_akhir['WNI_L']+$penduduk_akhir['WNA_L'])+($penduduk_akhir['WNI_P']+$penduduk_akhir['WNA_P']),'-')?></td>
      <td class="angka"><?php echo show_zero_as($penduduk_akhir['KK_L'],'-')?></td>
      <td class="angka"><?php echo show_zero_as($penduduk_akhir['KK_P'],'-')?></td>
      <td class="angka"><?php echo show_zero_as($penduduk_akhir['KK'],'-')?></td>
  	</tr>
  </tbody>
</table>
