<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<header id="page-header">
    <!-- Header Content -->
    <div class="content-header">
        <!-- Left Section -->
        <div class="content-header-section">
            <!-- Logo -->
            <div class="content-header-item">
                <a class="link-effect font-w700 mr-5" href="<?= site_url()."first"?>">
                    <i class="fa fa-institution text-primary js-animation-object animated zoomInLeft"></i>
                    <span class="h6 text-dual-primary-dark js-animation-object animated zoomInRight"><?php echo $desa['nama_desa']?></span>
                </a>
            </div>
            <!-- END Logo -->
        </div>
        <!-- END Left Section -->

        <!-- Middle Section -->
        <div class="content-header-section d-none d-lg-block">
            <!-- Header Navigation -->
            <!--
            Desktop Navigation, mobile navigation can be found in #sidebar

            If you would like to use the same navigation in both mobiles and desktops, you can use exactly the same markup inside sidebar and header navigation ul lists
            If your sidebar menu includes headings, they won't be visible in your header navigation by default
            If your sidebar menu includes icons and you would like to hide them, you can add the class 'nav-main-header-no-icons'
            -->
            <ul class="nav-main-header">
                <li>
                    <a class="active" href="<?= site_url()."first"?>"><i class="si si-compass"></i>Beranda</a>
                </li>
                <li>
                    <a class="activehref=" href="<?= site_url()."first/gallery" ?>"><i class="si si-picture"></i>gallery</a>
                </li>
                <li>
                    <a class="activehref=" href="<?= site_url()."first/dpt" ?>"><i class="si si-badge"></i>Calon Pemilih</a>
                </li>
                <?php foreach($menu_kiri as $data){?>
                <!-- <li>
                    <a <?php if(count($data['submenu'])>0) { echo "class='nav-submenu' data-toggle='nav-submenu'"; } ?> href="<?= site_url()."first/kategori/".$data['id']?>"><i class="si si-magnifier"></i><?= $data['nama']; ?></a>
               
                <?php if(count($data['submenu'])>0): ?> 
                    <ul>
                    <?php foreach($data['submenu'] as $submenu): ?>
                        <li>
                            <a href="<?= site_url()."first/kategori/".$submenu['id']."/";?>"><?= $submenu['nama']?></a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>  
                </li> -->
                <?php }?>
                <?php foreach($menu_atas as $data){?>
                <li>
                    <a <?php if(count($data['submenu'])>0) { echo "class='nav-submenu' data-toggle='nav-submenu'"; } ?> href="<?= $data['link']?>"><i class="fa fa-circle text-success"></i><?= $data['nama'];?></a>
               
                <?php if(count($data['submenu'])>0): ?> 
                    <ul>
                    <?php foreach($data['submenu'] as $submenu): ?>
                        <li>
                            <a href="<?= $submenu['link']?>"><?= $submenu['nama']?></a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>  
                </li>
                <?php }?>
            </ul>
            <!-- END Header Navigation -->
        </div>
        <!-- END Middle Section -->

        <!-- Right Section -->
        <div class="content-header-section">
            <button type="button" class="btn btn-square btn-dual-secondary" data-toggle="layout"
                data-action="header_search_on">
                <i class="fa fa-search"></i><span> Cari Artikel ..</span>
            </button>
            <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none" data-toggle="layout"
                data-action="sidebar_toggle">
                <i class="fa fa-navicon"></i>
            </button>
            <!-- END Toggle Sidebar -->
        </div>
        <!-- END Right Section -->
    </div>
    <!-- END Header Content -->

    <!-- Header Search -->
    <div id="page-header-search" class="overlay-header">
        <div class="content-header content-header-fullrow">
            <form method=get action="<?= site_url('first');?>" >
                <div class="input-group">
                    <div class="input-group-prepend">
                        <!-- Close Search Section -->
                        <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
                        <button type="button" class="btn btn-secondary px-15" data-toggle="layout"
                            data-action="header_search_off">
                            <i class="fa fa-times"></i>
                        </button>
                        <!-- END Close Search Section -->
                    </div>
                    <input type="text" class="form-control" name="cari"  value="<?= $_GET['cari']; ?>" placeholder="Cari Artikel">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-secondary px-15">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Header Loader -->
    <!-- Please check out the Activity page under Elements category to see examples of showing/hiding it -->
    <div id="page-header-loader" class="overlay-header bg-primary">
        <div class="content-header content-header-fullrow text-center">
            <div class="content-header-item">
                <i class="fa fa-sun-o fa-spin text-white"></i>
            </div>
        </div>
    </div>
    <!-- END Header Loader -->
</header>