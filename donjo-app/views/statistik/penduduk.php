<div id="pageC"> 
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
	<td class="side-menu">		
		<legend>Statistik Keluarga</legend>
			<div id="" class="lmenu">
				<ul>
				<a href="<?=site_url()?>statistik/index/22"><li <?if($lap==22){?>class="selected"<?}?>>
					Raskin</li></a>
				<a href="<?=site_url()?>statistik/index/23"><li <?if($lap==23){?>class="selected"<?}?>>
					BLSM</li></a>
				<a href="<?=site_url()?>statistik/index/25"><li <?if($lap==25){?>class="selected"<?}?>>
					PKH</li></a>
				<a href="<?=site_url()?>statistik/index/27"><li <?if($lap==27){?>class="selected"<?}?>>
					Bedah Rumah</li></a>
				</ul>
			</div>
		<fieldset>
		<legend>Statistik Penduduk</legend>
			<div id="sidecontent2" class="lmenu">
				<ul>		
				<a href="<?=site_url()?>statistik/index/13"><li <?if($lap==13){?>class="selected"<?}?>>
					Umur</li></a>	
				<a href="<?=site_url()?>statistik/index/0"><li <?if($lap==0){?>class="selected"<?}?>>
					Pendidikan dalam KK</li></a>
				<a href="<?=site_url()?>statistik/index/14"><li <?if($lap==14){?>class="selected"<?}?>>
					Pendidikan sedang Ditempuh</a></li>
				<a href="<?=site_url()?>statistik/index/1"><li <?if($lap==1){?>class="selected"<?}?>>
					Pekerjaan</li></a>
				<a href="<?=site_url()?>statistik/index/2"><li <?if($lap==2){?>class="selected"<?}?>>
					Status Perkawinan</li></a>
				<a href="<?=site_url()?>statistik/index/3"><li <?if($lap==3){?>class="selected"<?}?>>
					Agama</li></a>
				<a href="<?=site_url()?>statistik/index/4"><li <?if($lap==4){?>class="selected"<?}?>>
					Jenis Kelamin</li></a>
				<a href="<?=site_url()?>statistik/index/5"><li <?if($lap==5){?>class="selected"<?}?>>
					Warga Negara</li></a>
				<a href="<?=site_url()?>statistik/index/6"><li <?if($lap==6){?>class="selected"<?}?>>
					Status Penduduk</li></a>
				<a href="<?=site_url()?>statistik/index/7"><li <?if($lap==7){?>class="selected"<?}?>>
					Golongan Darah</li></a>	
				<a href="<?=site_url()?>statistik/index/9"><li <?if($lap==9){?>class="selected"<?}?>>
					Penyandang Cacat</li></a>
				<?/*<a href="<?=site_url()?>statistik/index/10"><li <?if($lap==10){?>class="selected"<?}?>>
					Sakit Menahun</li></a>	*/?>	
				<a href="<?=site_url()?>statistik/index/11"><li <?if($lap==11){?>class="selected"<?}?>>
					Penerima Jamkesmas</li></a>	
				<!--<a href="<?=site_url()?>statistik/index/15"><li <?if($lap==15){?>class="selected"<?}?>>
					Umur</li></a>	-->
				</ul>
			</div>
		</fieldset>
	</td>
<td style="background:#fff;padding:0px;"> 
<div class="content-header">
    <h3>Statistik</h3>
