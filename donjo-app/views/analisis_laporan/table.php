<?php
	$subjek = $_SESSION['subjek_tipe'];
	switch($subjek){
		case 1: $sql = $nama="Nama"; $nomor="NIK";$asubjek="Penduduk"; break;
		case 2: $sql = $nama="Kepala Keluarga"; $nomor="Nomor KK";$asubjek="Keluarga"; break;
		case 3: $sql = $nama="Kepala Rumahtangga"; $nomor="Nomor Rumahtangga";$asubjek="Rumahtangga"; break;
		case 4: $sql = $nama="Nama Kelompok"; $nomor="ID Kelompok";$asubjek="Kelompok"; break;
		default: return null;
	}
?>
<script>
	$(function() {
		var keyword = <?php echo $keyword?> ;
		$( "#cari" ).autocomplete({
			source: keyword
		});
	});
</script>
<style>
	table.head{
		font-size:16px;
		font-weight:normal;
	}
</style>
<div id="pageC">
<div id="contentpane">
	<form id="mainform" name="mainform" action="" method="post">
<div class="ui-layout-north panel">
</div>
<div class="ui-layout-center" id="maincontent">
		<table class="head">
			<tr>
				<td width="150">Nama Analisis</td>
				<td> : </td>
				<td><a href="<?php echo site_url()?>analisis_master/menu/<?php echo $_SESSION['analisis_master']?>"><?php echo $analisis_master['nama']?></a></td>
			</tr>
			<tr>
				<td>Subjek Analisis</td>
				<td> : </td>
				<td><?php echo $asubjek?></td>
			</tr>
			<tr>
				<td>Periode</td>
				<td> : </td>
				<td><?php echo $analisis_periode?></td>
			</tr>
		</table>
