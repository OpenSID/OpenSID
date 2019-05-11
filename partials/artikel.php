<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if($single_artikel["id"]) : ?>
	<div class="single_page_area" id="<?php echo 'artikel-'.$single_artikel['judul']?>">
		<h2 class="judul"><?php echo $single_artikel["judul"]?></h2>
		<div class="post_commentbox">
			<i class="fa fa-clock-o"></i> <a><?php echo tgl_indo2($single_artikel['tgl_upload']);?></a>
			<i class="fa fa-user"></i> <a><?php echo $single_artikel['owner']?></a>
			<?php if (trim($single_artikel['kategori']) != '') : ?>
				<i class='fa fa-tag'></i> <a href="<?php echo site_url('first/kategori/'.$single_artikel['id_kategori'])?>"><?php echo $single_artikel['kategori']?></a>
			<?php endif; ?>
		</div>
		<div class="single_page_content" style="margin-bottom:10px;">	
		<?php if($single_artikel["isi"]!='<p>&nbsp;&nbsp;</p>'): ?>
		<?php if($single_artikel['gambar']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar'])): ?>
		<div class="sampul">
				<img width="300px" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?php echo AmbilFotoArtikel($single_artikel['gambar'],'sedang')?>"/>
			</div>
		<?php endif; ?>
		<?php endif; ?>
		<?php if($single_artikel["isi"]=='<p>&nbsp;&nbsp;</p>'): ?>
			<div class="sampul">
				<img width="100%" class="img-fluid img-thumbnail" src="<?php echo AmbilFotoArtikel($single_artikel['gambar'],'sedang')?>"/>
			</div>
		<?php endif; ?>
		
		<div class="teks"><?php echo $single_artikel["isi"]?></div>

		<?php	if($single_artikel['dokumen']!='' and is_file(LOKASI_DOKUMEN.$single_artikel['dokumen'])): ?>
			<p>Dokumen Lampiran : <a href="<?php echo base_url().LOKASI_DOKUMEN.$single_artikel['dokumen']?>" title=""><?php echo $single_artikel['link_dokumen']?></a></p>
			<br/>
		<?php endif; ?>
		<?php if($single_artikel['gambar1']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar1'])): ?>
			<div class="sampul">
				<img width="300px" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?php echo AmbilFotoArtikel($single_artikel['gambar1'],'sedang')?>"/>
			</div>
		<?php endif; ?>
		<?php if($single_artikel['gambar2']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar2'])): ?>
			<div class="sampul">
				<img width="300px" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?php echo AmbilFotoArtikel($single_artikel['gambar2'],'sedang')?>"/>
			</div>
		<?php endif; ?>
		<?php if($single_artikel['gambar3']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar3'])): ?>
			<div class="sampul">
				<img width="300px" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?php echo AmbilFotoArtikel($single_artikel['gambar3'],'sedang')?>"/>
			</div>
		<?php endif; ?>
		</div>
		<div class="btn-group" role="group" aria-label="Bagikan ke teman anda" style="clear:both;">
		    <a name="fb_share" href="http://www.facebook.com/sharer.php?u=<?php echo site_url().'first/artikel/'.$single_artikel['id']?>" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='nofollow' target='_blank' title='Facebook'><button type="button" class="btn btn-primary btn-sm"><i class="fa fa-facebook-square fa-2x"></i></button></a>
			<a href="http://twitter.com/share?source=sharethiscom&text=<?php echo $single_artikel["judul"];?>%0A&url=<?php echo site_url().'first/artikel/'.$single_artikel['id'].'&via=ariandii'?>" class="twitter-share-button" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='nofollow' target='_blank' title='Twitter'><button type="button" class="btn btn-info btn-sm"><i class="fa fa-twitter fa-2x"></i></button></a>
			<a href="https://plus.google.com/share?url=<?php echo site_url().'first/artikel/'.$single_artikel['id'].'&hl=id'?>" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='nofollow' target='_blank' title='Google'><button type="button" class="btn btn-danger btn-sm"><i class="fa fa-google-plus fa-2x"></i></button></a>
		    <a href="tg://msg?text=<?php echo $single_artikel["judul"];?>%0A<?php echo site_url().'first/artikel/'.$single_artikel['id']?>" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='nofollow' target='_blank' title='Whatsapp'><button type="button" class="btn btn-dark btn-sm"><i class="fa fa-telegram fa-2x"></i></button></a>
		    <a href="whatsapp://send?text=<?php echo $single_artikel["judul"];?>%0A<?php echo site_url().'first/artikel/'.$single_artikel['id']?>" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='nofollow' target='_blank' title='Whatsapp'><button type="button" class="btn btn-success btn-sm"><i class="fa fa-whatsapp fa-2x"></i></button></a>

            <!--
		    <a id="button" onClick="window.open('http://www.facebook.com/sharer.php?s=100&p[title]=<?php echo $single_artikel['judul']; ?>&p[summary]=<?php echo $single_artikel['judul'];?>&p[url]=<?php echo site_url().'first/artikel/'.$single_artikel['id']?>&p[images][0]=<?php echo AmbilFotoArtikel($single_artikel['gambar'],'sedang')?>', 'sharer', 'toolbar=0,status=0,width=550,height=400');" target="_parent" href="javascript: void(0)">s</a>
			<a href="https://plus.google.com/share?url=<?php echo site_url().'first/artikel/'.$single_artikel['id'].'&hl=id'?>" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='nofollow' target='_blank' title='Google'><button type="button" class="btn btn-danger"><i class="fa fa-google-plus" style="color:red"></i>&nbsp;Bagikan</button></a>
		    <script src=\"http://static.ak.fbcdn.net/connect.php/js/FB.Share\" type=\"text/javascript\"></script>
			<script src=\"http://platform.twitter.com/widgets.js\" type=\"text/javascript\"></script>
			-->
        </div>
		</div>
		    <div class="fb-comments" data-href="<?php echo site_url().'first/artikel/'.$single_artikel['id']?>" width="100%" data-numposts="5"></div>

		<div class="contact_bottom">
			<?php if(is_array($komentar)): ?>
				<div class="contact_bottom">
					<div class="box-body">
						<?php foreach($komentar AS $data): ?>
							<?php if($data['enabled']==1): ?>
								<div class="kom-box">
									<div>
										<i class="fa fa-clock-o"></i> <?php echo tgl_indo2($data['tgl_upload'])?>
									</div>
									<div>
										<blockquote><?php echo $data['owner']?> : <?php echo $data['komentar']?></blockquote>
									</div>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php elseif($single_artikel['boleh_komentar']): ?>
			<?php endif; ?>
		</div>
		<div class="form-group group-komentar">
			<?php if($single_artikel['boleh_komentar']): ?>
				<div class="box box-default">
					<!-- Tampilkan hanya jika 'flash_message' ada -->
					<div class="box-header">
						<h2 class="box-title">Tinggalkan Pesan</h2>
					</div><hr>
					
					<?php $label = !empty($_SESSION['validation_error']) ? 'label-danger' : 'label-info'; ?>
					<?php if ($flash_message): ?>
						<div class="box-header <?php echo $label?>"><?php echo $flash_message?></div>
					<?php endif; ?>
					<div class="contact_bottom">
						<form class="contact_form" id="form-komentar" name="form" action="<?php echo site_url('first/add_comment/'.$single_artikel['id'])?>" method="POST" onSubmit="return validasi(this);">
							<table width="100%">
								<tr class="komentar nama">
									<td width="20%">Nama</td>
									<td>
										<input class="form-group" type="text" name="owner" maxlength="100" placeholder="ketik di sini" value="<?php echo !empty($_SESSION['post']['owner']) ? $_SESSION['post']['owner'] : $_SESSION['nama'] ?>">
									</td>
								</tr>
								<tr class="komentar alamat">
									<td>No. Hp</td>
									<td>
										<input class="form-group" type="text" name="no_hp" maxlength="15" placeholder="ketik di sini" value="<?php echo $_SESSION['post']['no_hp'] ?>">
									</td>
								</tr><!--
								<tr class="komentar alamat">
									<td>E-mail</td>
									<td>
										<input class="form-group" type="text" name="email" maxlength="100" placeholder="email@gmail.com" value="<?php echo $_SESSION['post']['email'] ?>">
									</td>
								</tr>-->
								<tr class="komentar pesan">
									<td valign="top">Isi Pesan</td>
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
								<tr class="submit">
									<td><br><br></td>
								</tr>
							</table>
						</form>
					</div>
				</div>
			<?php else: ?>
				<span class='info'></span>
			<?php endif; ?>
		</div>
	
<?php else: ?>
	<div class="artikel" id="artikel-blank">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="error_page_content">
          <h1>404</h1>
          <h2>Maaf</h2>
          <h3>Halaman ini belum tersedia atau sedang dalam perbaikan</h3>
          <p class="wow fadeInLeftBig">Silahkan kembali lagi ke halaman <a href="/first">Beranda</a></p>
        </div>
      </div>
	</div>
<?php endif; ?>
