<script>
	$(function() {
		var keyword = <?=$keyword?> ;
		$( "#cari" ).autocomplete({
			source: keyword
		});
	});
</script>

<div id="pageC"> 
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">

	
<td style="background:#fff;padding:0px;"> 
<div class="content-header">
    <h3>Data Keluarga</h3>
</div>
<div id="contentpane">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
                <a href="<?=site_url('keluarga/form')?>" class="uibutton tipsy south" title="Tambah Data" ><span class="icon-plus-sign icon-large">&nbsp;</span>Tambah Data Baru</a>
                
                <a href="<?=site_url('keluarga/form_old')?>" target="ajax-modal" rel="window" header="Tambah Data Keluarga" class="uibutton tipsy south" title="Tambah Data dari penduduk yang sudah ter-input" ><span class="icon-plus icon-large">&nbsp;</span>Tambah Data</a>
                
                <? if($grup==1){?><button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?=site_url("keluarga/delete_all/$p/$o")?>')" class="uibutton tipsy south"><span class="icon-trash icon-large">&nbsp;</span>Hapus Data</button><? }?>
				
				<a href="<?=site_url("keluarga/cetak_statistik/$tipe")?>" target="_blank" class="uibutton tipsy south" title="Print Data" ><span class="icon-print icon-large">&nbsp;</span>Cetak</a>
		<a href="<?=site_url("keluarga/excel/$o")?>" target="_blank" class="uibutton tipsy south" title="Data Excel" ><span class="icon-file-text icon-large">&nbsp;</span>Excel</a>
                
            </div>
        </div>
        <div class="right">
            <div class="uibutton-group">
                
<!--				<a href="<?//=site_url("keluarga/sosial/")?>" class="uibutton confirm" title="Grafik Kelas Sosial" ><span class="icon-bar-chart icon-large">&nbsp;</span>Grafik Kelas Sosial</a>
                
				<a href="<?//=site_url("keluarga/raskin_graph/")?>" class="uibutton confirm "><span class="icon-bar-chart icon-large">&nbsp;</span>Grafik Raskin</a>
				
				<a href="<?//=site_url("keluarga/jamkesmas_graph/")?>" class="uibutton confirm "><span class="icon-bar-chart icon-large">&nbsp;</span>Grafik Jamkesmas</a>-->
            </div>
        </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">			
                
				<select name="dusun" onchange="formAction('mainform','<?=site_url('keluarga/dusun')?>')">
                    <option value="">Dusun</option>
					<?foreach($list_dusun AS $data){?>
                    <option value="<?=$data['dusun']?>" <?if($dusun == $data['dusun']) :?>selected<?endif?>><?=strtoupper(unpenetration(ununderscore($data['dusun'])))?></option>
					<?}?>
                </select>
				
				<?if($dusun){?>
                <select name="rw" onchange="formAction('mainform','<?=site_url('keluarga/rw')?>')">
                    <option value="">RW</option>
					<?foreach($list_rw AS $data){?>
                    <option value="<?=$data['rw']?>" <?if($rw == $data['rw']) :?>selected<?endif?>><?=$data['rw']?></option>
					<?}?>
                </select>
				<?}?>
				
				<?if($rw){?>
                <select name="rt" onchange="formAction('mainform','<?=site_url('keluarga/rt')?>')">
                    <option value="">RT</option>
					<?foreach($list_rt AS $data){?>
                    <option value="<?=$data['rt']?>" <?if($rt == $data['rt']) :?>selected<?endif?>><?=$data['rt']?></option>
					<?}?>
                </select>
				<?}?>
				<strong><? echo $_SESSION['judul_statistik']; ?></strong>
            </div>
            <div class="right">
                <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?=$cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?=site_url('keluarga/search')?>');$('#'+'mainform').submit();}" />
                <button type="button" onclick="$('#'+'mainform').attr('action','<?=site_url('keluarga/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="icon-search icon-large">&nbsp;</span>Cari</button>
            </div>
			
        </div>
        <table class="list">
		<thead>
            <tr>
                <th>No</th>
                <th><input type="checkbox" class="checkall"/></th>
                <th width="160">Aksi</th>
				
				<th width="150" align="left">
				<? if($o==2): ?>
				<a href="<?=site_url("keluarga/index/$p/1")?>">Nomor KK<span class="ui-icon ui-icon-triangle-1-n">
				<? elseif($o==1): ?>
				<a href="<?=site_url("keluarga/index/$p/2")?>">Nomor KK<span class="ui-icon ui-icon-triangle-1-s">
				<? else: ?>
				<a href="<?=site_url("keluarga/index/$p/1")?>">Nomor KK<span class="ui-icon ui-icon-triangle-2-n-s">
				<? endif; ?>
				&nbsp;</span></a></th>

				<th align="left">
				<? if($o==4): ?>
				<a href="<?=site_url("keluarga/index/$p/3")?>">Kepala Keluarga<span class="ui-icon ui-icon-triangle-1-n">
				<? elseif($o==3): ?>
				<a href="<?=site_url("keluarga/index/$p/4")?>">Kepala Keluarga<span class="ui-icon ui-icon-triangle-1-s">
				<? else: ?>
				<a href="<?=site_url("keluarga/index/$p/3")?>">Kepala Keluarga<span class="ui-icon ui-icon-triangle-2-n-s">
				<? endif; ?>
				&nbsp;</span></a></th>
				
				<th width="100" align="left" align="center">Jumlah Anggota</th>
				<th align="left" align="center" width="120">Dusun</th>
				<th align="left" align="center" width="30">RW</th>
				<th align="left" align="center" width="30">RT</th>
				<th align="left" align="center" width="100">Tanggal Terdaftar</th>
			</tr>
		</thead>
		<tbody>
        <? foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?=$data['no']?></td>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" />
			</td>
          <td width="5"><div class="uibutton-group">
<a href="<?=site_url("keluarga/anggota/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Rincian Anggota Keluarga"><span class="icon-list icon-large"> Rincian </span></a>
            <a href="<?=site_url("keluarga/edit_nokk/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data" target="ajax-modalx" rel="window" header="Ubah Nomor KK"><span class="icon-edit icon-large"></span></a>
			
			<a href="<?=site_url("keluarga/kartu_keluarga/$p/$o/$data[id]")?>" header="Tambah Anggota Keluarga" class="uibutton tipsy south" title="Tambah Anggota Keluarga"><span  class="icon-plus-sign-alt  icon-large"></span></a>
			<a href="<?=site_url("keluarga/ajax_penduduk_pindah/$data[id]")?>"  class="uibutton tipsy south" title="Pindah Keluarga dalam Desa" target="ajax-modal" rel="window" header="Pindah Keluarga"><span class="icon-share icon-large"></span></a>
        <? if($grup==1){?><a href="<?=site_url("keluarga/delete/$p/$o/$data[id]")?>"  class="uibutton tipsy south"  title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span  class="icon-trash icon-large"></span> </a><? } ?>
 </div> </td>
          <td><a href="<?=site_url("keluarga/kartu_keluarga/$p/$o/$data[id]")?>"> <?=$data['no_kk']?> </a></td>
		  <td><?=strtoupper(unpenetration($data['kepala_kk']))?></td>
          <td><a href="<?=site_url("keluarga/anggota/$p/$o/$data[id]")?>"><?=$data['jumlah_anggota']?></a></td>
          <td><?=strtoupper(unpenetration(ununderscore($data['dusun'])))?></td>
		  <td><?=strtoupper($data['rw'])?></td>
          <td><?=strtoupper($data['rt'])?></td>
          <td><?=tgl_indo($data['tgl_daftar'])?></td>
		  </tr>
        <? endforeach; ?>
		</tbody>
		
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
		<div class="table-info">
          <form id="paging" action="<?=site_url('keluarga')?>" method="post">
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
				<a href="<?=site_url("keluarga/index/$paging->start_link/$o")?>" class="uibutton"  >Awal</a>
			<? endif; ?>
			<? if($paging->prev): ?>
				<a href="<?=site_url("keluarga/index/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
			<? endif; ?>
            </div>
            <div class="uibutton-group">
                
				<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?=site_url("keluarga/index/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
				<? endfor; ?>
            </div>
            <div class="uibutton-group">
			<? if($paging->next): ?>
				<a href="<?=site_url("keluarga/index/$paging->next/$o")?>" class="uibutton">Next</a>
			<? endif; ?>
			<? if($paging->end_link): ?>
                <a href="<?=site_url("keluarga/index/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
			<? endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
