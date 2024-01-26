<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="content_left" style="margin-bottom:10px;">
    <div class="archive_style_1">
        <div style="margin-top:10px;">
            <?php if (!empty($teks_berjalan)): ?>
            <marquee onmouseover="this.stop()" onmouseout="this.start()">
                <?php $this->load->view("$folder_themes/layouts/teks_berjalan"); ?>
            </marquee>
            <?php endif; ?>
        </div>
        <?php $this->load->view("$folder_themes/layouts/slider"); ?>
        <?php if ($this->setting->covid_data) $this->load->view("$folder_themes/partials/corona-widget"); ?>
        <?php if ($this->setting->covid_desa) $this->load->view("$folder_themes/partials/corona-local"); ?>
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
                                        <?php if ($headline["gambar"] != "") : ?>
                                            <?php if (is_file(LOKASI_FOTO_ARTIKEL . "sedang_" . $headline['gambar'])) : ?>
                                                <img data-src="<?= AmbilFotoArtikel($headline['gambar'], 'sedang') ?>" src="<?= asset('images/img-loader.gif') ?>" width="300" class="yall_lazy img-fluid img-thumbnail hidden-sm hidden-xs" style="float:left; margin:0 8px 4px 0;" />
                                                <img data-src="<?= AmbilFotoArtikel($headline['gambar'], 'sedang') ?>" src="<?= asset('images/img-loader.gif') ?>" width="100%" class="yall_lazy img-fluid img-thumbnail hidden-lg hidden-md" style="float:left; margin:0 8px 4px 0;" />
                                            <?php else : ?>
                                                <img src="<?= base_url("$this->theme_folder/$this->theme/images/noimage.png") ?>" width="300px" class="img-fluid img-thumbnail hidden-sm hidden-xs" style="float:left; margin:0 8px 4px 0;" />
                                                <img src="<?= base_url("$this->theme_folder/$this->theme/images/noimage.png") ?>" width="100%" class="img-fluid img-thumbnail hidden-lg hidden-md" style="float:left; margin:0 8px 4px 0;" />
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
                                    <span class="meta_date"><?= tgl_indo($data['tgl_upload']); ?>&nbsp;
                                    <i class="fa fa-user"></i><?= $data['owner']?>&nbsp;
                                    <i class="fa fa-eye"></i><?= hit($data['hit']) ?>&nbsp;
                                    <i class="fa fa-comments"></i><?php $baca_komentar = $this->db->query("SELECT * FROM komentar WHERE id_artikel = '".$data['id']."'"); $komentarku = $baca_komentar->num_rows();
                                    echo number_format($komentarku,0,',','.'); ?>&nbsp;
                                    </span>
                                </div>
                                <a href="<?= site_url('artikel/'.buat_slug($data))?>" title="Baca Selengkapnya" style="font-weight:bold">
                                <?php if (is_file(LOKASI_FOTO_ARTIKEL."kecil_".$data['gambar'])): ?>
                                    <img data-src="<?= AmbilFotoArtikel($data['gambar'], 'sedang') ?>" src="<?= asset('images/img-loader.gif') ?>" width="300" class="yall_lazy img-fluid img-thumbnail hidden-sm hidden-xs" style="float:left; margin:0 8px 4px 0;" alt="<?= $data["judul"] ?>" />
                                            <img data-src="<?= AmbilFotoArtikel($data['gambar'], 'sedang') ?>" src="<?= asset('images/img-loader.gif') ?>" width="100%" class="yall_lazy img-fluid img-thumbnail hidden-lg hidden-md" style="float:left; margin:0 8px 4px 0;" alt="<?= $data["judul"] ?>" />
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

<?php $this->load->view("$folder_themes/commons/page"); ?>
