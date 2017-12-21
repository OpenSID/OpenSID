<?php
/*
 * Menampilkan daftar data suplemen yang dapat digunakan untuk menjelaskan status
 * sasaran penduduk, keluarga, rumah tangga atau kelompok warga
 */

?>
<div id="pageC">
<table class="inner">
	<tr style="vertical-align:top">
		<td class="side-menu">
		<?php
		$this->load->view('suplemen/menu_kiri.php')
		?>
		</td>
		<td class="contentpane">
			<div id="contentpane">
				<div class="ui-layout-center" id="maincontent">
					<?php
					if($tampil == 0){
						echo "<legend>Daftar Data Suplemen</legend>";
					}else{
						echo "<legend>Daftar Data Suplemen dengan Sasaran ".$sasaran[$tampil]."</legend>";
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
									<th>Nama Data</th>
									<th>Sasaran</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$nomer = 0;
							foreach ($suplemen as $item):
								$nomer++;
							?>
								<tr>
									<td class="angka" style="width:40px;"><?php echo $nomer; ?></td>
									<td style="width:120px;" align="center">
										<div class="uibutton-group">
											<a class="uibutton tipsy south fa-tipis" href="<?php echo site_url('suplemen/rincian/1/'.$item["id"].'/'); ?>" title="Rincian"><span class="fa fa-list"></span> Rincian</a>
											<a class="uibutton tipsy south" href="<?php echo site_url('suplemen/edit/'.$item["id"].'/'); ?>" title="Ubah"><span class="fa fa-pencil"></span></a>
											<a class="uibutton tipsy south" href="<?php echo site_url('suplemen/hapus/'.$item["id"].'/'); ?>" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
										</div>
									</td>
									<td><a href="<?php echo site_url('suplemen/rincian/1/'.$item["id"].'/')?>"><?php echo $item["nama"] ?></a></td>
									<td><a href="<?php echo site_url('suplemen/sasaran/'.$item["sasaran"])?>"><?php echo $sasaran[$item["sasaran"]]?></a></td>
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
		$this->load->view('suplemen/panduan.php');
		?>
		</td>
	</tr>
</table>
</div>
