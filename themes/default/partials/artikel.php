<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if($single_artikel["id"]) : ?>
	<div class="artikel" id="<?php echo 'artikel-'.$single_artikel['judul']?>">
		<h2 class="judul"><?php echo $single_artikel["judul"]?></h2>
		<h3 class="kecil">
			<i class="fa fa-user"></i> <?php echo $single_artikel['owner']?>
			<i class="fa fa-clock-o"></i><?php echo tgl_indo2($single_artikel['tgl_upload']);?>
			<?php if (trim($single_artikel['kategori']) != '') : ?>
				<i class='fa fa-tag'></i> <a href="<?php echo site_url('first/kategori/'.$single_artikel['id_kategori'])?>"><?php echo $single_artikel['kategori']?></a>
			<?php endif; ?>
		</h3>

		<?php if($single_artikel['gambar']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar'])): ?>
			<div class="sampul">
				<a class="group2" href="<?php echo AmbilFotoArtikel($single_artikel['gambar'],'sedang')?>" title=""><img src="<?php echo AmbilFotoArtikel($single_artikel['gambar'],'sedang')?>" /></a>
			</div>
		<?php endif; ?>
		<div class="teks"><?php echo $single_artikel["isi"]?></div>

		<?php	if($single_artikel['dokumen']!='' and is_file(LOKASI_DOKUMEN.$single_artikel['dokumen'])): ?>
			<p>Dokumen Lampiran : <a href="<?php echo base_url().LOKASI_DOKUMEN.$single_artikel['dokumen']?>" title=""><?php echo $single_artikel['link_dokumen']?></a></p>
			<br/>
		<?php endif; ?>
		<?php if($single_artikel['gambar1']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar1'])): ?>
			<div class="sampul2"><a class="group2" href="<?php echo AmbilFotoArtikel($single_artikel['gambar1'],'sedang')?>" title=""><img src="<?php echo AmbilFotoArtikel($single_artikel['gambar1'],'sedang')?>" /></a>
			</div>
		<?php endif; ?>
		<?php if($single_artikel['gambar2']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar2'])): ?>
			<div class="sampul2"><a class="group2" href="<?php echo AmbilFotoArtikel($single_artikel['gambar2'],'sedang')?>" title=""><img src="<?php echo AmbilFotoArtikel($single_artikel['gambar2'],'sedang')?>" /></a>
			</div>
		<?php endif; ?>
		<?php if($single_artikel['gambar3']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar3'])): ?>
			<div class="sampul2"><a class="group2" href="<?php echo AmbilFotoArtikel($single_artikel['gambar3'],'sedang')?>" title=""><img src="<?php echo AmbilFotoArtikel($single_artikel['gambar3'],'sedang')?>" /></a>
			</div>
		<?php endif; ?>
		<div class="form-group" style="clear:both;">
			<ul id="pageshare" title="Bagikan ke teman anda" class="pagination">
				<li class="sbutton" id="fb"><a name="fb_share" href="http://www.facebook.com/sharer.php?u=<?php echo site_url().'first/artikel/'.$single_artikel['id']?>"><i class="fa fa-facebook-square"></i>&nbsp;Share</a></li>
				<li class="sbutton" id="rt"><a href="http://twitter.com/share" class="twitter-share-button"><i class="fa fa-twitter"></i>&nbsp;Tweet</a></li>
				<li class="sbutton" id="gpshare"><a href="https://plus.google.com/share?url=<?php echo site_url().'first/artikel/'.$single_artikel['id'].'&hl=id'?>"><i class="fa fa-google-plus" style="color:red"></i>&nbsp;Bagikan</a></li>
				<li class="sbutton" id="wa_share"><a href="whatsapp://send?text=<?php echo site_url().'first/artikel/'.$single_artikel['id']?>"><i class="fa fa-whatsapp" style="color:green"></i>&nbsp;WhatsApp</a></li>
			</ul>
			<!--
			<script src=\"http://static.ak.fbcdn.net/connect.php/js/FB.Share\" type=\"text/javascript\"></script>
			<script src=\"http://platform.twitter.com/widgets.js\" type=\"text/javascript\"></script>
			-->
		</div>

		<div class="form-group">
			<?php if(is_array($komentar)): ?>
				<div class="box box-default box-solid">
					<div class="box-header">
						<h3 class="box-title">Komentar atas <?php echo $single_artikel["judul"]?></h3>
					</div>
					<div class="box-body">
						<?php foreach($komentar AS $data): ?>
							<?php if($data['enabled']==1): ?>
								<div class="kom-box">
									<div style="font-size:.8em;font-color:#aaa;">
										<i class="fa fa-user"></i><?php echo $data['owner']?> <i class="fa fa-clock-o"></i> <?php echo tgl_indo2($data['tgl_upload'])?>
									</div>
									<div>
										<blockquote><?php echo $data['komentar']?></blockquote>
									</div>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php elseif($single_artikel['boleh_komentar']): ?>
				<div>Silakan tulis komentar dalam formulir berikut ini (Gunakan bahasa yang santun)</div>
			<?php endif; ?>
		</div>
		<div class="form-group group-komentar">
			<?php if($single_artikel['boleh_komentar']): ?>
				<div class="box box-default">
					<div class="box-header">
						<h3 class="box-title">Formulir Komentar (Komentar baru terbit setelah disetujui Admin)</h3>
					</div>

					<!-- Tampilkan hanya jika 'flash_message' ada -->
					<?php $label = !empty($_SESSION['validation_error']) ? 'label-danger' : 'label-info'; ?>
					<?php if ($flash_message): ?>
						<div class="box-header <?php echo $label?>"><?php echo $flash_message?></div>
					<?php endif; ?>
					<div class="box-body">
						<form id="form-komentar" name="form" action="<?php echo site_url('first/add_comment/'.$single_artikel['id'])?>" method="POST" onSubmit="return validasi(this);">
							<table width="100%">
								<tr class="komentar nama">
									<td>Nama</td>
									<td>
										<input type="text" name="owner" maxlength="30" value="<?php echo !empty($_SESSION['post']['owner']) ? $_SESSION['post']['owner'] : $_SESSION['nama'] ?>">
									</td>
								</tr>
								<tr class="komentar alamat">
									<td>Alamat e-mail</td>
									<td>
										<input type="text" name="email" maxlength="30" value="<?php echo $_SESSION['post']['email'] ?>">
									</td>
								</tr>
								<tr class="komentar pesan">
									<td valign="top">Komentar</td>
									<td>
										<textarea name="komentar"><?php echo $_SESSION['post']['komentar']?></textarea>
									</td>
								</tr>
								<tr class="captcha"><td>&nbsp;</td>
									<td>
										<img id="captcha" src="<?php echo base_url().'securimage/securimage_show.php'?>" alt="CAPTCHA Image"/>
										<a href="#" onclick="document.getElementById('captcha').src = '<?php echo base_url()."securimage/securimage_show.php?"?>' + Math.random(); return false">[ Ganti gambar ]</a>
									</td>
								</tr>
								<tr class="captcha_code">
									<td>&nbsp;</td>
									<td>
										<input type="text" name="captcha_code" maxlength="6" value="<?php echo $_SESSION['post']['captcha_code']?>"/> Isikan kode di gambar
									</td>
								</tr>
								<tr class="submit">
									<td>&nbsp;</td>
									<td><input type="submit" value="Kirim"></td>
								</tr>
							</table>
						</form>
					</div>
				</div>
			<?php else: ?>
				<span class='info'>Komentar untuk artikel ini telah ditutup.</span>
			<?php endif; ?>
		</div>
	</div>
<?php else: ?>
	<div class="artikel" id="artikel-blank">
		<div class="box box-danger box-solid">
			<div class="box-header"><h3 class="box-title">Maaf, data tidak ditemukan</h3></div>
			<div class="box-body">
				Anda telah terdampar di halaman yang datanya tidak ada lagi di web ini. Mohon periksa kembali, atau laporkan kepada kami.
			</div>
		</div>
	</div>
<?php endif; ?>


