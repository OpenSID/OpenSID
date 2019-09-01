<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if($single_artikel["id"]) : ?>
<div class="block-header block-header-default">
    <h2 class="h5 font-w500"><?= $single_artikel["judul"]?></h2>
    <div class="push float-right">

        <button type="button" class="btn btn-lg btn-circle btn-warning mr-5" onclick="Codebase.helpers('print-page');"
            title='Print Artikel'>
            <i class="fa fa-print"></i>
        </button>
        <button type="button" class="btn btn-lg btn-circle btn-primary mr-5" name="fb_share"
            href="http://www.facebook.com/sharer.php?u=<?= "https://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]?>"
            onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;'
            rel='nofollow' target='_blank' title='Facebook'>
            <i class="fa fa-facebook"></i>
        </button>
        <button type="button" class="btn btn-lg btn-circle btn-primary mr-5"
            href="http://twitter.com/share?source=sharethiscom&text=<?= $single_artikel["judul"];?>%0A&url=<?= "https://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI].'&via=ariandii'?>"
            onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;'
            rel='nofollow' target='_blank' title='Twitter'>
            <i class="fa fa-twitter"></i>
        </button>
        <button type="button" class="btn btn-lg btn-circle btn-success mr-5"
            href="mailto:?subject=<?= $single_artikel["judul"];?>&body=<?= potong_teks($single_artikel["isi"], 1000);?> ... Selengkapnya di <?= "https://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]?>"
            title='Email'>
            <i class="fa fa-envelope"></i>
        </button>
        <button type="button" class="btn btn-lg btn-circle btn-danger mr-5"
            href="https://telegram.me/share/url?url=<?= "https://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]?>&text=<?= $single_artikel["judul"];?>%0A"
            onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;'
            rel='nofollow' target='_blank' title='Telegram'>
            <i class="fa fa-telegram"></i>
        </button>
        <button type="button" class="btn btn-lg btn-circle btn-success mr-5"
            href="https://api.whatsapp.com/send?text=<?= $single_artikel["judul"];?>%0A<?= "https://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]?>"
            onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;'
            rel='nofollow' target='_blank' title='Whatsapp'>
            <i class="fa fa-whatsapp"></i>
        </button>
    </div>
