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
	      <li class="active"><a data-toggle="tab" href="#terkini">Yang akan datang</a></li>
	      <li><a data-toggle="tab" href="#acak">Lama</a></li>
	    </ul>
	    <div class="tab-content">
	      <div id="terkini" class="tab-pane fade in active">
		      <ul id="ul-agenda" class="sidebar-latest">
		      	<?php if (count($agenda['yad']) > 0): ?>
			        <?php foreach ($agenda['yad'] as $l): ?>
			          <li>
								  <table id="table-agenda" width="100%">
										<tr>
											<td colspan="3"><a href="<?= site_url("first/artikel/$l[id_artikel]")?>"><?= $l['judul']?></a></td>
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

	      <div id="acak" class="tab-pane fade">
			    <marquee onmouseover="this.stop()" onmouseout="this.start()" scrollamount="2" direction="up" width="100%" height="100" align="center" behavior=”alternate”>
			      <ul id="ul-agenda" class="sidebar-latest">
			        <?php foreach ($agenda['lama'] as $l): ?>
			          <li>
								  <table id="table-agenda" width="100%">
										<tr>
											<td colspan="3"><a href="<?= site_url("first/artikel/$l[id_artikel]")?>"><?= $l['judul']?></a></td>
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
