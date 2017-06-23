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
<h3>Analisis Statistik Jawaban - <a href="<?php echo site_url()?>analisis_master/menu/<?php echo $_SESSION['analisis_master']?>"><a href="<?php echo site_url()?>analisis_master/menu/<?php echo $_SESSION['analisis_master']?>"><?php echo $analisis_master['nama']?></a></a></h3>
</div>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<div class="table-panel top">
<div class="left">
<select name="tipe" onchange="formAction('mainform','<?php echo site_url('analisis_statistik_jawaban/tipe')?>')">
<option value="">-- Filter by Tipe Indikator --</option>
					<?php foreach($list_tipe AS $data){?>
					<option value="<?php echo $data['id']?>" <?php if($tipe == $data['id']) :?>selected<?php endif?>><?php echo $data['tipe']?></option>
					<?php }?>
</select>
				&nbsp;
<select name="kategori" onchange="formAction('mainform','<?php echo site_url('analisis_statistik_jawaban/kategori')?>')">
<option value="">-- Filter by Kategori Indikator --</option>
					<?php foreach($list_kategori AS $data){?>
					<option value="<?php echo $data['id']?>" <?php if($kategori == $data['id']) :?>selected<?php endif?>><?php echo $data['kategori']?></option>
					<?php }?>
</select>
				&nbsp;
<select name="filter" onchange="formAction('mainform','<?php echo site_url('analisis_statistik_jawaban/filter')?>')">
<option value="">-- Filter by Aksi Analisis</option>
<option value="1" <?php if($filter==1) :?>selected<?php endif?>>Ya</option>
<option value="2" <?php if($filter==2) :?>selected<?php endif?>>Tidak</option>
</select>

				<select name="dusun" onchange="formAction('mainform','<?php echo site_url('analisis_statistik_jawaban/dusun')?>')">
					<option value="">Dusun</option>
					<?php foreach($list_dusun AS $data){?>
					<option value="<?php echo $data['dusun']?>" <?php if($dusun == $data['dusun']) :?>selected<?php endif?>><?php echo ununderscore(unpenetration($data['dusun']))?></option>
					<?php }?>
				</select>

				<?php if($dusun){?>
				<select name="rw" onchange="formAction('mainform','<?php echo site_url('analisis_statistik_jawaban/rw')?>')">
					<option value="">RW</option>
					<?php foreach($list_rw AS $data){?>
					<option value="<?php echo $data['rw']?>" <?php if($rw == $data['rw']) :?>selected<?php endif?>><?php echo $data['rw']?></option>
					<?php }?>
				</select>
				<?php }?>

				<?php if($rw){?>
				<select name="rt" onchange="formAction('mainform','<?php echo site_url('analisis_statistik_jawaban/rt')?>')">
					<option value="">RT</option>
					<?php foreach($list_rt AS $data){?>
					<option value="<?php echo $data['rt']?>" <?php if($rt == $data['rt']) :?>selected<?php endif?>><?php echo $data['rt']?></option>
					<?php }?>
				</select>
				<?php }?>

