    <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav custom_nav">
            <li class=""><a href="<?= site_url()."first"?>">Beranda</a></li>
			<?php foreach($menu_kiri as $data){?>
            <!-- <li class="dropdown"> <a href="<?= site_url()."first/kategori/".$data['id']?>" class="" data-toggle="dropdown" role="button" aria-expanded="false"><?= $data['nama']; if(count($data['submenu'])>0) { echo "<span class='caret'></span>"; } ?></a>
             <?php if(count($data['submenu'])>0): ?> 
			  <ul class="dropdown-menu" role="menu">
				<?php foreach($data['submenu'] as $submenu): ?>
                <li><a href="<?= site_url()."first/kategori/".$submenu['id']."/";?>"><?= $submenu['nama']?></a></li>
				<?php endforeach; ?>
              </ul>
			<?php endif; ?>  
            </li> -->
			<?php }?>
			<?php foreach($menu_atas as $data){?>
			<li class="dropdown"><a class="dropdown-toggle" href="<?= $data['link']?>"><?= $data['nama']; if(count($data['submenu'])>0) { echo "<span class='caret'></span>"; } ?></a>
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
			<?php }?>
			<li class=""><a href="<?= site_url()."siteman"?>">Login</a></li>
          </ul>
        </div>
      </div>
    </nav>
