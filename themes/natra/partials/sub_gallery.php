<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
	echo "
	<div style=\"\">
		<div class=\"\">
			<div class=\"archive_style_1\" style=\"margin-top:20px;\">
			 <h2><span class=\"bold_line\"><span></span></span>
				<span class=\"solid_line\"></span>
				<span class=\"title_text\">Galeri Album: $parrent[nama]</span>
			 </h2>
			</div>
				<div class=\"content_bottom_left\" style=\"margin-top:20px;\">	
					<div class=\"single_category wow fadeInDown animated\">
				
						<div class=\"\">
					";$i=1;
			foreach($gallery AS $data){if(is_file(LOKASI_GALERI . "sedang_" . $data['gambar'])) {echo " 
					
					<ul class=\"fashion_catgnav\"> 
					 <li>
							<div class=\"\">
									<img class='img-fluid img-thumbnail' src=\"".AmbilGaleri($data['gambar'], 'kecil')."\" />	
							</div>
						<div class=\"title\">". $data["nama"]."</div>
					 </li>
					</ul>";
					if(fmod($i,2)==0){echo "";}
					$i++;
				}
			}

			echo "
					
				 <br class=\"clearboth\">
				
				
		</div>
				</div> 
			</div>

				<div class=\"pagination_area\">
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
					$strC = ($p == $i)? "class=\"\"":"";
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
