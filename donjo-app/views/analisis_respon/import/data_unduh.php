<?php
$tgl =  date('d_m_Y');
$subjek = $_SESSION['subjek_tipe'];
$mas = $_SESSION['analisis_master'];
$key = ($periode+3)*($mas+7)*($subjek*3);
$key = "AN".$key;
switch($subjek){
	case 1: $sql = $nama="Nama"; $nomor="NIK";$asubjek="Penduduk"; break;
	case 2: $sql = $nama="Kepala Keluarga"; $nomor="Nomor KK";$asubjek="Keluarga"; break;
	case 3: $sql = $nama="Kepala Rumahtangga"; $nomor="Nomor Rumahtangga";$asubjek="Rumahtangga"; break;
	case 4: $sql = $nama="Nama Kelompok"; $nomor="ID Kelompok";$asubjek="Kelompok"; break;
	default: return null;
}
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=statistik_analisis_jawaban_$tgl.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<style>
td{
	mso-number-format:"\@";
	vertical-align:top;
}
td,th{
	font-size:9pt;
	line-height:9px;
	border:0.5px solid #555;
	cell-padding:-2px;
	margin:0px;
}
</style>
<div id="body">
 <table>
	<tr>
		<th>No</th>
		<th><?php echo $nomor ?></th>
		<th><?php echo $nama ?></th>
		<th>L/P</th>
		<th>Dusun</th>
		<th>RW</th>
		<th>RT</th>
		<th style="background-color:#fefe00">Batas</th>
		<?php
		$tot = count($indikator);
		foreach($indikator as $pt){
			if($pt['par']){
				$w = "";
			}else{
				$w = "width='80'";
			}
			
			if($pt['id_tipe'] == 1){
				
				echo "<td $w>";
				echo $pt['no']."<br>".$pt['pertanyaan'];
				
				if($pt['par']){
					foreach($pt['par'] AS $jb){
						echo "<br>".$jb['kode_jawaban']." ".$jb['jawaban'];
					}
				}
				
				echo "</td>";
				
			}else
			if($pt['id_tipe'] == 2){
				
				echo "<td $w style='background-color:#aaaafe;'>";
				echo $pt['no']."<br>".$pt['pertanyaan'];
				
				if($pt['par']){
					foreach($pt['par'] AS $jb){
						echo "<br>".$jb['kode_jawaban']." ".$jb['jawaban'];
					}
				}
				
				echo "</td>";
				
			} elseif($pt['id_tipe'] == 3) {
				echo "<td style='background-color:#00fe00;'>";
				echo $pt['no']."<br>".$pt['pertanyaan'];
				echo "</td>";
			}else {
				echo "<td style='background-color:#feaaaa;'>";
				echo $pt['no']."<br>".$pt['pertanyaan'];
				echo "</td>";
			}
		}
		?>
	</tr>
	<tr>
		<th colspan='7' style="background-color:#fefe00"></th>
		<th style="background-color:#fefe00"><?php echo $key?></th>
		<?php
		$tot = count($indikator);
		foreach($indikator as $pt){
			echo "<td style='background-color:#fefe00'>";
			echo $pt['nomor'];
			echo "</td>";
		}
		?>
	</tr>
	<?php foreach($main as $data): ?>
	<tr>
		<td><?php echo $data['no']?></td>
		<td><?php echo $data['nid']?></td>
		<td><?php echo $data['nama']?></td>
		<td><?php echo $data['jk']?></td>
		<td><?php echo $data['dusun']?></td>
		<td><?php echo $data['rw']?></td>
		<td><?php echo $data['rt']?></td>
		<td style="background-color:#fefe00"><?php echo $data['id']?></td>
		<?php
		if($data['par']==null){
			for($j=0;$j<$tot;$j++){
				echo "<td></td>";
			}
		}else{
			
			foreach($indikator as $pt){
				//cumawarna
				$bx = "";
				$false = 0;
				foreach($data['par'] AS $jawab){
					$isi = "";
					if($pt['id'] == $jawab['id_indikator'] AND $false == 0){
						
						if($pt['id_tipe'] == 1){
							$isi = $jawab['kode_jawaban'];
						}elseif($pt['id_tipe'] == 2){
							$isi .= $jawab['kode_jawaban'];
						}else{
							$isi = $jawab['jawaban'];
						}
						
						//kosong dia
						if($isi == ""){
							$bx = "style='background-color:#bbffbb;'";
						}
						
						//koreksi
						if($jawab['korek'] == -1){
							$bx = "style='background-color:#ff9999;'";
						}
						
						if($pt['id_tipe'] != 2){
						$false = 1;
						}
					}
				}
				
				echo "<td $bx>";
				
				$false = 0;
				$isi = "";
				foreach($data['par'] AS $jawab){
					if($pt['id'] == $jawab['id_indikator'] AND $false == 0){
						
						if($pt['id_tipe'] == 1){
							$isi = $jawab['kode_jawaban'];
						}elseif($pt['id_tipe'] == 2){
							$isi .= $jawab['kode_jawaban'].",";
						}else{
							$isi = $jawab['jawaban'];
						}
						
						//kosong dia
						if($isi == ""){
							$bx = "style='background-color:#bbffbb;'";
						}
						
						//koreksi
						if($jawab['korek'] == -1){
							$isi = "xxx";
							$bx = "style='background-color:#ff9999;'";
						}
						
						if($pt['id_tipe'] != 2){
						$false = 1;
						}
					}
				}
				
				//DEL last koma
				if($pt['id_tipe'] == 2){
					$jml = strlen($isi);
					$isi = substr($isi,0,$jml-1);
				}
				
				echo $isi;
				echo "</td $bx>";
			}
			
		}
		?>
	</tr>
	<?php endforeach; ?>
</table>
</div>