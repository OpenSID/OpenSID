<!-- Widget Agenda -->

<?php if ($agenda): ?>
  <div class="box box-primary box-solid">
    <div class="box-header">
      <h3 class="box-title"><a href="<?= site_url();?>first/kategori/1000"><i class="fa fa-calendar"></i> Agenda</a></h3>
    </div>
    <div class="box-body">
      <ul id="ul-agenda" class="sidebar-latest">
        <?php foreach ($agenda as $l): ?>
          <li>
		  <table id="table-agenda" width="100%">
			<tr>
				<td colspan="3"><a href="<?= site_url("first/artikel/$l[id_artikel]")?>"><?= $l['judul']?></td>
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
  </div>
<?php endif; ?>