<div class="table-panel top">
	<div class="left">
		<select name="klasifikasi" onchange="formAction('mainform','<?php echo site_url('analisis_laporan/klasifikasi')?>')">
			<option value=""> --- Klasifikasi --- </option>
			<?php foreach($list_klasifikasi AS $data){?>
			<option value="<?php echo $data['id']?>" <?php if($klasifikasi == $data['id']) :?>selected<?php endif?>><?php echo $data['nama']?></option>
			<?php }?>
		</select>
		<select name="dusun" onchange="formAction('mainform','<?php echo site_url('analisis_laporan/dusun')?>')">
			<option value=""> --- Dusun --- </option>
			<?php foreach($list_dusun AS $data){?>
				<option value="<?php echo $data['dusun']?>" <?php if($dusun == $data['dusun']) :?>selected<?php endif?>><?php echo $data['dusun']?></option>
			<?php }?>
		</select>

		<?php if($dusun){?>
			<select name="rw" onchange="formAction('mainform','<?php echo site_url('analisis_laporan/rw')?>')">
				<option value="">RW</option>
				<?php foreach($list_rw AS $data){?>
					<option value="<?php echo $data['rw']?>" <?php if($rw == $data['rw']) :?>selected<?php endif?>><?php echo $data['rw']?></option>
				<?php }?>
			</select>
		<?php }?>

		<?php if($rw){?>
		 <select name="rt" onchange="formAction('mainform','<?php echo site_url('analisis_laporan/rt')?>')">
			<option value="">RT</option>
			<?php foreach($list_rt AS $data){?>
				<option value="<?php echo $data['rt']?>" <?php if($rt == $data['rt']) :?>selected<?php endif?>><?php echo $data['rt']?></option>
			<?php }?>
		 </select>
		<?php }?>
		<a href="<?php echo site_url("analisis_laporan/cetak/$o")?>" class="uibutton special tipsy south" title="Cetak Data" target="_blank"><span class="fa fa-print">&nbsp;</span>Cetak</a>
		<a href="<?php echo site_url("analisis_laporan/excel/$o")?>" class="uibutton special tipsy south" title="Data Excel" target="_blank"><span class="fa fa-file-text">&nbsp;</span>Excel</a>
		<a href="<?php echo site_url("analisis_laporan/ajax_multi_jawab")?>" target="ajax-modal-map" rel="window" header="Filter Indikator" class="uibutton tipsy south" title="Filter Indikator"><span class="fa fa-search">&nbsp;</span>Filter Indikator</a>
	</div>
	<div class="right">
		<input name="cari" id="cari" type="text" class="inputbox help tipped" size="40" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('analisis_laporan/search')?>');$('#'+'mainform').submit();}" />
		<button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('analisis_laporan/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south" title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
	</div>
</div>
<table class="list">
		<thead>
			<tr>
				<th width="10">No</th>
				<th width='50'>Rincian</th>
			<?php if($o==2): ?>
				<th align="left" width='120'><a href="<?php echo site_url("analisis_respon/index/$p/1")?>"><?php echo $nomor?> <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==1): ?>
				<th align="left" width='120'><a href="<?php echo site_url("analisis_respon/index/$p/2")?>"><?php echo $nomor?> <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th align="left" width='120'><a href="<?php echo site_url("analisis_respon/index/$p/1")?>"><?php echo $nomor?> <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>

			<?php if($o==4): ?>
				<th align="left" width='250'><a href="<?php echo site_url("analisis_respon/index/$p/3")?>"><?php echo $nama?> <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==3): ?>
				<th align="left" width='250'><a href="<?php echo site_url("analisis_respon/index/$p/4")?>"><?php echo $nama?> <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th align="left" width='250'><a href="<?php echo site_url("analisis_respon/index/$p/3")?>"><?php echo $nama?> <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>

				<th width='50'>L/P</th>
				<th width='100'>Dusun</th>
				<th width='30'>RW</th>
				<th width='30'>RT</th>

			<?php if($o==6): ?>
				<th align="left" width='50'><a href="<?php echo site_url("analisis_laporan/index/$p/5")?>">Nilai <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==5): ?>
				<th align="left" width='50'><a href="<?php echo site_url("analisis_laporan/index/$p/6")?>">Nilai <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th align="left" width='50'><a href="<?php echo site_url("analisis_laporan/index/$p/5")?>">Nilai <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>

			<?php if($o==6): ?>
				<th align="left" width='100'><a href="<?php echo site_url("analisis_laporan/index/$p/5")?>">Klasifikasi <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==5): ?>
				<th align="left" width='100'><a href="<?php echo site_url("analisis_laporan/index/$p/6")?>">Klasifikasi <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th align="left" width='100'><a href="<?php echo site_url("analisis_laporan/index/$p/5")?>">Klasifikasi <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>
			<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($main as $data): ?>
			<tr>
				<td align="center" width="2"><?php echo $data['no']?></td>
				<td>
					<div class="uibutton-group">
						<a href="<?php echo site_url("analisis_laporan/kuisioner/$p/$o/$data[id]")?>" class="uibutton south fa-tipis"><span class="fa fa-list"></span> Rincian</a>
					</div>
				</td>
				<td><?php echo $data['uid']?></td>
				<td><?php echo $data['nama']?></td>
				<td align="center"><?php echo $data['jk']?></td>
				<td><?php echo $data['dusun']?></td>
				<td><?php echo $data['rw']?></td>
				<td><?php echo $data['rt']?></td>
				<td align="right"><?php echo $data['nilai']?></td>
				<td align="right"><?php echo $data['klasifikasi']?></td>
				<td></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
</table>
</div>
</form>
<div class="ui-layout-south panel bottom">
	<div class="left">
		<div class="table-info">
			<form id="paging" action="<?php echo site_url('analisis_laporan')?>" method="post">
				<a href="<?php echo site_url()?>analisis_laporan/leave" class="uibutton icon prev">Kembali</a>
				<label></label>
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
		<a href="<?php echo site_url("analisis_laporan/index/$paging->start_link/$o")?>" class="uibutton" ><span class="fa fa-fast-backward"></span> Awal</a>
		<?php endif; ?>
		<?php if($paging->prev): ?>
		<a href="<?php echo site_url("analisis_laporan/index/$paging->prev/$o")?>" class="uibutton" ><span class="fa fa-step-backward"></span> Prev</a>
		<?php endif; ?>
	</div>
	<div class="uibutton-group">
		<?php for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
		<a href="<?php echo site_url("analisis_laporan/index/$i/$o")?>" <?php jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
		<?php endfor; ?>
	</div>
	<div class="uibutton-group">
		<?php if($paging->next): ?>
		<a href="<?php echo site_url("analisis_laporan/index/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
		<?php endif; ?>
		<?php if($paging->end_link): ?>
	<a href="<?php echo site_url("analisis_laporan/index/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
		<?php endif; ?>
	</div>
</div>
</div>
</div>
</div>