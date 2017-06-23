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
			<div id="contentpane">
				<div class="ui-layout-center" id="maincontent">
					<?php
					if($tampil == 0){
						echo "<legend>Daftar Program Bantuan</legend>";
					}else{
						echo "<legend>Daftar Program Bantuan dengan Sasaran ".$sasaran[$tampil]."</legend>";
					}

					if($_SESSION["success"]==1){
						echo "
						<div>
						".$_SESSION["pesan"]."
						</div>";
						$_SESSION["success"]==0;
					}

					?>

					<div class="table-panel top">
						<table class="list">
							<thead>
								<tr>
									<th>#</th>
									<th>Aksi</th>
									<th>Nama Program</th>
									<th>Masa Berlaku</th>
									<th>Sasaran</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$nomer = 0;
							foreach ($program as $item):
								$nomer++;
							?>
								<tr>
									<td class="angka" style="width:40px;"><?php echo $nomer; ?></td>
									<td style="width:120px;" align="center">
										<div class="uibutton-group">
											<a class="uibutton tipsy south fa-tipis" href="<?php echo site_url('program_bantuan/detail/1/'.$item["id"].'/'); ?>" title="Rincian"><span class="fa fa-list"></span> Rincian</a>
											<a class="uibutton tipsy south" href="<?php echo site_url('program_bantuan/edit/'.$item["id"].'/'); ?>" title="Ubah"><span class="fa fa-pencil"></span></a>
											<a class="uibutton tipsy south" href="<?php echo site_url('program_bantuan/hapus/'.$item["id"].'/'); ?>" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
										</div>
									</td>
									<td><a href="<?php echo site_url('program_bantuan/detail/1/'.$item["id"].'/')?>"><?php echo $item["nama"] ?></a></td>
									<td align="center"><?php echo fTampilTgl($item["sdate"],$item["edate"]);?></td>
									<td><a href="<?php echo site_url('program_bantuan/sasaran/'.$item["sasaran"].'/'.$sasaran[$item["sasaran"]].'')?>"><?php echo $sasaran[$item["sasaran"]]?></a></td>
								</tr>
							<?php endforeach ?>
							</tbody>
						</table>
					</div>
				</div>
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
