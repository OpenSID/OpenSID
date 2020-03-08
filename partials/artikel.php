<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if($single_artikel["id"]) : ?>
	<div class="single_page_area" id="<?= 'artikel-'.$single_artikel['judul']?>">
        <div style="margin-top:0px;">
            <?php if (!empty($teks_berjalan)): ?>
            <marquee onmouseover="this.stop()" onmouseout="this.start()">
                <?php $this->load->view($folder_themes.'/layouts/teks_berjalan.php'); ?>
	        </marquee>
	        <?php endif; ?>
	    </div>
        <div class="single_category wow fadeInDown">
            <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Artikel</span> </h2> 
		</div>
		<div id="printableArea">
		<h4 class="catg_titile" style="font-family: Oswald"><font color="#FFFFFF"><?= $single_artikel["judul"]?></font></h4>
		<div class="post_commentbox">
		    <span class="meta_date"><?= tgl_indo2($single_artikel['tgl_upload']);?>&nbsp;
		    <i class="fa fa-user"></i><?= $single_artikel['owner']?>&nbsp;
		    <i class="fa fa-eye"></i><?= hit($single_artikel['hit']) ?> Dibaca&nbsp;
		    <?php if (trim($single_artikel['kategori']) != '') : ?>
		    <a href="<?= site_url('first/kategori/'.$single_artikel['id_kategori'])?>"><i class='fa fa-tag'></i><?= $single_artikel['kategori']?></a>
		    <?php endif; ?>
		    </span>
			<div class="fb-like" data-href="<?= site_url()?>first/artikel/<?= $single_artikel['id'];?>" data-width="" data-layout="button_count" data-action="like" data-size="small" data-share="true"></div>
		</div>
		<div class="single_page_content" style="margin-bottom:10px;">	
		<div class="sampul">
		    <?php if($single_artikel["isi"]=='<p>&nbsp;&nbsp;</p>'): ?>
		        <?php if($single_artikel['gambar']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar'])): ?>
		        <a data-fancybox="gallery" href="<?= AmbilFotoArtikel($single_artikel['gambar'],'sedang')?>">
		            <img width="100%" class="img-fluid img-thumbnail" src="<?= AmbilFotoArtikel($single_artikel['gambar'],'sedang')?>"/>
			    </a>
		        <?php else: ?>
		            <img width="100%" class="img-fluid img-thumbnail" src="<?= base_url("$this->theme_folder/$this->theme/images/noimage.png") ?>"/>
		        <?php endif;?>
            <?php else: ?>
                <?php if($single_artikel['gambar']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar'])): ?>
		   		<a data-fancybox="gallery" href="<?= AmbilFotoArtikel($single_artikel['gambar'],'sedang')?>">
		            <img width="270px" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?= AmbilFotoArtikel($single_artikel['gambar'],'sedang')?>"/>
			    </a>
		        <?php else: ?>
		            <img width="270px" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?= base_url("$this->theme_folder/$this->theme/images/noimage.png") ?>"/>
		        <?php endif;?>
	       	<?php endif; ?>
		</div>		
		<div class="teks"><?= $single_artikel["isi"]?></div>

		<?php	if($single_artikel['dokumen']!='' and is_file(LOKASI_DOKUMEN.$single_artikel['dokumen'])): ?>
			<p>Download Lampiran:<br><a href="<?= base_url().LOKASI_DOKUMEN.$single_artikel['dokumen']?>" title=""><?= $single_artikel['link_dokumen']?></a></p>
			<br/>
		<?php endif; ?>
		<?php if($single_artikel['gambar1']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar1'])): ?>
			<div class="sampul">
			    <a data-fancybox="gallery" href="<?= AmbilFotoArtikel($single_artikel['gambar1'],'sedang')?>">
				<img width="270px" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?= AmbilFotoArtikel($single_artikel['gambar1'],'sedang')?>"/>
				</a>
			</div>
		<?php endif; ?>
		<?php if($single_artikel['gambar2']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar2'])): ?>
			<div class="sampul">
			    <a data-fancybox="gallery" href="<?= AmbilFotoArtikel($single_artikel['gambar2'],'sedang')?>">
				<img width="270px" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?= AmbilFotoArtikel($single_artikel['gambar2'],'sedang')?>"/>
				</a>
			</div>
		<?php endif; ?>
		<?php if($single_artikel['gambar3']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar3'])): ?>
			<div class="sampul">
			    <a data-fancybox="gallery" href="<?= AmbilFotoArtikel($single_artikel['gambar3'],'sedang')?>">
				<img width="270px" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?= AmbilFotoArtikel($single_artikel['gambar3'],'sedang')?>"/>
				</a>
			</div>
		<?php endif; ?>
		</div>
		</div>
		<div class="btn-group" role="group" aria-label="Bagikan ke teman anda" style="clear:both;">
		    <a name="fb_share" href="http://www.facebook.com/sharer.php?u=<?= "https://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]?>" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='noopener noreferrer' target='_blank' title='Facebook'><button type="button" class="btn btn-primary btn-sm"><i class="fa fa-facebook-square fa-2x"></i></button></a>
			<a href="http://twitter.com/share?source=sharethiscom&text=<?= $single_artikel["judul"];?>%0A&url=<?= "https://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI].'&via=ariandii'?>" class="twitter-share-button" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='noopener noreferrer' target='_blank' title='Twitter'><button type="button" class="btn btn-info btn-sm"><i class="fa fa-twitter fa-2x"></i></button></a>
			<a href="mailto:?subject=<?= $single_artikel["judul"];?>&body=<?= potong_teks($single_artikel["isi"], 1000);?> ... Selengkapnya di <?= "https://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]?>" title='Email'><button type="button" class="btn btn-danger btn-sm"><i class="fa fa-envelope fa-2x"></i></button></a>
		    <a href="https://telegram.me/share/url?url=<?= "https://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]?>&text=<?= $single_artikel["judul"];?>%0A" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='noopener noreferrer' target='_blank' title='Telegram'><button type="button" class="btn btn-dark btn-sm"><i class="fa fa-telegram fa-2x"></i></button></a>
		    <a href="#" onclick="printDiv('printableArea')" title='Cetak Artikel'><button type="button" class="btn btn-warning btn-sm"><i class="fa fa-print fa-2x"></i></button></a>
		    <a href="https://api.whatsapp.com/send?text=<?= $single_artikel["judul"];?>%0A<?= "https://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]?>" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='noopener noreferrer' target='_blank' title='Whatsapp'><button type="button" class="btn btn-success btn-sm"><i class="fa fa-whatsapp fa-2x"></i></button></a>
        </div>
		</div>
		<?php if($single_artikel['boleh_komentar']): ?>
		    <div class="fb-comments" data-href="<?= site_url().'first/artikel/'.$single_artikel['id']?>" width="100%" data-numposts="5"></div>
		<?php endif; ?>
		<div class="contact_bottom">
			<?php if(is_array($komentar)): ?>
				<div class="contact_bottom">
					<div class="box-body">
						<?php foreach($komentar AS $data): ?>
							<?php if($data['enabled']==1): ?>
								<table class="table table-bordered table-striped dataTable table-hover nowrap">
                                    <thead class="bg-gray disabled color-palette">
                                        <tr>
                                            <th><i class="fa fa-comment"></i> <?= $data['owner']?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <font color='green'><small><?= tgl_indo2($data['tgl_upload'])?></small></font><br/><?= $data['komentar']?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
						<h2 class="box-title">Kirim Komentar</h2>
					</div><hr />
					
					<?php $label = !empty($_SESSION['validation_error']) ? 'label-danger' : 'label-info'; ?>
					<?php if ($flash_message): ?>
						<div class="box-header <?= $label?>"><?= $flash_message?></div>
					<?php endif; ?>
					<div class="contact_bottom">
						<form class="contact_form" id="form-komentar" name="form" action="<?= site_url('first/add_comment/'.$single_artikel['id'])?>" method="POST" onSubmit="return validasi(this);">
							<table width="100%">
								<tr class="komentar nama">
									<td width="20%">Nama</td>
									<td>
										<input class="form-group" type="text" name="owner" maxlength="100" placeholder="ketik di sini" value="<?= !empty($_SESSION['post']['owner']) ? $_SESSION['post']['owner'] : $_SESSION['nama'] ?>">
									</td>
								</tr>
								<tr class="komentar alamat">
									<td>No. Hp</td>
									<td>
										<input class="form-group" type="text" name="no_hp" maxlength="15" placeholder="ketik di sini" value="<?= $_SESSION['post']['no_hp'] ?>">
									</td>
								</tr><!--
								<tr class="komentar alamat">
									<td>E-mail</td>
									<td>
										<input class="form-group" type="text" name="email" maxlength="100" placeholder="email@gmail.com" value="<?= $_SESSION['post']['email'] ?>">
									</td>
								</tr>-->
								<tr class="komentar pesan">
									<td valign="top">Isi Pesan</td>
									<td>
										<textarea name="komentar"><?= $_SESSION['post']['komentar']?></textarea>
									</td>
								</tr>
								<tr class="captcha"><td>&nbsp;</td>
									<td>
										<img id="captcha" src="<?= base_url().'securimage/securimage_show.php'?>" alt="CAPTCHA Image"/>
										<a href="#" onclick="document.getElementById('captcha').src = '<?= base_url()."securimage/securimage_show.php?"?>' + Math.random(); return false">[ Ganti gambar ]</a>
									</td>
								</tr>
								<tr class="captcha_code">
									<td>&nbsp;</td>
									<td>
										<input type="text" name="captcha_code" maxlength="6" value="<?= $_SESSION['post']['captcha_code']?>"/> Isikan kode di gambar
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
          <p class="wow fadeInLeftBig">Silahkan kembali lagi ke halaman <a href="<?= site_url(); ?>first">Beranda</a></p>
        </div>
      </div>
	</div>
<?php endif; ?>
