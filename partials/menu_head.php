<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <div class="row visible-xs">
            <div class="col-xs-6 visible-xs">
                <img src="<?= gambar_desa($desa['logo']);?>" class="cardz hidden-lg hidden-md" width="30" align="left" alt="<?= $desa['nama_desa']?>"/>
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
                <li class=""><a href="<?= site_url(); ?>">Beranda</a></li>
                <?php foreach($menu_atas as $data) { ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" href="<?= $data['link']?>"><?= $data['nama']; if(count($data['submenu'])>0) { echo "<span class='caret'></span>"; } ?></a>
                    <?php if(count($data['submenu'])>0): ?>
                    <ul class="dropdown-menu">
                        <?php foreach($data['submenu'] as $submenu): ?>
                        <li>
                            <a href="<?= $submenu['link']?>"><?= $submenu['nama']?></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </li>
                <?php } ?>
                <li><a href="<?= site_url()."siteman"?>" target="_blank">Login</a></li>
                <div class="hidden-xs navbar-right" style="margin-right: 15px; margin-top: 10px">
                	<form method=get action="<?= site_url(); ?>" class="form-inline">
                		<input type="text" name="cari" maxlength="50" class="form-control" value="<?= $cari ?>" placeholder="Cari Artikel">
                		<button type="submit" class="btn btn-primary">Cari</button>
                	</form>	
                  </div>
            </ul>
			</div>
    </div>
</nav>