</div>
<hr>
<div class="block-content" data-toggle="slimscroll" data-height="900px" data-color="#ef5350" data-opacity="1"
    data-always-visible="true">
    <div class="row justify-content-center pb-20">
        <div class="col-lg-12 ml-10 mr-10 text-center">
            <h2 class="h2 font-w400"><?= $single_artikel["judul"]?></h2>
            <div class="font-size-md">
                <h6 class="h5 font-w400"><?= $single_artikel['owner']?>, <?= tgl_indo2($single_artikel['tgl_upload']);?>
                </h6>
            </div>
            <hr>
            <!-- END Simple Gallery -->

            <p><?php if($single_artikel["isi"]!=''): ?></p>
            <?php if($single_artikel['gambar']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar'])): ?>

            <div class="col-12 animated fadeIn">
                <a class="img-link img-link-simple img-link-zoom-in img-lightbox"
                    href="<?= AmbilFotoArtikel($single_artikel['gambar'],'sedang')?>">
                    <img class="img-fluid" src="<?= AmbilFotoArtikel($single_artikel['gambar'],'sedang')?>" alt="">
                </a>
            </div>
            <?php endif; ?>
            <?php endif; ?>
            <?php if($single_artikel["isi"]=='<p>&nbsp;&nbsp;</p>'): ?>
            <div class="col-12 animated fadeIn">
                <a class="img-link img-link-simple img-link-zoom-in img-lightbox"
                    href="<?= AmbilFotoArtikel($single_artikel['gambar'],'sedang')?>">
                    <img class="img-fluid" src="<?= AmbilFotoArtikel($single_artikel['gambar'],'sedang')?>" alt="">
                </a>
            </div>
            <?php endif; ?>
            <h5><?= $single_artikel["isi"]?></h5>

            <?php	if($single_artikel['dokumen']!='' and is_file(LOKASI_DOKUMEN.$single_artikel['dokumen'])): ?>

            <p>
                <h3>DOCUMENT LAMPIRAN : </h3><a href="<?= base_url().LOKASI_DOKUMEN.$single_artikel['dokumen']?>"
                    title=""><?= $single_artikel['link_dokumen']?></a>
            </p>

            <?php endif; ?>
            <?php if($single_artikel['gambar2']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar2'])): ?>
            <div class="col-12 animated fadeIn">
                <a class="img-link img-link-simple img-link-zoom-in img-lightbox"
                    href="<?= AmbilFotoArtikel($single_artikel['gambar2'],'sedang')?>">
                    <img class="img-fluid" src="<?= AmbilFotoArtikel($single_artikel['gambar2'],'sedang')?>" alt="">
                </a>
            </div>
            <?php endif; ?>
            <?php if($single_artikel['gambar3']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar3'])): ?>
            <div class="col-12 animated fadeIn">
                <a class="img-link img-link-simple img-link-zoom-in img-lightbox"
                    href="<?= AmbilFotoArtikel($single_artikel['gambar3'],'sedang')?>">
                    <img class="img-fluid" src="<?= AmbilFotoArtikel($single_artikel['gambar3'],'sedang')?>" alt="">
                </a>
            </div>
            <?php endif; ?>
            <br><br><br><br>
            <h3 class="pb-20">Terima Kasih</h3>
        </div>
    </div>
</div>
<div class="block block-themed block-mode-hidden py-20">
    <div class="block-header bg-gray-darker">
        <h3 class="block-title"><i class="si si-bubble"></i> Komentar </h3>
        <div class="block-options">
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"><i
                    class="si si-size-fullscreen"></i></button>
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"><i
                    class="si si-arrow-up"></i></button>
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="close">
                <i class="si si-close"></i>
            </button>
        </div>
    </div>
    <div class="block-content ">
        <?php if($single_artikel['boleh_komentar']): ?>
        <div class="fb-comments" data-href="<?= site_url().'first/artikel/'.$single_artikel['id']?>" width="100%"
            data-numposts="5"></div>
        <?php endif; ?>
        <?php if(is_array($komentar)): ?>
        <?php foreach($komentar AS $data): ?>
        <?php if($data['enabled']==1): ?>
        <div class="media mb-20">
            <img class="img-avatar img-avatar32 d-flex mr-20" src="assets/img/avatars/avatar13.jpg" alt="">
            <div class="media-body">
                <p class="mb-5"><?= $data['owner']?></p>
                <p><?= $data['komentar']?></p>
                <div class="font-size-sm text-muted"><?= tgl_indo2($data['tgl_upload'])?></div>
            </div>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
        <?php elseif($single_artikel['boleh_komentar']): ?>
        <?php endif; ?>
        <?php if($single_artikel['boleh_komentar']): ?>
        <?php $label = !empty($_SESSION['validation_error']) ? 'label-danger' : 'label-info'; ?>
        <?php if ($flash_message): ?>
        <div class="alert alert-danger alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <h3 class="alert-heading font-size-h4 font-w400">EROR</h3>
            <p class="mb-0"><?= $label?><?= $flash_message?> </p>
        </div>
        <?php endif; ?>
        <div class="content content-full">
            <div class="row justify-content-center py-20">
                <div class="col-lg-10 col-xl-10">
                    <form class="js-validation-be-contact" id="form-komentar" name="form"
                        action="<?= site_url('first/add_comment/'.$single_artikel['id'])?>" method="POST"
                        onSubmit="return validasi(this);">
                        <div class="form-group row">
                            <div class="col-6">
                                <label>Nama</label>
                                <input class="form-control form-control-lg" type="text" name="no_hp" maxlength="15"
                                    placeholder="ketik di sini" value="<?= $_SESSION['post']['no_hp'] ?>">
                            </div>
                            <div class="col-6">
                                <label>Nama</label>
                                <input class="form-control form-control-lg" type="text" name="owner" maxlength="100"
                                    placeholder="ketik di sini"
                                    value="<?= !empty($_SESSION['post']['owner']) ? $_SESSION['post']['owner'] : $_SESSION['nama'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="be-contact-message">Komentar</label>
                            <div class="col-12">
                                <textarea class="form-control form-control-lg" name="komentar" rows="5"
                                    placeholder="Masukan Pesan Anda"><?= $_SESSION['post']['komentar']?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-6">
                                <div class="block">
                                    <div class="block-content">
                                        <img id="captcha" src="<?= base_url().'securimage/securimage_show.php'?>"
                                            alt="CAPTCHA Image" />
                                        <br>
                                        <button class="btn btn-alt-danger"
                                            onclick="document.getElementById('captcha').src = '<?= base_url()."securimage/securimage_show.php?"?>' + Math.random(); return false">Ganti
                                            gambar </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 captha_code">
                                <label class="col-12" for="be-contact-message">Kode CAPTHA</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="captcha_code" maxlength="6"
                                        value="<?= $_SESSION['post']['captcha_code']?>" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fa fa-key"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 text-center">
                                <button type="submit" value="Kirim" class="btn btn-hero btn-alt-primary min-width-175">
                                    <i class="fa fa-send mr-5"></i> Kirim Komentar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php else: ?>
        <span class='info'></span>
        <?php endif; ?>
    </div>
</div>



<?php else: ?>
<div class="hero bg-white">
    <div class="hero-inner">
        <div class="content content-full">
            <div class="py-30 text-center">
                <div class="display-3 text-danger">
                    <i class="fa fa-warning"></i> 404
                </div>
                <h1 class="h2 font-w700 mt-30 mb-10">Oops.. Artikel Tidak Ditemukan..</h1>
                <h2 class="h3 font-w400 text-muted0">Mohon Maaf..</h2>
                <a class="btn btn-hero btn-rounded btn-alt-danger" href="<?= site_url()."first"?>">
                    <i class="fa fa-arrow-left mr-10"></i> Kembali Ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>