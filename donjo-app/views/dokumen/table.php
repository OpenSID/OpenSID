<script>
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

	<?php if($this->modul_ini <> 15): ?>
		<td class="side-menu">
			<legend>Kategori Dokumen</legend>
			<div class="lmenu">
				<ul>
					<a href="<?php echo site_url("dokumen/index/1")?>"><li <?php if($kat==1)echo "class='selected'";?>>Umum</li></a>
				</ul>
			</div>
		</td>
	<?php endif; ?>

	<td style="background:#fff;padding:0px;">
		<div id="contentpane">
			<form id="mainform" name="mainform" action="" method="post">
				<input name="kategori" type="hidden" value="<?php echo $kat?>">
		    <div class="ui-layout-north panel">
					<div class="content">
						<h3>Dokumen: <?php echo $kat_nama?></h3>
					</div>
		      <div class="left">
		        <div class="uibutton-group">
		          <a href='<?php echo site_url("{$this->controller}/form/$kat")?>' class="uibutton tipsy south" title="Tambah Data" ><span class="fa fa-plus-square">&nbsp;</span>Tambah Dokumen Baru</a>
		          <button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("{$this->controller}/delete_all/$kat/$p/$o")?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data</button>
              <a href="<?php echo site_url("{$this->controller}/dialog_cetak/$kat")?>" class="uibutton tipsy south"  title="Cetak Laporan" target="ajax-modal" rel="window" header="Cetak Laporan" modalWidth="auto" modalHeight="auto"><span class="fa fa-print"></span> Cetak</a>
              <a href="<?php echo site_url("{$this->controller}/dialog_excel/$kat")?>" class="uibutton tipsy south"  title="Unduh Laporan" target="ajax-modal" rel="window" header="Unduh Laporan" modalWidth="auto" modalHeight="auto"><span class="fa fa-download"></span> Unduh</a>
		        </div>
		      </div>
		    </div>
		    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
		      <div class="table-panel top">
		        <div class="left">
		          <select name="filter" onchange="formAction('mainform','<?php echo site_url($this->controller.'/filter')?>')">
		            <option value="">Semua</option>
		            <option value="1" <?php if($filter==1) :?>selected<?php endif?>>Aktif</option>
		            <option value="2" <?php if($filter==2) :?>selected<?php endif?>>Non-aktif</option>
		          </select>
		        </div>
		        <div class="right">
		          <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('{$this->controller}/search')?>');$('#'+'mainform').submit();}" />
		          <button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('{$this->controller}/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
		        </div>
		      </div>
		      <table class="list">
						<thead>
		          <tr>
		            <th>No</th>
		            <th><input type="checkbox" class="checkall"/></th>
		            <th width="120">Aksi</th>
						 		<?php  if($o==2): ?>
									<th align="left"><a href="<?php echo site_url("{$this->controller}/index/$kat/$p/1")?>">Judul / Tentang <span class="fa fa-sort-asc fa-sm">
								<?php  elseif($o==1): ?>
									<th align="left"><a href="<?php echo site_url("{$this->controller}/index/$kat/$p/2")?>">Judul / Tentang <span class="fa fa-sort-desc fa-sm">
								<?php  else: ?>
									<th align="left"><a href="<?php echo site_url("{$this->controller}/index/$kat/$p/1")?>">Judul / Tentang <span class="fa fa-sort fa-sm">
								<?php  endif; ?>&nbsp;</span></a></th>

							  <?php if ($kat == 2) : ?>
							  	<th>Nomor Dan Tanggal Keputusan</th>
							  	<th>Uraian Singkat</th>
							  <?php elseif ($kat == 3) : ?>
							  	<th>Nomor Dan Tanggal Ditetapkan</th>
							  	<th>Uraian Singkat</th>
							  <?php endif; ?>

								<?php  if($o==4): ?>
									<th align="center"><a href="<?php echo site_url("{$this->controller}/index/$kat/$p/3")?>">Aktif? <span class="fa fa-sort-asc fa-sm">
								<?php  elseif($o==3): ?>
									<th align="center"><a href="<?php echo site_url("{$this->controller}/index/$kat/$p/4")?>">Aktif? <span class="fa fa-sort-desc fa-sm">
								<?php  else: ?>
									<th align="center"><a href="<?php echo site_url("{$this->controller}/index/$kat/$p/3")?>">Aktif? <span class="fa fa-sort fa-sm">
								<?php  endif; ?>&nbsp;</span></a></th>

								<?php  if($o==6): ?>
									<th align="center" width='200'><a href="<?php echo site_url("{$this->controller}/index/$kat/$p/5")?>">Diunggah pada <span class="fa fa-sort-asc fa-sm">
								<?php  elseif($o==5): ?>
									<th align="center" width='200'><a href="<?php echo site_url("{$this->controller}/index/$kat/$p/6")?>">Diunggah pada <span class="fa fa-sort-desc fa-sm">
								<?php  else: ?>
									<th align="center" width='200'><a href="<?php echo site_url("{$this->controller}/index/$kat/$p/5")?>">Diunggah pada <span class="fa fa-sort fa-sm">
								<?php  endif; ?>&nbsp;</span></a></th>
							</tr>
						</thead>
						<tbody>
					    <?php foreach($main as $data){?>
								<tr>
									<td align="center" width="2"><?php echo $data['no']?></td>
									<td align="center" width="5">
										<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
									</td>
									<td>
										<div class="uibutton-group" style="display: flex;">
											<a href="<?php echo site_url("{$this->controller}/form/$kat/$p/$o/$data[id]")?>" class="uibutton tipsy south fa-tipis" title="Ubah Data"><span class="fa fa-edit"></span> Ubah</a>
											<a href="<?php echo base_url().LOKASI_DOKUMEN.underscore($data['satuan'])?>" class="uibutton tipsy south fa-tipis" title="Unduh Berkas"><span class="fa fa-download"></span> Unduh</a>
											<a href="<?php echo site_url("{$this->controller}/delete/$kat/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"><span></a>
											<?php if($data['enabled'] == '2'):?>
												<a href="<?php echo site_url($this->controller.'/dokumen_lock/'.$kat.'/'.$data['id'])?>" class="uibutton tipsy south" title="Aktivasi dokumen"><span class="fa fa-lock"></span></a>
											<?php elseif($data['enabled'] == '1'): ?>
												<a href="<?php echo site_url($this->controller.'/dokumen_unlock/'.$kat.'/'.$data['id'])?>" class="uibutton tipsy south" title="Non-aktifkan dokumen"><span class="fa fa-unlock"><span></a>
											<?php endif?>
										</div>
								  </td>
								  <td><?php echo $data['nama']?></td>
								  <?php if ($kat == 2) : ?>
								  	<td><?php echo $data['attr']['no_kep_kades']." / ".$data['attr']['tgl_kep_kades']?></td>
								  	<td><?php echo $data['attr']['uraian']?></td>
								  <?php elseif ($kat == 3) : ?>
								  	<td><?php echo $data['attr']['no_ditetapkan']." / ".$data['attr']['tgl_ditetapkan']?></td>
								  	<td><?php echo $data['attr']['uraian']?></td>
								  <?php endif; ?>
								  <td align="center"><?php echo $data['aktif']?></td>
								  <td align="center"><?php echo tgl_indo2($data['tgl_upload'])?></td>
								</tr>
					    <?php }?>
						</tbody>
			    </table>
		    </div>
			</form>
		  <div class="ui-layout-south panel bottom">
		    <div class="left">
					<div class="table-info">
		        <form id="paging" action="<?php echo site_url($this->controller.'/index/'.$kat)?>" method="post">
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
							<a href="<?php echo site_url("{$this->controller}/index/$kat/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
						<?php  endif; ?>
						<?php  if($paging->prev): ?>
							<a href="<?php echo site_url("{$this->controller}/index/$kat/$paging->prev/$o")?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
						<?php  endif; ?>
		      </div>
		      <div class="uibutton-group">

						<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
							<a href="<?php echo site_url("{$this->controller}/index/$kat/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
						<?php  endfor; ?>
		      </div>
		      <div class="uibutton-group">
					<?php  if($paging->next): ?>
						<a href="<?php echo site_url("{$this->controller}/index/$kat/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
					<?php  endif; ?>
					<?php  if($paging->end_link): ?>
		        <a href="<?php echo site_url("{$this->controller}/index/$kat/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
					<?php  endif; ?>
		    </div>
		  </div>
		</div>
	</td>
</tr>
</table>
</div>
