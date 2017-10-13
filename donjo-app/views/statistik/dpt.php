<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style type="text/css">
	th.kanan {text-align: right;}
</style>
<?php

	echo "
	<div class=\"box box-danger\">
		<div class=\"box-header with-border\">
			<h3 class=\"box-title\">Daftar Pemilih Tetap (DPT) Berdasarkan Wilayah</h3>
		</div>
		<div class=\"box-body\">";
			if(count($main) > 0){
				echo "
			<table class=\"table table-striped\">
				<thead><tr>
					<th>No</th>
					<th>Nama Dusun</th>
					<th>RW</th>
					<th class=\"kanan\">Jiwa</th>
					<th class=\"kanan\">Lk</th>
					<th class=\"kanan\">Pr</th>
				</tr></thead>
				<tbody>
					";
					foreach($main as $data){
						echo "<tr>
							<td>".$data['no']."</td>
							<td>".strtoupper($data['dusun'])."</td>
							<td>".strtoupper($data['rw'])."</td>
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

