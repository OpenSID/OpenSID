<div id="pageC">
	<table class="inner">
<tr style="vertical-align:top">
		<td style="background:#fff;padding:0px;"> 
<div class="content-header">
</div>
<div id="contentpane"> 
<div class="ui-layout-north panel top">
		<form id="mainform" name="mainform" action="" method="post">
		<h4><?php echo $analisis_statistik_pertanyaan['pertanyaan']?></h4>
		<h4><?php echo $analisis_statistik_jawaban['jawaban']?></h4>
		
<div class="left">
			<a href="<?php echo site_url("analisis_statistik_jawaban/cetak2/$analisis_statistik_pertanyaan[id]/$analisis_statistik_jawaban[id]")?>" class="uibutton special tipsy south" title="Cetak Data" target="_blank"><span class="fa fa-print">&nbsp;</span>Cetak</a>
			<a href="<?php echo site_url("analisis_statistik_jawaban/excel2/$analisis_statistik_pertanyaan[id]/$analisis_statistik_jawaban[id]")?>" class="uibutton special tipsy south" title="Data Excel" target="_blank"><span class="fa fa-file-text">&nbsp;</span>Excel</a>
				
			<select name="dusun" onchange="formAction('mainform','<?php echo site_url("analisis_statistik_jawaban/dusun2/$analisis_statistik_pertanyaan[id]/$analisis_statistik_jawaban[id]")?>')">
					<option value="">Dusun</option>
					<?php foreach($list_dusun AS $data){?>
					<option value="<?php echo $data['dusun']?>" <?php if($dusun == $data['dusun']) :?>selected<?php endif?>><?php echo ununderscore(unpenetration($data['dusun']))?></option>
					<?php }?>
				</select>
				
				<?php if($dusun){?>
				<select name="rw" onchange="formAction('mainform','<?php echo site_url("analisis_statistik_jawaban/rw2/$analisis_statistik_pertanyaan[id]/$analisis_statistik_jawaban[id]")?>')">
					<option value="">RW</option>
					<?php foreach($list_rw AS $data){?>
					<option value="<?php echo $data['rw']?>" <?php if($rw == $data['rw']) :?>selected<?php endif?>><?php echo $data['rw']?></option>
					<?php }?>
				</select>
				<?php }?>
				
				<?php if($rw){?>
				<select name="rt" onchange="formAction('mainform','<?php echo site_url("analisis_statistik_jawaban/rt2/$analisis_statistik_pertanyaan[id]/$analisis_statistik_jawaban[id]")?>')">
					<option value="">RT</option>
					<?php foreach($list_rt AS $data){?>
					<option value="<?php echo $data['rt']?>" <?php if($rt == $data['rt']) :?>selected<?php endif?>><?php echo $data['rt']?></option>
					<?php }?>
				</select>
				<?php }?>
			</div>
	</form>
</div>
				
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
				
<table class="list">
		<thead>
<tr>
<th>No</th>
				<th align="center" width='100'>NIK</th>
				<th align="center" width='250'>Nama</th>
			<th width='80'>Dusun</th>
			<th width='40'>RW</th>
			<th width='40'>RT</th>
				<th align="center" width='50'>Umur (Tahun)</th>
				<th align="center" width='80'>J. Kelamin</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
<?php foreach($main as $data): ?>
		<tr>
<td align="center" width="2"><?php echo $data['no']?></td>
<td><a href="<?php echo site_url("penduduk/detail/1/0/$data[id_pend]");?>" target="_blank"><?php echo $data['nik']?></a></td>
<td><a href="<?php echo site_url("penduduk/detail/1/0/$data[id_pend]");?>" target="_blank"><?php echo $data['nama']?></a></td>
			<td><?php echo strtoupper(ununderscore($data['dusun']))?></td>
			<td><?php echo $data['rw']?></td>
			<td><?php echo $data['rt']?></td>
<td><?php echo $data['umur']?></td>
<td><?php echo $data['sex']?></td>
<td></td>
		 </tr>
<?php endforeach; ?>
		</tbody>
</table> 
</div>
<div class="ui-layout-south panel bottom">
<div class="left"> 
			<a href="<?php echo site_url()?>analisis_statistik_jawaban" class="uibutton icon prev">Kembali</a>
</div>
</div>
</div>
</div>
</div>