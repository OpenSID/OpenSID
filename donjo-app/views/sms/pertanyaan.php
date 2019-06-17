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
				<li><a href="<?php echo site_url('sms/kontak')?>">Daftar Kontak</a></li>
				<li  class="selected"><a href="<?php echo site_url('sms/group')?>">Group Kontak</a></li>
				</ul>
			</div>
		</fieldset>

	</td>
		</td>
		<td style="background:#fff;padding:5px;">
<div class="content-header">
    <h3>Manajemen Anggota Group Kontak <?php  //foreach($main as $data): endforeach;?><?php echo $grup['nama_grup']?><?php   ?></h3>
</div>
<div id="contentpane">
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
                <a href="<?php echo site_url("sms/form_anggota/$grup[nama_grup]")?>" class="uibutton tipsy south" title="Tambah Anggota" target="ajax-modalx" rel="window" header="Tambah Anggota"><span class="fa fa-plus-square">&nbsp;</span>Tambah Anggota</a>
                <button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("sms/delete_all_anggota/$grup[nama_grup]")?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data</button>
            </div>
        </div>
    </div>

    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="right">
                <input name="cari_anggota" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari_anggota?>" title="Search.."/>
                <button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url("sms/search_anggota/$grup[nama_grup]")?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
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
        		<?php  $no=1; foreach($main as $data): ?>
			<tr>
		  		<td align="center" width="2"><?php echo $no?></td>
				<td align="center" width="5">
					<input type="checkbox" name="id_cb[]" value="<?php echo $data['id_kontak']?>" />
				</td>
		  		<td align="center">
				<?php // $x=$data['id'];?>
				    <a href="<?php echo site_url("sms/anggota_delete/$data[nama_grup]/$data[id_kontak]")?>" class="ui-icons fa fa-trash tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"></a>
		  		</td>
				 <td><?php echo $data['nama']?></td>
				 <td><?php echo $data['sex']?></td>
				 <td><?php echo $data['alamat_sekarang']?></td>
				 <td align="center"><?php echo $data['no_hp']?></td>
			</tr>
      			<?php  $no++; endforeach; ?>
		</tbody>
        </table>
	<?php  if($main){ ?>
    	</div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left">
		<div class="table-info">
          <form id="paging" action="<?php echo site_url("sms/anggota/$data[nama_grup]")?>" method="post">
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
				<a href="<?php echo site_url("sms/anggota/$data[nama_grup]/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
			<?php  endif; ?>
			<?php  if($paging->prev): ?>
				<a href="<?php echo site_url("sms/anggota/$data[nama_grup]/$paging->prev/$o")?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
			<?php  endif; ?>
            </div>
            <div class="uibutton-group">

				<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("sms/anggota/$data[nama_grup]/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php  endfor; ?>
            </div>
            <div class="uibutton-group">
			<?php  if($paging->next): ?>
				<a href="<?php echo site_url("sms/anggota/$data[nama_grup]/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
			<?php  endif; ?>
			<?php  if($paging->end_link): ?>
                <a href="<?php echo site_url("sms/anggota/$data[nama_grup]/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
			<?php  endif; }?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
