<script>
	$(function() {
		var keyword = <?php echo $keyword?> ;
		$( "#cari" ).autocomplete({
			source: keyword
		});
	});
	$('document').ready(function(){
		$('select[name=pamong_ttd]').change(function(e) {
			$('select[name=jabatan_ttd]').val($(this).find(':selected').data('jabatan'));
		});
		$('select[name=pamong_ketahui]').change(function(e) {
			$('select[name=jabatan_ketahui]').val($(this).find(':selected').data('jabatan'));
		});
	});
</script>
<div id="pageC">
	<div id="contentpane">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="ui-layout-north panel">
				<div class="left">
					<div class="uibutton-group">
						<a href="<?php echo site_url('surat_masuk/form')?>" class="uibutton tipsy south" title="Tambah Data" ><span class="fa fa-plus-square">&nbsp;</span>Tambah Surat Baru</a>
						<button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("surat_masuk/delete_all/$p/$o")?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Surat
					</div>
				</div>
		    <div class="left">
		      <div class="uibutton-group">
	         <a class="uibutton tipsy south" title="Cetak laporan" onclick="$('#'+'mainform').attr('target','_blank');formAction('mainform','<?php echo site_url('surat_masuk/cetak')?>')"><span class="fa fa-print">&nbsp;</span>Cetak</a>
	          <a class="uibutton tipsy south" title="Unduh laporan" onclick="$('#'+'mainform').attr('target','_blank');formAction('mainform','<?php echo site_url('surat_masuk/excel')?>')"><span class="fa fa-file-text">&nbsp;</span>Excel</a>
		      </div>
		    </div>
				<div class="right">
					<input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('surat_masuk/search')?>');$('#'+'mainform').submit();}" />
					<button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('surat_masuk/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
				</div>
		    <span style="clear: both; text-align: center;"><h3>SURAT MASUK</h3></span>
		    <table id="j" style="width: 100%; margin-left: 5px;">
			    <tr>
			    	<td class="nostretch">Tahun Penerimaan</td>
            <td class="nostretch">:</td>
						<td class="nostretch">
	            <select name="filter" onchange="formAction('mainform','<?php echo site_url('surat_masuk/filter')?>')">
	                <option value="" <?php if(empty($filter)) :?>selected<?php endif;?>>Semua</option>
	                <?php foreach($tahun_penerimaan as $tahun): ?>
		                <option value="<?php echo $tahun['tahun']?>" <?php if($filter==$tahun['tahun']) :?>selected<?php endif?>><?php echo $tahun['tahun']?></option>
		              <?php endforeach; ?>
	            </select>
						</td>
						<td colspan="3">&nbsp;</td>
					</tr>
          <tr>
            <td class="nostretch">Laporan Ditandatangani</td>
            <td class="nostretch">:</td>
            <td class="nostretch">
	            <select name="pamong_ttd"  class="inputbox">
	              <option value="">Pilih Staf Pemerintah <?php echo ucwords($this->setting->sebutan_desa)?></option>
	              <?php foreach($pamong AS $data){?>
	                <option value="<?php echo $data['pamong_nama']?>" data-jabatan="<?php echo trim($data['jabatan'])?>"><?php echo $data['pamong_nama']?>(<?php echo $data['jabatan']?>)</option>
	              <?php }?>
              </select></td>
            <td class="nostretch" style="padding-left: 5px;">Sebagai : </td>
            <td class="nostretch">
	            <select name="jabatan_ttd"  class="inputbox">
	              <option value="">Pilih Jabatan</option>
	              <?php foreach($pamong AS $data){?>
	                <option ><?php echo $data['jabatan']?></option>
	              <?php }?>
              </select>
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td class="nostretch">Laporan Diketahui</td>
            <td class="nostretch">:</td>
            <td class="nostretch">
	            <select name="pamong_ketahui"  class="inputbox">
	              <option value="">Pilih Staf Pemerintah <?php echo ucwords($this->setting->sebutan_desa)?></option>
	              <?php foreach($pamong AS $data){?>
	                <option value="<?php echo $data['pamong_nama']?>" data-jabatan="<?php echo trim($data['jabatan'])?>"><?php echo $data['pamong_nama']?>(<?php echo $data['jabatan']?>)</option>
	              <?php }?>
              </select></td>
            <td class="nostretch" style="padding-left: 5px;">Sebagai : </td>
            <td class="nostretch">
	            <select name="jabatan_ketahui"  class="inputbox">
	              <option value="">Pilih Jabatan</option>
	              <?php foreach($pamong AS $data){?>
	                <option ><?php echo $data['jabatan']?></option>
	              <?php }?>
              </select>
            </td>
            <td>&nbsp;</td>
          </tr>
		    </table>
			</div>
			<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
				<table class="list">
					<thead>
						<tr>
							<?php  if($o==1) {$order = 2; $fa_sort = "fa-sort-desc";}
								elseif($o==2) {$order = 1; $fa_sort = "fa-sort-asc";}
								else {$order = 2; $fa_sort = "fa-sort";}
							?>
							<th width="60" align="center"><a href="<?php echo site_url("surat_masuk/index/$p/$order")?>">No. Urut <span class="fa <?php echo $fa_sort?> fa-sm">&nbsp;</span></a></th>

							<th width="5"><input type="checkbox" class="checkall"/></th>
							<th class="nostretch">Aksi</th>

							<?php  if($o==3) {$order = 4; $fa_sort = "fa-sort-desc";}
								elseif($o==4) {$order = 3; $fa_sort = "fa-sort-asc";}
								else {$order = 3; $fa_sort = "fa-sort";}
							?>
							<th align="left"><a href="<?php echo site_url("surat_masuk/index/$p/$order")?>">Tanggal Penerimaan <span class="fa <?php echo $fa_sort?> fa-sm">&nbsp;</span></a></th>

							<th>Nomor Surat</th>
							<th>Tanggal Surat</th>


							<?php  if($o==5) {$order = 6; $fa_sort = "fa-sort-desc";}
								elseif($o==6) {$order = 5; $fa_sort = "fa-sort-asc";}
								else {$order = 5; $fa_sort = "fa-sort";}
							?>
							<th align="center"><a href="<?php echo site_url("surat_masuk/index/$p/$order")?>">Pengirim <span class="fa <?php echo $fa_sort?> fa-sm">&nbsp;</span></a></th>

							<th width="">Isi Singkat</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($main as $data): ?>
				      <tr>
								<td align="center">
									<?php echo $data['nomor_urut']?>
								</td>
								<td align="center">
									<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" <?php if($data['jenis']==1){echo " disabled= disabled";}?> />
								</td>
								<td class="nostretch">
									<div class="uibutton-group" style="display: flex;">
										<a href="<?php echo site_url("surat_masuk/form/$p/$o/$data[id]")?>" class="uibutton tipsy south fa-tipis" title="Ubah Data"><span class="fa fa-edit"></span> Ubah</a>
										<?php if($data['berkas_scan']): ?>
											<a href="<?php echo base_url(LOKASI_ARSIP.$data['berkas_scan'])?>" class="uibutton tipsy south fa-tipis" title="Unduh Surat"><span class="fa fa-download"></span> Unduh</a>
										<?php endif; ?>
										<a class="uibutton tipsy south" title="Lembar Disposisi" onclick="$('#'+'mainform').attr('target','_blank');formAction('mainform','<?php echo site_url('surat_masuk/disposisi/'.$data['id'])?>')"><span class="fa fa-file-text-o"></span></a>
										<a href="<?php echo site_url("surat_masuk/delete/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
									</div>
								</td>
								<td><?php echo tgl_indo_out($data['tanggal_penerimaan'])?></td>
								<td><?php echo $data['nomor_surat']?></td>
								<td><?php echo tgl_indo_out($data['tanggal_surat'])?></td>
								<td><?php echo $data['pengirim']?></td>
								<td><?php echo $data['isi_singkat']?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</form>
		<div class="ui-layout-south panel bottom">
			<div class="left">
				<div class="table-info">
					<form id="paging" action="<?php echo site_url('surat_masuk')?>" method="post">
						<label>Tampilkan </label>
						<select name="per_page" onchange="$('#paging').submit()" >
							<option value="20" <?php  selected($per_page,20); ?> >20</option>
							<option value="50" <?php  selected($per_page,50); ?> >50</option>
							<option value="100" <?php  selected($per_page,100); ?> >100</option>
						</select>
						<label>Dari</label>
						<label><?php echo $paging->num_rows?></label>
						<label>Total Data</label>
					</form>
				</div>
			</div>
			<div class="right">
				<div class="uibutton-group">
					<?php  if($paging->start_link): ?>
						<a href="<?php echo site_url("surat_masuk/index/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
					<?php endif; ?>
					<?php  if($paging->prev): ?>
						<a href="<?php echo site_url("surat_masuk/index/$paging->prev/$o")?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
					<?php  endif; ?>
				</div>
				<div class="uibutton-group">
					<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
						<a href="<?php echo site_url("surat_masuk/index/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
					<?php  endfor; ?>
				</div>
				<div class="uibutton-group">
					<?php  if($paging->next): ?>
						<a href="<?php echo site_url("surat_masuk/index/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
					<?php  endif; ?>
					<?php  if($paging->end_link): ?>
						<a href="<?php echo site_url("surat_masuk/index/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
					<?php  endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>