<a href="<?php echo site_url("analisis_statistik_jawaban/cetak/$o")?>" class="uibutton special tipsy south" title="Cetak Data" target="_blank"><span class="fa fa-print">&nbsp;</span>Cetak</a>
<a href="<?php echo site_url("analisis_statistik_jawaban/excel/$o")?>" class="uibutton special tipsy south" title="Data Excel" target="_blank"><span class="fa fa-file-text">&nbsp;</span>Excel</a>
</div>
<div class="right">
<input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('analisis_statistik_jawaban/search')?>');$('#'+'mainform').submit();}" />
<button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('analisis_statistik_jawaban/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south" title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
</div>
</div>
<table class="list">
	<thead>
		<tr>
			<th width="10">No</th>
	 		<?php if($o==4): ?>
				<th align="left"><a href="<?php echo site_url("analisis_statistik_jawaban/index/$p/3")?>">Pertanyaan <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==3): ?>
				<th align="left"><a href="<?php echo site_url("analisis_statistik_jawaban/index/$p/4")?>">Pertanyaan <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th align="left"><a href="<?php echo site_url("analisis_statistik_jawaban/index/$p/3")?>">Pertanyaan <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>

			<th align="left">Total</th>
	 		<?php if($o==2): ?>
				<th align="left" width="10"><a href="<?php echo site_url("analisis_statistik_jawaban/index/$p/1")?>">Kode <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==1): ?>
				<th align="left" width="10"><a href="<?php echo site_url("analisis_statistik_jawaban/index/$p/2")?>">Kode <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th align="left" width="10"><a href="<?php echo site_url("analisis_statistik_jawaban/index/$p/1")?>">Kode <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>

			<th align="left" colspan="2">Jawaban</th>
			<th align="left">Responden</th>

			<?php if($o==6): ?>
				<th align="left" width='100'><a href="<?php echo site_url("analisis_statistik_jawaban/index/$p/5")?>">Tipe Indikator <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==5): ?>
				<th align="left" width='100'><a href="<?php echo site_url("analisis_statistik_jawaban/index/$p/6")?>">Tipe Indikator <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th align="left" width='100'><a href="<?php echo site_url("analisis_statistik_jawaban/index/$p/5")?>">Tipe Indikator <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>

			<?php if($o==6): ?>
				<th align="left" width='100'><a href="<?php echo site_url("analisis_statistik_jawaban/index/$p/5")?>">Kategori Indikator <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==5): ?>
				<th align="left" width='100'><a href="<?php echo site_url("analisis_statistik_jawaban/index/$p/6")?>">Kategori Indikator <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th align="left" width='100'><a href="<?php echo site_url("analisis_statistik_jawaban/index/$p/5")?>">Kategori Indikator <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>

			<?php if($o==2): ?>
				<th align="left" width='100'><a href="<?php echo site_url("analisis_statistik_jawaban/index/$p/1")?>">Aksi Analisis <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==1): ?>
				<th align="left" width='100'><a href="<?php echo site_url("analisis_statistik_jawaban/index/$p/2")?>">Aksi Analisis <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th align="left" width='10'><a href="<?php echo site_url("analisis_statistik_jawaban/index/$p/1")?>">Aksi Analisis <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>
			</tr>
		</thead>
		<tbody>
<?php foreach($main as $data): ?>
	<tr>
		<td align="center" width="2"><?php echo $data['no']?></td>
		<td><?php echo $data['pertanyaan']?></a></td>
		<td align="right"><a href="<?php echo site_url("analisis_statistik_jawaban/grafik_parameter/$data[id]")?>" ><?php echo $data['bobot']?></a></td>
		<td><?php echo $data['nomor']?></td>
			 <td align="right">
				<?php foreach($data['par'] as $par): ?>
				<?php echo $par['kode_jawaban']?>.<br>
				<?php endforeach; ?>
			 </td>
			 <td>
				<?php foreach($data['par'] as $par): ?>
				<?php echo $par['jawaban']?><br>
				<?php endforeach; ?>
			 </td>
			 <td align="right">
				<?php foreach($data['par'] as $par): ?>
				<a href="<?php echo site_url("analisis_statistik_jawaban/subjek_parameter/$data[id]/$par[id]")?>" ><?php echo $par['jml_p']?></a><br>
				<?php endforeach; ?>
			 </td>
			 <td><?php echo $data['tipe_indikator']?></td>
		<td><?php echo $data['kategori']?></td>
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
<form id="paging" action="<?php echo site_url('analisis_statistik_jawaban')?>" method="post">
<select name="per_page" onchange="$('#paging').submit()" >
<option value="20" <?php selected($per_page,20); ?> >20</option>
<option value="50" <?php selected($per_page,50); ?> >50</option>
<option value="100" <?php selected($per_page,100); ?> >100</option>
</select>
<label>Dari <?php echo $paging->num_rows?> Total Data</label>
</form>
		</div>
</div>
<div class="right">
<div class="uibutton-group">
<?php if($paging->start_link): ?>
				<a href="<?php echo site_url("analisis_statistik_jawaban/index/$paging->start_link/$o")?>" class="uibutton" ><span class="fa fa-fast-backward"></span> Awal</a>
			<?php endif; ?>
			<?php if($paging->prev): ?>
				<a href="<?php echo site_url("analisis_statistik_jawaban/index/$paging->prev/$o")?>" class="uibutton" ><span class="fa fa-step-backward"></span> Prev</a>
			<?php endif; ?>
</div>
<div class="uibutton-group">

				<?php for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("analisis_statistik_jawaban/index/$i/$o")?>" <?php jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php endfor; ?>
</div>
<div class="uibutton-group">
			<?php if($paging->next): ?>
				<a href="<?php echo site_url("analisis_statistik_jawaban/index/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
			<?php endif; ?>
			<?php if($paging->end_link): ?>
<a href="<?php echo site_url("analisis_statistik_jawaban/index/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
			<?php endif; ?>
</div>
</div>
</div>
</div>
</div>