
<div id="pageC">
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
	<td class="side-menu">
		<fieldset><legend>Laporan : </legend>
			<div class="lmenu">
				<ul>
				<li ><a href="<?php echo site_url()?>sid_laporan_bulanan">Laporan Bulanan</a></li>
				<li ><a href="<?php echo site_url()?>sid_laporan_kelompok">Data Kelompok Rentan</a></li>
				</ul>
			</div>
		</fieldset>

		<fieldset><legend>Penduduk Kelurahan</legend>
			<div class="lmenu">
				<ul>
				<li ><a href="<?php echo site_url()?>sid_laporan_penduduk_status">Penduduk Tetap</a></li>
				<li ><a href="<?php echo site_url()?>sid_laporan_pasif">Penduduk Pasif</a></li>
				<li ><a href="<?php echo site_url()?>sid_laporan_tetap_pasif">Tetap + Pasif</a></li>
				<li ><a href="<?php echo site_url()?>sid_laporan_pendatang">Penduduk Pendatang</a></li>
				<li ><a href="<?php echo site_url()?>sid_laporan_tetap_pendatang">Pendatang + Tetap</a></li>
				</ul>
			</div>
		</fieldset>


		</td>
<td style="background:#fff;padding:0px;">
<div class="content-header">
    <h3>Manajemen Penduduk</h3>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
                <a href="<?php echo site_url('sid_penduduk/form')?>" class="uibutton tipsy south" title="Tambah Data" ><span class="fa fa-plus-square">&nbsp;</span>Tambah Data</a>
                <button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("sid_penduduk/delete_all/$p/$o")?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data</button>
            </div>
        </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">
                <select name="filter" onchange="formAction('mainform','<?php echo site_url('sid_penduduk/filter')?>')">
                    <option value="">Semua</option>
                    <option value="1" <?php if($filter==1) :?>selected<?php endif?>>Asli</option>
                    <option value="2" <?php if($filter==2) :?>selected<?php endif?>>Pendatang</option>
                </select>
<button href="<?php echo site_url("sid_penduduk/ajax_adv_search")?>"  target="ajax-modalx" rel="window" header="Pencarian Spesifik"  class="uibutton tipsy south"  title="Pencarian Spesifik"><span class="fa fa-search">&nbsp;</span>Pencarian Lanjutan</button>
                <a href="<?php echo site_url("sid_penduduk/clear")?>"  class="uibutton tipsy south"  title="Clear Pencarian"><span class="fa fa-refresh">&nbsp;</span>Bersihkan</a>
            </div>
            <div class="right">
                <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Search.."/>
                <button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('sid_penduduk/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
            </div>
        </div>
        <table class="list">
<thead>
            <tr>
                <th>No</th>
                <th><input type="checkbox" class="checkall"/></th>
                <th width="50">Aksi</th>
                <?php  if($o==2): ?>
<th align="left" width='100'><a href="<?php echo site_url("sid_penduduk/index/$p/1")?>">NIK <span class="fa fa-sort-asc fa-sm"></span></a></th>
<?php  elseif($o==1): ?>
<th align="left" width='100'><a href="<?php echo site_url("sid_penduduk/index/$p/2")?>">NIK <span class="fa fa-sort-desc fa-sm"></span></a></th>
<?php  else: ?>
<th align="left" width='100'><a href="<?php echo site_url("sid_penduduk/index/$p/1")?>">NIK <span class="fa fa-sort fa-sm"></span></a></th>
<?php  endif; ?>

 <?php  if($o==4): ?>
<th align="left"><a href="<?php echo site_url("sid_penduduk/index/$p/3")?>">Nama <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
<?php  elseif($o==3): ?>
<th align="left"><a href="<?php echo site_url("sid_penduduk/index/$p/4")?>">Nama <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
<?php  else: ?>
<th align="left"><a href="<?php echo site_url("sid_penduduk/index/$p/3")?>">Nama <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
<?php  endif; ?>

<th align="left" width='100'>No. KK</th>
<th align="left" align="center">Alamat</th>
<th align="left" align="center">Pendidikan</th>
<th align="left" align="center">Umur</th>
<th align="left" align="center">Pekerjaan</th>
<th align="left" align="center">Status Perkawinan</th>
<th width="70" align="left" align="center">Status Penduduk</th>

</tr>
</thead>
<tbody>
        <?php  foreach($main as $data): ?>
<tr>
          <td align="center" width="2"><?php echo $data['no']?></td>
<td align="center" width="5">
<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
</td>
<td>
<a href="<?php echo site_url("sid_penduduk/form/$p/$o/1/$data[id]")?>" class="ui-icons fa fa-edit tipsy south" title="Ubah Data"></a><a href="<?php echo site_url("sid_penduduk/ajax_penduduk_maps/$p/$o/$data[id]")?>" target="ajax-modalz" rel="window" header="Lokasi <?php echo $data['nama']?>" class="ui-icons fa fa-map tipsy south" title="Lokasi <?php echo $data['nama']?>"></a><a href="<?php echo site_url("sid_penduduk/delete/$p/$o/$data[id]")?>" class="ui-icons fa fa-trash tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"></a>
</td>

<td><a href="<?php echo site_url("sid_penduduk/detail/$p/$o/$data[id]")?>" id="test" name="<?php echo $data['id']?>"><?php echo $data['nik']?></a></td>
<td><a href="<?php echo site_url("sid_penduduk/detail/$p/$o/$data[id]")?>"><?php echo strtoupper($data['nama'])?></a></td>
<td><a href="<?php echo site_url("sid_keluarga/kartu_keluarga/$p/$o/$data[id_kk]")?>"><?php echo $data['no_kk']?> </a> </td>
<td><?php echo strtoupper($data['alamat'])?></td>
<td><?php echo strtoupper($data['pendidikan'])?></td>
<td><?php echo strtoupper($data['umur'])?></td>
<td><?php echo strtoupper($data['pekerjaan'])?></td>
<td><?php echo strtoupper($data['kawin'])?></td>

  <td><?php if($data['status']==1){echo "Tetap";}else{echo "Pendatang";}?></td>
  </tr>
        <?php  endforeach; ?>
</tbody>
        </table>
    </div>
</form>
    <div class="ui-layout-south panel bottom">
        <div class="left">
<div class="table-info">
          <form id="paging" action="<?php echo site_url('sid_penduduk')?>" method="post">
  <label>Tampilkan</label>
            <select name="per_page" onchange="$('#paging').submit()" >
              <option value="20" <?php  selected($per_page,20); ?> >20</option>
              <option value="50" <?php  selected($per_page,50); ?> >50</option>
              <option value="100" <?php  selected($per_page,100); ?> >100</option>
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
<a href="<?php echo site_url("sid_penduduk/index/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
<?php  endif; ?>
<?php  if($paging->prev): ?>
<a href="<?php echo site_url("sid_penduduk/index/$paging->prev/$o")?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
<?php  endif; ?>
            </div>
            <div class="uibutton-group">

<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
<a href="<?php echo site_url("sid_penduduk/index/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
<?php  endfor; ?>
            </div>
            <div class="uibutton-group">
<?php  if($paging->next): ?>
<a href="<?php echo site_url("sid_penduduk/index/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
<?php  endif; ?>
<?php  if($paging->end_link): ?>
                <a href="<?php echo site_url("sid_penduduk/index/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
<?php  endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
