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
				<li><a href="<?=site_url('sms/kontak')?>">Daftar Kontak</a></li>
				<li  class="selected"><a href="<?=site_url('sms/group')?>">Group Kontak</a></li>
				</ul>
			</div>
		</fieldset>
		
	</td>
		</td>
		<td style="background:#fff;padding:5px;"> 
<div class="content-header">
    <h3>Manajemen Anggota Group Kontak <? //foreach($main as $data): endforeach;?><?=$grup['nama_grup']?><?  ?></h3>
</div>
<div id="contentpane">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
                <a href="<?=site_url("sms/form_anggota/$grup[nama_grup]")?>" class="uibutton tipsy south" title="Tambah Anggota" target="ajax-modalx" rel="window" header="Tambah Anggota"><span class="ui-icon ui-icon-plus">&nbsp;</span>Tambah Anggota</a>
                <button type="button" title="Delete Data" onclick="deleteAllBox('mainform','<?=site_url("sms/delete_all_anggota/$grup[nama_grup]")?>')" class="uibutton tipsy south"><span class="ui-icon ui-icon-trash">&nbsp;</span>Delete Data
            </div>
        </div>
    </div>

    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="right">
                <input name="cari_anggota" id="cari" type="text" class="inputbox help tipped" size="20" value="<?=$cari_anggota?>" title="Search.."/>
                <button type="button" onclick="$('#'+'mainform').attr('action','<?=site_url("sms/search_anggota/$grup[nama_grup]")?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="ui-icon ui-icon-search">&nbsp;</span>Search</button>
            </div>
        </div>

        <table class="list">
		<thead>
		    	<tr>
				<th width="10">No</th>
				<th width="15"><input type="checkbox" class="checkall"/></th>
				<th width="15" >Aksi</th>
				<th width="100">Nama Anggota</th>
			    	<th width="25">Jenis Kelamin</th>	
			    	<th width="25">Alamat</th>	
			    	<th width="25">No HP</th>	
		   	 </tr>
		</thead>
		<tbody>
        		<? $no=1; foreach($main as $data): ?>
			<tr>
		  		<td align="center" width="2"><?=$no?></td>
				<td align="center" width="5">
					<input type="checkbox" name="id_cb[]" value="<?=$data['id_kontak']?>" />
				</td>
		  		<td align="center">
				<?// $x=$data['id'];?>
				    <a href="<?=site_url("sms/anggota_delete/$data[nama_grup]/$data[id_kontak]")?>" class="ui-icons icon-remove tipsy south" title="Delete Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"></a>
		  		</td>
				 <td><?=$data['nama']?></td>
				 <td><?=$data['sex']?></td>
				 <td><?=$data['alamat_sekarang']?></td>
				 <td align="center"><?=$data['no_hp']?></td>
			</tr>
      			<? $no++; endforeach; ?>
		</tbody>
        </table>
	<? if($main){ ?>
    	</div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
		<div class="table-info">
          <form id="paging" action="<?=site_url("sms/anggota/$data[nama_grup]")?>" method="post">
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
				<a href="<?=site_url("sms/anggota/$data[nama_grup]/$paging->start_link/$o")?>" class="uibutton"  >First</a>
			<? endif; ?>
			<? if($paging->prev): ?>
				<a href="<?=site_url("sms/anggota/$data[nama_grup]/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
			<? endif; ?>
            </div>
            <div class="uibutton-group">
                
				<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?=site_url("sms/anggota/$data[nama_grup]/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
				<? endfor; ?>
            </div>
            <div class="uibutton-group">
			<? if($paging->next): ?>
				<a href="<?=site_url("sms/anggota/$data[nama_grup]/$paging->next/$o")?>" class="uibutton">Next</a>
			<? endif; ?>
			<? if($paging->end_link): ?>
                <a href="<?=site_url("sms/anggota/$data[nama_grup]/$paging->end_link/$o")?>" class="uibutton">Last</a>
			<? endif; }?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
