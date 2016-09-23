<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
		<td class="side-menu">
			<div class="lmenu">
				<ul>
					<li class="selected"><a href="<?php echo site_url('keluar')?>">Surat Keluar</a></li>
					<li ><a href="<?php echo site_url('keluar/perorangan')?>">Rekam Surat Perorangan</a></li>
					<li ><a href="<?php echo site_url('keluar/graph')?>">Grafik Surat keluar</a></li>
				</ul>
			</div>
		</td>
		<td style="background:#fff;padding:5px;">
			<div class="content-header">

			</div>
			<div id="contentpane">
			<div class="ui-layout-north panel">
			<h3>Manajemen Surat Keluar</h3>
			</div>

			<div class="ui-layout-north panel">
			</div>
			<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
				<table class="list">
					<thead>
						<tr>
							<th>No</th>
							<th>Aksi</th>
							<?php  if($o==2): ?>
							<th align="left" width='100'>Nomor Surat</th>
							<?php  elseif($o==1): ?>
							<th align="left" width='100'>Nomor Surat</th>
							<?php  else: ?>
							<th align="left" width='100'>Nomor Surat</th>
							<?php  endif; ?>

							<th align="left">Jenis Surat</th>

							 <?php  if($o==4): ?>
							<th align="left">Nama Penduduk</th>
							<?php  elseif($o==3): ?>
							<th align="left">Nama Penduduk</th>
							<?php  else: ?>
							<th align="left">Nama Penduduk</th>
							<?php  endif; ?>

							<th align="left" width='160'>Ditandatangani Oleh	</th>

							<?php  if($o==6): ?>
							<th align="left" width='160'>Tanggal</th>
							<?php  elseif($o==5): ?>
							<th align="left" width='160'>Tanggal</th>
							<?php  else: ?>
							<th align="left" width='160'>Tanggal</th>
							<?php  endif; ?>

							<th align="left">User</th>

						</tr>
					</thead>
					<tbody>
						<?php
						foreach($main as $data):
							if ($data['nama_surat']):
								$berkas = $data['nama_surat'];
							else:
								$berkas = $data["berkas"]."_".$data["nik"]."_".date("Y-m-d").".rtf";
							endif;
							$theFile = FCPATH.LOKASI_ARSIP.$berkas;
							?>
							<tr>
								<td align="center" width="2"><?php echo $data['no']?></td>
								<td>
									<div class="uibutton-group">
										<?php
											if(is_file($theFile)){?>
												<a href="<?php echo base_url(LOKASI_ARSIP.$berkas)?>" class="uibutton tipsy south" title="Cetak"><span class="icon-download icon-large"> Cetak </span></a>
										<?php	}?>
										<a href="<?php echo site_url("keluar/delete/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="icon-trash icon-large"></span></a>
									</div>
								</td>
								<td><?php echo $data['no_surat']?></td>
								<td><?php echo $data['format']?></td>
								<td><?php echo unpenetration($data['nama'])?></td>
								<td><?php echo $data['pamong']?></td>
								<td><?php echo tgl_indo2($data['tanggal'])?></td>
								<td><?php echo $data['nama_user']?></td>
							</tr>
						<?php
						endforeach;
						?>
					</tbody>
				</table>
			</div>
			<div class="ui-layout-south panel bottom">
				<div class="left">
					<div class="table-info">
					<form id="paging" action="<?php echo site_url('keluar')?>" method="post">
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
						<a href="<?php echo site_url("keluar/index/$paging->start_link/$o")?>" class="uibutton"  >First</a>
						<?php  endif; ?>
						<?php  if($paging->prev): ?>
						<a href="<?php echo site_url("keluar/index/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
						<?php  endif; ?>
					</div>
					<div class="uibutton-group">
						<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
						<a href="<?php echo site_url("keluar/index/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
						<?php  endfor; ?>
							</div>
							<div class="uibutton-group">
						<?php  if($paging->next): ?>
						<a href="<?php echo site_url("keluar/index/$paging->next/$o")?>" class="uibutton">Next</a>
						<?php  endif; ?>
						<?php  if($paging->end_link): ?>
									<a href="<?php echo site_url("keluar/index/$paging->end_link/$o")?>" class="uibutton">Last</a>
						<?php  endif; ?>
					</div>
				</div>
			</div>
		</div>
		</td></tr>
	</table>
</div>
