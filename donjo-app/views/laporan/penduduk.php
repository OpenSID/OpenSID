
<div id="pageC"> 
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
	<td class="side-menu">
		<fieldset><legend>Laporan : </legend>
			<div class="lmenu">
				<ul>
				<li ><a href="<?=site_url()?>sid_laporan_bulanan">Laporan Bulanan</a></li>
				<li ><a href="<?=site_url()?>sid_laporan_kelompok">Data Kelompok Rentan</a></li>
				
				</ul>
			</div>
		</fieldset>
		
		
		</td>
<td style="background:#fff;padding:0px;"> 
<div class="content-header">
    <h3>Laporan</h3>
</div>
<div id="contentpane" style="overflow:auto;">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel top">
        <div class="left">
            <div class="uibutton-group">
			
			<a href="<?=site_url("sid_laporan_penduduk/cetak/$lap")?>" class="uibutton tipsy south" title="Cetak Data" target="_blank"><span class="ui-icon ui-icon-print">&nbsp;</span>Cetak Data</a>
			
			<a href="<?=site_url("sid_laporan_penduduk/graph/$lap")?>" class="uibutton tipsy south" title="Grafik"><span class="ui-icon ui-icon-print">&nbsp;</span>Grafik Data</a>
			
			<a href="<?=site_url("sid_laporan_penduduk/pie/$lap")?>" class="uibutton tipsy south" title="Grafik"><span class="ui-icon ui-icon-print">&nbsp;</span>Pie Chart</a>
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
                <th>No</th>
				<th align="left" align="center">Statistik</th>
				<th align="left" align="center">Jumlah</th>
				<th align="left" align="center" width="60">Laki-laki</th>
				<th align="left" align="center" width="60">Perempuan</th>
            
			</tr>
		</thead>
		<tbody>
        <? foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?=$data['no']?></td>
          <td><?=$data['nama']?></td>
          <td><?=$data['jumlah']?></td>
		  <td><?=$data['laki']?></td>
          <td><?=$data['perempuan']?></td>
		  </tr>
        <? endforeach; ?>
		</tbody>
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
		<div class="table-info">
          <form id="paging" action="<?=site_url("sid_laporan_penduduk/index/$lap/")?>" method="post">
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
				<a href="<?=site_url("sid_laporan_penduduk/index/$lap/$paging->start_link/$o")?>" class="uibutton"  >First</a>
			<? endif; ?>
			<? if($paging->prev): ?>
				<a href="<?=site_url("sid_laporan_penduduk/index/$lap/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
			<? endif; ?>
            </div>
            <div class="uibutton-group">
                
				<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?=site_url("sid_laporan_penduduk/index/$lap/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
				<? endfor; ?>
            </div>
            <div class="uibutton-group">
			<? if($paging->next): ?>
				<a href="<?=site_url("sid_laporan_penduduk/index/$lap/$paging->next/$o")?>" class="uibutton">Next</a>
			<? endif; ?>
			<? if($paging->end_link): ?>
                <a href="<?=site_url("sid_laporan_penduduk/index/$lap/$paging->end_link/$o")?>" class="uibutton">Last</a>
			<? endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
