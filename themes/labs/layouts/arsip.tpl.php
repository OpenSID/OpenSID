<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<!--[if lte IE 9]>     <html lang="en" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!-->
<html lang="en" class="no-focus">
<!--<![endif]-->

<head>
<?php $this->load->view("$folder_themes/commons/meta.php"); ?>
<?php $this->load->view("$folder_themes/commons/style.php"); ?>
<?php $this->load->view("$folder_themes/commons/scripts.php"); ?>

    <div id="page-container"
        class="sidebar-inverse side-overlay-hover side-scroll page-header-fixed page-header-inverse main-content-boxed side-trans-enabled">

        <!-- header Mobile -->
        <?php $this->load->view("$folder_themes/partials/header_mobile.php"); ?>
        <!-- header mobile -->

        <!-- header -->
        <?php $this->load->view("$folder_themes/partials/header.php"); ?>
        <!-- header -->
        <main id="main-container">
        <div class="bg-image" style="background-image: url('<?= base_url("$this->theme_folder/$this->theme/assets/headwalp.jpg"); ?>');">
            <div class="bg-black-op">
                <div class="content content-top text-center">
                <img class="img-avatar" src="<?php echo LogoDesa($desa['logo']);?>" alt="<?php echo $desa['nama_desa']?>">
                    <div class="pb-50 pt-10">
                        <h1 class="font-w700 text-white mb-10 js-animation-object animated lightSpeedIn"><?= $this->setting->website_title. ' ' . ucwords($this->setting->sebutan_desa). (($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></h1>
                        <h2 class="h4 font-w400 text-white-op" data-animation-class="lightSpeedOut" > 
                        <em>
                            <?= ucwords($this->setting->sebutan_kecamatan_singkat." ".$desa['nama_kecamatan'])?>,
                            <?= ucwords($this->setting->sebutan_kabupaten_singkat." ".$desa['nama_kabupaten'])?>,
                            <?= ucwords("Prov. ".$desa['nama_propinsi'])?></h2></em>
                        <button class="btn btn-hero btn-noborder btn-rounded btn-alt-success mb-10">
                            <span id="jam"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
            <div class="content content-full">
                <!-- Dummy content -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php $this->load->view("$folder_themes/partials/all_statistik.php"); ?>
                            </div>
                            <div class="col-sm-12">
                                <div class="block">
                                    <?php $this->load->view("$folder_themes/partials/statistik.php"); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="block">
                                    <div class="block-content">
                                        <?php $this->load->view("$folder_themes/partials/widget.php"); ?>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Dummy content -->
            </div>
        </main>
        <?php $this->load->view("$folder_themes/partials/footer.php"); ?>
    </div>
</body>

</html>