<script>
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
<td class="side-menu">
<div class="lmenu">
<ul>
<li class="selected"><a href="<?=site_url('keluar')?>">Surat Keluar</a></li>
<li ><a href="<?=site_url('keluar/perorangan')?>">Rekam Surat Perorangan</a></li>
<li ><a href="<?=site_url('keluar/graph')?>">Grafik Surat keluar</a></li>
</ul>
</div>

</td>
</td>
<td style="background:#fff;padding:5px;"> 
<div class="content-header">
    
</div>
<div id="contentpane">
<div class="ui-layout-north panel">
<h3>Manajemen Surat Keluar</h3>
</div>
    
<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">

  <div class="right">
      <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?=$cari?>" title="Cari.."onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?=site_url('keluar/search')?>');$('#'+'mainform').submit();}" />
      <button type="button" onclick="$('#'+'mainform').attr('action','<?=site_url('keluar/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="icon-search icon-large">&nbsp;</span>Cari</button>
  </div>
        </div>
        <table class="list">
<thead>
  <tr>
      <th>No</th>
    
    

<? if($o==2): ?>
<th align="left" width='100'>Nomor Surat</th>
<? elseif($o==1): ?>
<th align="left" width='100'>Nomor Surat</th>
<? else: ?>
<th align="left" width='100'>Nomor Surat</th>
<? endif; ?>

<th align="left">Jenis Surat</th>

 <? if($o==4): ?>
<th align="left">Nama Penduduk</th>
<? elseif($o==3): ?>
<th align="left">Nama Penduduk</th>
<? else: ?>
<th align="left">Nama Penduduk</th>
<? endif; ?>

<th align="left" width='160'>Nama Staf Pemerintah Desa</th>

<? if($o==6): ?>
<th align="left" width='160'>Tanggal</th>
<? elseif($o==5): ?>
<th align="left" width='160'>Tanggal</th>
<? else: ?>
<th align="left" width='160'>Tanggal</th>
<? endif; ?>
  
</tr>
</thead>
<tbody>
        <? foreach($main as $data): ?>
<tr>
<td align="center" width="2"><?=$data['no']?></td>
<td><?=$data['no_surat']?></td>
<td><?=$data['format']?></td>
<td><?=unpenetration($data['nama'])?></td>
<td><?=$data['pamong']?></td>
<td><?=tgl_indo2($data['tanggal'])?></td>
  </tr>
        <? endforeach; ?>
</tbody>
        </table>
    </div>
</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
<div class="table-info">
<form id="paging" action="<?=site_url('keluar')?>" method="post">
  <label>Tampilkan</label>
  <select name="per_page" onchange="$('#paging').submit()" >
    <option value="20" <? selected($per_page,20); ?> >20</option>
    <option value="50" <? selected($per_page,50); ?> >50</option>
    <option value="100" <? selected($per_page,100); ?> >100</option>
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
<a href="<?=site_url("keluar/index/$paging->start_link/$o")?>" class="uibutton"  >First</a>
<? endif; ?>
<? if($paging->prev): ?>
<a href="<?=site_url("keluar/index/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
<? endif; ?>
  </div>
  <div class="uibutton-group">
      
<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
<a href="<?=site_url("keluar/index/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
<? endfor; ?>
  </div>
  <div class="uibutton-group">
<? if($paging->next): ?>
<a href="<?=site_url("keluar/index/$paging->next/$o")?>" class="uibutton">Next</a>
<? endif; ?>
<? if($paging->end_link): ?>
      <a href="<?=site_url("keluar/index/$paging->end_link/$o")?>" class="uibutton">Last</a>
<? endif; ?>
  </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
