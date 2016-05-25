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
		<td style="background:#fff;padding:0px;"> 
<div class="content-header">
    <h3>Manajemen User</h3>
</div>
<div id="contentpane">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
                <a href="<?php echo site_url('man_user/form')?>" class="uibutton tipsy south" title="Tambah Data" ><span class="icon-plus-sign icon-large">&nbsp;</span>Tambah User Baru</a>
                <button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("man_user/delete_all/$p/$o")?>')" class="uibutton tipsy south"><span class="icon-trash icon-large">&nbsp;</span>Hapus Data
            </div>
        </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">
                <select name="filter" onchange="formAction('mainform','<?php echo site_url('man_user/filter')?>')">
                    <option value="">Semua</option>
                    <option value="1" <?php if($filter==1) :?>selected<?php endif?>>Administrator</option>
                    <option value="2" <?php if($filter==2) :?>selected<?php endif?>>Operator</option>
                    <option value="3" <?php if($filter==3) :?>selected<?php endif?>>Redaksi</option>
                </select>
            </div>
            <div class="right">
                <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('man_user/search')?>');$('#'+'mainform').submit();}" />
                <button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('man_user/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="icon-search icon-large">&nbsp;</span>Cari</button>
            </div>
        </div>
        <table class="list">
		<thead>
            <tr>
                <th>No</th>
                <th><input type="checkbox" class="checkall"/></th>
                <th width="100">Aksi</th>
                <?php  if($o==2): ?>
				<th align="left" width='100'><a href="<?php echo site_url("man_user/index/$p/1")?>">Username<span class="ui-icon ui-icon-triangle-1-n"></span></a></th>
			<?php  elseif($o==1): ?>
				<th align="left" width='100'><a href="<?php echo site_url("man_user/index/$p/2")?>">Username<span class="ui-icon ui-icon-triangle-1-s"></span></a></th>
			<?php  else: ?>
				<th align="left" width='100'><a href="<?php echo site_url("man_user/index/$p/1")?>">Username<span class="ui-icon ui-icon-triangle-2-n-s"></span></a></th>
			<?php  endif; ?>
			
	 		<?php  if($o==4): ?>
				<th align="left"><a href="<?php echo site_url("man_user/index/$p/3")?>">Nama<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<?php  elseif($o==3): ?>
				<th align="left"><a href="<?php echo site_url("man_user/index/$p/4")?>">Nama<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<?php  else: ?>
				<th align="left"><a href="<?php echo site_url("man_user/index/$p/3")?>">Nama<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<?php  endif; ?>
			
			<?php  if($o==6): ?>
				<th align="left" width='100'><a href="<?php echo site_url("man_user/index/$p/5")?>">Group<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<?php  elseif($o==5): ?>
				<th align="left" width='100'><a href="<?php echo site_url("man_user/index/$p/6")?>">Group<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<?php  else: ?>
				<th align="left" width='100'><a href="<?php echo site_url("man_user/index/$p/5")?>">Group<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<?php  endif; ?>
				<th align="left" width='160' align="center">Last Login</th>
            
			</tr>
		</thead>
		<tbody>
    <?php  
    foreach($main as $data){
			echo "
			<tr>
				<td>".$data["no"]."</td>
				<td>";
				if($data["username"] != "siteman"){
					echo "<input type=\"checkbox\" name=\"id_cb[]\" value=\"". $data['id']."\" />";
				}
				$strUrl = "man_user/form/".$p."/".$o."/".$data[id];
				$strDel = "man_user/delete/".$p."/".$o."/".$data[id];
				echo "</td>
				<td>
				<div class=\"uibutton-group\">
					<a href=\"".$strUrl."\" class=\"uibutton tipsy south\" title=\"Ubah Data\"><span class=\"icon-edit icon-large\"> Ubah </span></a>";
					if($data['username']!='admin'){
						echo "<a href=\"". site_url($strDel)."\" class=\"uibutton tipsy south\" title=\"Hapus Data\" target=\"confirm\" message=\"Apakah Anda Yakin?\" header=\"Hapus Data\"><span class=\"icon-trash icon-large\"></span></a>";
						if($data['active'] == '0'){
							echo "<a href=\"".site_url('man_user/user_unlock/'.$data['id'])."\" class=\"uibutton tipsy south\" title=\"Aktivasi User\"><span class=\"icon-lock icon-large\"></span></a>";
						}elseif($data['active'] == '1'){
							echo "<a href=\"".site_url('man_user/user_lock/'.$data['id'])."\" class=\"uibutton tipsy south\" title=\"Non-aktifkan User\"><span class=\"icon-unlock icon-large\"></span></a>";
						}
					}
				echo "
				</div>
				</td>
				<td>".$data["username"]."</td>
				<td>".$data["nama"]."</td>
				<td>".$data["grup"]."</td>
				<td>".tgl_indo($data["last_login"])."</td>
			</tr>
			";
		}
     ?>
		</tbody>
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
		<div class="table-info">
          <form id="paging" action="<?php echo site_url('man_user')?>" method="post">
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
				<a href="<?php echo site_url("man_user/index/$paging->start_link/$o")?>" class="uibutton"  >Awal</a>
			<?php  endif; ?>
			<?php  if($paging->prev): ?>
				<a href="<?php echo site_url("man_user/index/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
			<?php  endif; ?>
            </div>
            <div class="uibutton-group">
                
				<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("man_user/index/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php  endfor; ?>
            </div>
            <div class="uibutton-group">
			<?php  if($paging->next): ?>
				<a href="<?php echo site_url("man_user/index/$paging->next/$o")?>" class="uibutton">Next</a>
			<?php  endif; ?>
			<?php  if($paging->end_link): ?>
                <a href="<?php echo site_url("man_user/index/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
			<?php  endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
