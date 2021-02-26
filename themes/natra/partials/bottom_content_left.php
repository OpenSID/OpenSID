<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="content_bottom_left" style="margin-bottom:10px;">
    <div class="archive_style_1">
        <div style="margin-top:10px;">
            <?php if (!empty($teks_berjalan)): ?>
            <marquee onmouseover="this.stop()" onmouseout="this.start()">
                <?php $this->load->view($folder_themes.'/layouts/teks_berjalan.php') ?>
            </marquee>
            <?php endif; ?>
        </div>
        <?php $this->load->view($folder_themes."/layouts/slider.php") ?>
		<?php if ($this->setting->covid_data) $this->load->view($folder_themes."/partials/corona-widget.php")?>
		<?php if ($this->setting->covid_desa) $this->load->view($folder_themes."/partials/corona-local.php");?>
        <?php if ($headline): ?>
        <?php $abstrak_headline = potong_teks($headline['isi'], 550) ?>
        <div class="single_category wow fadeInDown">
            <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Berita Utama</span> </h2>
        </div>
        <div id="headline" class="single_category wow fadeInDown">
            <div class="archive_style_1">
                <div class="business_category_left wow fadeInDown">
                    <ul class="fashion_catgnav">
                        <li>
                            <div class="catgimg2_container2">
                                <h5 class="catg_titile">
                                    <a href="<?= site_url('artikel/'.buat_slug($headline))?>"> <?= $headline['judul'] ?></a>
                                </h5>
                                <a href="<?= site_url('artikel/'.buat_slug($headline))?>">
                                <?php if ($headline["gambar"] != ""): ?>
                                <?php if (is_file(LOKASI_FOTO_ARTIKEL."sedang_".$headline['gambar'])): ?>
                                <img src="<?= AmbilFotoArtikel($headline['gambar'],'sedang') ?>" width="300" class="img-fluid img-thumbnail hidden-sm hidden-xs" style="float:left; margin:0 8px 4px 0;" />
                                <img src="<?= AmbilFotoArtikel($headline['gambar'],'sedang') ?>" width="100%" class="img-fluid img-thumbnail hidden-lg hidden-md" style="float:left; margin:0 8px 4px 0;" />
                                <?php else: ?>
                                <img src="<?= base_url("$this->theme_folder/$this->theme/images/noimage.png") ?>" width="300px" class="img-fluid img-thumbnail hidden-sm hidden-xs" style="float:left; margin:0 8px 4px 0;"/>
                                <img src="<?= base_url("$this->theme_folder/$this->theme/images/noimage.png") ?>" width="100%" class="img-fluid img-thumbnail hidden-lg hidden-md" style="float:left; margin:0 8px 4px 0;"/>
                                <?php endif; ?>
                                <?php endif; ?>
                                </a>
                                <div style="text-align: justify;" class="hidden-sm hidden-xs">
                                    <?= $abstrak_headline ?> ...
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
    	</div>
    	<?php endif; ?>
	</div>
    <?php $title = (!empty($judul_kategori))? $judul_kategori : "Artikel Terkini" ?>
    <?php if (is_array($title)): ?>
    	<?php foreach ($title as $item): ?>
    		<?php $title= $item ?>
    	<?php endforeach; ?>
    <?php endif; ?>
    <div class="single_category wow fadeInDown">
        <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text"><?= $title ?></span> </h2>
    </div>
    <?php if ($artikel): ?>
    <div class="single_category wow fadeInDown">
        <div class="archive_style_1">
            <?php foreach ($artikel as $data): ?>
            <?php $abstrak = potong_teks($data['isi'], 550) ?>
            <div class="business_category_left wow fadeInDown">
                <ul class="fashion_catgnav">
                    <li>
                        <div class="catgimg2_container2">
                            <h5 class="catg_titile">
                                <a href="<?= site_url('artikel/'.buat_slug($data))?>" title="Baca Selengkapnya"><?= $data["judul"] ?></a>
                            </h5>
                            <div class="post_commentbox">
                                <span class="meta_date"><?= tgl_indo($data['tgl_upload']);?>&nbsp;
                                <i class="fa fa-user"></i><?= $data['owner']?>&nbsp;
                                <i class="fa fa-eye"></i><?= hit($data['hit']) ?>&nbsp;
                                <i class="fa fa-comments"></i><?php $baca_komentar = $this->db->query("SELECT * FROM komentar WHERE id_artikel = '".$data['id']."'"); $komentarku = $baca_komentar->num_rows();
                                echo number_format($komentarku,0,',','.'); ?>&nbsp;
                                </span>
                            </div>
                            <a href="<?= site_url('artikel/'.buat_slug($data))?>" title="Baca Selengkapnya" style="font-weight:bold">
                            <?php if (is_file(LOKASI_FOTO_ARTIKEL."kecil_".$data['gambar'])): ?>
                            <img src="<?= AmbilFotoArtikel($data['gambar'],'sedang') ?>" width="300" class="img-fluid img-thumbnail hidden-sm hidden-xs" style="float:left; margin:0 8px 4px 0;" alt="<?= $data["judul"] ?>"/>
                            <img src="<?= AmbilFotoArtikel($data['gambar'],'sedang') ?>" width="100%" class="img-fluid img-thumbnail hidden-lg hidden-md" style="float:left; margin:0 8px 4px 0;" alt="<?= $data["judul"] ?>"/>
                            <?php else: ?>
                            <img src="<?= base_url("$this->theme_folder/$this->theme/images/noimage.png") ?>" width="300px" class="img-fluid img-thumbnail hidden-sm hidden-xs" style="float:left; margin:0 8px 4px 0;" alt="<?= $data["judul"] ?>" />
                            <img src="<?= base_url("$this->theme_folder/$this->theme/images/noimage.png") ?>" width="100%" class="img-fluid img-thumbnail hidden-lg hidden-md" style="float:left; margin:0 8px 4px 0;" alt="<?= $data["judul"] ?>" />
                            <?php endif;?>
                            </a>
                            <div style="text-align: justify;" class="hidden-sm hidden-xs">
                                <?= $abstrak ?> ...
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="business_category_left wow fadeInDown" id="artikel-blank">
        <div class="box box-warning box-solid">
            <div class="box-header"><h3 class="box-title">Maaf, belum ada data</h3></div>
            <div class="box-body">
                <p>Belum ada artikel yang dituliskan dalam <?= $title ?></p>
                <p>Silakan kunjungi situs web kami dalam waktu dekat.</p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php if ($artikel AND $paging->num_rows > $paging->per_page): ?>
<div class="pagination_area">
    <div>Halaman <?= $p ?> dari <?= $paging->end_link ?></div>
    <ul class="pagination">
        <?php if ($paging->start_link): ?>
        <li><a href="<?= site_url($paging_page."/$paging->start_link" . $paging->suffix) ?>" title="Halaman Pertama"><i class="fa fa-fast-backward"></i>&nbsp;</a></li>
        <?php endif; ?>
        <?php foreach ($pages as $i): ?>
        <li <?= ($p == $i) ? 'class="active"' : "" ?>>
            <a href="<?= site_url($paging_page."/$i" . $paging->suffix) ?>" title="Halaman <?= $i ?>"><?= $i ?></a>
        </li>
        <?php endforeach; ?>
        <?php if ($i != $paging->end_link): ?>
        <li class='disabled'>
            <a>...</a>
        </li>
        <?php endif; ?>
        <?php if ($paging->end_link): ?>
        <li><a href="<?= site_url($paging_page."/$paging->end_link" . $paging->suffix) ?>" title="Halaman Terakhir"><i class="fa fa-fast-forward"></i>&nbsp;</a></li>
        <?php endif; ?>
    </ul>
</div>
<?php endif; ?>
