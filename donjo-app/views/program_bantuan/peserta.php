<?php
/*
 * program.php
 *
 * Backend View untuk Program Bantuan
 *
 * Copyright 2015 Isnu Suntoro <isnusun@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 *
 */

?>
<div id="pageC">
<table class="inner">
	<tr style="vertical-align:top">
		<td class="side-menu">
		<?php
		$this->load->view('program_bantuan/menu_kiri.php')


		?>
		</td>
		<td class="contentpane">
			<legend>Profil Penerima Manfaat Program</legend>
			<?php
			echo "
			<div style=\"margin-bottom:2em;\">
				<table class=\"form\">
					<tr><td>Nama</td><td><strong>".strtoupper($profil["nama"])."</strong></td></tr>
					<tr><td>Keterangan</td><td><strong>".$profil["ndesc"]."</strong></td></tr>
				</table>
			</div>
			";

			?>
			<legend>Program yang pernah diikuti</legend>
			<div class="table-panel top">
				<table class="list">
					<thead><tr><th>#</th><th>Waktu/Tanggal</th><th>Nama Program</th><th>Keterangan</th></tr></thead>
					<tbody>

						<?php
						$nomer = 0;
						foreach ($programkerja as $item):
							$nomer++;
						?>
							<tr>
								<td class="angka" style="width:40px;"><?php echo $nomer; ?></td>
								<td><?php echo fTampilTgl($item["sdate"],$item["edate"]);?></td>
								<td><a href="<?php echo site_url('program_bantuan/detail/1/'.$item["id"].'/')?>"><?php echo $item["nama"] ?></a></td>
								<td><?php echo $item["ndesc"];?></td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>

		</td>
		<td style="width:250px;" class="contentpane">
		<?php
		$this->load->view('program_bantuan/panduan.php');
		?>
		</td>
	</tr>
</table>
</div>
