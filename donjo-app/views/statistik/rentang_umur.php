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
		<td style="background:#fff;padding:5px;"> 
<div class="content-header">
    <h3>Rentang Umur</h3>
</div>
<div id="contentpane">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
                <a href="<?php echo site_url('statistik/form_rentang/0')?>" class="uibutton tipsy south" title="Tambah Data" target="ajax-modal" rel="window" header="Tambah Rentang"><span class="fa fa-plus-square">&nbsp;</span>Tambah Rentang</a>
                <button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url('statistik/delete_all_rentang')?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data
            </div>
        </div>
		<div class="right">
            <div class="uibutton-group">
<a href="<?php echo site_url('statistik/index/13')?>" class="uibutton icon prev">Kembali</a>
            </div>
        </div>
    </div>

    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <table class="list">
		<thead>
		    	<tr>
				<th width="5">No</th>
				<th width="5"><input type="checkbox" class="checkall"/></th>
				<th width="8%">Aksi</th>
		    	<th width="20%">Rentang</th>	
		    	<th></th>
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
		    		    <a href="<?php echo site_url("statistik/form_rentang/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data" target="ajax-modal" rel="window" header="Ubah Data"><span class="fa fa-edit"> Ubah</span></a>
				    <a href="<?php echo site_url("statistik/rentang_delete/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
				</div>
		  		</td>
				 <td><?php echo $data['dari']?> - <?php echo $data['sampai']?> Tahun</td>
				 <td></td>
			</tr>
      			<?php  $no++; endforeach; ?>
		</tbody>
        </table>

    	</div>
	</form>

</div>
</td></tr></table>
</div>
