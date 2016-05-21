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
				<li class="selected"><a href="<?=site_url('sms/polling')?>">Kirim Polling</a></li>
				<li  ><a href="<?=site_url('sms/hasil_polling')?>">Hasil Polling</a></li>
				</ul>
			</div>
		</fieldset>
		
	</td>
		</td>
		<td style="background:#fff;padding:5px;"> 
<div class="content-header">
    <h3>Polling SMS</h3>
</div>
<div id="contentpane">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
                <a href="<?=site_url('sms/form_polling/0')?>" class="uibutton tipsy south" title="Tambah Polling" target="ajax-modalx" rel="window" header="Tambah Polling"><span class="ui-icon ui-icon-plus">&nbsp;</span>Tambah Polling</a>
                <button type="button" title="Delete Data" onclick="deleteAllBox('mainform','<?=site_url('sms/delete_all_polling')?>')" class="uibutton tipsy south"><span class="ui-icon ui-icon-trash">&nbsp;</span>Delete Data
            </div>
        </div>
    </div>

    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">

        </div>

        <table class="list">
		<thead>
		    	<tr>
				<th width="10">No</th>
				<th width="15"><input type="checkbox" class="checkall"/></th>
				<th width="15" >Aksi</th>
				<th width="100">Nama Polling</th>
				<th width="100">Keterangan</th>
			    	<th width="25">Jumlah Pertanyaan</th>	
		   	 </tr>
		</thead>
		<tbody>
        		<? $no=1; foreach($main as $data): ?>
			<tr>
		  		<td align="center" width="2"><?=$no?></td>
				<td align="center" width="5">
					<input type="checkbox" name="id_cb[]" value="<?=$data['id_polling']?>" />
				</td>
		  		<td align="center">
				<?// $x=$data['id'];?>
		    		    <a href="<?=site_url("sms/form_polling/$data[id_polling]")?>" class="ui-icons icon-edit tipsy south" title="Edit Data" target="ajax-modalx" rel="window" header="Edit Data"></a>			    <?if($data['jumlah_pertanyaan']=="0"){?>
				<a href="<?=site_url("sms/polling_delete/$data[id_polling]")?>" class="ui-icons icon-remove tipsy south" title="Delete Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"></a><? } ?>
				    <a href="<?=site_url("sms/pertanyaan/$data[id_polling]")?>" class="ui-icons icon-document-table tipsy south" title="Rincian Anggota"></a>
		  		</td>
				 <td><?=$data['nama_polling']?></td>
				<td><?=$data['ket_polling']?></td>	
				 <td align="center"><?=$data['jumlah_pertanyaan']?></td>
			</tr>
      			<? $no++; endforeach; ?>
		</tbody>
        </table>

    	</div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
		<div class="table-info">
          <form id="paging" action="<?=site_url('sms/polling')?>" method="post">
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
				<a href="<?=site_url("sms/polling/$paging->start_link/$o")?>" class="uibutton"  >First</a>
			<? endif; ?>
			<? if($paging->prev): ?>
				<a href="<?=site_url("sms/polling/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
			<? endif; ?>
            </div>
            <div class="uibutton-group">
                
				<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?=site_url("sms/polling/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
				<? endfor; ?>
            </div>
            <div class="uibutton-group">
			<? if($paging->next): ?>
				<a href="<?=site_url("sms/polling/$paging->next/$o")?>" class="uibutton">Next</a>
			<? endif; ?>
			<? if($paging->end_link): ?>
                <a href="<?=site_url("sms/polling/$paging->end_link/$o")?>" class="uibutton">Last</a>
			<? endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
