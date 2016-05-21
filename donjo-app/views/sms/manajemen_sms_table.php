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
	<td class="side-menu">
		<fieldset>
			<div class="lmenu">
				<ul>
				<li class="selected"><a href="<?=site_url('sms/clear')?>">Kotak Masuk</a></li>
				<li><a href="<?=site_url('sms/outbox')?>">Tulis Pesan</a></li>
				<li><a href="<?=site_url('sms/sentitem')?>">Berita Terkirim</a></li>
				<li><a href="<?=site_url('sms/pending')?>">Pesan Tertunda</a></li>
				</ul>
			</div>
		</fieldset>
		
	</td>
		</td>
		<td style="background:#fff;padding:5px;"> 
<div class="content-header">
    <h3>Kotak Masuk</h3>
</div>
<div id="contentpane">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
                <a href="<?=site_url('sms/form/0/0/1')?>" class="uibutton tipsy south" title="Tulis Pesan Baru" target="ajax-modalx" rel="window" header="Tulis Pesan Baru"><span class="icon-comment icon-large">&nbsp;</span>Tulis Pesan Baru</a>
                <button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?=site_url("sms/delete_all/$p/$o/1")?>')" class="uibutton tipsy south"><span class="icon-trash icon-large">&nbsp;</span>Hapus Data
            </div>
        </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">

        <table class="list">
		<thead>
            <tr>
                <th width="20">No</th>
                <th width="20"><input type="checkbox" class="checkall"/></th>
                <th width="90">Aksi</th>
                <th width="150">Nama</th>
                <? if($o==2): ?>
				<th align="left" width='100'><a href="<?=site_url("sms/index/$p/1")?>">Nomor HP<span class="ui-icon ui-icon-triangle-1-n"></span></a></th>
			<? elseif($o==1): ?>
				<th align="left" width='100'><a href="<?=site_url("sms/index/$p/2")?>">Nomor HP<span class="ui-icon ui-icon-triangle-1-s"></span></a></th
			<? else: ?>
				<th align="left" width='100'><a href="<?=site_url("sms/index/$p/1")?>">Nomor HP<span class="ui-icon ui-icon-triangle-2-n-s"></span></a></th>
			<? endif; ?>
			
			<th align="left">Isi Pesan</th>
			
			
			<? if($o==6): ?>
				<th align="left" width='160'><a href="<?=site_url("sms/index/$p/5")?>">Diterima<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==5): ?>
				<th align="left" width='160'><a href="<?=site_url("sms/index/$p/6")?>">Diterima<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left" width='160'><a href="<?=site_url("sms/index/$p/5")?>">Diterima<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
			</tr>
		</thead>
		<tbody>
        <? $no=1;foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?=$no;$no++; ?></td>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="<?=$data['ID']?>" />
			</td>
          <td><div class="uibutton-group">
        <a href="<?=site_url("sms/form/$p/$o/1/$data[ID]")?>" class="uibutton tipsy south" title="Tampilkan dan Balas" target="ajax-modal" rel="window" header="Lihat Pesan"><span class="icon-mail-reply icon-large"> Balas </span></a><a href="<?=site_url("sms/delete/$p/$o/1/$data[ID]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="icon-trash icon-large"></span></a>
	</div>
          </td>
          <td><?=unpenetration($data['nama'])?></td>
          <td><?=$data['SenderNumber']?></td>
          <td><?=$data['TextDecoded']?></td>
          
          <td><?=tgl_indo2($data['ReceivingDateTime'])?></td>
		  </tr>
        <? endforeach; ?>
		</tbody>
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
		<div class="table-info">
          <form id="paging" action="<?=site_url('sms')?>" method="post">
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
				<a href="<?=site_url("sms/index/$paging->start_link/$o")?>" class="uibutton"  >Awal</a>
			<? endif; ?>
			<? if($paging->prev): ?>
				<a href="<?=site_url("sms/index/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
			<? endif; ?>
            </div>
            <div class="uibutton-group">
                
				<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?=site_url("sms/index/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
				<? endfor; ?>
            </div>
            <div class="uibutton-group">
			<? if($paging->next): ?>
				<a href="<?=site_url("sms/index/$paging->next/$o")?>" class="uibutton">Next</a>
			<? endif; ?>
			<? if($paging->end_link): ?>
                <a href="<?=site_url("sms/index/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
			<? endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
