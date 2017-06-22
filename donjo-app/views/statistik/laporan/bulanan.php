
<div id="pageC">
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;">
<div id="contentpane">
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel top">
        <div class="left">
            <div class="uibutton-group">

			<a href="<?php echo site_url("laporan/cetak")?>" class="uibutton special tipsy south" title="Cetak" target="_blank"><span class="fa fa-print">&nbsp;</span>Cetak</a>
		<a href="<?php echo site_url("laporan/excel")?>" class="uibutton special tipsy south" title="Excel" target="_blank"><span class="fa fa-print">&nbsp;</span>Excel</a>
            </div>
        </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">

<style type="text/css">
table.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #729ea5;border-collapse: collapse;}
table.tftable th {font-size:12px;background-color:#8DABD4;border-width: 1px;padding: 3px;border-style: solid;border-color: #7195BA;text-align:left;}
table.tftable tr {background-color:#ffffff;}
table.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #729ea5;}
</style>

	   <table  width="100%">
				<tbody><tr>	<?php foreach($config as $data){?>
				<td width="37%"><h4>PEMERINTAH KABUPATEN/KOTA  <?php echo $data['nama_kabupaten']?></h4></td>
				<td align= "right" width="17%"><h4>LAMPIRAN A - 9</h4></td>		</tr>
				<tr>
					<td></td>
					<td width="100%"><h3>LAPORAN BULANAN KELURAHAN</h3></td>


				</tr>
				</tbody></table>
				<table>
				<tbody><tr>
					<td>Kelurahan</td>
					<td width="3%">:</td>
					<td width="38.5%"><?php echo $data['nama_desa']?></h4></td>
					<td></td>

				</tr>
				<tr>
					<td><?php echo ucwords($this->setting->sebutan_kecamatan)?></td>
					<td width="3%">:</td>
					<td width="38.5%"><?php echo $data['nama_kecamatan']?></td>
					<td></td>
					<?php  } ?>
				</tr>
				<tr>
					<td>Tahun</td>
					<td width="3%">:</td>
					<td><input name="tahun" type="text" class="inputbox required" size="5" value="<?php echo $tahun ?>"/></td>
				</tr>
				 <tr>
					<td>Bulan</td>
					<td width="3%">:</td>
					<td>
					<select name="bulan" onchange="formAction('mainform','<?php echo site_url('laporan/bulan')?>')" >
					<option value="">Pilih bulan</option>
					<option value="1" <?php  if($bulan=="1"){?>selected<?php  }?>>Januari</option>
					<option value="2" <?php  if($bulan=="2"){?>selected<?php  }?>>Februari</option>
					<option value="3" <?php  if($bulan=="3"){?>selected<?php  }?>>Maret</option>
					<option value="4" <?php  if($bulan=="4"){?>selected<?php  }?>>April</option>
					<option value="5" <?php  if($bulan=="5"){?>selected<?php  }?>>Mei</option>
					<option value="6" <?php  if($bulan=="6"){?>selected<?php  }?>>Juni</option>
					<option value="7" <?php  if($bulan=="7"){?>selected<?php  }?>>Juli</option>
					<option value="8" <?php  if($bulan=="8"){?>selected<?php  }?>>Agustus</option>
					<option value="9" <?php  if($bulan=="9"){?>selected<?php  }?>>September</option>
					<option value="10" <?php  if($bulan=="10"){?>selected<?php  }?>>Oktober</option>
					<option value="11" <?php  if($bulan=="11"){?>selected<?php  }?>>November</option>
					<option value="12" <?php  if($bulan=="12"){?>selected<?php  }?>>Desember</option>
					</select>
					</td>
					<td width="40%"></td>
				</tr>
		</tbody></table>

	<table width="100%" id="tfhover" class="tftable" border="1">
			<thead>
				<tr>
					<th scope="col" width="4%"><div align="center">No.</div></th>
					<th scope="col" width="18%"><div align="center">PERINCIAN </div></th>
				   <th colspan="2" scope="col"><div align="center">
      			<table  width="100%">
          			<tbody><tr>
            		<th colspan="2" scope="col"><div align="center">Warga Negara Indonesia </div> </th>
          			</tr>
          			<tr>
            		<th width="50%"><div align="center">Laki-laki</div></th>
            		<th width="100%"><div align="center">Perempuan</div></th>
          			</tr>
        			</tbody></table></div>
        			</th>
				   <th colspan="2" scope="col"><div align="center">
      			<table  width="100%">
          			<tbody><tr>
            		<th colspan="2" scope="col"><div align="center">Orang Asing</div> </th>
          			</tr>
          			<tr>
            		<th width="50%"><div align="center">Laki-laki</div></th>
            		<th width="100%"><div align="center">Perempuan</div></th>
          			</tr>
        			</tbody></table></div>
        			</th>
				   <th colspan="3" scope="col"><div align="center">
      			<table  width="100%">
          			<tbody><tr>
            		<th colspan="3" scope="col"><div align="center">Jumlah</div> </th>
          			</tr>
          			<tr>
            		<th width="50"><div align="center">Laki-laki</div></th>
            		<th width="50"><div align="center">Perempuan</div></th>
            		<th width="50"><div align="center">L + P</div></th>
          			</tr>
        			</tbody></table></div>
        			</th>


				</tr>
				<tr>
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



<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

    </div></div>
    <div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>sid_wilayah" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton confirm" type="submit" ><span class="fa fa-print"></span> Cetak</button>
</div>
</div>

	</form>
</div>
</td></tr></table>
</div>
