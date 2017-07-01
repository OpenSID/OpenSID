<script>
	$(function() {
		var keyword = <?php echo $keyword?> ;
		$( "#cari" ).autocomplete({
			source: keyword
		});
	});
</script>
<div id="pageC">
	<div id="contentpane">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="ui-layout-north panel">
				<div class="left">
					<h3>Manajemen Modul</h3>
				</div>
			</div>
		 <div class="ui-layout-center" id="maincontent">
			 <div class="table-panel top">
				 <div class="left">
					 <select name="filter" onchange="formAction('mainform','<?php echo site_url('modul/filter')?>')">
					 <option value="">Semua</option>
					 <option value="1" <?php if($filter==1 ) :?>selected<?php endif?>>Aktif</option>
					 <option value="2" <?php if($filter==2 ) :?>selected<?php endif?>>Tidak Aktif</option>
					 </select>
				 </div>
				 <div class="right">
					 <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('modul/search')?>');$('#'+'mainform').submit();}" />
					 <button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('modul/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south" title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
				 </div>
			</div>

			<table class="list">
				<thead>
				<tr>
					<th width="5">No</th>
					<th width="10"><input type="checkbox" class="checkall"/></th>
					<th width="100">Aksi</th>
					<th align="left" width="350">Nama Modul </th>
					<th align="left" width="250">URL</th>
					<th align="left" width="220">Aktif</th>
					<th>&nbsp;</th>
				</tr>
				</thead>
				<tbody>
					<?php foreach($main as $data){ ?>
					<tr>
						<td align="center" width="2"><?php echo $data['no']?></td>
						<td align="center" width="5">
							<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
						</td>
						<td width="5">
							<div class="uibutton-group">
								<a href="<?php echo site_url("modul/form/$data[id]")?>" class="uibutton fa-tipis" title="Ubah Data"><span class="fa fa-edit"></span> Ubah</a>
							</div>
						</td>
						<td><?php echo $data['modul']?></td>
						<td><?php echo $data['url']?></td>
						<td><?php
						if($data['aktif']==1)
							echo "Aktif";
						else
							echo "Tidak Aktif";
						?></td>
						<td>&nbsp;</td>
					</tr>
					<?php }?>
				</tbody>
			</table>

		 </div>
			</form>
		 <div class="ui-layout-south panel bottom">
		 <div class="left">
		 </div>
		 <div class="right">
		 </div>
		 </div>
	</div>
</div>