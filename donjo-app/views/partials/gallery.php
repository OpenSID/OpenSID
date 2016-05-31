<?php

	echo "
	<div style=\"margin-left:.5em;\">
		<div class=\"box box-primary box-solid\">
			<div class=\"box-header\"><h3 class=\"box-title\">Arsip Galeri ".$desa["nama_desa"]."</h3></div>
			<div class=\"box-body\">
				<ul class=\"thumbnail\">";
			$i=1;
			foreach($gallery AS $data){
				if(is_file("assets/files/galeri/sedang_".$data['gambar'])) {
					echo "
					<li>
						<div class=\"entry\">
						<a class=\"group2\" href=\"". base_url()."assets/files/galeri/sedang_". $data['gambar']."\">
							<img src=\"". base_url()."assets/files/galeri/kecil_". $data['gambar']."\" /></a>
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
		</div>
	</div>
	";

?>
