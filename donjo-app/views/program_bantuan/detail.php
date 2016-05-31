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
					<legend>Detail Program</legend>
					<div>
						<?php
						if($_SESSION["success"]==1){
							echo "
							Data Pembaruan telah tersimpan
							";
						}
						$detail = $program[0];
						echo "
						<div>
							<table class=\"form\">
								<tr><td>Nama Program</td><td><strong>".strtoupper($program[0]["nama"])."</strong></td></tr>
								<tr><td>Sasaran Peserta</td><td><strong>".$sasaran[$program[0]["sasaran"]]."</strong></td></tr>
								<tr><td>Masa Berlaku</td><td><strong>".fTampilTgl($program[0]["sdate"],$program[0]["edate"])."</strong></td></tr>
								<tr><td>Keterangan</td><td><strong>".$program[0]["ndesc"]."</strong></td></tr>
							</table>
						</div>
						";
						
						if($program[0]["status"] == 0){
							echo "
							<div>
								<fieldset>
									<legend>Formulir Penambahan Peserta</legend>
									<div>
										<form action=\"\" id=\"main\" name=\"main\" method=\"POST\">
										<label>Cari Nama Peserta dari Database Desa</label>
										<div id=\"nik\" name=\"nik\"></div>
										</form>
									</div>
								</fieldset>
							</div>
							";
							echo "
							<script>
								$(document).ready(function() {
									var nik = {};
									nik.results = [";
									foreach ($program[2] as $item){
										if(strlen($item["id"])>0){
											echo "{id: ".$item["id"].", name:\"".$item["nama"]."\",info:\"".$item["info"]."\"},\n";
										}
									}
									echo "
									];
									
									$('#nik').flexbox(nik, {
										resultTemplate: '<div><label>No ID : </label>{name}</div><div>{info}</div>',
										watermark: \"Cari nama di sini..\",
										width: 260,
										noResultsText :'Tidak ada no nik yang sesuai..',
										onSelect: function() {
											$('#'+'main').submit();
									}  
									});
								});
							</script>
							";
						}
						$peserta = $program[1];
						?>
						<legend>Daftar Peserta Program</legend>
						<table class="list">
							<thead><tr><th>#</th><th>Nama Peserta</th><th>Keterangan</th></tr></thead>
							<tbody>
							<?php 
							$nomer = 0;
							if(is_array($peserta)){
								foreach ($peserta as $key=>$item){
									$nomer++;
								?>
									<tr>
										<td><?php echo $nomer; ?></td>
										<td><a href="<?php echo site_url('program_bantuan/peserta/'.$program[0]["sasaran"].'/'.$item["nik"].'/')?>"><?php echo $item["nama"] ?></a></td>
										<td><?php echo $item["info"];?></td>
									</tr>
								<?php 
								}
							}
							?>
							</tbody>
						</table>
						<div style="padding:1em 0;">
							<a class="uibutton" href="<?php echo site_url('program_bantuan/unduhsheet/'.$program[0]["id"].'/')?>"><i class="icon icon-download"></i> Unduh dlm format .xls</a>
						</div>
					</div>
				</div>
			</div>
		</td>
	</tr>
</table>
</div>
