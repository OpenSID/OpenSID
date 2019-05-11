 <!-- Widget Agenda -->

<?php if ($agenda): ?>
  <div class="single_bottom_rightbar">
    <div class="single_bottom_rightbar">
      <h2>Agenda</h2>
    </div>
    <div class="box-body">
      <ul id="ul-menu" class="sidebar-latest">
        <?php foreach ($agenda as $l): ?>
          <table id="table-agenda" width="100%" style="margin-bottom:10px;">
                      <tr>
                        <td>Agenda</td>
                        <td class="titik">:</td>
                        <td><a href="<?= site_url("first/artikel/$l[id_artikel]")?>"><?= $l['judul']?></a></td>
                      </tr>
                	  <tr>
                        <td width="15%">Tanggal</td>
                        <td width="5%" class="titik">:</td>
                        <td width="80%"><div id="small-agenda"><?= tgl_indo($l['tgl_agenda'])?></div></td>
                      </tr>
                      <tr>
                        <td>Lokasi</td>
                        <td class="titik">:</td>
                        <td><?= $l['lokasi_kegiatan']?></td>
                      </tr>
                      <tr>
                        <td>Koordinator</td>
                        <td class="titik">:</td>
                        <td><?= $l['koordinator_kegiatan']?></td>
                      </tr>
                    </table>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
<?php endif; ?>
 
