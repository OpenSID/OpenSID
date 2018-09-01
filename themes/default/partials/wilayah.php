<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php

	echo "
	<div class=\"box box-danger\">
		<div class=\"box-header with-border\">
			<h3 class=\"box-title\">Data Demografi Berdasar ". $heading."</h3>
		</div>
		<div class=\"box-body\">";
			if(count($main) > 0){
				echo "
			<table class=\"table table-striped\">
				<thead><tr>
					<th>No</th>
					<th>Nama Dusun</th>
					<th>Nama Kepala Dusun</th>
					<th>Jumlah RT</th>
					<th>Jumlah KK</th>
					<th>Jiwa</th>
					<th>Lk</th>
					<th>Pr</th>
				</tr></thead>
				<tbody>
					";
					foreach($main as $data){
						echo "<tr>
							<td>".$data['no']."</td>
							<td>".strtoupper(unpenetration(ununderscore($data['dusun'])))."</td>
							<td>".strtoupper(unpenetration($data['nama_kadus']))."</td>
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
							<th colspan=\"3\" class=\"angka\">TOTAL</th>
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

