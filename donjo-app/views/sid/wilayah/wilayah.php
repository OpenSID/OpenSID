<script>
	$(function() {
		var keyword = <?=$keyword?> ;
		$( "#cari" ).autocomplete({
			source: keyword
		});
	});
</script>

<div id="pageC"> 
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">

<td style="background:#fff;padding:0px;"> 
<div id="contentpane">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
    <h3>Wilayah Administratif Dusun</h3>
        <div class="left">
            <div class="uibutton-group">
                <a href="<?=site_url('sid_core/form')?>" class="uibutton tipsy south" title="Tambah Data" ><span class="icon-plus-sign icon-large">&nbsp;</span>Tambah Dusun</a>
                <a href="<?=site_url('sid_core/cetak')?>" target="_blank" class="uibutton tipsy south" title="Print Data" ><span class="icon-print icon-large">&nbsp;</span>Cetak</a>
		<a href="<?=site_url('sid_core/excel')?>" target="_blank" class="uibutton tipsy south" title="Data Excel" ><span class="icon-file-text icon-large">&nbsp;</span>Excel</a>
            </div>
        </div>
		<div class="right">
			<input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?=$cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?=site_url('sid_core/search')?>');$('#'+'mainform').submit();}" />
			<button type="button" onclick="$('#'+'mainform').attr('action','<?=site_url('sid_core/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="icon-search icon-large">&nbsp;</span>Cari</button>
		</div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">
            </div>
        </div>
        <table class="list">
		<thead>
            <tr>
                <th width="5">No</th>
                <th width="5"><input type="checkbox" class="checkall"/></th>
                <th width="100">Aksi</th>
				<th width="200">Nama Dusun</th>
				<th width="200">Nama Kepala Dusun</th>
				<th width="50">RW</th>
				<th width="50">RT</th>
				<th width="50">KK</th>
				<th width="50">Jiwa</th>
				<th width="50">LK</th>
				<th width="50">PR</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
        <? foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?=$data['no']?></td>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" />
			</td>
			<td width="4" align="center"><div class="uibutton-group">
						
<a href="<?=site_url("sid_core/sub_rw/$data[id]")?>" class="uibutton tipsy south" title="Rincian Sub Wilayah"><span class="icon-list icon-large"> Rincian</span></a>
<a href="<?=site_url("sid_core/form/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data"><span  class="icon-edit icon-large"></span></a>

<? if($grup==1){?>
				<a href="<?=site_url("sid_core/delete/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin? Menghapus data dusun akan mempengaruhi struktur data yang ada dibawah dusun. pilih tidak untuk membatalkan penghapusan." header="Hapus Data"><span class="icon-trash icon-large"></span></a><? } ?>
</div>
			</td>
			<td><?=strtoupper(unpenetration(ununderscore($data['dusun'])))?></td>
			<td><?=strtoupper(unpenetration($data['nama_kadus']))?></td> 
	
			<td align="right"><a href="<?=site_url("sid_core/sub_rw/$data[id]")?>" title="Rincian Sub Wilayah"><?=$data['jumlah_rw']?></a></td>
			<td align="right"><?=$data['jumlah_rt']?></td>
			<td align="right"><a href="<?=site_url("sid_core/warga_kk/$data[id]")?>"><?=$data['jumlah_kk']?></a></td>
			<td align="right"><a href="<?=site_url("sid_core/warga/$data[id]")?>"><?=$data['jumlah_warga']?></a></td>
			<td align="right"><a href="<?=site_url("sid_core/warga_l/$data[id]")?>"><?=$data['jumlah_warga_l']?></a></td>
			<td align="right"><a href="<?=site_url("sid_core/warga_p/$data[id]")?>"><?=$data['jumlah_warga_p']?></a></td>
				<td></td>
		</tr>
        <? endforeach; ?>
		</tbody>
		
            <tr style="background-color:#BDD498;font-weight:bold;">
                <td colspan="5" align="left"><label>TOTAL</label></td>
				<td align="right"><?=$total['total_rw']?></td>
				<td align="right"><?=$total['total_rt']?></td>
				<td align="right"><?=$total['total_kk']?></td>
				<td align="right"><?=$total['total_warga']?></td>
				<td align="right"><?=$total['total_warga_l']?></td>
				<td align="right"><?=$total['total_warga_p']?></td>
				<td></td>
			</tr>
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
		<div class="table-info">
          <form id="paging" action="<?=site_url('sid_core')?>" method="post">
		  <label>Tampilkan</label>
            <select name="per_page" onchange="$('#paging').submit()" >
              <option value="50" <? selected($per_page,50); ?> >50</option>
              <option value="100" <? selected($per_page,100); ?> >100</option>
              <option value="200" <? selected($per_page,200); ?> >200</option>
            </select>
            <label>Dari</label>
            <label><strong><?=$paging->num_rows?></strong></label>
            <label>Total Data</label>
          </form>
          </div>
        </div>
        <div class="right">
            <div class="uibutton-group">
            <? if($paging->start_link): ?>
				<a href="<?=site_url("sid_core/index/$paging->start_link/$o")?>" class="uibutton"  >Awal</a>
			<? endif; ?>
			<? if($paging->prev): ?>
				<a href="<?=site_url("sid_core/index/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
			<? endif; ?>
            </div>
            <div class="uibutton-group">
                
				<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?=site_url("sid_core/index/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
				<? endfor; ?>
            </div>
            <div class="uibutton-group">
			<? if($paging->next): ?>
				<a href="<?=site_url("sid_core/index/$paging->next/$o")?>" class="uibutton">Next</a>
			<? endif; ?>
			<? if($paging->end_link): ?>
                <a href="<?=site_url("sid_core/index/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
			<? endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
