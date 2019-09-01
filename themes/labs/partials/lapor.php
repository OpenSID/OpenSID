<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="block block-themed">
    <div class="block-header">
        <h3 class="block-title"><i class="si si-envelope"></i> Laporan Masyrakat</h3>
        <div class="block-options mr-15">
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"><i
                    class="si si-size-fullscreen"></i></button>
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle"
                data-action-mode="demo">
                <i class="si si-refresh"></i>
            </button>
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="close">
                <i class="si si-close"></i>
            </button>
        </div>
    </div>
    <div class="content content-full">
        <div class="row justify-content-center py-30">
            <div class="col-lg-10 col-xl-10">
                <?php if($_SESSION['sukses']==1){echo "Data telah terkirim, dan akan segera kami proses";unset($_SESSION['sukses']);} ?>
                <form class="js-validation-be-contact" id="validasi" action="<?= site_url()?>lapor_web/insert"
                    method="POST" enctype="multipart/form-data">
                    <div class="form-group row">
                        <div class="col-12">
                            <label for="be-contact-name">Nama</label>
                            <input type="text" class="form-control form-control-lg" name="owner"
                                value="<?= $_SESSION['nama']?>" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <label for="be-contact-name">NIK</label>
                            <input type="text" class="form-control form-control-lg" name="nik"
                                value="<?= $_SESSION['nik']?>" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12" for="be-contact-message">Laporan Komentar</label>
                        <div class="col-12">
                            <textarea class="form-control form-control-lg" name="komentar" rows="10"
                                placeholder="EMasukan Komentar.."></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 text-center">
                            <button type="submit" value="Kirim" class="btn btn-hero btn-success min-width-175">
                                <i class="fa fa-send mr-5"></i> Kirim Laporan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END Contact Form -->