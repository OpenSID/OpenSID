<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
	echo "
	<div style=\"margin-left:.5em;\">
		<div class=\"box box-primary box-solid\">
			<div class=\"box-header\"><h3 class=\"box-title\">Galeri Album: $parrent[nama]</h3></div>
			<div class=\"box-body\">
				<ul class=\"thumbnail\">";
			$i=1;
			foreach($gallery AS $data){
				if(is_file(LOKASI_GALERI . "sedang_" . $data['gambar'])) {
					echo "
					<li>
						<div class=\"entry\">
						<a class=\"group2\" href=\"".AmbilGaleri($data['gambar'], 'sedang')."\" title=\"". $data['nama']."\">
							<img src=\"".AmbilGaleri($data['gambar'], 'kecil')."\" /></a>
						</div>
						<div class=\"title\">". $data["nama"]."</div>
					</li>";
					if(fmod($i,2)==0){echo "<br class=\"clearboth\">";}
					$i++;
				}
			}

			echo "
				</ul>
				<br class=\"clearboth\">
			</div>

			<div class=\"box-footer\">
			<div>Halaman ".$p." dari ".$paging->end_link."</div>
				<ul class=\"pagination pagination-sm no-margin\">";
				// TODO : butuh helper untuk menggenerate html tag untuk paging
				if($paging->start_link){
					echo "<li><a href=\"".site_url("first/sub_gallery/$parrent[id]/$paging->start_link")."\" title=\"Halaman Pertama\"><i class=\"fa fa-fast-backward\"></i>&nbsp;</a></li>";
				}
				if($paging->prev){
					echo "<li><a href=\"".site_url("first/sub_gallery/$parrent[id]/$paging->prev")."\" title=\"Halaman Sebelumnya\"><i class=\"fa fa-backward\"></i>&nbsp;</a></li>";
				}

				foreach($pages as $i) {
					$strC = ($p == $i)? "class=\"active\"":"";
					echo "<li ".$strC."><a href=\"".site_url("first/sub_gallery/$parrent[id]/$i")."\" title=\"Halaman ".$i."\">".$i."</a></li>";
				}

				if($paging->next){
					echo "<li><a href=\"".site_url("first/sub_gallery/$parrent[id]/$paging->next")."\" title=\"Halaman Selanjutnya\"><i class=\"fa fa-forward\"></i>&nbsp;</a></li>";
				}
				if($paging->end_link){
					echo "<li><a href=\"".site_url("first/sub_gallery/$parrent[id]/$paging->end_link")."\" title=\"Halaman Terakhir\"><i class=\"fa fa-fast-forward\"></i>&nbsp;</a></li>";
				}
					echo "";
				echo "
				</ul>
			</div>
		</div>
	</div>
	";

?>
