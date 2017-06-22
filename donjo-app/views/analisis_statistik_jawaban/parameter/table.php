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
		<td style="background:#fff;padding:0px 10px;"> 
<div class="content-header">
</div>
<div id="contentpane"> 
	<form id="mainform" name="mainform" action="" method="post">
 <div class="ui-layout-north panel">
 <h3>Manajemen Parameter Analisis</h3>
	<p> &nbsp; Pertanyaan : <?php echo $analisis_statistik_jawaban['pertanyaan']?></p>
 <div class="left">
 <div class="uibutton-group">
 <?php if($analisis_master['lock']==1){?> <a href="<?php echo site_url("analisis_statistik_jawaban/form_parameter/$analisis_statistik_jawaban[id]")?>" class="uibutton tipsy south" title="Tambah Data" target="ajax-modal" rel="window" header="Form Data Parameter"><span class="fa fa-plus-square">&nbsp;</span>Tambah Parameter Baru</a>
 <button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("analisis_statistik_jawaban/p_delete_all_parameter]")?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data<?php }?>
 </div>
 </div>
 </div>
 <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
 <div class="table-panel top">
 <div class="left">
 </div>
 <div class="right">
 </div>
 </div>
 <table class="list">
		<thead>
 <tr>
 <th width="10">No</th>
				<?php if($analisis_master['lock']==1){?>
 <th width="10"><input type="checkbox" class="checkall"/></th>
 <th width="80">Aksi</th>
				<?php }?>
 <th width="80">Kode</th>
 <th width="400">Jawaban</th>
 <th width="20">Nilai</th>
 <th></th>
			</tr>
		</thead>
		<tbody>
 <?php foreach($main as $data): ?>
		<tr>
 <td align="center" width="2"><?php echo $data['no']?></td>
		 <?php if($analisis_master['lock']==1){?>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
			</td>
 <td><div class="uibutton-group">
 <a href="<?php echo site_url("analisis_statistik_jawaban/form_parameter/$analisis_statistik_jawaban[id]/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data" target="ajax-modal" rel="window" header="Form Data Parameter"><span class="fa fa-edit"></span> Ubah</a><a href="<?php echo site_url("analisis_statistik_jawaban/delete_parameter/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
			</div>
 </td>
		 <?php }?>
 <td><?php echo $data['kode_jawaban']?></td>
 <td><?php echo $data['jawaban']?></td>
 <td><?php echo $data['nilai']?></td>
 <td></td>
		 </tr>
 <?php endforeach; ?>
		</tbody>
 </table>
 </div>
	</form>
 <div class="ui-layout-south panel bottom">
 <div class="left"> 
<a href="<?php echo site_url()?>analisis_statistik_jawaban" class="uibutton icon prev">Kembali</a>
 <div class="right">
 </div>
 </div>
</div>
</td></tr></table>
</div>