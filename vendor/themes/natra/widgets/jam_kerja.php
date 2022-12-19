<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php if ($jam_kerja) : ?>
<div class="archive_style_1">
	<div class="single_bottom_rightbar">
		<h2 class="box-title">
			<i class="fa fa-clock-o"></i>&ensp;<?= $judul_widget ?>
		</h2>
		<div class="data-case-container">
			<ul class="ants-right-headline">
				<li class="info-case">
					<table style="width: 100%;" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th>Hari</th>
								<th>Mulai</th>
								<th>Selesai</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($jam_kerja as $value) : ?>
							<tr>
								<td><?= $value->nama_hari ?></td>
								<?php if ($value->status) : ?>
									<td><?= $value->jam_masuk ?></td>
									<td><?= $value->jam_keluar ?></td>
								<?php else : ?>
									<td colspan="2"><span class="label label-danger"> Libur </span></td>
								<?php endif ?>
							</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</li>
			</ul>
		</div>
	</div>
</div>
<?php endif ?>