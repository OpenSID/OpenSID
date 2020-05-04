<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
	echo "
	<div style=\"content_bottom_left\">
			<div class=\"single_page_area\"><h2>Arsip Galeri ".$desa["nama_desa"]."</h2></div>
				<ul>";
			$i=1;
			foreach($gallery AS $data){
				if(is_file(LOKASI_GALERI . "sedang_" . $data['gambar'])) {
					echo "
					<li>
						<div class=\"single_page_content\">
						<a class=\"group2\" href=\"".site_url()."first/sub_gallery/". $data['id']."\">
							<img class='img-fluid img-thumbnail' src=\"".AmbilGaleri($data['gambar'],'kecil')."\" /></a>
						</div>
						<div class=\"title\"><a href=\"". site_url()."first/sub_gallery/". $data['id']."\" title=\"".$data["nama"]."\">Album : ". $data["nama"]."</a></div>
					</li>";
					if(fmod($i,2)==0){echo "<br class=\"clearboth\">";}
					$i++;
				}
			}
			echo "
				</ul>
				<br class=\"clearboth\">
			</div>

			<div class=\"pagination_area\">
			<div>Halaman ".$p." dari ".$paging->end_link."</div>
				<ul class=\"pagination\">";
				// TODO : butuh helper untuk menggenerate html tag untuk paging
				if($paging->start_link){
					echo "<li><a href=\"".site_url("first/gallery/$paging->start_link")."\" title=\"Halaman Pertama\"><i class=\"fa fa-fast-backward\"></i>&nbsp;</a></li>";
				}
				if($paging->prev){
					echo "<li><a href=\"".site_url("first/gallery/$paging->prev")."\" title=\"Halaman Sebelumnya\"><i class=\"fa fa-backward\"></i>&nbsp;</a></li>";
				}

				foreach($pages as $i) {
					$strC = ($p == $i)? "class=\"active\"":"";
					echo "<li ".$strC."><a href=\"".site_url("first/gallery/$i")."\" title=\"Halaman ".$i."\">".$i."</a></li>";
				}

				if($paging->next){
					echo "<li><a href=\"".site_url("first/gallery/$paging->next")."\" title=\"Halaman Selanjutnya\"><i class=\"fa fa-forward\"></i>&nbsp;</a></li>";
				}
				if($paging->end_link){
					echo "<li><a href=\"".site_url("first/gallery/$paging->end_link")."\" title=\"Halaman Terakhir\"><i class=\"fa fa-fast-forward\"></i>&nbsp;</a></li>";
				}
					echo "";
				echo "
				</ul>
	</div>
	";

?>
