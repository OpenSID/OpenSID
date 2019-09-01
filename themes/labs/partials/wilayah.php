<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php

	echo "
	<div class=\"block block-themed\">
		<div class=\"block-header block-header-default\">
		<h2 class=\"h4 font-w400 block-title\">  <i class=\"si si-pointer\"></i> Data Demografi Berdasar ". $heading."</h2>
		</div>
		<div class=\"block-content\">";
			if(count($main) > 0){
				echo "
			<table class=\"table table-striped\">
				<thead><tr>
					<th>No</th>
					<th>Jumlah RT</th>
					<th>Jumlah KK</th>
					<th>Jiwa</th>
					<th>L</th>
					<th>P</th>
				</tr></thead>
				<tbody>
					";
					foreach($main as $data){
						echo "<tr>
							<td>".$data['no']."</td>
							<td class=\"angka\">".$data['jumlah_rt']."</td>
							<td class=\"angka\">".$data['jumlah_kk']."</td>
							<td class=\"angka\">".$data['jumlah_warga']."</td>
							<td class=\"angka\">".$data['jumlah_warga_l']."</td>
							<td class=\"angka\">".$data['jumlah_warga_p']."</td>
						</tr>";
					}
					echo "
					</tbody>
					<tfooter>
						<tr>
							<th colspan=\"1\" class=\"angka\">TOTAL</th>
							<th class=\"angka\">".$total['total_rt']."</th>
							<th class=\"angka\">".$total['total_kk']."</th>
							<th class=\"angka\">".$total['total_warga']."</th>
							<th class=\"angka\">".$total['total_warga_l']."</th>
							<th class=\"angka\">".$total['total_warga_p']."</th>
						</tr>
					</tfooter>
				</table>";
			}else{
				echo "<div class=\"\">Belum ada data</div>";
			}

		echo "
		</div>
	</div>";
?>

