<script>
	$(function() {
		var keyword = <?php echo $keyword?> ;
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
				<li><a href="<?php echo site_url('sms/clear')?>">Kotak Masuk</a></li>
				<li><a href="<?php echo site_url('sms/outbox')?>">Tulis Pesan</a></li>
				<li class="selected"><a href="<?php echo site_url('sms/sentitem')?>">Berita Terkirim</a></li>
				<li><a href="<?php echo site_url('sms/pending')?>">Pesan Tertunda</a></li>
				</ul>
			</div>
		</fieldset>
		
	</td>
		</td>
		<td style="background:#fff;padding:5px;"> 
<div class="content-header">
    <h3>Berita Terkirim</h3>
</div>
<div id="contentpane">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
                <a href="<?php echo site_url('sms/form/0/0/2')?>" class="uibutton tipsy south" title="Tulis Pesan Baru" target="ajax-modalx" rel="window" header="Tulis Pesan Baru"><span class="fa fa-commenting">&nbsp;</span>Tulis Pesan Baru</a>
                <button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("sms/delete_all/$p/$o/2")?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data
            </div>
        </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">

        <table class="list">
		<thead>
            <tr>
                <th>No</th>
                <th><input type="checkbox" class="checkall"/></th>
                <th width="90">Aksi</th>
                <th width="150">Nama</th>
                <?php  if($o==2): ?>
				<th align="left" width='100'><a href="<?php echo site_url("sms/index/$p/1")?>">Nomor HP <span class="fa fa-sort-asc fa-sm"></span></a></th>
			<?php  elseif($o==1): ?>
				<th align="left" width='100'><a href="<?php echo site_url("sms/index/$p/2")?>">Nomor HP <span class="fa fa-sort-desc fa-sm"></span></a></th>
			<?php  else: ?>
				<th align="left" width='100'><a href="<?php echo site_url("sms/index/$p/1")?>">Nomor HP <span class="fa fa-sort fa-sm"></span></a></th>
			<?php  endif; ?>
			
			<th align="left">Isi Pesan</th>
			
			
			<?php  if($o==6): ?>
				<th align="left" width='160'><a href="<?php echo site_url("sms/index/$p/5")?>">Dikirim <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php  elseif($o==5): ?>
				<th align="left" width='160'><a href="<?php echo site_url("sms/index/$p/6")?>">Dikirim <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php  else: ?>
				<th align="left" width='160'><a href="<?php echo site_url("sms/index/$p/5")?>">Dikirim <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php  endif; ?>
			</tr>
		</thead>
		<tbody>
        <?php  $no=1;foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?php echo $no;$no++; ?></td>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="<?php echo $data['ID']?>" />
			</td>
          <td><div class="uibutton-group">
            <a href="<?php echo site_url("sms/form/$p/$o/2/$data[ID]")?>" class="uibutton tipsy south" title="Tampilkan dan Balas" target="ajax-modalx" rel="window" header="Lihat Pesan"><span class="fa fa-search-plus"> Balas </span></a><a href="<?php echo site_url("sms/delete/$p/$o/2/$data[ID]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a></div>
          </td>
          <td><?php echo unpenetration($data['nama'])?></td>
          <td><?php echo $data['DestinationNumber']?></td>
          <td><?php echo $data['TextDecoded']?></td>
          
          <td><?php echo tgl_indo2($data['SendingDateTime'])?></td>
		  </tr>
        <?php  endforeach; ?>
		</tbody>
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
		<div class="table-info">
          <form id="paging" action="<?php echo site_url('sms/sentitem')?>" method="post">
		  <label>Tampilkan</label>
            <select name="per_page" onchange="$('#paging').submit()" >
              <option value="20" <?php  selected($per_page,20); ?> >20</option>
              <option value="50" <?php  selected($per_page,50); ?> >50</option>
              <option value="100" <?php  selected($per_page,100); ?> >100</option>
            </select>
            <label>Dari</label>
            <label><strong><?php echo $paging->num_rows?></strong></label>
            <label>Total Data</label>
          </form>
          </div>
        </div>
        <div class="right">
            <div class="uibutton-group">
            <?php  if($paging->start_link): ?>
				<a href="<?php echo site_url("sms/sentitem/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
			<?php  endif; ?>
			<?php  if($paging->prev): ?>
				<a href="<?php echo site_url("sms/sentitem/$paging->prev/$o")?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
			<?php  endif; ?>
            </div>
            <div class="uibutton-group">
                
				<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("sms/sentitem/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php  endfor; ?>
            </div>
            <div class="uibutton-group">
			<?php  if($paging->next): ?>
				<a href="<?php echo site_url("sms/sentitem/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
			<?php  endif; ?>
			<?php  if($paging->end_link): ?>
                <a href="<?php echo site_url("sms/sentitem/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
			<?php  endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
