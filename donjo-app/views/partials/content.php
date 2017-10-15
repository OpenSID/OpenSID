<?php
if($headline){
	echo "
	<div class=\"box box-danger\">
		<div class=\"box-header with-border\">
			<h3 class=\"box-title\"><a href=\"". site_url("first/artikel/$headline[id]")."\">". $headline['judul'] ."</a></h3>
			<div class=\"pull-right small\">". $headline['owner'].", ". tgl_indo2($headline['tgl_upload'])."</div>
		</div>
		<div class=\"box-body\" style=\"text-align:justify;\">";
		if($headline["gambar"]!=""){
			if(is_file("assets/files/artikel/sedang_".$headline['gambar'])){
				echo "
				<a class=\"group2\" href=\"". base_url()."assets/files/artikel/sedang_".$headline['gambar']."\" title=\"\">
				<img width=\"600\" src=\"". base_url()."assets/files/artikel/sedang_". $headline['gambar']."\" /></a>";
			}else{
				echo "
				<img style=\"margin-right: 10px; margin-bottom: 5px; float: left;\" src=\"". base_url() ."assets/images/404-image-not-found.jpg\" width=\"300\" height=\"180\"/>";
			}
		}
		$head = explode("</p>",$headline['isi']);
		echo $head[0]."</p>";
		
		echo "
		</div>
	</div>";
}
$title = (!empty($judul_kategori))? $judul_kategori: "Artikel Terkini";
if(is_array($title)){
	foreach($title as $item){
		$title= $item;
	}
}
echo "
	<div class=\"box box-primary\" style=\"margin-left:.2	5em;\">
		<div class=\"box-header with-border\">
			<h3 class=\"box-title\">".$title."</h3>
		</div>
		<div class=\"box-body\">
";
function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
if($artikel){
	echo "
	<div>
		<ul class=\"artikel-list artikel-list-in-box\">";
			foreach($artikel as $data){
				$teks = fixTag($data['isi']);
				if(strlen($teks)>310){
					$abstrak = substr($teks,0,strpos($teks," ",300));
				}else{
					$abstrak = $teks;
				}
				$judul = str_replace(" ","-",$data['judul']);
				$judul = preg_replace('/[^A-Za-z0-9\-]/','-', $judul);
				echo "
				<li class=\"artikel\">
					<h3 class=\"judul\"><a href=\"". site_url("first/artikel/$data[id]-$judul") ."\">". $data["judul"] ."</a></h3>
					
					<div class=\"teks\" style=\"text-align:justify;\">
						<div class=\"kecil\"><i class=\"fa fa-clock-o\"></i> ".tgl_indo2($data['tgl_upload'])." <i class=\"fa fa-user\"></i> ".$data['owner']."</div>
						<div class=\"img\">";
							if($data['gambar']!=''){
								if(is_file("assets/files/artikel/kecil_".$data['gambar'])) {
									echo "<img src=\"".base_url()."assets/files/artikel/kecil_".$data['gambar']."\" alt=\"". $data["judul"] ."\"/>";
								}else{
									echo "<img src=\"".base_url()."assets/images/404-image-not-found.jpg\" alt=\"". $data["judul"] ."\" />";
								}
							}
							echo "
						</div>
						".$abstrak." <a href=\"". site_url("first/artikel/".$data["id"]."") ."\">..selengkapnya</a>
					</div>
					<br class=\"clearboth gb\"/>
				</li>";
			}
			echo "
		</ul>
	</div>
	";
	 
}else{
	echo "
	<div class=\"artikel\" id=\"artikel-blank\">
		<div class=\"box box-warning box-solid\">
			<div class=\"box-header\"><h3 class=\"box-title\">Maaf, belum ada data</h3></div>
			<div class=\"box-body\">
				<p>Belum ada artikel yang dituliskan dalam ".$title.".</p>
				<p>Silakan kunjungi situs web kami dalam waktu dekat.</p>
			</div>
		</div>
	</div>
	";
}
echo "
		</div>";
if($artikel){		
	echo "
	<div class=\"box-footer\">
		<ul class=\"pagination pagination-sm no-margin\">";
		if($paging->start_link){
			echo "<li><a href=\"".site_url("first/index/$paging->start_link")."\" title=\"Halaman Pertama\"><i class=\"fa fa-fast-backward\"></i>&nbsp;</a></li>";
		}
		if($paging->prev){
			echo "<li><a href=\"".site_url("first/index/$paging->prev")."\" title=\"Halaman Sebelumnya\"><i class=\"fa fa-backward\"></i>&nbsp;</a></li>";
		}
		
		for($i=$paging->start_link;$i<=$paging->end_link;$i++){
			$strC = ($p == $i)? "class=\"active\"":"";
			echo "<li ".$strC."><a href=\"".site_url("first/index/$i")."\" title=\"Halaman ".$i."\">".$i."</a></li>";
		}
		
		if($paging->next){
			echo "<li><a href=\"".site_url("first/index/$paging->next")."\" title=\"Halaman Selanjutnya\"><i class=\"fa fa-forward\"></i>&nbsp;</a></li>";
		}
		if($paging->end_link){
			echo "<li><a href=\"".site_url("first/index/$paging->end_link")."\" title=\"Halaman Terakhir\"><i class=\"fa fa-fast-forward\"></i>&nbsp;</a></li>";
		}
			echo "";
		echo "
		</ul>
	</div>
	";
}
		echo "
	</div>
";
?>
<!--
<div class="themes nobig2">
<div class="bleft">
 <label>Jumlah Total Artikel:</label>
			<label><strong><?php echo $paging->num_rows?></strong></label>
</div>
 <div class="bright">
 <div class="uibutton-group">
 <?php if($paging->start_link): ?>
				<a href="<?php echo site_url("first/index/$paging->start_link")?>" class="uibutton" >Awal</a>
			<?php endif; ?>
			<?php if($paging->prev): ?>
				<a href="<?php echo site_url("first/index/$paging->prev")?>" class="uibutton" >Prev</a>
			<?php endif; ?>
 </div>
 <div class="uibutton-group">
 
				<?php for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("first/index/$i")?>" <?php jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php endfor; ?>
 </div>
 <div class="uibutton-group">
			<?php if($paging->next): ?>
				<a href="<?php echo site_url("first/index/$paging->next")?>" class="uibutton">Next</a>
			<?php endif; ?>
			<?php if($paging->end_link): ?>
 <a href="<?php echo site_url("first/index/$paging->end_link")?>" class="uibutton">Akhir</a>
			<?php endif; ?>
 </div>
 </div>
</div>
-->