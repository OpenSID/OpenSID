<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
	echo "
	<div style=\"margin-left:.5em;\">
	<div class=\"box box-primary box-solid\">
		<div class=\"box-header\"><h3 class=\"box-title\">Arsip Konten Situs Web ".$desa["nama_desa"]."</h3></div>
		<div class=\"box-body\">";
		if(count($farsip)>0){
			echo "
			<table class=\"table table-stripped\">
				<thead>
				</thead>
				<tbody>";
				foreach($farsip AS $data){
					echo "
					<tr><td class=\"angka\">".$data["no"]."</td>
					<td>".$data["tgl"]."</td>
					<td>".$data["isi"]."</td>
					</tr>
					";
				}
				echo "
				</tbody>
			</table>
			";

		}else{
			echo "Belum ada arsip konten web.";
		}

			echo "
		</div>";
		if(count($farsip)>0){
			echo "
			<div class=\"box-footer\">
				<ul class=\"pagination pagination-sm no-margin\">";
				if($paging->start_link){
					echo "<li><a href=\"".site_url("first/arsip/$paging->start_link")."\" title=\"Halaman Pertama\"><i class=\"fa fa-fast-backward\"></i>&nbsp;</a></li>";
				}
				if($paging->prev){
					echo "<li><a href=\"".site_url("first/arsip/$paging->prev")."\" title=\"Halaman Sebelumnya\"><i class=\"fa fa-backward\"></i>&nbsp;</a></li>";
				}

				for($i=$paging->start_link;$i<=$paging->end_link;$i++){
					$strC = ($p == $i)? "class=\"active\"":"";
					echo "<li ".$strC."><a href=\"".site_url("first/arsip/$i")."\" title=\"Halaman ".$i."\">".$i."</a></li>";
				}

				if($paging->next){
					echo "<li><a href=\"".site_url("first/arsip/$paging->next")."\" title=\"Halaman Selanjutnya\"><i class=\"fa fa-forward\"></i>&nbsp;</a></li>";
				}
				if($paging->end_link){
					echo "<li><a href=\"".site_url("first/arsip/$paging->end_link")."\" title=\"Halaman Terakhir\"><i class=\"fa fa-fast-forward\"></i>&nbsp;</a></li>";
				}
					echo "";
				echo "
				</ul>
			</div>
			";
		}
		echo "
	</div>
	</div>
	";
?>

