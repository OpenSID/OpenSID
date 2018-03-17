<script>
	$(function() {
		var keyword = <?php echo $keyword?> ;
		$( "#cari" ).autocomplete({
			source: keyword
		});
	});
</script>
<style type="text/css">
	.disabled {
	   pointer-events: none;
	   cursor: default;
	}
</style>
<div id="pageC">
<?php $this->load->view('analisis_master/left',$data);?>
<div class="content-header">
</div>
<div id="contentpane">
	<form id="mainform" name="mainform" action="" method="post">
<div class="ui-layout-north panel">
<h3>Manajemen Indikator Analisis - <a href="<?php echo site_url()?>analisis_master/menu/<?php echo $_SESSION['analisis_master']?>"><a href="<?php echo site_url()?>analisis_master/menu/<?php echo $_SESSION['analisis_master']?>"><?php echo $analisis_master['nama']?></a></a></h3>
<div class="left">
<div class="uibutton-group">
	<?php if($analisis_master['lock']==1){?>
		<a href="<?php echo site_url('analisis_indikator/form')?>" class="uibutton tipsy south <?php if($analisis_master['jenis']==1) echo 'disabled'?>" title="Tambah Data" ><span class="fa fa-plus-square">&nbsp;</span>Tambah Indikator Baru</a>
		<button type="button" title="Hapus Data"
			<?php if($analisis_master['jenis']!=1): ?>
				onclick="deleteAllBox('mainform','<?php echo site_url("analisis_indikator/delete_all/$p/$o")?>')"
			<?php endif; ?>
			class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data
	<?php }?>
</div>
</div>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<div class="table-panel top">
<div class="left">
<select name="tipe" onchange="formAction('mainform','<?php echo site_url('analisis_indikator/tipe')?>')">
<option value="">-- Filter by Tipe Pertanyaan --</option>
					<?php foreach($list_tipe AS $data){?>
					<option value="<?php echo $data['id']?>" <?php if($tipe == $data['id']) :?>selected<?php endif?>><?php echo $data['tipe']?></option>
					<?php }?>
</select>
				&nbsp;
<select name="kategori" onchange="formAction('mainform','<?php echo site_url('analisis_indikator/kategori')?>')">
<option value="">-- Filter by Kategori/Variabel --</option>
					<?php foreach($list_kategori AS $data){?>
					<option value="<?php echo $data['id']?>" <?php if($kategori == $data['id']) :?>selected<?php endif?>><?php echo $data['kategori']?></option>
					<?php }?>
</select>
				&nbsp;
