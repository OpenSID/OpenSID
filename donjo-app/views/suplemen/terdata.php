<div id="pageC">
<table class="inner">
	<tr style="vertical-align:top">
		<td class="side-menu">
		<?php
		$this->load->view('suplemen/menu_kiri.php')


		?>
		</td>
		<td class="contentpane">
			<legend>Profil Terdata Data Suplemen</legend>
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
			<legend>Suplemen yang terdata</legend>
			<div class="table-panel top">
				<table class="list">
					<thead>
						<tr>
							<th>#</th>
							<th>Nama Suplemen</th>
							<th>Keterangan</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$nomer = 0;
						foreach ($daftar_suplemen as $item):
							$nomer++;
						?>
							<tr>
								<td class="angka" style="width:40px;"><?php echo $nomer; ?></td>
								<td><a href="<?php echo site_url('suplemen/rincian/1/'.$item["id"].'/')?>"><?php echo $item["nama"] ?></a></td>
								<td><?php echo $item["keterangan"];?></td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
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
