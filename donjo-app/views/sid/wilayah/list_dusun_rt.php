
<div id="pageC"> 
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
	<td class="side-menu">
		<fieldset>
			<div class="lmenu">
				<ul>
				<li class="selected"><a href="<?=site_url('sid_wilayah')?>">Wilayah Administratif</a></li>
				<li><a href="<?=site_url('keluarga')?>">Data Keluarga</a></li>
				<li><a href="<?=site_url('penduduk')?>">Data Penduduk</a></li>
				</ul>
			</div>
		</fieldset>
		
		</td>
<td style="background:#fff;padding:0px;"> 
<div class="content-header">
    <h3>Wilayah Administratif RT (RW <?=$rw?> / Dusun <?=$dusun?>)</h3>
</div>
<div id="contentpane">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
                <a href="<?=site_url("sid_wilayah/form_rt/$p/$o/$dusun/$rw")?>" class="uibutton tipsy south" title="Tambah Data" ><span class="ui-icon ui-icon-plus">&nbsp;</span>Tambah RT</a>
                <button type="button" title="Delete Data" onclick="deleteAllBox('mainform','<?=site_url("sid_wilayah/delete_all_rt/$p/$o/$dusun/$rw")?>')" class="uibutton tipsy south"><span class="ui-icon ui-icon-trash">&nbsp;</span>Delete Data
            </div>
        </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">
            </div>
            <div class="right">
            </div>
        </div>
        <table class="list">
		<thead>
            <tr>
                <th>No</th>
                <th><input type="checkbox" class="checkall"/></th>
                <th width="35">Aksi</th>
				<th align="left" align="center">Nomor RT</th>
				<th align="left" align="center">NIK Ketua RT</th>
				<th align="left" align="center">Nama Ketua RT</th>
				<th align="left" align="center">Jumlah KK</th>
				<th width="50" align="left" align="center">Jiwa</th>
				<th width="50" align="left" align="center">LK</th>
				<th width="50" align="left" align="center">PR</th>
			</tr>
		</thead>
		<tbody>
        <? foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?=$data['no']?></td>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="<?=$data['rt']?>" />
			</td>
          <td width="5">
            <?if($data['rt']!="-"){?><a href="<?=site_url("sid_wilayah/form_rt/$p/$o/$dusun/$rw/$data[rt]")?>" class="icon-edit icon-large" title="Edit Data"></a><a href="<?=site_url("sid_wilayah/delete_rt/$p/$o/$dusun/$rw/$data[rt]")?>" class="icon-trash icon-large" title="Delete Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"></a><?}?>
          </td>
          <td><?=$data['rt']?></td>
		  <td><?=$data['nik_ketua']?></td>
          <td><?=$data['nama_ketua']?></td>
          <td><?=$data['jumlah_kk']?></td>
                <td><?=$data['jumlah_warga']?></td>
                <td><?=$data['jumlah_warga_l']?></td>
                <td><?=$data['jumlah_warga_p']?></td>
		  </tr>
        <? endforeach; ?>
		</tbody>
		
		
		<thead>
            <tr>
                <th>No</th>
                <th><input type="checkbox" class="checkall"/></th>
                <th width="50">Total</th>
				<th align="left" align="center"></th>
				<th align="left" align="center"></th>
				<th align="left" align="center"></th>
				<th align="left" align="center">total_kk</th>
				<th align="left" align="center">total_jiwa</th>
				<th align="left" align="center">total_lk</th>
				<th align="left" align="center">total_pr</th>
				
				
			</tr>
		</thead>
		
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
        <div class="left">     
            <a href="<?=site_url("sid_wilayah/sub_rw/$p/$o/$dusun")?>" class="uibutton icon prev">Kembali</a>
        </div>
        </div>
        <div class="right">
        </div>
    </div>
</div>
</td></tr></table>
</div>
