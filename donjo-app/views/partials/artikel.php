<?php
if($single_artikel["id"]){
	echo "
	<div class=\"artikel\" id=\"artikel-".$single_artikel["judul"]."\">
		<h2 class=\"judul\">".$single_artikel["judul"]."</h2>
		<h3 class=\"kecil\"><i class=\"fa fa-user\"></i> ".$single_artikel['owner']." <i class=\"fa fa-clock-o\"></i> ".tgl_indo2($single_artikel['tgl_upload'])."</h3>
		";
		
			if($single_artikel['gambar']!=''){
				if(is_file("assets/files/artikel/kecil_".$single_artikel['gambar'])) {
					echo "<div class=\"sampul\"><a class=\"group2\" href=\"".base_url()."assets/files/artikel/sedang_".$single_artikel['gambar']."\" title=\"\">
					<img src=\"".base_url()."assets/files/artikel/kecil_".$single_artikel['gambar']."\" /></a></div>";
				}
			}
		echo "
		<div class=\"teks\" style=\"text-align:justify;\">".$single_artikel["isi"]."</div>";
		
			if($single_artikel['dokumen']!=''){
				if(is_file("assets/files/dokumen/".$single_artikel['dokumen'])) {
					echo "<p>Dokumen Lampiran : <a target=\"_blank\" href=\"".base_url()."assets/files/dokumen/".$single_artikel['dokumen']."\" title=\"\">".$single_artikel['link_dokumen']."</a></p><br/>";
				}
			}
			if($single_artikel['gambar1']!=''){
				if(is_file("assets/files/artikel/kecil_".$single_artikel['gambar1'])) {
					echo "<div class=\"sampul2\"><a class=\"group2\" href=\"".base_url()."assets/files/artikel/sedang_".$single_artikel['gambar1']."\" title=\"\">
					<img src=\"".base_url()."assets/files/artikel/kecil_".$single_artikel['gambar1']."\" /></a></div>";
				}
			}
			if($single_artikel['gambar2']!=''){
				if(is_file("assets/files/artikel/kecil_".$single_artikel['gambar2'])) {
					echo "<div class=\"sampul2\"><a class=\"group2\" href=\"".base_url()."assets/files/artikel/sedang_".$single_artikel['gambar2']."\" title=\"\">
					<img src=\"".base_url()."assets/files/artikel/kecil_".$single_artikel['gambar2']."\" /></a></div>";
				}
			}
			if($single_artikel['gambar3']!=''){
				if(is_file("assets/files/artikel/kecil_".$single_artikel['gambar3'])) {
					echo "<div class=\"sampul2\"><a class=\"group2\" href=\"".base_url()."assets/files/artikel/sedang_".$single_artikel['gambar3']."\" title=\"\">
					<img src=\"".base_url()."assets/files/artikel/kecil_".$single_artikel['gambar3']."\" /></a></div>";
				}
			}
		echo "
		<div class=\"form-group\" style=\"clear:both;\">
			<ul id=\"pageshare\" title=\"bagikan ke teman anda\" class=\"pagination\">
				<li class=\"sbutton\" id=\"fb\"><a target=\"_blank\" name=\"fb_share\" href=\"http://www.facebook.com/sharer.php?u=\"".site_url()."first/artikel/".$single_artikel["id"]."\">Share</a></li>
				<li class=\"sbutton\" id=\"rt\"><a target=\"_blank\" href= \"http://twitter.com/share\" class=\"twitter-share-button\">Tweet</a></li>
			</ul>
			<!--
			<script src=\"http://static.ak.fbcdn.net/connect.php/js/FB.Share\" type=\"text/javascript\"></script>
			<script src=\"http://platform.twitter.com/widgets.js\" type=\"text/javascript\"></script>
			-->
		</div>
		<div class=\"form-group\">
		";
		if(is_array($komentar)){
			echo "
			<div class=\"box box-default box-solid\">
				<div class=\"box-header\"><h3 class=\"box-title\">Komentar atas ".$single_artikel["judul"]."</h3></div>
				<div class=\"box-body\">
			";
			foreach($komentar AS $data){
				if($data['enabled']==1){
					echo "
					<div class=\"kom-box\">
						<div style=\"font-size:.8em;font-color:#aaa;\">
							<i class=\"fa fa-user\"></i> ".$data['owner']." <i class=\"fa fa-clock-o\"></i> ".tgl_indo2($data['tgl_upload'])."
						</div>
						<div>
							<blockquote>".$data['komentar']."</blockquote>
						</div>
					</div>";
				}
			}
			echo "
				</div>
			</div>		
			";
		}else{
			echo "<div>Belum ada komentar atas artikel ini, silakan tuliskan dalam formulir berikut ini</div>";
		}
		echo "
		</div>
		<div class=\"form-group\">
			<div class=\"box box-default\">
				<div class=\"box-header\"><h3 class=\"box-title\">Formulir Penulisan Komentar</h3></div>
				<div class=\"box-body\">
					<form name=\"form\" action=\"".site_url("first/add_comment/".$single_artikel["id"])."\" method=POST onSubmit=\"return validasi(this)\">
					<table width=100%>
						<tr class=\"komentar\"><td>Nama</td><td> <input type=text name=\"owner\" size=20 maxlength=30></td></tr>
						<tr class=\"komentar\"><td>Alamat e-mail</td><td> <input type=text name=\"email\" size=20 maxlength=30></td></tr>
						<tr class=\"komentar\"><td valign=top>Komentar</td><td> <textarea name=\"komentar\" style=\"width: 300px; height: 100px;\"></textarea></td></tr>
						<tr><td>&nbsp;</td><td><input type=\"submit\" value=\"Kirim\"></td></tr>
					</table>
					</form>
				</div>
			</div>		
		</div>
	</div>
	";
}else{
	echo "
	<div class=\"artikel\" id=\"artikel-blank\">
		<div class=\"box box-danger box-solid\">
			<div class=\"box-header\"><h3 class=\"box-title\">Maaf, data tidak ditemukan</h3></div>
			<div class=\"box-body\">
				Anda telah terdampar di halaman yang datanya tidak ada lagi di web ini. Mohon periksa kembali, atau laporkan kepada kami.
			</div>
		</div>
	</div>
	";
}
?>