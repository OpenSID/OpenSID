<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="block block-themed js-animation-object animated lightSpeedIn">
    <div class="block-header bg-gd-sea">
        <h3 class="block-title"><i class="si si-login"></i> Layanan mandiri</h3>
        <div class="block-options mr-15">
        <button
                type="button"
                class="btn-block-option"
                data-toggle="block-option"
                data-action="fullscreen_toggle">
                <i class="si si-size-fullscreen"></i>
            </button>
            <button
                type="button"
                class="btn-block-option"
                data-toggle="block-option"
                data-action="content_toggle">
                <i class="si si-arrow-up"></i>
            </button>
        </div>
    </div>
    <div class="block-content">
        <?php
        if(!isset($_SESSION['mandiri']) OR $_SESSION['mandiri']<>1){ ?>
        <ul class="nav nav-pills push align-items-center" data-toggle="tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#Mandiri-login">Masuk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#mandiri-registere">Daftar </a>
            </li>
        </ul>
        <div class="block-content tab-content">
            <div class="tab-pane active show" id="Mandiri-login" role="tabpanel">
                <?php if($_SESSION['mandiri_wait']==1){ ?>
                <div id="note" class="alert alert-danger alert-dismissable" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h3 class="alert-heading font-size-h4 font-w400">Gagal 3 kali, NIK/PIN yang Anda masukkan salah!
                    </h3>
                    <p class="mb-0">Silakan coba kembali dalam
                        <a class="alert-link"><?= waktu_ind((time()- $_SESSION['mandiri_timeout'])*(-1));?></a> detik
                        lagi.</p>
                </div>
                <?php } else { ?>
                <form action="<?= site_url('first/auth')?>" method="post">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-user"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" name="nik" maxlength="16"
                                    placeholder="Ketik Nomor KTP" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <input type="password" class="form-control" name="pin" type="password"
                                    placeholder="Ketik Kode PIN" value="" maxlength="6" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa fa-key"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <button type="submit" id="but" class="btn btn-alt-primary">Masuk</button>
                        </div>
                    </div>
                    <?php  if($_SESSION['mandiri_try'] AND isset($_SESSION['mandiri']) AND $_SESSION['mandiri']==-1){ ?>
                   
                    <?php }?>
                    <?php  if(isset($_SESSION['mandiri']) AND $_SESSION['mandiri']==-1){ ?>
                    
                    <?php }?>
                </form>
            </div>
            <div class="tab-pane" id="mandiri-registere" role="tabpanel">

            <div class="alert alert-success alert-dismissable" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h3 class="alert-heading font-size-h4 font-w400">Success</h3>
                        <p class="mb-0">This is a successful message with a <a class="alert-link" href="javascript:void(0)">link</a>!</p>
                    </div>
            </div>
            </div>
        </div>
    <?php }
       
} else {
	if(isset($_SESSION['lg']) AND $_SESSION['lg']==1){ ?>

    <ul class="nav nav-pills pushalign-items-center" data-toggle="tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link" href="#ganti-password">Ganti Kata Sandi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#menu-layanan">Menu Layanan</a>
        </li>
    </ul>
    <div class="block-content tab-content">
        <div class="tab-pane" id="ganti-password" role="tabpanel">
            <form action="<?= site_url('first/ganti')?>" method="post">
                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="input-group">
                            <input type="password" class="form-control" name="pin2" type="password"
                                placeholder="Ketik Kode PIN Lama" value="" maxlength="6" required>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fa fa-key"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="input-group">
                            <input type="password" class="form-control" name="pin1" type="password"
                                placeholder="Ketik Kode PIN Baru" value="" maxlength="6" required>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fa fa-key"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <button type="submit" id="but" class="btn btn-alt-primary">Ubah Sandi</button>
                    </div>
                </div>
            </form>
            <?php if ($flash_message) { ?>
            <div class="alert alert-info alert-dismissable" id="notification" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h3 class="alert-heading font-size-h4 font-w400">Information</h3>
                <p class="mb-0"><?= $flash_message ?></p>
            </div>
            <script type="text/javascript">
            $('document').ready(function() {
                $('#notification').delay(4000).fadeOut();
            });
            </script>
            <?php } ?>
        </div>
        <?php } else if(isset($_SESSION['lg']) AND $_SESSION['lg']==2){ ?>
        <div class="alert alert-info alert-dismissable" id="notification" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <h3 class="alert-heading font-size-h4 font-w400">Ubah PIN Sukses</h3>
            <p class="mb-0">PIN Baru Berhasil Disimpan!></p>
        </div>
        <?php  unset($_SESSION['lg']);  } ?>

        <div class="tab-pane active show" id="menu-layanan" role="tabpanel">
        <a class="block block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right">
                    <i class="si si-users fa-4x text-dark"></i>
                    </div>
                    <div class="float-left mt-10">
                        <div class="font-w600 mb-5">NAMA <?= $_SESSION['nama'];?></div>
                        <div class="font-size-sm text-muted">NIK <?= $_SESSION['nik'];?></div>
                        <div class="font-size-sm text-muted"> NO.KK <?= $_SESSION['no_kk']?></div>
                       
                    </div>
                </div>
            </a>    
            <div class="col-12">
                <a href="<?= site_url();?>first/mandiri/1/1"><button class="btn btn btn-rounded btn-noborder btn-info min-width-125 mb-10 btn-xs">
                    <i class="fa fa-print"></i> Profil </button></a>
                <a href="<?= site_url();?>first/mandiri/1/3">
                <button class="btn btn btn-rounded btn-noborder btn-warning min-width-125 mb-10 btn-xs">
                    <i class="fa fa-print"></i> E-Lapor </button>
               </a>
                <a href="<?= site_url();?>first/mandiri/1/2">
                <button class="btn btn btn-rounded btn-noborder btn-success min-width-125 mb-10 btn-xs">
                    <i class="fa fa-print"></i> Layanan</button>
                </a>
                <a href="<?= site_url();?>first/mandiri/1/4">
                <button class="btn btn btn-rounded btn-noborder btn-dark min-width-125 mb-10 btn-xs">
                    <i class="fa fa-support"></i> Bantuan</button>
                    </a>
                <a href="<?= site_url();?>first/logout">
                <button class="btn btn btn-rounded btn-noborder btn-danger min-width-125 mb-10 btn-xs">
                    <i class="fa fa-sign-out"></i> Keluar</button></a>
            </div>
        </div>
    </div>
    <?php
}?>
</div>