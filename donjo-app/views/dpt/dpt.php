<script  TYPE='text/javascript'>
$(function() {
var keyword = <?php echo $keyword?> ;
$( "#cari" ).autocomplete({
source: keyword
});
});
</script>
<style type="text/css">

</style>

<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">

<td style="background:#fff;padding:0px;">
<div class="content-header">
    <h3>Manajemen Penduduk</h3>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="" method="post">
<input type="hidden" name="rt" value="">
  <div class="ui-layout-north panel">
    <div>
    	<p style="float: left; margin-left: 5px;"><strong>Tanggal Pemilihan: </strong><input id="tanggal_pemilihan" name="tanggal_pemilihan" type="text" class="inputbox datepicker" size="12" style="margin-left: 10px;" onchange="$('#mainform').attr('action', '<?php echo site_url('dpt/index/1/'.$o)?>'); $('#mainform').submit();" value="<?php echo $_SESSION['tanggal_pemilihan']?>"/></p>
    	<h3 style="float: none; text-align: center;">DAFTAR CALON PEMILIH UNTUK TANGGAL PEMILIHAN <?php echo $_SESSION['tanggal_pemilihan']?></h3>
    </div>
  	<div style="clear:both;"></div>
    <div class="left">
      <div class="uibutton-group">
        <a href="<?php echo site_url("dpt/cetak/$o")?>" class="uibutton tipsy south" title="Cetak Data" target="_blank"><span class="fa fa-print">&nbsp;</span>Cetak</a>
				<a href="<?php echo site_url("dpt/excel/$o")?>" class="uibutton tipsy south" title="Data Excel" target="_blank"><span class="fa fa-file-text">&nbsp;</span>Excel</a>
      </div>
    </div>
    <div class="right">
      <select name="sex" onchange="formAction('mainform','<?php echo site_url('dpt/sex/1/'.$o)?>')">
          <option value="">Jenis Kelamin</option>
          <option value="1" <?php if($sex==1 ) :?>selected<?php endif?>>Laki-Laki</option>
          <option value="2" <?php if($sex==2 ) :?>selected<?php endif?>>Perempuan</option>
      </select>

      <select name="dusun" onchange="formAction('mainform','<?php echo site_url('dpt/dusun/1/'.$o)?>')">
        <option value=""><?php echo ucwords($this->setting->sebutan_dusun)?></option>
				<?php foreach($list_dusun AS $data){?>
          <option value="<?php echo $data['dusun']?>" <?php if($dusun == $data['dusun']) :?>selected<?php endif?>><?php echo ununderscore(unpenetration($data['dusun']))?></option>
				<?php }?>
      </select>

			<?php if($dusun){?>
        <select name="rw" onchange="formAction('mainform','<?php echo site_url('dpt/rw/1/'.$o)?>')">
          <option value="">RW</option>
					<?php foreach($list_rw AS $data){?>
            <option value="<?php echo $data['rw']?>" <?php if($rw == $data['rw']) :?>selected<?php endif?>><?php echo $data['rw']?></option>
					<?php }?>
        </select>
			<?php }?>

			<?php if($rw){?>
        <select name="rt" onchange="formAction('mainform','<?php echo site_url('dpt/rt/1/'.$o)?>')">
          <option value="">RT</option>
					<?php foreach($list_rt AS $data){?>
            <option value="<?php echo $data['rt']?>" <?php if($rt == $data['rt']) :?>selected<?php endif?>><?php echo $data['rt']?></option>
					<?php }?>
        </select>
			<?php }?>
			<button href="<?php echo site_url("dpt/ajax_adv_search")?>"  target="ajax-modalx" rel="window" header="Pencarian Spesifik"  class="uibutton tipsy south"  title="Pencarian Spesifik"><span class="fa fa-search">&nbsp;</span>Pencarian Spesifik</button><a href="<?php echo site_url("dpt/clear")?>"  class="uibutton tipsy south"  title="Bersihkan Pencarian"><span class="fa fa-refresh">&nbsp;</span>Bersihkan</a>

      <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" style="margin-left: 100px;" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('dpt/search')?>');$('#'+'mainform').submit();}" />
      <button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('dpt/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="fa fa-search">&nbsp;</span> Cari </button>
    </div>
  </div>
  <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <table class="list">
	<thead>
    <?php if ($judul_statistik): ?>
      <tr>
        <td colspan="15" style="text-align: center;"><strong style="font-size:14px;"><?php  echo $judul_statistik; ?></strong></td>
      </tr>
    <?php endif; ?>
		<tr>
			<th>No</th>
			<?php  if($o==2): ?>
			<th align="center" width='100'><a href="<?php echo site_url("dpt/index/$p/1")?>">NIK <span class="fa fa-sort-asc fa-sm"></span></a></th>
			<?php  elseif($o==1): ?>
			<th align="center" width='100'><a href="<?php echo site_url("dpt/index/$p/2")?>">NIK <span class="fa fa-sort-desc fa-sm"></span></a></th>
			<?php  else: ?>
			<th align="center" width='100'><a href="<?php echo site_url("dpt/index/$p/1")?>">NIK <span class="fa fa-sort fa-sm"></span></a></th>
			<?php  endif; ?>

			<?php  if($o==4): ?>
			<th align="center"><a href="<?php echo site_url("dpt/index/$p/3")?>">Nama <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php  elseif($o==3): ?>
			<th align="center"><a href="<?php echo site_url("dpt/index/$p/4")?>">Nama <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php  else: ?>
			<th align="center"><a href="<?php echo site_url("dpt/index/$p/3")?>">Nama <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php  endif; ?>

			<th width="100" align="center">
			<?php  if($o==6): ?>
			<a href="<?php echo site_url("dpt/index/$p/5")?>">No. KK <span class="fa fa-sort-asc fa-sm">
			<?php  elseif($o==5): ?>
			<a href="<?php echo site_url("dpt/index/$p/6")?>">No. KK <span class="fa fa-sort-desc fa-sm">
			<?php  else: ?><a href="<?php echo site_url("dpt/index/$p/5")?>">No. KK <span class="fa fa-sort fa-sm">
			<?php  endif; ?>
			&nbsp;</span></a></th>

			<th align="center" align="center">Alamat</th>
            <th align="center" align="center"><?php echo ucwords($this->setting->sebutan_dusun)?></th>
            <th align="center" align="center">RW</th>
            <th align="center" align="center">RT</th>
			<th align="center" align="center">Pendidikan dalam KK</th>

			<th align="center">
				<?php  if($o==8): ?>
				<a href="<?php echo site_url("dpt/index/$p/7")?>">Umur Pada <?php echo $_SESSION['tanggal_pemilihan']?><span class="fa fa-sort-asc fa-sm">
				<?php  elseif($o==7): ?>
				<a href="<?php echo site_url("dpt/index/$p/8")?>">Umur Pada <?php echo $_SESSION['tanggal_pemilihan']?><span class="fa fa-sort-desc fa-sm">
				<?php  else: ?><a href="<?php echo site_url("dpt/index/$p/7")?>">Umur Pada <?php echo $_SESSION['tanggal_pemilihan']?><span class="fa fa-sort fa-sm">
				<?php  endif; ?>
				&nbsp;</span></a>
			</th>

			<th align="center">Pekerjaan</th>
			<th width="75" align="center">Kawin</th>

		</tr>
</thead>
<tbody>
<?php  foreach($main as $data): ?>
	<tr>
    <td align="center" width="2"><?php echo $data['no']?></td>
		<td align="center"><a href="<?php echo site_url("penduduk/detail/$p/$o/$data[id]")?>" id="test" name="<?php echo $data['id']?>"><?php echo $data['nik']?></a></td>
		<td><a href="<?php echo site_url("penduduk/detail/$p/$o/$data[id]")?>"><?php echo strtoupper(unpenetration($data['nama']))?></a></td>
		<td align="center"><a href="<?php echo site_url("keluarga/kartu_keluarga/$p/$o/$data[id_kk]")?>"><?php echo $data['no_kk']?> </a> </td>
		<td><?php echo strtoupper($data['alamat'])?></td>
		<td><?php echo strtoupper(unpenetration(ununderscore($data['dusun'])))?></td>
		<td align="center"><?php echo $data['rw']?></td>
		<td align="center"><?php echo $data['rt']?></td>
		<td><?php echo $data['pendidikan']?></td>
		<td align="center"><?php echo $data['umur_pada_pemilihan']?></td>
		<td><?php echo $data['pekerjaan']?></td>
		<td><?php echo $data['kawin']?></td>
  </tr>
<?php  endforeach; ?>
</tbody>
        </table>
    </div>
</form>
    <div class="ui-layout-south panel bottom">
        <div class="left">
<div class="table-info">
          <form id="paging" action="<?php echo site_url('dpt')?>" method="post">
  <label>Tampilkan</label>
            <select name="per_page" onchange="$('#paging').submit()" >
              <option value="50" <?php  selected($per_page,50); ?> >50</option>
              <option value="100" <?php  selected($per_page,100); ?> >100</option>
              <option value="200" <?php  selected($per_page,200); ?> >200</option>
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
				<a href="<?php echo site_url("dpt/index/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
				<?php  endif; ?>
				<?php  if($paging->prev): ?>
				<a href="<?php echo site_url("dpt/index/$paging->prev/$o")?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
				<?php  endif; ?>
			</div>
			<div class="uibutton-group">
				<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("dpt/index/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php  endfor; ?>
			</div>
			<div class="uibutton-group">
				<?php  if($paging->next): ?>
				<a href="<?php echo site_url("dpt/index/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
				<?php  endif; ?>
				<?php  if($paging->end_link): ?>
				<a href="<?php echo site_url("dpt/index/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
				<?php  endif; ?>
			</div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
