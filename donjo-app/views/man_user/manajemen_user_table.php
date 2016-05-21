<script>
	$(function() {
		var keyword = <?=$keyword?> ;
		$( "#cari" ).autocomplete({
			source: keyword
		});
	});
</script>

<div id="pageC">
	<table class="inner">
<tr style="vertical-align:top">
		<td style="background:#fff;padding:0px;"> 
<div class="content-header">
    <h3>Manajemen User</h3>
</div>
<div id="contentpane">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
                <a href="<?=site_url('man_user/form')?>" class="uibutton tipsy south" title="Tambah Data" ><span class="icon-plus-sign icon-large">&nbsp;</span>Tambah User Baru</a>
                <button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?=site_url("man_user/delete_all/$p/$o")?>')" class="uibutton tipsy south"><span class="icon-trash icon-large">&nbsp;</span>Hapus Data
            </div>
        </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">
                <select name="filter" onchange="formAction('mainform','<?=site_url('man_user/filter')?>')">
                    <option value="">Semua</option>
                    <option value="1" <?if($filter==1) :?>selected<?endif?>>Administrator</option>
                    <option value="2" <?if($filter==2) :?>selected<?endif?>>Operator</option>
                    <option value="3" <?if($filter==3) :?>selected<?endif?>>Redaksi</option>
                </select>
            </div>
            <div class="right">
                <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?=$cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?=site_url('man_user/search')?>');$('#'+'mainform').submit();}" />
                <button type="button" onclick="$('#'+'mainform').attr('action','<?=site_url('man_user/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="icon-search icon-large">&nbsp;</span>Cari</button>
            </div>
        </div>
        <table class="list">
		<thead>
            <tr>
                <th>No</th>
                <th><input type="checkbox" class="checkall"/></th>
                <th width="100">Aksi</th>
                <? if($o==2): ?>
				<th align="left" width='100'><a href="<?=site_url("man_user/index/$p/1")?>">Username<span class="ui-icon ui-icon-triangle-1-n"></span></a></th>
			<? elseif($o==1): ?>
				<th align="left" width='100'><a href="<?=site_url("man_user/index/$p/2")?>">Username<span class="ui-icon ui-icon-triangle-1-s"></span></a></th>
			<? else: ?>
				<th align="left" width='100'><a href="<?=site_url("man_user/index/$p/1")?>">Username<span class="ui-icon ui-icon-triangle-2-n-s"></span></a></th>
			<? endif; ?>
			
	 		<? if($o==4): ?>
				<th align="left"><a href="<?=site_url("man_user/index/$p/3")?>">Nama<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==3): ?>
				<th align="left"><a href="<?=site_url("man_user/index/$p/4")?>">Nama<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left"><a href="<?=site_url("man_user/index/$p/3")?>">Nama<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
			
			<? if($o==6): ?>
				<th align="left" width='100'><a href="<?=site_url("man_user/index/$p/5")?>">Group<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==5): ?>
				<th align="left" width='100'><a href="<?=site_url("man_user/index/$p/6")?>">Group<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left" width='100'><a href="<?=site_url("man_user/index/$p/5")?>">Group<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
				<th align="left" width='160' align="center">Last Login</th>
            
			</tr>
		</thead>
		<tbody>
        <? foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?=$data['no']?></td>
			<td align="center" width="5">
				<? if($data['username']!='siteman') :?>
					<input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" />
				<? endif; ?>
			</td>
          <td><div class="uibutton-group">
            <a href="<?=site_url("man_user/form/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data"><span class="icon-edit icon-large"> Ubah </span></a><?if($data['username']!='admin'){?><a href="<?=site_url("man_user/delete/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="icon-trash icon-large"></span></a><?php if($data['active'] == '0'): ?><a href="<?=site_url('man_user/user_unlock/'.$data['id'])?>" class="uibutton tipsy south" title="Aktivasi User"><span class="icon-lock icon-large"></span></a><?php elseif($data['active'] == '1'): ?><a href="<?=site_url('man_user/user_lock/'.$data['id'])?>" class="uibutton tipsy south" title="Non-aktifkan User"><span class="icon-unlock icon-large"></span></a>
			<?php endif; ?>
			<?}?></div>
          </td>
          <td><?=$data['username']?></td>
          <td><?=$data['nama']?></td>
		  <td><?=$data['grup']?></td>
          <td><?=tgl_indo($data['last_login'])?></td>
		  </tr>
        <? endforeach; ?>
		</tbody>
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
		<div class="table-info">
          <form id="paging" action="<?=site_url('man_user')?>" method="post">
		  <label>Tampilkan</label>
            <select name="per_page" onchange="$('#paging').submit()" >
              <option value="20" <? selected($per_page,20); ?> >20</option>
              <option value="50" <? selected($per_page,50); ?> >50</option>
              <option value="100" <? selected($per_page,100); ?> >100</option>
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
				<a href="<?=site_url("man_user/index/$paging->start_link/$o")?>" class="uibutton"  >Awal</a>
			<? endif; ?>
			<? if($paging->prev): ?>
				<a href="<?=site_url("man_user/index/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
			<? endif; ?>
            </div>
            <div class="uibutton-group">
                
				<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?=site_url("man_user/index/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
				<? endfor; ?>
            </div>
            <div class="uibutton-group">
			<? if($paging->next): ?>
				<a href="<?=site_url("man_user/index/$paging->next/$o")?>" class="uibutton">Next</a>
			<? endif; ?>
			<? if($paging->end_link): ?>
                <a href="<?=site_url("man_user/index/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
			<? endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
