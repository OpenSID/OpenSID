<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="widget-box bgwhite bordergrey">
	<div class="widget-head bggrad-color2 flexleft">
	<svg viewBox="0 0 24 24">
		<path d="M12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22C17.5 22 22 17.5 22 12S17.5 2 12 2M12.5 12.2L9.8 17L8.5 16.2L11 11.8V7H12.5V12.2Z" />
	</svg>
	<h1><?= $judul_widget ?></h1>
	</div>
	<div class="widget-inner">
		<?php if ($jam_kerja) : ?>
		<table class="jamkerja" style="width: 100%;" cellpadding="0" cellspacing="0">
          <thead>
            <tr>
              <th style="text-align:center;">Hari</th>
              <th style="text-align:center;">Mulai</th>
              <th style="text-align:center;">Selesai</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($jam_kerja as $value) : ?>
            <tr>
              <td><?= $value->nama_hari ?></td>
              <?php if ($value->status) : ?>
                <td style="text-align:center;"><?= $value->jam_masuk ?></td>
                <td style="text-align:center;"><?= $value->jam_keluar ?></td>
              <?php else : ?>
                <td colspan="2" style="text-align:center;"><span class="label bgorange" style="font-weight:500;"> Libur </span></td>
              <?php endif ?>
            </tr>
            <?php endforeach ?>
          </tbody>
        </table>
		<?php else : ?>
		<table class="jamkerja" style="width: 100%;" cellpadding="0" cellspacing="0">
          <thead>
            <tr>
              <th style="text-align:center;">Hari</th>
              <th style="text-align:center;">Mulai</th>
              <th style="text-align:center;">Selesai</th>
            </tr>
          </thead>
          <tbody>
            <tr>
				<td style="text-align:center;">Senin</td>
                <td style="text-align:center;">07.30</td>
                <td style="text-align:center;">16.00</td>
            </tr>
			<tr>
				<td style="text-align:center;">Selasa</td>
                <td style="text-align:center;">07.30</td>
                <td style="text-align:center;">16.00</td>
            </tr>
			<tr>
				<td style="text-align:center;">Rabu</td>
                <td style="text-align:center;">07.30</td>
                <td style="text-align:center;">16.00</td>
            </tr>
			<tr>
				<td style="text-align:center;">Kamis</td>
                <td style="text-align:center;">07.30</td>
                <td style="text-align:center;">16.00</td>
            </tr>
			<tr>
				<td style="text-align:center;">Jum;at</td>
                <td style="text-align:center;">07.30</td>
                <td style="text-align:center;">16.00</td>
            </tr>
			<tr>
				<td style="text-align:center;">Sabtu</td>
                <td colspan="2" style="text-align:center;"><span class="label bgorange" style="font-weight:500;"> Libur </span></td>
            </tr>
			<tr>
				<td style="text-align:center;">Minggu</td>
                <td colspan="2" style="text-align:center;"><span class="label bgorange" style="font-weight:500;"> Libur </span></td>
            </tr>
          </tbody>
        </table>
		<?php endif ?>
	</div>
</div>