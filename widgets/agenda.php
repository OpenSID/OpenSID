 <!-- Widget Agenda -->

<?php if ($agenda): ?>
  <div class="single_bottom_rightbar">
    <h2><a href="<?= site_url();?>first/kategori/1000"><i class="fa fa-calendar"></i> Agenda</a></h2>
    <div id="agenda" class="box-body">
	    <ul class="nav nav-tabs">
      	<?php if (count($agenda['hari_ini']) > 0): ?>
		      <li class="active"><a data-toggle="tab" href="#hari-ini">Hari ini</a></li>
		    <?php endif; ?>
	      <li <?php count($agenda['hari_ini']) > 0 or print('class="active"')?>><a data-toggle="tab" href="#yad">Jadwal</a></li>
      	<?php if (count($agenda['lama']) > 0): ?>
		      <li><a data-toggle="tab" href="#lalu">Lama</a></li>
		    <?php endif; ?>
	    </ul>

	    <div class="tab-content">
	      <div id="hari-ini" class="tab-pane fade in active">
		      <ul class="sidebar-latest">
		        <?php foreach ($agenda['hari_ini'] as $l): ?>
		          <li>
							  <table id="table-agenda" width="100%" style="margin-bottom:10px;">
                              <tr valign="top">
                                <th id="label-meta-agenda" width="35%">Kegiatan</th>
                                <td width="5%" class="titik">:</td>
                                <td><a href="<?= site_url('first/artikel/'.buat_slug($l))?>"><?= $l['judul']?></a></td>
                              </tr>
                        	  <tr valign="top">
                                <th id="label-meta-agenda">Waktu</th>
                                <td class="titik">:</td>
                                <td><div id="small-agenda"><?= tgl_indo2($l['tgl_agenda'])?></div></td>
                              </tr>
                              <tr valign="top">
                                <th id="label-meta-agenda">Lokasi</th>
                                <td class="titik">:</td>
                                <td><?= $l['lokasi_kegiatan']?></td>
                              </tr>
                              <tr valign="top">
                                <th id="label-meta-agenda">Koordinator</th>
                                <td class="titik">:</td>
                                <td><?= $l['koordinator_kegiatan']?></td>
                              </tr>
                            </table>
                            <?php if (count($agenda['yad']) > 0): ?><hr><?php endif; ?>
						  </li>
		        <?php endforeach; ?>
		      </ul>
		    </div>
		    
	      <div id="yad" class="tab-pane fade <?php count($agenda['hari_ini']) > 0 or print('in active')?> ">
		      <ul class="sidebar-latest">
		      	<?php if (count($agenda['yad']) > 0): ?>
			        <?php foreach ($agenda['yad'] as $l): ?>
			          <li>
			              <table id="table-agenda" width="100%" style="margin-bottom:10px;">
                              <tr valign="top">
                                <th id="label-meta-agenda" width="35%">Kegiatan</th>
                                <td width="5%" class="titik">:</td>
                                <td><a href="<?= site_url('first/artikel/'.buat_slug($l))?>"><?= $l['judul']?></a></td>
                              </tr>
                        	  <tr valign="top">
                                <th id="label-meta-agenda">Waktu</th>
                                <td class="titik">:</td>
                                <td><div id="small-agenda"><?= tgl_indo2($l['tgl_agenda'])?></div></td>
                              </tr>
                              <tr valign="top">
                                <th id="label-meta-agenda">Lokasi</th>
                                <td class="titik">:</td>
                                <td><?= $l['lokasi_kegiatan']?></td>
                              </tr>
                              <tr valign="top">
                                <th id="label-meta-agenda">Koordinator</th>
                                <td class="titik">:</td>
                                <td><?= $l['koordinator_kegiatan']?></td>
                              </tr>
                            </table>
                            <?php if (count($agenda['yad']) > 0): ?><hr><?php endif; ?>
					  </li>
			        <?php endforeach; ?>
			      <?php else: ?>
			      	<p align="center">Belum ada agenda mendatang.</p><hr>
			      <?php endif; ?>
		      </ul>
		    </div>

	      <div id="lalu" class="tab-pane fade">
			    <marquee onmouseover="this.stop()" onmouseout="this.start()" scrollamount="2" direction="up" width="100%" height="100" align="center" behavior=”alternate”>
			      <ul class="sidebar-latest">
			        <?php foreach ($agenda['lama'] as $l): ?>
			          <li>
			              <table id="table-agenda" width="100%" style="margin-bottom:10px;">
                              <tr valign="top">
                                <th id="label-meta-agenda" width="35%">Kegiatan</th>
                                <td width="5%" class="titik">:</td>
                                <td><a href="<?= site_url('first/artikel/'.buat_slug($l))?>"><?= $l['judul']?></a></td>
                              </tr>
                        	  <tr valign="top">
                                <th id="label-meta-agenda">Waktu</th>
                                <td class="titik">:</td>
                                <td><div id="small-agenda"><?= tgl_indo2($l['tgl_agenda'])?></div></td>
                              </tr>
                              <tr valign="top">
                                <th id="label-meta-agenda">Lokasi</th>
                                <td class="titik">:</td>
                                <td><?= $l['lokasi_kegiatan']?></td>
                              </tr>
                              <tr valign="top">
                                <th id="label-meta-agenda">Koordinator</th>
                                <td class="titik">:</td>
                                <td><?= $l['koordinator_kegiatan']?></td>
                              </tr>
                            </table>
                            <?php if (count($agenda['yad']) > 0): ?><hr><?php endif; ?>
                        </li>
			        <?php endforeach; ?>
			      </ul>
					</marquee>
	      </div>
	    </div>

    </div>
  </div>
<?php endif; ?>
 
