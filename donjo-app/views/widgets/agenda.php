<!-- Widget Agenda -->

<style type="text/css">
  #agenda .tab-content { margin-top: 10px; }
</style>

<?php if ($agenda): ?>
  <div class="box box-primary box-solid">
    <div class="box-header">
      <h3 class="box-title"><a href="<?= site_url();?>first/kategori/1000"><i class="fa fa-calendar"></i> Agenda</a></h3>
    </div>
    <div id="agenda" class="box-body">
	    <ul class="nav nav-tabs">
      	<?php if (count($agenda['hari_ini']) > 0): ?>
		      <li class="active"><a data-toggle="tab" href="#hari-ini">Hari ini</a></li>
		    <?php endif; ?>
	      <li <?php count($agenda['hari_ini']) > 0 or print('class="active"')?>><a data-toggle="tab" href="#yad">Yang akan datang</a></li>
      	<?php if (count($agenda['lama']) > 0): ?>
		      <li><a data-toggle="tab" href="#lalu">Lama</a></li>
		    <?php endif; ?>
	    </ul>

	    <div class="tab-content">
	      <div id="hari-ini" class="tab-pane fade in active">
		      <ul class="sidebar-latest">
		        <?php foreach ($agenda['hari_ini'] as $l): ?>
		          <li>
							  <table id="table-agenda" width="100%">
									<tr>
										<td colspan="3"><a href="<?= site_url('first/artikel/'.$l['thn'].'/'.$l['bln'].'/'.$l['hri'].'/'.$l['slug'])?>"><?= $l['judul']?></a></td>
									</tr>
									<tr>
										<th id="label-meta-agenda" width="40%">Waktu</th>
										<td width="5%">:</td>
										<td id="isi-meta-agenda" width="55%"><?= tgl_indo2($l['tgl_agenda'])?></td>
									</tr>
									<tr>
										<th id="label-meta-agenda">Lokasi</th>
										<td>:</td>
										<td id="isi-meta-agenda"><?= $l['lokasi_kegiatan']?></td>
									</tr>
									<tr>
										<th id="label-meta-agenda">Koordinator</th>
										<td>:</td>
										<td id="isi-meta-agenda"><?= $l['koordinator_kegiatan']?></td>
									</tr>
							  </table>
						  </li>
		        <?php endforeach; ?>
		      </ul>
		    </div>
		    
	      <div id="yad" class="tab-pane fade <?php count($agenda['hari_ini']) > 0 or print('in active')?> ">
		      <ul class="sidebar-latest">
		      	<?php if (count($agenda['yad']) > 0): ?>
			        <?php foreach ($agenda['yad'] as $l): ?>
			          <li>
								  <table id="table-agenda" width="100%">
										<tr>
											<td colspan="3"><a href="<?= site_url('first/artikel/'.$l['thn'].'/'.$l['bln'].'/'.$l['hri'].'/'.$l['slug'])?>"><?= $l['judul']?></a></td>
										</tr>
										<tr>
											<th id="label-meta-agenda" width="40%">Waktu</th>
											<td width="5%">:</td>
											<td id="isi-meta-agenda" width="55%"><?= tgl_indo2($l['tgl_agenda'])?></td>
										</tr>
										<tr>
											<th id="label-meta-agenda">Lokasi</th>
											<td>:</td>
											<td id="isi-meta-agenda"><?= $l['lokasi_kegiatan']?></td>
										</tr>
										<tr>
											<th id="label-meta-agenda">Koordinator</th>
											<td>:</td>
											<td id="isi-meta-agenda"><?= $l['koordinator_kegiatan']?></td>
										</tr>
								  </table>
							  </li>
			        <?php endforeach; ?>
			      <?php else: ?>
			      	<p>Belum ada agenda</p>
			      <?php endif; ?>
		      </ul>
		    </div>

	      <div id="lalu" class="tab-pane fade">
			    <marquee onmouseover="this.stop()" onmouseout="this.start()" scrollamount="2" direction="up" width="100%" height="100" align="center" behavior=”alternate”>
			      <ul class="sidebar-latest">
			        <?php foreach ($agenda['lama'] as $l): ?>
			          <li>
								  <table id="table-agenda" width="100%">
										<tr>
											<td colspan="3"><a href="<?= site_url('first/artikel/'.$l['thn'].'/'.$l['bln'].'/'.$l['hri'].'/'.$l['slug'])?>"><?= $l['judul']?></a></td>
										</tr>
										<tr>
											<th id="label-meta-agenda" width="40%">Waktu</th>
											<td width="5%">:</td>
											<td id="isi-meta-agenda" width="55%"><?= tgl_indo2($l['tgl_agenda'])?></td>
										</tr>
										<tr>
											<th id="label-meta-agenda">Lokasi</th>
											<td>:</td>
											<td id="isi-meta-agenda"><?= $l['lokasi_kegiatan']?></td>
										</tr>
										<tr>
											<th id="label-meta-agenda">Koordinator</th>
											<td>:</td>
											<td id="isi-meta-agenda"><?= $l['koordinator_kegiatan']?></td>
										</tr>
								  </table>
							  </li>
			        <?php endforeach; ?>
			      </ul>
					</marquee>
	      </div>
	    </div>

    </div>
  </div>
<?php endif; ?>
