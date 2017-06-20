
<div id="pageC"> 
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">

<td style="background:#fff;padding:0px;"> 
<div class="content-header">
</div>
<div id="contentpane">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
    <h3>Wilayah Administratif RT (RW <?php echo $rw?> / Dusun <?php echo unpenetration(ununderscore($dusun))?>)</h3>
        <div class="left">
            <div class="uibutton-group">
                <a href="<?php echo site_url("sid_core/form_rt/$id_dusun/$rw")?>" class="uibutton tipsy south" title="Tambah Data" ><span class="fa fa-plus-square">&nbsp;</span>Tambah RT</a>
                <a href="<?php echo site_url("sid_core/cetak_rt/$id_dusun/$rw")?>" target="_blank" class="uibutton tipsy south" title="Print Data" ><span class="fa fa-print">&nbsp;</span>Cetak</a>
	<a href="<?php echo site_url("sid_core/excel_rt/$id_dusun/$rw")?>" target="_blank" class="uibutton tipsy south" title="Data Excel" ><span class="fa fa-file-text">&nbsp;</span>Excel</a>
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
                <th width="5">No</th>
                <th width="5"><input type="checkbox" class="checkall"/></th>
                <th width="100">Aksi</th>
				<th width="50">RT</th>
				<th width="150">NIK Ketua RT</th>
				<th width="150">Nama Ketua RT</th>
				<th width="75">Jumlah KK</th>
				<th width="75">Jiwa</th>
				<th width="50">LK</th>
				<th width="50">PR</th>
				<th></th>
				
			</tr>
		</thead>
		<tbody>
        <?php  foreach($main as $data): ?>
		<tr>
			<td align="center" width="2"><?php echo $data['no']?></td>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
			</td>
			<td width="5"><div class="uibutton-group">
				<?php if($data['rt']!="-"){?><a href="<?php echo site_url("sid_core/form_rt/$id_dusun/$rw/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data"><span class="fa fa-edit"></span> Ubah</a>
				<a href="<?php echo site_url("sid_core/delete_rt/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a><?php }?>
			</div></td>
			<td><?php echo $data['rt']?></td>
			<td><?php echo $data['nik_ketua']?></td>
			<td><?php echo unpenetration($data['nama_ketua'])?></td>
			<td align="right"><?php echo $data['jumlah_kk']?></td>
			<td align="right"><?php echo $data['jumlah_warga']?></td>
			<td align="right"><?php echo $data['jumlah_warga_l']?></td>
			<td align="right"><?php echo $data['jumlah_warga_p']?></td>
			<td></td>
			
		  </tr>
        <?php  endforeach; ?>
		</tbody>
            <tr style="background-color:#BDD498;font-weight:bold;">
			<td colspan="6" width="50"><label>TOTAL</label></th>
			<td align="right"><?php echo $total['jmlkk']?></th>
			<td align="right"><?php echo $total['jmlwarga']?></th>
			<td align="right"><?php echo $total['jmlwargal']?></th>
			<td align="right"><?php echo $total['jmlwargap']?></th>
			<td></td>
			
		</tr>
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
        <div class="left">     
            <a href="<?php echo site_url("sid_core/sub_rw/$id_dusun")?>" class="uibutton icon prev">Kembali</a>
        </div>
        </div>
        <div class="right">
        </div>
    </div>
</div>
</td></tr></table>
</div>
