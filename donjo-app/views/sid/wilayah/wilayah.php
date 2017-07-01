<script>
	$(function() {
		var keyword = <?php echo $keyword?> ;
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
<div id="contentpane">
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
    <h3>Wilayah Administratif <?php echo ucwords($this->setting->sebutan_dusun)?></h3>
        <div class="left">
            <div class="uibutton-group">
                <a href="<?php echo site_url('sid_core/form')?>" class="uibutton tipsy south" title="Tambah Data" ><span class="fa fa-plus-square">&nbsp;</span>Tambah <?php echo ucwords($this->setting->sebutan_dusun)?></a>
                <a href="<?php echo site_url('sid_core/cetak')?>" target="_blank" class="uibutton tipsy south" title="Print Data" ><span class="fa fa-print">&nbsp;</span>Cetak</a>
		<a href="<?php echo site_url('sid_core/excel')?>" target="_blank" class="uibutton tipsy south" title="Data Excel" ><span class="fa fa-file-text">&nbsp;</span>Excel</a>
            </div>
        </div>
		<div class="right">
			<input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('sid_core/search')?>');$('#'+'mainform').submit();}" />
			<button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('sid_core/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
		</div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">
            </div>
        </div>
        <table class="list">
		<thead>
            <tr>
                <th width="5">No</th>
                <th width="5"><input type="checkbox" class="checkall"/></th>
                <th width="120">Aksi</th>
				<th width="200">Nama <?php echo ucwords($this->setting->sebutan_dusun)?></th>
				<th width="200">Nama Kepala <?php echo ucwords($this->setting->sebutan_dusun)?></th>
				<th width="50">RW</th>
				<th width="50">RT</th>
				<th width="50">KK</th>
				<th width="50">Jiwa</th>
				<th width="50">LK</th>
				<th width="50">PR</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
        <?php
        $total = array();

        $total['total_rw'] = 0;
        $total['total_rt'] = 0;
        $total['total_kk'] = 0;
        $total['total_warga'] = 0;
        $total['total_warga_l'] = 0;
        $total['total_warga_p'] = 0;

        foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?php echo $data['no']?></td>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
			</td>
			<td width="4" align="center"><div class="uibutton-group">

<a href="<?php echo site_url("sid_core/sub_rw/$data[id]")?>" class="uibutton tipsy south fa-tipis" title="Rincian Sub Wilayah"><span class="fa fa-bars"></span> Rincian</a>
<a href="<?php echo site_url("sid_core/form/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data"><span class="fa fa-edit"></span></a>

<?php  if($grup==1){?>
				<a href="<?php echo site_url("sid_core/delete/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin? Menghapus data dusun akan mempengaruhi struktur data yang ada dibawah dusun. pilih tidak untuk membatalkan penghapusan." header="Hapus Data"><span class="fa fa-trash"></span></a><?php  } ?>
</div>
			</td>
			<td><?php echo strtoupper(unpenetration(ununderscore($data['dusun'])))?></td>
			<td><?php echo strtoupper(unpenetration($data['nama_kadus']))?></td>

			<td align="center"><a href="<?php echo site_url("sid_core/sub_rw/$data[id]")?>" title="Rincian Sub Wilayah"><?php echo $data['jumlah_rw']?></a></td>
			<td align="center"><?php echo $data['jumlah_rt']?></td>
			<td align="center"><a href="<?php echo site_url("sid_core/warga_kk/$data[id]")?>"><?php echo $data['jumlah_kk']?></a></td>
			<td align="center"><a href="<?php echo site_url("sid_core/warga/$data[id]")?>"><?php echo $data['jumlah_warga']?></a></td>
			<td align="center"><a href="<?php echo site_url("sid_core/warga_l/$data[id]")?>"><?php echo $data['jumlah_warga_l']?></a></td>
			<td align="center"><a href="<?php echo site_url("sid_core/warga_p/$data[id]")?>"><?php echo $data['jumlah_warga_p']?></a></td>
				<td></td>
		</tr>
        <?php
        $total['total_rw'] += $data['jumlah_rw'];
        $total['total_rt'] += $data['jumlah_rt'];
        $total['total_kk'] += $data['jumlah_kk'];
        $total['total_warga'] += $data['jumlah_warga'];
        $total['total_warga_l'] += $data['jumlah_warga_l'];
        $total['total_warga_p'] += $data['jumlah_warga_p'];


        endforeach; ?>
		</tbody>

            <tr style="background-color:#BDD498;font-weight:bold;">
                <td colspan="5" align="center"><label>TOTAL</label></td>
				<td align="center"><?php echo $total['total_rw']?></td>
				<td align="center"><?php echo $total['total_rt']?></td>
				<td align="center"><?php echo $total['total_kk']?></td>
				<td align="center"><?php echo $total['total_warga']?></td>
				<td align="center"><?php echo $total['total_warga_l']?></td>
				<td align="center"><?php echo $total['total_warga_p']?></td>
				<td></td>
			</tr>
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left">
		<div class="table-info">
          <form id="paging" action="<?php echo site_url('sid_core')?>" method="post">
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
				<a href="<?php echo site_url("sid_core/index/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
			<?php  endif; ?>
			<?php  if($paging->prev): ?>
				<a href="<?php echo site_url("sid_core/index/$paging->prev/$o")?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
			<?php  endif; ?>
            </div>
            <div class="uibutton-group">

				<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("sid_core/index/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php  endfor; ?>
            </div>
            <div class="uibutton-group">
			<?php  if($paging->next): ?>
				<a href="<?php echo site_url("sid_core/index/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
			<?php  endif; ?>
			<?php  if($paging->end_link): ?>
                <a href="<?php echo site_url("sid_core/index/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
			<?php  endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