</div>
<div id="contentpane" style="overflow:auto;">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel top">
        <div class="left">
            <div class="uibutton-group">
			<a href="<?=site_url("statistik/cetak/$lap")?>" class="uibutton tipsy south" title="Cetak Data" target="_blank"><span class="icon-print icon-large">&nbsp;</span>Cetak Data</a>
			<a href="<?=site_url("statistik/excel/$lap")?>" class="uibutton tipsy south" title="Data Excel" target="_blank"><span class="icon-file-text icon-large">&nbsp;</span>Data Excel</a>
			<a href="<?=site_url("statistik/graph/$lap")?>" class="uibutton tipsy south" title="Grafik"><span class="icon-bar-chart icon-large">&nbsp;</span>Grafik Data</a>
			<a href="<?=site_url("statistik/pie/$lap")?>" class="uibutton tipsy south" title="Grafik"><span class="icon-time icon-large">&nbsp;</span>Pie Chart</a>
			<? if($lap=='13'){?>
			<a href="<?=site_url("statistik/rentang_umur")?>" class="uibutton tipsy south" title="Rentang Umut"><span class="icon-resize-horizontal icon-large">&nbsp;</span>Atur Rentang Umur</a><? }?>
            </div>
        </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">
            </div>
            <div class="right">
            </div>
			<h4 align="center">Tabel Data Kependudukan menurut <?=($stat);?></h4>
         </div>
       <table class="list">
		<thead>
            <tr>
                <th>No</th>
				
	 		<? if($o==2): ?>
				<th align="left"><a href="<?=site_url("statistik/index/$lap/$p/1")?>">Jenis Kelompok<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==1): ?>
				<th align="left"><a href="<?=site_url("statistik/index/$lap/$p/2")?>">Jenis Kelompok<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left"><a href="<?=site_url("statistik/index/$lap/$p/1")?>">Jenis Kelompok<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
			
	 		<? if($o==6): ?>
				<th width="100" align="left"><a href="<?=site_url("statistik/index/$lap/$p/5")?>">Jumlah <span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==5): ?>
				<th width="100" align="left"><a href="<?=site_url("statistik/index/$lap/$p/6")?>">Jumlah <span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th width="100" align="left"><a href="<?=site_url("statistik/index/$lap/$p/5")?>">Jumlah <span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
                <th width="5"></th>
				
		<? if($lap<20){?>
	 		<? if($o==4): ?>
				<th width="80" align="left"><a href="<?=site_url("statistik/index/$lap/$p/3")?>">Laki-Laki<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==3): ?>
				<th width="80" align="left"><a href="<?=site_url("statistik/index/$lap/$p/4")?>">Laki-Laki<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th width="80" align="left"><a href="<?=site_url("statistik/index/$lap/$p/3")?>">Laki-Laki<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
                <th width="5"></th>
						
	 		<? if($o==8): ?>
				<th width="80" align="left"><a href="<?=site_url("statistik/index/$lap/$p/7")?>">Perempuan<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==7): ?>
				<th width="80" align="left"><a href="<?=site_url("statistik/index/$lap/$p/8")?>">Perempuan<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th width="80" align="left"><a href="<?=site_url("statistik/index/$lap/$p/7")?>">Perempuan<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
		<? endif; ?>
                <th width="5"></th>
			
            	<? }?>
			</tr>
		</thead>
		<tbody>

        <? foreach($main as $data): ?>
		<tr>
            <td align="center" width="2"><?=$data['no']?></td>
            <td><?=$data['nama'];?></td>
			<td align="right">
			<? if($lap==21 OR $lap==22 OR $lap==23 OR $lap==24 OR $lap==25 OR $lap==26 OR $lap==27){?>
			<a href="<?=site_url("keluarga/statistik/$lap/$data[id]")?>"><?=$data['jumlah']?></a>
			<? } else { ?>
			<a href="<?=site_url("penduduk/statistik/$lap/$data[id]")?>/0"><?=$data['jumlah']?></a>
			<?}?>
			</td>
            <td><?=$data['persen'];?></td>
		<? if($lap<20){?>
		  <td align="right"><a href="<?=site_url("penduduk/statistik/$lap/$data[id]")?>/1"><?=$data['laki']?></a></td>
            <td><?=$data['persen1'];?></td>
          <td align="right"><a href="<?=site_url("penduduk/statistik/$lap/$data[id]")?>/2"><?=$data['perempuan']?></a></td>
            <td><?=$data['persen2'];?></td>
		<? }?>
		  </tr>
        <? endforeach; ?>

		</tbody>
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
    <?/*    <div class="left"> 
		<div class="table-info">
          <form id="paging" action="<?=site_url("statistik/index/$lap/")?>" method="post">
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
				<a href="<?=site_url("statistik/index/$lap/$paging->start_link/$o")?>" class="uibutton"  >Awal</a>
			<? endif; ?>
			<? if($paging->prev): ?>
				<a href="<?=site_url("statistik/index/$lap/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
			<? endif; ?>
            </div>
            <div class="uibutton-group">
                
				<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?=site_url("statistik/index/$lap/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
				<? endfor; ?>
            </div>
            <div class="uibutton-group">
			<? if($paging->next): ?>
				<a href="<?=site_url("statistik/index/$lap/$paging->next/$o")?>" class="uibutton">Next</a>
			<? endif; ?>
			<? if($paging->end_link): ?>
                <a href="<?=site_url("statistik/index/$lap/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
			<? endif; ?>
            </div>
        </div>*/?>
    </div>
</div>
</td></tr></table>
</div>