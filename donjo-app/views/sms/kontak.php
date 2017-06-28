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
				<li class="selected"><a href="<?php echo site_url('sms/kontak')?>">Daftar Kontak</a></li>
				<li><a href="<?php echo site_url('sms/group')?>">Group Kontak</a></li>
				</ul>
			</div>
		</fieldset>

	</td>
		</td>
		<td style="background:#fff;padding:5px;">
<div class="content-header">
    <h3>Manajemen Nomer Kontak</h3>
</div>
<div id="contentpane">
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
                <a href="<?php echo site_url('sms/form_kontak/0')?>" class="uibutton tipsy south" title="Tambah Data" target="ajax-modal" rel="window" header="Tambah Kontak"><span class="fa fa-plus-square">&nbsp;</span>Tambah Kontak</a>
                <button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url('sms/delete_all_kontak')?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data
            </div>
        </div>
    </div>

    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="right">
                <input name="cari_kontak" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari_kontak?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('sms/search_kontak')?>');$('#'+'mainform').submit();}" />
                <button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('sms/search_kontak')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
            </div>
        </div>

        <table class="list">
		<thead>
		    	<tr>
				<th width="20">No</th>
				<th width="15"><input type="checkbox" class="checkall"/></th>
				<th width="30">Aksi</th>
				<th width="100">Nama</th>
			    	<th width="25">Jenis Kelamin</th>
				<th width="200">Alamat</th>
				<th width="50">No HP</th>
		   	 </tr>
		</thead>
		<tbody>
        		<?php  $no=1; foreach($main as $data): ?>
			<tr>
		  		<td align="center" width="2"><?php echo $no?></td>
				<td align="center" width="5">
					<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
				</td>
		  		<td align="center">
				<div class="uibutton-group">
		    		    <a href="<?php echo site_url("sms/form_kontak/$data[id]")?>" class="uibutton tipsy south fa-tipis" title="Ubah Data" target="ajax-modal" rel="window" header="Ubah Data"><span class="fa fa-edit"></span> Ubah</a>
				    <a href="<?php echo site_url("sms/kontak_delete/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
				</div>
		  		</td>
				 <td><a href="<?php echo site_url("penduduk/detail/$p/$o/$data[id]")?>"><?php echo unpenetration($data['nama'])?></a></td>
				 <td><?php echo $data['sex']?></td>
				 <td><?php echo $data['alamat_sekarang']?></td>
				 <td align="center"><?php echo $data['no_hp']?></td>
			</tr>
      			<?php  $no++; endforeach; ?>
		</tbody>
        </table>

    	</div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left">
		<div class="table-info">
          <form id="paging" action="<?php echo site_url('sms/kontak')?>" method="post">
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
				<a href="<?php echo site_url("sms/kontak/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
			<?php  endif; ?>
			<?php  if($paging->prev): ?>
				<a href="<?php echo site_url("sms/kontak/$paging->prev/$o")?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
			<?php  endif; ?>
            </div>
            <div class="uibutton-group">

				<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("sms/kontak/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php  endfor; ?>
            </div>
            <div class="uibutton-group">
			<?php  if($paging->next): ?>
				<a href="<?php echo site_url("sms/kontak/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
			<?php  endif; ?>
			<?php  if($paging->end_link): ?>
                <a href="<?php echo site_url("sms/kontak/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
			<?php  endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
