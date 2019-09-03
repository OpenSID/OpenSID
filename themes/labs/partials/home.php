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
                <div class="col-sm-12 py-30">
                    <?php $this->load->view("$folder_themes/layouts/slider.php"); ?>
                </div>
                <div class="col-sm-12">
                <?php if (!empty($teks_berjalan)): ?>
                    <marquee onmouseover="this.stop()" onmouseout="this.start()">
						<?php $this->load->view($folder_themes.'/layouts/teks_berjalan.php'); ?>
                    </marquee>
                    <?php endif; ?>
                </div>
                <div class="col-sm-12">
                    <?php $this->load->view("$folder_themes/partials/artikel_home.php"); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <?php $this->load->view("$folder_themes/partials/widget.php"); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- END Dummy content -->
</div>
