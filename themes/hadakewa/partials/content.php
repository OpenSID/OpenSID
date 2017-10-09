<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if(count($slide_galeri)>0 OR count($slide_artikel)>0){
	$this->load->view($folder_themes."/layouts/slider.php");
}
if($headline){
	$abstrak_headline = potong_teks($headline['isi'], 700);
	echo "
	<div id=\"headline\" class=\"box box-danger\">
		<div class=\"box-header with-border\">
			<h3 class=\"box-title\"><a href=\"". site_url("first/artikel/$headline[id]")."\">". $headline['judul'] ."</a></h3>
			<div class=\"pull-right small\">". $headline['owner'].", ". tgl_indo2($headline['tgl_upload'])."</div>
		</div>
		<div class=\"box-body\">";
		if($headline["gambar"]!=""){
			if(is_file(LOKASI_FOTO_ARTIKEL."sedang_".$headline['gambar'])){
				echo "
				<a class=\"group2\" href=\"".AmbilFotoArtikel($headline['gambar'],'sedang')."\" title=\"\">
				<img src=\"".AmbilFotoArtikel($headline['gambar'],'sedang')."\" /></a>";
			}else{
				echo "
				<img style=\"margin-right: 10px; margin-bottom: 5px; float: left;\" src=\"". base_url() ."assets/images/404-image-not-found.jpg\" width=\"300\" height=\"180\"/>";
			}
		}
		echo
			$abstrak_headline." <a href=\"". site_url("first/artikel/".$headline["id"]."") ."\">..selengkapnya</a>";


		echo "
		</div>
	</div>";

}

/*
 * List Konten
 * */
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
		<div class=\"box-body-artikel\">
";

if($artikel){
	echo "
	<div>
		<ul class=\"artikel-list artikel-list-in-box\">";
			foreach($artikel as $data){
				$abstrak = potong_teks($data['isi'], 300);
				echo "
				<li class=\"artikel\">
					<h3 class=\"judul\"><a href=\"". site_url("first/artikel/$data[id]") ."\">". $data["judul"] ."</a></h3>

					<div class=\"teks\">
						<div class=\"kecil\"><i class=\"fa fa-calendar-check-o\"></i> ".tgl_indo2($data['tgl_upload'])." - <i class=\"fa fa-user-circle-o\"></i>  ".$data['owner']."</div>
						<div class=\"img\">";
							if($data['gambar']!=''){
								if(is_file(LOKASI_FOTO_ARTIKEL."kecil_".$data['gambar'])) {
									echo "<img src=\"".AmbilFotoArtikel($data['gambar'],'kecil')."\" alt=\"". $data["judul"] ."\"/>";
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
	/*
	 * Pengaturan halaman
	 * */

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
			echo "<li><a href=\"".site_url("first/".$paging_page."/$paging->start_link")."\" title=\"Halaman Pertama\"><i class=\"fa fa-fast-backward\"></i>&nbsp;</a></li>";
		}
		if($paging->prev){
			echo "<li><a href=\"".site_url("first/".$paging_page."/$paging->prev")."\" title=\"Halaman Sebelumnya\"><i class=\"fa fa-backward\"></i>&nbsp;</a></li>";
		}

		for($i=$paging->start_link;$i<=$paging->end_link;$i++){
			$strC = ($p == $i)? "class=\"active\"":"";
			echo "<li ".$strC."><a href=\"".site_url("first/".$paging_page."/$i")."\" title=\"Halaman ".$i."\">".$i."</a></li>";
		}

		if($paging->next){
			echo "<li><a href=\"".site_url("first/".$paging_page."/$paging->next")."\" title=\"Halaman Selanjutnya\"><i class=\"fa fa-forward\"></i>&nbsp;</a></li>";
		}
		if($paging->end_link){
			echo "<li><a href=\"".site_url("first/".$paging_page."/$paging->end_link")."\" title=\"Halaman Terakhir\"><i class=\"fa fa-fast-forward\"></i>&nbsp;</a></li>";
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
