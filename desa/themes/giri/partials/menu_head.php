<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <div class="row visible-xs">
            <div class="col-xs-6 visible-xs">
                <img src="<?= gambar_desa($desa['logo']); ?>" class="cardz hidden-lg hidden-md" width="30" align="left" alt="<?= $desa['nama_desa']; ?>"/>
            </div>
            <div class="col-xs-6 visible-xs">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            </div>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav custom_nav">
                <li class="dropdown"><a href="<?= site_url(); ?>">Beranda</a></li>
				<?php createDropdownMenu(menu_tema()) ?>               
            </ul>
		</div>
    </div>
</nav>

<style>
    .dropdown>.dropdown-menu>.dropdown>.dropdown-menu{
        position: absolute;
        left: 100%;
        top: 10%;
        
    }
    .dropdown>.dropdown-menu>.dropdown:hover>.dropdown-menu{
        background-color: #E64946;
        color: #FFF
    }
</style>