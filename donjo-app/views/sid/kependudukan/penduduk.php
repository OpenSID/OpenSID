<script  TYPE='text/javascript'>
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
    <h3>Manajemen Penduduk</h3>
</div>
<div id="contentpane">    
<form id="mainform" name="mainform" action="" method="post">
<input type="hidden" name="rt" value="">
    <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
                <a href="<?php echo site_url('penduduk/form')?>" class="uibutton tipsy south" title="Tambah Data" ><span class="icon-plus-sign icon-large">&nbsp;</span>Penduduk Pendatang</a>
                
				<?php  if($grup==1){?><button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("penduduk/delete_all/$p/$o")?>')" class="uibutton chrome"><span class="icon-trash icon-large">&nbsp;</span>Hapus Data</button><?php  }?>
				
                <a href="<?php echo site_url("penduduk/cetak/$o")?>" class="uibutton" title="Cetak Data" target="_blank"><span class="icon-print icon-large">&nbsp;</span>Cetak</a>
				
				<a href="<?php echo site_url("penduduk/excel/$o")?>" class="uibutton tipsy south" title="Data Excel" target="_blank"><span class="icon-file-text icon-large">&nbsp;</span>Excel</a>
				
            </div>
        </div>
        <div class="right">
            <div class="uibutton-group">
                <a href="<?php echo site_url("penduduk_log/clear")?>" class="uibutton tipsy south" title="Log Data" ><span class="icon-book icon-large">&nbsp;</span>Log Penduduk</a>
            </div>
        </div>
            <div class="left">
                <select name="filter" onchange="formAction('mainform','<?php echo site_url('penduduk/filter')?>')">
                    <option value="">Semua</option>
                    <option value="1" <?php if($filter==1 ) :?>selected<?php endif?>>Tetap</option>
                    <option value="2" <?php if($filter==2 ) :?>selected<?php endif?>>Tidak Aktif</option>
                    <option value="3" <?php if($filter==3) :?>selected<?php endif?>>Pendatang</option>
                </select>
				
                <select name="sex" onchange="formAction('mainform','<?php echo site_url('penduduk/sex')?>')">
                    <option value="">Jenis Kelamin</option>
                    <option value="1" <?php if($sex==1 ) :?>selected<?php endif?>>Laki-Laki</option>
                    <option value="2" <?php if($sex==2 ) :?>selected<?php endif?>>Perempuan</option>
                </select>
				
<!--               
				<strong style="padding-left:20px;font-size:14px;"><?php  echo $judul_statistik; ?></strong> <select name="agama" onchange="formAction('mainform','<?php //=site_url('penduduk/agama')?>')">
                    <option value="">Agama</option>
					<?php foreach($list_agama AS $data){?>
                    <option value="<?php //=$data['id']?>" <?php if($agama == $data['id']) :?>selected<?php endif?>><?php //=$data['nama']?></option>
					<?php }?>
                </select>-->
								
                <select name="dusun" onchange="formAction('mainform','<?php echo site_url('penduduk/dusun')?>')">
                    <option value="">Dusun</option>
					<?php foreach($list_dusun AS $data){?>
                    <option value="<?php echo $data['dusun']?>" <?php if($dusun == $data['dusun']) :?>selected<?php endif?>><?php echo ununderscore(unpenetration($data['dusun']))?></option>
					<?php }?>
                </select>
				
				<?php if($dusun){?>
                <select name="rw" onchange="formAction('mainform','<?php echo site_url('penduduk/rw')?>')">
                    <option value="">RW</option>
					<?php foreach($list_rw AS $data){?>
                    <option value="<?php echo $data['rw']?>" <?php if($rw == $data['rw']) :?>selected<?php endif?>><?php echo $data['rw']?></option>
					<?php }?>
                </select>
				<?php }?>
				
				<?php if($rw){?>
                <select name="rt" onchange="formAction('mainform','<?php echo site_url('penduduk/rt')?>')">
                    <option value="">RT</option>
					<?php foreach($list_rt AS $data){?>
                    <option value="<?php echo $data['rt']?>" <?php if($rt == $data['rt']) :?>selected<?php endif?>><?php echo $data['rt']?></option>
					<?php }?>
                </select>
				<?php }?>
				
				<button href="<?php echo site_url("penduduk/ajax_adv_search")?>"  target="ajax-modalx" rel="window" header="Pencarian Spesifik"  class="uibutton tipsy south"  title="Pencarian Spesifik"><span class="icon-search icon-large">&nbsp;</span>Pencarian Spesifik</button><a href="<?php echo site_url("penduduk/clear")?>"  class="uibutton tipsy south"  title="Bersihkan Pencarian"><span class="icon-refresh icon-large">&nbsp;</span>Bersihkan</a>
			  </div>
            <div class="right">
                <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('penduduk/search')?>');$('#'+'mainform').submit();}" />
                <button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('penduduk/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="icon-search icon-large">&nbsp;</span> Cari </button>
            </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <table class="list">
	<thead>
		<tr>
			<th>No</th>
			<th><input type="checkbox" class="checkall"/></th>
			<th width="160">Aksi</th>
			<?php  if($o==2): ?>
			<th align="left" width='100'><a href="<?php echo site_url("penduduk/index/$p/1")?>">NIK<span class="ui-icon ui-icon-triangle-1-n"></span></a></th>
			<?php  elseif($o==1): ?>
			<th align="left" width='100'><a href="<?php echo site_url("penduduk/index/$p/2")?>">NIK<span class="ui-icon ui-icon-triangle-1-s"></span></a></th>
			<?php  else: ?>
			<th align="left" width='100'><a href="<?php echo site_url("penduduk/index/$p/1")?>">NIK<span class="ui-icon ui-icon-triangle-2-n-s"></span></a></th>
			<?php  endif; ?>

			<?php  if($o==4): ?>
			<th align="left"><a href="<?php echo site_url("penduduk/index/$p/3")?>">Nama<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<?php  elseif($o==3): ?>
			<th align="left"><a href="<?php echo site_url("penduduk/index/$p/4")?>">Nama<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<?php  else: ?>
			<th align="left"><a href="<?php echo site_url("penduduk/index/$p/3")?>">Nama<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<?php  endif; ?>
			
			<th width="100" align="left">
			<?php  if($o==6): ?>
			<a href="<?php echo site_url("penduduk/index/$p/5")?>">No. KK<span class="ui-icon ui-icon-triangle-1-n">
			<?php  elseif($o==5): ?>
			<a href="<?php echo site_url("penduduk/index/$p/6")?>">No. KK<span class="ui-icon ui-icon-triangle-1-s">
			<?php  else: ?><a href="<?php echo site_url("penduduk/index/$p/5")?>">No. KK<span class="ui-icon ui-icon-triangle-2-n-s">
			<?php  endif; ?>
			&nbsp;</span></a></th>

			<th align="left" align="center">Dusun</th>
			<th align="left" align="center">RW</th>
			<th align="left" align="center">RT</th>
			<th align="left" align="center">Pendidikan dalam KK</th>
			
			<th width="50" align="left">
			<?php  if($o==8): ?>
			<a href="<?php echo site_url("penduduk/index/$p/7")?>">Umur<span class="ui-icon ui-icon-triangle-1-n">
			<?php  elseif($o==7): ?>
			<a href="<?php echo site_url("penduduk/index/$p/8")?>">Umur<span class="ui-icon ui-icon-triangle-1-s">
			<?php  else: ?><a href="<?php echo site_url("penduduk/index/$p/7")?>">Umur<span class="ui-icon ui-icon-triangle-2-n-s">
			<?php  endif; ?>
			&nbsp;</span></a></th>
			
			<th align="left">Pekerjaan</th>
			<th width="75" align="left">Kawin</th>
			<th align="left">Status</th>
							
		</tr>
</thead>
<tbody>
        <?php  foreach($main as $data): ?>
<tr>
          <td align="center" width="2"><?php echo $data['no']?></td>
<td align="center" width="5">
<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
</td>
<td> <div class="uibutton-group">
<a href="<?php echo site_url("penduduk/detail/$p/$o/$data[id]")?>"  class="uibutton tipsy south"  title="Rincian Data Penduduk"> <span  class="icon-zoom-in icon-large"> Rincian </span></a>
<a href="<?php echo site_url("penduduk/form/$p/$o/$data[id]")?>"  class="uibutton tipsy south"  title="Ubah Data"> <span  class="icon-edit icon-large"></span> </a>


<a href="<?php echo site_url("penduduk/edit_status_dasar/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Ubah Status Dasar" target="ajax-modal" rel="window" header="Ubah Status Dasar"><span class="icon-wrench icon-large"></span></a>
<a href="<?php echo site_url("penduduk/ajax_penduduk_pindah/$data[id]")?>" class="uibutton tipsy south" title="Pindah Penduduk dalam Desa" target="ajax-modal" rel="window" header="pindah penduduk"><span class="icon-share icon-large"></span></a>
<?php  if($grup==1){?><a href="<?php echo site_url("penduduk/delete/$p/$o/$data[id]")?>" class="uibutton tipsy south"  title="Hapus Data"  target="confirm" message="Apakah Anda Yakin?" rel="window" header="Hapus Data"><span class="icon-trash icon-large"></span></a><?php  }?></div>
</td>
<td><a href="<?php echo site_url("penduduk/detail/$p/$o/$data[id]")?>" id="test" name="<?php echo $data['id']?>"><?php echo $data['nik']?></a></td>
<td><a href="<?php echo site_url("penduduk/detail/$p/$o/$data[id]")?>"><?php echo strtoupper(unpenetration($data['nama']))?></a></td>
<td><a href="<?php echo site_url("keluarga/kartu_keluarga/$p/$o/$data[id_kk]")?>"><?php echo $data['no_kk']?> </a> </td>
<td><?php echo strtoupper(unpenetration(ununderscore($data['dusun'])))?></td>
<td><?php echo $data['rw']?></td>
<td><?php echo $data['rt']?></td>
<td><?php echo $data['pendidikan']?></td>
<td><?php echo $data['umur']?></td>
<td><?php echo $data['pekerjaan']?></td>
<td><?php echo $data['kawin']?></td>
   
  <td><?php if($data['status']==1){echo "Tetap";}elseif($data['status']==2){echo "Tidak Aktif";}else{echo "Pendatang";}?></td>
  </tr>
        <?php  endforeach; ?>
</tbody>
        </table>
    </div>
</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
<div class="table-info">
          <form id="paging" action="<?php echo site_url('penduduk')?>" method="post">
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
<a href="<?php echo site_url("penduduk/index/$paging->start_link/$o")?>" class="uibutton">Awal</a>
<?php  endif; ?>
<?php  if($paging->prev): ?>
<a href="<?php echo site_url("penduduk/index/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
<?php  endif; ?>
            </div>
            <div class="uibutton-group">
                
<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
<a href="<?php echo site_url("penduduk/index/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
<?php  endfor; ?>
            </div>
            <div class="uibutton-group">
<?php  if($paging->next): ?>
<a href="<?php echo site_url("penduduk/index/$paging->next/$o")?>" class="uibutton">Next</a>
<?php  endif; ?>
<?php  if($paging->end_link): ?>
                <a href="<?php echo site_url("penduduk/index/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
<?php  endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
