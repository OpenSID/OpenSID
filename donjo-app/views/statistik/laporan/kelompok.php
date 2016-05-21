
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
			<a href="<?=site_url("laporan_rentan/cetak")?>" class="uibutton special tipsy south" title="Cetak" target="_blank"><span class="ui-icon ui-icon-print">&nbsp;</span>Cetak</a>
	<a href="<?=site_url("laporan_rentan/excel")?>" class="uibutton special tipsy south" title="Excel" target="_blank"><span class="ui-icon ui-icon-print">&nbsp;</span>Excel</a>
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

    
	   <table  width="100%"><?foreach($config as $data){?>	
				<tbody><tr>			
				<td width="37%"><h4>PEMERINTAH KABUPATEN/KOTA <?=$data['nama_kabupaten']?></h4></td>
																	
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
					<td width="38.5%"><?=$data['nama_desa']?></h4></td>
					<td></td>	

				</tr>
				<tr>					
					<td>Kecamatan</td>
					<td width="3%">:</td>
					<td width="38.5%"><?=$data['nama_kecamatan']?></td>
					<td></td>	
<?}?>	
				</tr>
				<tr>						
					<td>Laporan Bulan</td>
					<td width="3%">:</td>
<?$bln = date("m");?>
					<td><?=$bln?> </td>
					<td width="40%"></td>	
				</tr>
				 <tr>						
					<td>Dusun</td>
					<td width="3%">:</td>
					<td>
					<select name="dusun" onchange="formAction('mainform','<?=site_url('laporan_rentan/dusun')?>')" >
					<option value="">Pilih dusun</option>
					<?foreach($list_dusun as $data){?>
					<option value="<?=$data['dusun']?>" <?if($dusun==$data['dusun']){?>selected<?}?>><?=ununderscore($data['dusun'])?></option>
					<?}?></select> 
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
<?foreach($main as $data){?>
<td><?=$data['rw']?></td>
<td><?=$data['rt']?></td>
<td><?=$data['L']?></td>
<td><?=$data['P']?></td>
<td width="13%"><?=$data['bayi']?></td>
<td width="14%"><?=$data['balita']?></td>
<td width="13%"><?=$data['sd']?></td>
<td width="15%"><?=$data['smp']?></td>
<td width="15%"><?=$data['sma']?></td>
<td width="13%"><?=$data['lansia']?></td>
<td><?=$data['fisik']?></td>
<td><?=$data['mental']?></td>
<td><?=$data['sakit_L']?></td>
<td><?=$data['sakit_P']?></td>
<td><?=$data['hamil']?></td>
</tr>
  <?}?>
  </tbody>
</table>   


<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

    </div>
	</div>
	<div class="ui-layout-south panel bottom">
<div class="left">     
<a href="<?=site_url()?>sid_wilayah" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset">Clear</button>
<button class="uibutton confirm" type="submit" >Cetak</button>

</div>
</div>
	</form>
</div>
</td></tr></table>
</div>
