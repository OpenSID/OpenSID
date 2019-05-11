<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
echo "
<div class='single_page_area'>

	<div class='single_page_area'>
		<h2>Daftar Calon Pemilih (pada tgl pemilihan $tanggal_pemilihan)</h2>

		<div class='table-responsive'>
		<table id='dpt' class='table table-bordered table-striped'>
		<thead>
		<tr>
			<th>No</th>
			<th>RW</th>
			<th>Jiwa</th>
			<th>L</th>
			<th>P</th>
		</tr>
		</thead>";
		if(count($main) > 0){
			echo "
			<tbody>";

			foreach($main as $data){
				echo "<tr>
					<td>".$data['no']."</td>
					<td>".strtoupper($data['rw'])."</td>
					<td class='text-right'>".$data['jumlah_warga']."</td>
					<td class='text-right'>".$data['jumlah_warga_l']."</td>
					<td class='text-right'>".$data['jumlah_warga_p']."</td>
				</tr>";
			}
			echo "
			</tbody>

			<tfooter>
			<tr>
				<th colspan=2 class='text-right'>TOTAL</th>
				<th class='text-right'>".$total['total_warga']."</th>
				<th class='text-right'>".$total['total_warga_l']."</th>
				<th class='text-right'>".$total['total_warga_p']."</th>
			</tr>
			</tfooter>";

		} else {
			echo "<tr><td colspan=6 class='text-center'>Daftar masih kosong</td></tr>";
		}

		echo "
		</table>
		</div>
	</div>

</div> <!-- .list-frame -->";

