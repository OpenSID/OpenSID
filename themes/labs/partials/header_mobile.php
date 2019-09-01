<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- Sidebar -->
<nav id="sidebar">
    <!-- Sidebar Scroll Container -->
    <div id="sidebar-scroll">
        <!-- Sidebar Content -->
        <div class="sidebar-content">
            <!-- Side Header -->
            <div class="content-header content-header-fullrow bg-black-op-10">
                <div class="content-header-section text-center align-parent">
                    <!-- Close Sidebar, Visible only on mobile screens -->
                    <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
                    <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                        <i class="fa fa-times text-danger"></i>
                    </button>
                    <!-- END Close Sidebar -->

                    <!-- Logo -->
                    <div class="content-header-item">
                        <a class="link-effect font-w700" href="index.php">
                            <i class="si si-fire text-primary"></i>
                            <span class="font-size-xl text-dual-primary-dark">code</span><span class="font-size-xl text-primary">base</span>
                        </a>
                    </div>
                    <!-- END Logo -->
                </div>
            </div>
            <!-- END Side Header -->

            <!-- Side Main Navigation -->
            <div class="content-side content-side-full">
                <!--
                Mobile navigation, desktop navigation can be found in #page-header

                If you would like to use the same navigation in both mobiles and desktops, you can use exactly the same markup inside sidebar and header navigation ul lists
                -->
                <ul class="nav-main">
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
            </div>
            <!-- END Side Main Navigation -->
        </div>
        <!-- Sidebar Content -->
    </div>
    <!-- END Sidebar Scroll Container -->
</nav>
<!-- END Sidebar -->
    
