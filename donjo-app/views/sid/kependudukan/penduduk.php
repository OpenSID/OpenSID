<script  TYPE='text/javascript'>
$(function() {
var keyword = <?=$keyword?> ;
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
                <a href="<?=site_url('penduduk/form')?>" class="uibutton tipsy south" title="Tambah Data" ><span class="icon-plus-sign icon-large">&nbsp;</span>Penduduk Pendatang</a>
                
				<? if($grup==1){?><button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?=site_url("penduduk/delete_all/$p/$o")?>')" class="uibutton chrome"><span class="icon-trash icon-large">&nbsp;</span>Hapus Data</button><? }?>
				
                <a href="<?=site_url("penduduk/cetak/$o")?>" class="uibutton" title="Cetak Data" target="_blank"><span class="icon-print icon-large">&nbsp;</span>Cetak</a>
				
				<a href="<?=site_url("penduduk/excel/$o")?>" class="uibutton tipsy south" title="Data Excel" target="_blank"><span class="icon-file-text icon-large">&nbsp;</span>Excel</a>
				
            </div>
        </div>
        <div class="right">
            <div class="uibutton-group">
                <a href="<?=site_url("penduduk_log/clear")?>" class="uibutton tipsy south" title="Log Data" ><span class="icon-book icon-large">&nbsp;</span>Log Penduduk</a>
            </div>
        </div>
            <div class="left">
                <select name="filter" onchange="formAction('mainform','<?=site_url('penduduk/filter')?>')">
                    <option value="">Semua</option>
                    <option value="1" <?if($filter==1 ) :?>selected<?endif?>>Tetap</option>
                    <option value="2" <?if($filter==2 ) :?>selected<?endif?>>Tidak Aktif</option>
                    <option value="3" <?if($filter==3) :?>selected<?endif?>>Pendatang</option>
                </select>
				
                <select name="sex" onchange="formAction('mainform','<?=site_url('penduduk/sex')?>')">
                    <option value="">Jenis Kelamin</option>
                    <option value="1" <?if($sex==1 ) :?>selected<?endif?>>Laki-Laki</option>
                    <option value="2" <?if($sex==2 ) :?>selected<?endif?>>Perempuan</option>
                </select>
				
<!--               
				<strong style="padding-left:20px;font-size:14px;"><? echo $judul_statistik; ?></strong> <select name="agama" onchange="formAction('mainform','<?//=site_url('penduduk/agama')?>')">
                    <option value="">Agama</option>
					<?foreach($list_agama AS $data){?>
                    <option value="<?//=$data['id']?>" <?if($agama == $data['id']) :?>selected<?endif?>><?//=$data['nama']?></option>
					<?}?>
                </select>-->
								
                <select name="dusun" onchange="formAction('mainform','<?=site_url('penduduk/dusun')?>')">
                    <option value="">Dusun</option>
					<?foreach($list_dusun AS $data){?>
                    <option value="<?=$data['dusun']?>" <?if($dusun == $data['dusun']) :?>selected<?endif?>><?=ununderscore(unpenetration($data['dusun']))?></option>
					<?}?>
                </select>
				
				<?if($dusun){?>
                <select name="rw" onchange="formAction('mainform','<?=site_url('penduduk/rw')?>')">
                    <option value="">RW</option>
					<?foreach($list_rw AS $data){?>
                    <option value="<?=$data['rw']?>" <?if($rw == $data['rw']) :?>selected<?endif?>><?=$data['rw']?></option>
					<?}?>
                </select>
				<?}?>
				
				<?if($rw){?>
                <select name="rt" onchange="formAction('mainform','<?=site_url('penduduk/rt')?>')">
                    <option value="">RT</option>
					<?foreach($list_rt AS $data){?>
                    <option value="<?=$data['rt']?>" <?if($rt == $data['rt']) :?>selected<?endif?>><?=$data['rt']?></option>
					<?}?>
                </select>
				<?}?>
				
				<button href="<?=site_url("penduduk/ajax_adv_search")?>"  target="ajax-modalx" rel="window" header="Pencarian Spesifik"  class="uibutton tipsy south"  title="Pencarian Spesifik"><span class="icon-search icon-large">&nbsp;</span>Pencarian Spesifik</button><a href="<?=site_url("penduduk/clear")?>"  class="uibutton tipsy south"  title="Bersihkan Pencarian"><span class="icon-refresh icon-large">&nbsp;</span>Bersihkan</a>
			  </div>
            <div class="right">
                <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?=$cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?=site_url('penduduk/search')?>');$('#'+'mainform').submit();}" />
                <button type="button" onclick="$('#'+'mainform').attr('action','<?=site_url('penduduk/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="icon-search icon-large">&nbsp;</span> Cari </button>
            </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <table class="list">
	<thead>
		<tr>
			<th>No</th>
			<th><input type="checkbox" class="checkall"/></th>
			<th width="150">Aksi</th>
			<? if($o==2): ?>
			<th align="left" width='100'><a href="<?=site_url("penduduk/index/$p/1")?>">NIK<span class="ui-icon ui-icon-triangle-1-n"></span></a></th>
			<? elseif($o==1): ?>
			<th align="left" width='100'><a href="<?=site_url("penduduk/index/$p/2")?>">NIK<span class="ui-icon ui-icon-triangle-1-s"></span></a></th>
			<? else: ?>
			<th align="left" width='100'><a href="<?=site_url("penduduk/index/$p/1")?>">NIK<span class="ui-icon ui-icon-triangle-2-n-s"></span></a></th>
			<? endif; ?>

			<? if($o==4): ?>
			<th align="left"><a href="<?=site_url("penduduk/index/$p/3")?>">Nama<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==3): ?>
			<th align="left"><a href="<?=site_url("penduduk/index/$p/4")?>">Nama<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
			<th align="left"><a href="<?=site_url("penduduk/index/$p/3")?>">Nama<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
			
			<th width="100" align="left">
			<? if($o==6): ?>
			<a href="<?=site_url("penduduk/index/$p/5")?>">No. KK<span class="ui-icon ui-icon-triangle-1-n">
			<? elseif($o==5): ?>
			<a href="<?=site_url("penduduk/index/$p/6")?>">No. KK<span class="ui-icon ui-icon-triangle-1-s">
			<? else: ?><a href="<?=site_url("penduduk/index/$p/5")?>">No. KK<span class="ui-icon ui-icon-triangle-2-n-s">
			<? endif; ?>
			&nbsp;</span></a></th>

			<th align="left" align="center">Dusun</th>
			<th align="left" align="center">RW</th>
			<th align="left" align="center">RT</th>
			<th align="left" align="center">Pendidikan dalam KK</th>
			
			<th width="50" align="left">
			<? if($o==8): ?>
			<a href="<?=site_url("penduduk/index/$p/7")?>">Umur<span class="ui-icon ui-icon-triangle-1-n">
			<? elseif($o==7): ?>
			<a href="<?=site_url("penduduk/index/$p/8")?>">Umur<span class="ui-icon ui-icon-triangle-1-s">
			<? else: ?><a href="<?=site_url("penduduk/index/$p/7")?>">Umur<span class="ui-icon ui-icon-triangle-2-n-s">
			<? endif; ?>
			&nbsp;</span></a></th>
			
			<th align="left">Pekerjaan</th>
			<th width="75" align="left">Kawin</th>
			<th align="left">Status</th>
							
		</tr>
</thead>
<tbody>
        <? foreach($main as $data): ?>
<tr>
          <td align="center" width="2"><?=$data['no']?></td>
<td align="center" width="5">
<input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" />
</td>
<td> <div class="uibutton-group">
<a href="<?=site_url("penduduk/detail/$p/$o/$data[id]")?>"  class="uibutton tipsy south"  title="Rincian Data Penduduk"> <span  class="icon-zoom-in icon-large"> Rincian </span></a>
<a href="<?=site_url("penduduk/form/$p/$o/$data[id]")?>"  class="uibutton tipsy south"  title="Ubah Data"> <span  class="icon-edit icon-large"></span> </a>


<a href="<?=site_url("penduduk/edit_status_dasar/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Ubah Status Dasar" target="ajax-modal" rel="window" header="Ubah Status Dasar"><span class="icon-wrench icon-large"></span></a>
<a href="<?=site_url("penduduk/ajax_penduduk_pindah/$data[id]")?>" class="uibutton tipsy south" title="Pindah Penduduk dalam Desa" target="ajax-modal" rel="window" header="pindah penduduk"><span class="icon-share icon-large"></span></a>
<? if($grup==1){?><a href="<?=site_url("penduduk/delete/$p/$o/$data[id]")?>" class="uibutton tipsy south"  title="Hapus Data"  target="confirm" message="Apakah Anda Yakin?" rel="window" header="Hapus Data"><span class="icon-trash icon-large"></span></a><? }?></div>
</td>
<td><a href="<?=site_url("penduduk/detail/$p/$o/$data[id]")?>" id="test" name="<?=$data['id']?>"><?=$data['nik']?></a></td>
<td><a href="<?=site_url("penduduk/detail/$p/$o/$data[id]")?>"><?=strtoupper(unpenetration($data['nama']))?></a></td>
<td><a href="<?=site_url("keluarga/kartu_keluarga/$p/$o/$data[id_kk]")?>"><?=$data['no_kk']?> </a> </td>
<td><?=strtoupper(unpenetration(ununderscore($data['dusun'])))?></td>
<td><?=$data['rw']?></td>
<td><?=$data['rt']?></td>
<td><?=$data['pendidikan']?></td>
<td><?=$data['umur']?></td>
<td><?=$data['pekerjaan']?></td>
<td><?=$data['kawin']?></td>
   
  <td><?if($data['status']==1){echo "Tetap";}elseif($data['status']==2){echo "Tidak Aktif";}else{echo "Pendatang";}?></td>
  </tr>
        <? endforeach; ?>
</tbody>
        </table>
    </div>
</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
<div class="table-info">
          <form id="paging" action="<?=site_url('penduduk')?>" method="post">
  <label>Tampilkan</label>
            <select name="per_page" onchange="$('#paging').submit()" >
              <option value="50" <? selected($per_page,50); ?> >50</option>
              <option value="100" <? selected($per_page,100); ?> >100</option>
              <option value="200" <? selected($per_page,200); ?> >200</option>
            </select>
            <label>Dari</label>
            <label><strong><?=$paging->num_rows?></strong></label>
            <label>Total Data</label>
          </form>
          </div>
        </div>
        <div class="right">
            <div class="uibutton-group">
            <? if($paging->start_link): ?>
<a href="<?=site_url("penduduk/index/$paging->start_link/$o")?>" class="uibutton">Awal</a>
<? endif; ?>
<? if($paging->prev): ?>
<a href="<?=site_url("penduduk/index/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
<? endif; ?>
            </div>
            <div class="uibutton-group">
                
<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
<a href="<?=site_url("penduduk/index/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
<? endfor; ?>
            </div>
            <div class="uibutton-group">
<? if($paging->next): ?>
<a href="<?=site_url("penduduk/index/$paging->next/$o")?>" class="uibutton">Next</a>
<? endif; ?>
<? if($paging->end_link): ?>
                <a href="<?=site_url("penduduk/index/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
<? endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