<select name="filter" onchange="formAction('mainform','<?php echo site_url('analisis_indikator/filter')?>')">
<option value="">-- Filter by Aksi Analisis</option>
<option value="1" <?php if($filter==1) :?>selected<?php endif?>>Ya</option>
<option value="2" <?php if($filter==2) :?>selected<?php endif?>>Tidak</option>
</select>
</div>
<div class="right">
<input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('analisis_indikator/search')?>');$('#'+'mainform').submit();}" />
<button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('analisis_indikator/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south" title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
</div>
</div>
<table class="list">
		<thead>
<tr>
<th width="10">No</th>
<?php if($analisis_master['lock']==1){?> <th><input type="checkbox" class="checkall"/></th>
<th class='nostretch'>Aksi</th><?php }?>
	 		<?php if($o==2): ?>
				<th align="left"><a href="<?php echo site_url("analisis_indikator/index/$p/1")?>">Kode <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==1): ?>
				<th align="left"><a href="<?php echo site_url("analisis_indikator/index/$p/2")?>">Kode <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th align="left"><a href="<?php echo site_url("analisis_indikator/index/$p/1")?>">Kode <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>

	 		<?php if($o==4): ?>
				<th align="left"><a href="<?php echo site_url("analisis_indikator/index/$p/3")?>">Pertanyaan/Indikator <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==3): ?>
				<th align="left"><a href="<?php echo site_url("analisis_indikator/index/$p/4")?>">Pertanyaan/Indikator <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th align="left"><a href="<?php echo site_url("analisis_indikator/index/$p/3")?>">Pertanyaan/Indikator <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>

			<?php if($o==6): ?>
				<th align="left" width='100'><a href="<?php echo site_url("analisis_indikator/index/$p/5")?>">Tipe Pertanyaan <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==5): ?>
				<th align="left" width='100'><a href="<?php echo site_url("analisis_indikator/index/$p/6")?>">Tipe Pertanyaan <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th align="left" width='100'><a href="<?php echo site_url("analisis_indikator/index/$p/5")?>">Tipe Pertanyaan <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>

			<?php if($o==6): ?>
				<th align="left"><a href="<?php echo site_url("analisis_indikator/index/$p/5")?>">Kategori/Variabel <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==5): ?>
				<th align="left"><a href="<?php echo site_url("analisis_indikator/index/$p/6")?>">Kategori/Variabel <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th align="left"><a href="<?php echo site_url("analisis_indikator/index/$p/5")?>">Kategori/Variabel <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>

			<?php if($o==2): ?>
				<th align="left" width='50'><a href="<?php echo site_url("analisis_indikator/index/$p/1")?>">Bobot <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==1): ?>
				<th align="left" width='50'><a href="<?php echo site_url("analisis_indikator/index/$p/2")?>">Bobot <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th align="left" width='50'><a href="<?php echo site_url("analisis_indikator/index/$p/1")?>">Bobot <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>

			<?php if($o==2): ?>
				<th align="left"><a href="<?php echo site_url("analisis_indikator/index/$p/1")?>">Aksi Analisis <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==1): ?>
				<th align="left"><a href="<?php echo site_url("analisis_indikator/index/$p/2")?>">Aksi Analisis <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th align="left"><a href="<?php echo site_url("analisis_indikator/index/$p/1")?>">Aksi Analisis <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>
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
			<td class="nostretch">
				<div class="uibutton-group" style="display: flex;">
					<?php if($data['id_tipe']==1 OR $data['id_tipe']==2){?>
						<a href="<?php echo site_url("analisis_indikator/parameter/$data[id]")?>" class="uibutton"><span class="fa fa-list"> Jawaban</span></a>
					<?php }?>
					<a href="<?php echo site_url("analisis_indikator/form/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data"><span class="fa fa-edit"> Ubah </span></a>
					<?php if($analisis_master['jenis']!=1): ?>
						<a href="<?php echo site_url("analisis_indikator/delete/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
					<?php endif; ?>
				</div>
			</td>

			<?php }?>
			<td><label><?php echo $data['nomor']?></label></td>
			<td><?php echo $data['pertanyaan']?></td>
			<td><?php echo $data['tipe_indikator']?></td>
			<td><?php echo $data['kategori']?></td>
			<td><?php echo $data['bobot']?></td>
			<td><?php echo $data['act_analisis']?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
</div>
</form>
<div class="ui-layout-south panel bottom">
	<div class="left">
		<div class="table-info">
			<form id="paging" action="<?php echo site_url('analisis_indikator')?>" method="post">
			<a href="<?php echo site_url()?>analisis_indikator/leave" class="uibutton icon prev">Kembali</a>
				<select name="per_page" onchange="$('#paging').submit()" >
					<option value="20" <?php selected($per_page,20); ?> >20</option>
					<option value="50" <?php selected($per_page,50); ?> >50</option>
					<option value="100" <?php selected($per_page,100); ?> >100</option>
				</select>
				<label>Dari</label>
				<label><?php echo $paging->num_rows?></label>
				<label>Total Data</label>
			</form>
		</div>
	</div>
	<div class="right">
		<div class="uibutton-group">
		<?php if($paging->start_link): ?>
			<a href="<?php echo site_url("analisis_indikator/index/$paging->start_link/$o")?>" class="uibutton" >Awal</a>
		<?php endif; ?>
		<?php if($paging->prev): ?>
			<a href="<?php echo site_url("analisis_indikator/index/$paging->prev/$o")?>" class="uibutton" >Prev</a>
		<?php endif; ?>
		</div>
		<div class="uibutton-group">
		<?php for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
			<a href="<?php echo site_url("analisis_indikator/index/$i/$o")?>" <?php jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
		<?php endfor; ?>
		</div>
		<div class="uibutton-group">
		<?php if($paging->next): ?>
			<a href="<?php echo site_url("analisis_indikator/index/$paging->next/$o")?>" class="uibutton">Next</a>
		<?php endif; ?>
		<?php if($paging->end_link): ?>
		<a href="<?php echo site_url("analisis_indikator/index/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
		<?php endif; ?>
		</div>
	</div>
</div>
</div>
</div>