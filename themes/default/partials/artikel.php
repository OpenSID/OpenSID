<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
/*
echo "
	<div class=\"callout callout-danger\">
		<h4>Pesan</h4>
		<p>Kontent:</p>
		<p>".$single_artikel["id"]."</p>
	</div>

";

*/

if($single_artikel["id"]){
	echo "
	<div class=\"artikel\" id=\"artikel-".$single_artikel["judul"]."\">
		<h2 class=\"judul\">".$single_artikel["judul"]."</h2>
		<h3 class=\"kecil\"><i class=\"fa fa-user\"></i> ".$single_artikel['owner']." <i class=\"fa fa-clock-o\"></i> ".tgl_indo2($single_artikel['tgl_upload'])."</h3>
		";

			if($single_artikel['gambar']!=''){
				if(is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar'])) {
					echo "<div class=\"sampul\"><a class=\"group2\" href=\"".AmbilFotoArtikel($single_artikel['gambar'],'sedang')."\" title=\"\">
					<img src=\"".AmbilFotoArtikel($single_artikel['gambar'],'sedang')."\" /></a></div>";
				}
			}
		echo "
		<div class=\"teks\">".$single_artikel["isi"]."</div>";

			if($single_artikel['dokumen']!=''){
				if(is_file(LOKASI_DOKUMEN.$single_artikel['dokumen'])) {
					echo "<p>Dokumen Lampiran : <a href=\"".base_url().LOKASI_DOKUMEN.$single_artikel['dokumen']."\" title=\"\">".$single_artikel['link_dokumen']."</a></p><br/>";
				}
			}
			if($single_artikel['gambar1']!=''){
				if(is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar1'])) {
					echo "<div class=\"sampul2\"><a class=\"group2\" href=\"".AmbilFotoArtikel($single_artikel['gambar1'],'sedang')."\" title=\"\">
					<img src=\"".AmbilFotoArtikel($single_artikel['gambar1'],'sedang')."\" /></a></div>";
				}
			}
			if($single_artikel['gambar2']!=''){
				if(is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar2'])) {
					echo "<div class=\"sampul2\"><a class=\"group2\" href=\"".AmbilFotoArtikel($single_artikel['gambar2'],'sedang')."\" title=\"\">
					<img src=\"".AmbilFotoArtikel($single_artikel['gambar2'],'sedang')."\" /></a></div>";
				}
			}
			if($single_artikel['gambar3']!=''){
				if(is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar3'])) {
					echo "<div class=\"sampul2\"><a class=\"group2\" href=\"".AmbilFotoArtikel($single_artikel['gambar3'],'sedang')."\" title=\"\">
					<img src=\"".AmbilFotoArtikel($single_artikel['gambar3'],'sedang')."\" /></a></div>";
				}
			}
		echo "
		<div class=\"form-group\" style=\"clear:both;\">
			<ul id=\"pageshare\" title=\"bagikan ke teman anda\" class=\"pagination\">
				<li class=\"sbutton\" id=\"fb\"><a name=\"fb_share\" href=\"http://www.facebook.com/sharer.php?u=".site_url()."first/artikel/".$single_artikel["id"]."\"><i class=\"fa fa-facebook-square\"></i>&nbsp;Share</a></li>
				<li class=\"sbutton\" id=\"rt\"><a href=\"http://twitter.com/share\" class=\"twitter-share-button\"><i class=\"fa fa-twitter\"></i>&nbsp;Tweet</a></li>
				<li class=\"sbutton\" id=\"gpshare\"><a href=\"https://plus.google.com/share?url=".site_url()."first/artikel/".$single_artikel["id"]."&hl=id"."\"><i class=\"fa fa-google-plus\" style=\"color:red\"></i>&nbsp;Bagikan</a></li>";
				echo "<li class=\"sbutton\" id=\"wa_share\"><a href=\"whatsapp://send?text=".site_url()."first/artikel/".$single_artikel["id"]."\"><i class=\"fa fa-whatsapp\"style=\"color:green\"></i>&nbsp;WhatsApp</a></li>";
		echo "
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
				<div class=\"box-body\">";

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
		}elseif($single_artikel['boleh_komentar']){
			echo "<div>Silakan tulis komentar dalam formulir berikut ini (Gunakan bahasa yang santun)</div>";
		}

		echo "
		</div>
			<div class=\"form-group group-komentar\">";
		if($single_artikel['boleh_komentar']){
			echo "
				<div class=\"box box-default\">
					<div class=\"box-header\"><h3 class=\"box-title\">Formulir Komentar (Komentar baru terbit setelah disetujui Admin)</h3></div>";

					// tampilkan hanya jika 'flash_message' ada
					if (isset($_SESSION['validation_error']) AND $_SESSION['validation_error']) $label = 'label-danger'; else $label = 'label-info';
					if ($flash_message) {
						echo "<div class='box-header ".$label."'>$flash_message</div>";
					}
					echo "
					<div class=\"box-body\">
						<form id=\"form-komentar\" name=\"form\" action=\"".site_url("first/add_comment/".$single_artikel["id"])."\" method=POST onSubmit=\"return validasi(this)\">
						<table width=100%>
							<tr class=\"komentar nama\"><td>Nama</td><td> <input type=text name=\"owner\" maxlength=30 value=\"".$_SESSION['post']['owner']."\"></td></tr>
							<tr class=\"komentar alamat\"><td>Alamat e-mail</td><td> <input type=text name=\"email\" maxlength=30 value=\"".$_SESSION['post']['email']."\"></td></tr>
							<tr class=\"komentar pesan\"><td valign=top>Komentar</td><td> <textarea name=\"komentar\">".$_SESSION['post']['komentar']."</textarea></td></tr>
							<tr class=\"captcha\"><td>&nbsp;</td>
								<td>
									<img id=\"captcha\" src=\"".base_url()."securimage/securimage_show.php\" alt=\"CAPTCHA Image\"/>
									<a href=\"#\" onclick=\"document.getElementById('captcha').src = '".base_url()."securimage/securimage_show.php?' + Math.random(); return false\">[ Ganti gambar ]</a>
								</td></tr>
							<tr class=\"captcha_code\"><td>&nbsp;</td><td>
									<input type=\"text\" name=\"captcha_code\" maxlength=\"6\" value=\"".$_SESSION['post']['captcha_code']."\"/> Isikan kode di gambar
								</td></tr>
							<tr class=\"submit\"><td>&nbsp;</td><td><input type=\"submit\" value=\"Kirim\"></td></tr>
						</table>
						</form>
					</div>
				</div>";
		}else{
			echo "
				<span class='info'>Komentar untuk artikel ini telah ditutup.</span>
			";
		};
		echo "
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
