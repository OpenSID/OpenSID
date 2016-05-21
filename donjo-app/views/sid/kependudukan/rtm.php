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
    <h3>Manajemen Rumah Tangga</h3>
        <div class="left">
            <div class="uibutton-group">
                <a href="<?=site_url('rtm/form_old')?>" target="ajax-modalx" rel="window" header="Tambah Data Rumah Tangga Per Penduduk" class="uibutton tipsy south" title="Tambah data dari penduduk" ><span class="icon-plus icon-large">&nbsp;</span>Tambah Rumah Tangga</a>
                
                <?/*<a href="<?=site_url('rtm/form_kk')?>" target="ajax-modalx" rel="window" header="Tambah Data Per Keluarga" class="uibutton tipsy south" title="Tambah Data Berbasis Keluarga" ><span class="icon-plus icon-large">&nbsp;</span>Tambah Data By Keluarga</a>*/?>
                
                <? if($grup==1){?><button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?=site_url("rtm/delete_all/$p/$o")?>')" class="uibutton tipsy south"><span class="icon-trash icon-large">&nbsp;</span>Hapus Data</button><? }?>
				
				<a href="<?=site_url("rtm/cetak/$o")?>" target="_blank" class="uibutton tipsy south" title="Print Data" ><span class="icon-print icon-large">&nbsp;</span>Cetak</a>
			
			<a href="<?=site_url("rtm/excel/$o")?>" target="_blank" class="uibutton tipsy south" title="Data Excel" ><span class="icon-file-text icon-large">&nbsp;</span>Excel</a>
                &nbsp;
				<select name="dusun" onchange="formAction('mainform','<?=site_url('rtm/dusun')?>')">
                    <option value="">Dusun</option>
					<?foreach($list_dusun AS $data){?>
                    <option value="<?=$data['dusun']?>" <?if($dusun == $data['dusun']) :?>selected<?endif?>><?=strtoupper(unpenetration(ununderscore($data['dusun'])))?></option>
					<?}?>
                </select>
				
				<?if($dusun){?>
                <select name="rw" onchange="formAction('mainform','<?=site_url('rtm/rw')?>')">
                    <option value="">RW</option>
					<?foreach($list_rw AS $data){?>
                    <option value="<?=$data['rw']?>" <?if($rw == $data['rw']) :?>selected<?endif?>><?=$data['rw']?></option>
					<?}?>
                </select>
				<?}?>
				
				<?if($rw){?>
                <select name="rt" onchange="formAction('mainform','<?=site_url('rtm/rt')?>')">
                    <option value="">RT</option>
					<?foreach($list_rt AS $data){?>
                    <option value="<?=$data['rt']?>" <?if($rt == $data['rt']) :?>selected<?endif?>><?=$data['rt']?></option>
					<?}?>
                </select>
				<?}?>
				
            </div>
        </div>
        <div class="right">
            <div class="uibutton-group">
                
                <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?=$cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?=site_url('rtm/search')?>');$('#'+'mainform').submit();}" />
                <button type="button" onclick="$('#'+'mainform').attr('action','<?=site_url('rtm/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="icon-search icon-large">&nbsp;</span>Cari</button>
<!--				<a href="<?//=site_url("rtm/sosial/")?>" class="uibutton confirm" title="Grafik Kelas Sosial" ><span class="icon-bar-chart icon-large">&nbsp;</span>Grafik Kelas Sosial</a>
                
				<a href="<?//=site_url("rtm/raskin_graph/")?>" class="uibutton confirm "><span class="icon-bar-chart icon-large">&nbsp;</span>Grafik Raskin</a>
				
				<a href="<?//=site_url("rtm/jamkesmas_graph/")?>" class="uibutton confirm "><span class="icon-bar-chart icon-large">&nbsp;</span>Grafik Jamkesmas</a>-->
            </div>
        </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">

        <table class="list">
		<thead>
            <tr>
                <th>No</th>
                <th><input type="checkbox" class="checkall"/></th>
                <th width="160">Aksi</th>
				
				<th width="150" align="left">
				<? if($o==2): ?>
				<a href="<?=site_url("rtm/index/$p/1")?>">Nomor Rumah Tangga<span class="ui-icon ui-icon-triangle-1-n">
				<? elseif($o==1): ?>
				<a href="<?=site_url("rtm/index/$p/2")?>">Nomor Rumah Tangga<span class="ui-icon ui-icon-triangle-1-s">
				<? else: ?>
				<a href="<?=site_url("rtm/index/$p/1")?>">Nomor Rumah Tangga<span class="ui-icon ui-icon-triangle-2-n-s">
				<? endif; ?>
				&nbsp;</span></a></th>

				<th align="left">
				<? if($o==4): ?>
				<a href="<?=site_url("rtm/index/$p/3")?>">Kepala Rumah Tangga<span class="ui-icon ui-icon-triangle-1-n">
				<? elseif($o==3): ?>
				<a href="<?=site_url("rtm/index/$p/4")?>">Kepala Rumah Tangga<span class="ui-icon ui-icon-triangle-1-s">
				<? else: ?>
				<a href="<?=site_url("rtm/index/$p/3")?>">Kepala Rumah Tangga<span class="ui-icon ui-icon-triangle-2-n-s">
				<? endif; ?>
				&nbsp;</span></a></th>
				
				<th width="100" align="left" align="center">Jumlah Anggota</th>
				<th align="left" align="center" width="120">Dusun</th>
				<th align="left" align="center" width="30">RW</th>
				<th align="left" align="center" width="30">RT</th>
				<th align="left" align="center" width="100">Tanggal Terdaftar</th>
			</tr>
		</thead>
		<tbody>
        <? foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?=$data['no']?></td>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" />
			</td>
          <td width="5"><div class="uibutton-group">
			<a href="<?=site_url("rtm/anggota/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Rincian Anggota rtm"><span class="icon-list icon-large"> Rincian </span></a>
			<a href="<?=site_url("rtm/ajax_add_anggota/$p/$o/$data[id]")?>" target="ajax-modalx" rel="window" header="Tambah Anggota rtm" class="uibutton tipsy south" title="Tambah Anggota rtm"><span  class="icon-plus-sign-alt  icon-large"></span></a>
        <? if($grup==1){?><a href="<?=site_url("rtm/delete/$p/$o/$data[id]")?>"  class="uibutton tipsy south"  title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span  class="icon-trash icon-large"></span> </a><? } ?>
		</div> </td>
          <td><label> <?=$data['no_kk']?> </label></td>
		  <td><?=strtoupper(unpenetration($data['kepala_kk']))?></td>
          <td><a href="<?=site_url("rtm/anggota/$p/$o/$data[id]")?>"><?=$data['jumlah_anggota']?></a></td>
          <td><?=strtoupper(unpenetration(ununderscore($data['dusun'])))?></td>
		  <td><?=strtoupper($data['rw'])?></td>
          <td><?=strtoupper($data['rt'])?></td>
          <td><?=tgl_indo($data['tgl_daftar'])?></td>
		  </tr>
        <? endforeach; ?>
		</tbody>
		
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
		<div class="table-info">
          <form id="paging" action="<?=site_url('rtm')?>" method="post">
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
				<a href="<?=site_url("rtm/index/$paging->start_link/$o")?>" class="uibutton"  >Awal</a>
			<? endif; ?>
			<? if($paging->prev): ?>
				<a href="<?=site_url("rtm/index/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
			<? endif; ?>
            </div>
            <div class="uibutton-group">
                
				<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?=site_url("rtm/index/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
				<? endfor; ?>
            </div>
            <div class="uibutton-group">
			<? if($paging->next): ?>
				<a href="<?=site_url("rtm/index/$paging->next/$o")?>" class="uibutton">Next</a>
			<? endif; ?>
			<? if($paging->end_link): ?>
                <a href="<?=site_url("rtm/index/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
			<? endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
