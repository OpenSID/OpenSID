<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $title = (!empty($judul_kategori))? $judul_kategori : "Artikel Terkini" ?>
<?php if (is_array($title)): ?>
<?php foreach ($title as $item): ?>
<?php $title= $item ?>
<?php endforeach; ?>
<?php endif; ?>
<?php if ($headline): ?>
<?php $abstrak_headline = potong_teks($headline['isi'], 550) ?>
<div class="block block-themed">
    <div class="block-header">
        <h3 class="block-title">Berita Utama</h3>
        <div class="block-options">
            <a
                href="<?= site_url('first/artikel/'.$headline['thn'].'/'.$headline['bln'].'/'.$headline['hri'].'/'.$headline['slug']) ?>">
                <button type="submit" class="btn btn-sm btn-alt-primary">
                    <i class="si si-action-redo"></i> Selengakpanya ...
                </button>
            </a>
        </div>
    </div>
    <div class="block-content">
        <div class="row">
            <div class="col-md-4">
                <?php if ($headline["gambar"] != ""): ?>
                <?php if (is_file(LOKASI_FOTO_ARTIKEL."sedang_".$headline['gambar'])): ?>
                <a
                    href="<?= site_url('first/artikel/'.$headline['thn'].'/'.$headline['bln'].'/'.$headline['hri'].'/'.$headline['slug']) ?>">
                    <img width="220" class="img-fluid" style="float:left; margin:0 8px 4px 0;"
                        alt="<?= $data["judul"] ?>" src="<?= AmbilFotoArtikel($headline['gambar'], 'sedang') ?>" />
                </a>
                <?php else: ?>
                <img width="220" class="img-fluid"
                    src="<?= base_url("$this->theme_folder/$this->theme/assets/noimage.jpg") ?>"/>
                <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <h4 class="link-effect lead"><a
                    href="<?= site_url('first/artikel/'.$headline['thn'].'/'.$headline['bln'].'/'.$headline['hri'].'/'.$headline['slug']) ?>">
                    <?= $headline['judul'] ?></a></h4>
                <p class="text-right"><?= $abstrak_headline ?> ...</p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<div class="pb-30 text-center">
    <a class="btn btn-hero btn-noborder btn-rounded btn-alt-primary">
        <i class="si si-chemistry text-primary"></i> Artikel Terkini
    </a>
</div>
<?php if ($artikel): ?>
<div class="row">
    <?php foreach ($artikel as $data): ?>
    <?php $abstrak = potong_teks($data['isi'], 250) ?>
    <div class="col-6 col-sm-6 col-xl-6 col-xs-6 ">
        <div class="block block block-shadow ribbon ribbon-bottom ribbon-modern ribbon-primary">
            <?php if ($data['gambar']!=''): ?>
            <?php if (is_file(LOKASI_FOTO_ARTIKEL."sedang_".$data['gambar'])): ?>
            <a href="<?= site_url('first/artikel/'.$data['thn'].'/'.$data['bln'].'/'.$data['hri'].'/'.$data['slug']) ?>"
                title="Baca Selengkapnya">
                <img class="img-fluid" src="<?= AmbilFotoArtikel($data['gambar'],'sedang') ?>"
                    alt="<?= $data["judul"] ?>" />
            </a>
            <?php else: ?>
            <img class="img-fluid" src="<?= base_url("$this->theme_folder/$this->theme/assets/noimage.jpg") ?>">
            <?php endif; ?>
            <?php else: ?>
            <img class="img-fluid" src="<?= base_url("$this->theme_folder/$this->theme/assets/noimage.jpg") ?>">
            <?php endif; ?>
            <div class="ribbon-box">
                <?= $data['owner'] ?>
            </div>
            <div class="block-content block-content-full ">
                <a href="<?= site_url('first/artikel/'.$data['thn'].'/'.$data['bln'].'/'.$data['hri'].'/'.$data['slug']) ?>"
                    title="Baca Selengkapnya">
                    <h3 class="h6 font-w700 text-uppercase mb-10"> <?= $data['judul'] ?></h3>
                </a>
                <div class="text-muted mb-10">
                    <span class="mr-15">
                        <i class="fa fa-fw fa-calendar mr-5"></i><?= tgl_indo2($data['tgl_upload']) ?>
                    </span><br>
                    <a class="text-muted"><i
                            class="fa fa-fw fa-tag mr-5"></i><?php if (trim($data['kategori']) != ''): ?><?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="col-sm-12">
        <div class="block">
            <div class="hero bg-white">
                <div class="hero-inner">
                    <div class="content content-full">
                        <div class="py-30 text-center">
                            <div class="display-3 text-danger">
                                <i class="fa fa-warning"></i> 404
                            </div>
                            <h1 class="h2 font-w700 mt-30 mb-10">Oops.. Pencarian Tidak Ditemukan..</h1>
                            <h2 class="h3 font-w400 text-muted mb-50">Mohon Maaf..</h2>
                            <a class="btn btn-hero btn-rounded btn-alt-danger"
                                href="http://localhost/OSID/index.php/first">
                                <i class="fa fa-arrow-left mr-10"></i> Kembali Ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <?php endif; ?>
    </div>
    <div class="block-content center-block ">
        <hr>
        <nav aria-label="Page navigation ">
            <ul class="pagination pagination-lg">
                <?php if ($paging->start_link): ?>
                <li class="page-item">
                    <a class="page-link"
                        href="<?= site_url("first/".$paging_page."/$paging->start_link" . $paging->suffix) ?>"
                        aria-label="Previous">
                        <span aria-hidden="true">
                            <i class="fa fa-angle-left"></i>
                        </span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php foreach ($pages as $i): ?>
                <li class="page-item <?php ($p != $i) or print('active');?>">
                    <a class="page-link" href="<?= site_url("first/".$paging_page."/$i" . $paging->suffix) ?>"
                        title="Halaman <?= $i ?>"><?= $i ?></a>
                </li>
                <?php endforeach; ?>
                <?php if ($i != $paging->end_link): ?>
                <li class="page-item" disabled>
                    <a class="page-link" href="javascript:void(0)" aria-label="Next" disabled>
                        <span aria-hidden="true">
                            <i class="fa fa-angle-right"></i>
                        </span>
                        <span class="sr-only">...</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link"
                        href="<?= site_url("first/".$paging_page."/$paging->end_link" . $paging->suffix) ?>"
                        aria-label="Next">
                        <span aria-hidden="true">
                            <i class="fa fa-angle-right"></i>
                        </span>
                        <span class="sr-only"><?= $paging->end_link ?></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($paging->next): ?>
                <li class="page-item">
                    <a class="page-link"
                        href="<?= site_url("first/".$paging_page."/$paging->next" . $paging->suffix) ?>"
                        aria-label="Next">
                        <span aria-hidden="true">
                            <i class="fa fa-angle-right"></i>
                        </span>
                        <span class="sr-only">Selanjutnya</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <hr>
    </div>