<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!--
 List Konten
 -->
<?php $title = (!empty($judul_kategori))? $judul_kategori : "Artikel Terkini" ?>

<?php if (is_array($title)): ?>
	<?php foreach ($title as $item): ?>
		<?php $title= $item ?>
	<?php endforeach; ?>
<?php endif; ?>
<div>
    <div class="content_bottom_left">
            <div class="archive_style_1">
                <div style="margin-top:15px;">
                    <?php if (!empty($teks_berjalan)): ?>
                    <marquee onmouseover="this.stop()" onmouseout="this.start()">
						<?php $this->load->view($folder_themes.'/layouts/teks_berjalan.php'); ?>
                    </marquee>
                    <?php endif; ?>
                </div>
                <?php if ($headline): ?>
            	<?php $abstrak_headline = potong_teks($headline['isi'], 550) ?>
        	    <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Berita Utama</span> </h2> 
            	<div id="headline" class="box box-danger" style="margin-bottom:20px;">
            		<div class="box-header with-border">
            			<h3 class="catg_titile" style="font-family: Oswald">
            				<a href="<?= site_url("first/artikel/$headline[id]") ?>"> <?= $headline['judul'] ?></a>
            			</h3>
            		</div>
            		<div class="box-body">
            			<?php if ($headline["gambar"] != ""): ?>
            				<?php if (is_file(LOKASI_FOTO_ARTIKEL."sedang_".$headline['gambar'])): ?>
            					<a href="<?= site_url('first/artikel/'.$headline['id']) ?>">
            					    <img width="300" class="img-fluid img-thumbnail" style="float:left; margin:0 8px 4px 0;" alt="<?= $data["judul"] ?>" src="<?= AmbilFotoArtikel($headline['gambar'], 'sedang') ?>" />
            					</a>
            				<?php else: ?>
            					<img width="300" class="img-fluid img-thumbnail" src="<?= base_url('assets/images/404-image-not-found.jpg') ?>"/>
            				<?php endif; ?>
            			<?php endif; ?>
            			<div class="post">
            				<div style="text-align: justify;"><?= $abstrak_headline ?> ...
            				<a href="<?= site_url('first/artikel/'.$headline['id']) ?>"><button type="button" class="btn btn-info btn-block">Baca Selengkapnya <i class="fa fa-arrow-right"></i></button></a></div>
            			</div>
            		</div>
            	</div>
            <?php endif; ?>
			</div>	
        <div class="single_category wow fadeInDown">
            <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text"><?= $title ?></span> </h2> 
		</div>
		<?php if ($artikel): ?>
		<div class="single_category wow fadeInDown">
		    <div class="archive_style_1">
		        <?php foreach ($artikel as $data): ?>
		        <?php $abstrak = potong_teks($data['isi'], 250) ?>
		        <div class="business_category_left wow fadeInDown">
		            <ul class="fashion_catgnav">
		                <li>
		                    <div class="catgimg2_container">
		                        <h2 class="catg_titile"><a href="<?= site_url("first/artikel/$data[id]") ?>" title="Baca Selengkapnya"><?= $data["judul"] ?></a></h2>
		                        <div class="comments_box"> <span class="meta_date"><?= tgl_indo2($data['tgl_upload']) ?></span> <span class="meta_comment"> <?= $data['owner'] ?></span> <?php if (trim($data['kategori']) != ''): ?>
		                        <i class='fa fa-tag'></i> <a href="<?= site_url('first/kategori/'.$data['id_kategori']) ?>"><?= $data['kategori'] ?></a>
		                        <?php endif; ?></div>
		                        <?php if ($data['gambar']!=''): ?>
		                        <?php if (is_file(LOKASI_FOTO_ARTIKEL."sedang_".$data['gambar'])): ?>
		                        <div class="catgimg2_container">
		                            <a href="<?= site_url("first/artikel/$data[id]") ?>" title="Baca Selengkapnya">
		                                <img src="<?php echo AmbilFotoArtikel($data['gambar'],'sedang') ?>" width="300" class="img-fluid img-thumbnail" style="float:left; margin:0 8px 4px 0;" alt="<?= $data["judul"] ?>"/>
		                            </a><!--
		                            <p style="text-align: justify;"><?= $abstrak ?> ...
		                            <a href="<?= site_url("first/artikel/".$data["id"]) ?>">
										<div class="readmore"> Selengkapnya <i class="fa fa-arrow-right"></i></div>
									</a></p>-->
		                        </div>
		                        <?php else: ?>
		                        <div class="catgimg2_container">
		                            <a href="<?= site_url("first/artikel/$data[id]") ?>" title="Baca Selengkapnya">
		                                <img width="300px" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?= base_url("$this->theme_folder/$this->theme/images/noimage.png") ?>"/>
		                            </a>
		                        </div>
		                        <?php endif;?>
		                        
		                        <?php else: ?>
		                        <div class="catgimg2_container">
		                            <a href="<?= site_url("first/artikel/$data[id]") ?>" title="Baca Selengkapnya">
		                                <img width="300px" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?= base_url("$this->theme_folder/$this->theme/images/noimage.png") ?>"/>
		                            </a><!--
		                            <p style="text-align: justify;"><?= $abstrak ?> ...
		                            <a href="<?= site_url("first/artikel/".$data["id"]) ?>">
										<div class="readmore"> Selengkapnya <i class="fa fa-arrow-right"></i></div>
									</a></p>-->
		                        </div>
		                        <?php endif; ?>
		                    </div>
		                </li>
		            </ul>
		        </div><?php endforeach; ?>
		    </div>
	    </div>
		<!--
			Pengaturan halaman
		 -->
		<?php else: ?>
			<div class="business_category_left wow fadeInDown" id="artikel-blank">
				<div class="box box-warning box-solid">
					<div class="box-header"><h3 class="box-title">Maaf, belum ada artikel di Halaman  <?= $title ?> </h3></div>
					<div class="box-body">
    					<blockquote>
    						<p>Belum ada artikel yang dituliskan dalam Halaman ini.</p>
    						<p>Silakan kunjungi situs web kami dalam waktu dekat.</p>
    					<blockquote>
					</div>
				</div>
			</div>
		<?php endif; ?>
	          </div>
	          	<?php if ($artikel): ?>
		<div class="pagination_area">
			<ul class="pagination">
				<?php if ($paging->start_link): ?>
					<li><a href="<?= site_url("first/".$paging_page."/$paging->start_link" . $paging->suffix) ?>" title="Halaman Pertama"><i class="fa fa-fast-backward"></i>&nbsp;</a></li>
				<?php endif; ?>
				<!--
				<?php if ($paging->prev): ?>
					<li><a href="<?= site_url("first/".$paging_page."/$paging->prev" . $paging->suffix) ?>" title="Halaman Sebelumnya"><i class="fa fa-backward"></i>&nbsp;</a></li>
				<?php endif; ?>
				-->

				<?php foreach ($pages as $i): ?>
					<li <?= ($p == $i) ? 'class=""' : "" ?>>
						<a href="<?= site_url("first/".$paging_page."/$i" . $paging->suffix) ?>" title="Halaman <?= $i ?>"><?= $i ?></a>
					</li>
				<?php endforeach; ?>
				
				<?php if ($i != $paging->end_link): ?>
    				<li class='disabled'>
    				    <a>...</a>
    				</li>
    				<li>
    				    <a href="<?= site_url("first/".$paging_page."/$paging->end_link" . $paging->suffix) ?>" title="Halaman Terakhir"><?= $paging->end_link ?></a>
    				</li>
    			<?php endif; ?>
				<?php if ($paging->next): ?>
					<li><a href="<?= site_url("first/".$paging_page."/$paging->next" . $paging->suffix) ?>" title="Halaman Selanjutnya"><i class="fa fa-forward"></i>&nbsp;</a></li>
				<?php endif; ?>
				<!--
				<?php if ($paging->end_link): ?>
					<li><a href="<?= site_url("first/".$paging_page."/$paging->end_link" . $paging->suffix) ?>" title="Halaman Terakhir"><i class="fa fa-fast-forward"></i>&nbsp;</a></li>
				<?php endif; ?>
				-->
			</ul>
		</div>
	<?php endif; ?>
</div>
