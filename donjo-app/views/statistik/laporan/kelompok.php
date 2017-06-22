
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
			<a href="<?php echo site_url("laporan_rentan/cetak")?>" class="uibutton special tipsy south" title="Cetak" target="_blank"><span class="fa fa-print">&nbsp;</span>Cetak</a>
	<a href="<?php echo site_url("laporan_rentan/excel")?>" class="uibutton special tipsy south" title="Excel" target="_blank"><span class="fa fa-print">&nbsp;</span>Excel</a>
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


	   <table  width="100%"><?php foreach($config as $data){?>
				<tbody><tr>
				<td width="37%"><h4>PEMERINTAH KABUPATEN/KOTA <?php echo $data['nama_kabupaten']?></h4></td>

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
<?php }?>
				</tr>
				<tr>
					<td>Laporan Bulan</td>
					<td width="3%">:</td>
<?php $bln = date("m");?>
					<td><?php echo $bln?> </td>
					<td width="40%"></td>
				</tr>
				 <tr>
					<td>Dusun</td>
					<td width="3%">:</td>
					<td>
					<select name="dusun" onchange="formAction('mainform','<?php echo site_url('laporan_rentan/dusun')?>')" >
					<option value="">Pilih dusun</option>
					<?php foreach($list_dusun as $data){?>
					<option value="<?php echo $data['dusun']?>" <?php if($dusun==$data['dusun']){?>selected<?php }?>><?php echo ununderscore($data['dusun'])?></option>
					<?php }?></select>
					</td>
					<td width="40%"></td>
				</tr>
		</tbody></table>
	<table width="100%" id="tfhover" class="tftable" border="1">

<thead>
<tr>
<h3>DATA PILAH DUSUN  </h3>
</tr>
<tr>
<th scope="col" width="4%"><div align="center">RW</div></th>
<th scope="col" width="4%"><div align="center">RT</div></th>
<th colspan="2" scope="col"><div align="center">
<table width="100%">
 <tr>
<th colspan="2" scope="col">KK</th>
 </tr>
 <tr>
<th width="100%" ><div align="center">L</div></th>
<th width="100%" ><div align="center">P</div></th>
 </tr>
  </table></div>
<th colspan="6" scope="col"><div align="center">
<table width="100%">
 <tr>
<th colspan="6" scope="col"><div align="center">Kondisi dan kelompok umur </div> </th>
 </tr>
 <tr>
<th><div align="center">Bayi(<1thn)</div></th>
<th><div align="center">Balita(1-5thn)</div></th>
<th><div align="center">SD(6-12thn)</div></th>
<th><div align="center">SMP(13-15thn)</div></th>
<th><div align="center">SMA(16-18thn)</div></th>
<th><div align="center">Lansia(>60)</div></th>
 </tr>
  </table></div>
  </th>
<th colspan="2" scope="col"><div align="center">
<table width="100%">
 <tr>
<th colspan="2" scope="col"><div align="center">Difabel</div> </th>
 </tr>
 <tr>
<th width="100%" ><div align="center">Fisik</div></th>
<th width="100%" ><div align="center">Mental</div></th>

 </tr>
  </table></div>
  </th>
<th colspan="2" scope="col"><div align="center">
<table width="100%">
 <tr>
<th colspan="2" scope="col"><div align="center">Sakit Menahun</div> </th>
 </tr>
 <tr>
<th width="100%" ><div align="center">L</div></th>
<th width="100%" ><div align="center">P</div></th>
 </tr>
  </table></div>
  </th>
<th width="100%" ><div align="center">Hamil</div></th>
 </tr>
</thead>
<tbody>
<?php foreach($main as $data){?>
<td><?php echo $data['rw']?></td>
<td><?php echo $data['rt']?></td>
<td><?php echo $data['L']?></td>
<td><?php echo $data['P']?></td>
<td width="13%"><?php echo $data['bayi']?></td>
<td width="14%"><?php echo $data['balita']?></td>
<td width="13%"><?php echo $data['sd']?></td>
<td width="15%"><?php echo $data['smp']?></td>
<td width="15%"><?php echo $data['sma']?></td>
<td width="13%"><?php echo $data['lansia']?></td>
<td><?php echo $data['fisik']?></td>
<td><?php echo $data['mental']?></td>
<td><?php echo $data['sakit_L']?></td>
<td><?php echo $data['sakit_P']?></td>
<td><?php echo $data['hamil']?></td>
</tr>
  <?php }?>
  </tbody>
</table>


<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

    </div>
	</div>
	<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>sid_wilayah" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
<button class="uibutton confirm" type="submit" ><span class="fa fa-print"></span> Cetak</button>

</div>
</div>
	</form>
</div>
</td></tr></table>
</div>
