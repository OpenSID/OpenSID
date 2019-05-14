    <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav custom_nav">
            <li class=""><a href="<?php echo site_url()."first"?>">Beranda</a></li>
			<?php foreach($menu_kiri as $data){?>
            <!-- <li class="dropdown"> <a href="<?php echo site_url()."first/kategori/".$data['id']?>" class="" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $data['nama']; if(count($data['submenu'])>0) { echo "<span class='caret'></span>"; } ?></a>
             <?php if(count($data['submenu'])>0): ?> 
			  <ul class="dropdown-menu" role="menu">
				<?php foreach($data['submenu'] as $submenu): ?>
                <li><a href="<?php echo site_url()."first/kategori/".$submenu['id']."/";?>"><?php echo $submenu['nama']?></a></li>
				<?php endforeach; ?>
              </ul>
			<?php endif; ?>  
            </li> -->
			<?php }?>
			<?php foreach($menu_atas as $data){?>
			<li class="dropdown"><a class="dropdown-toggle" href="<?php echo $data['link']?>"><?php echo $data['nama']; if(count($data['submenu'])>0) { echo "<span class='caret'></span>"; } ?></a>
			<?php if(count($data['submenu'])>0): ?>
			    <ul class="dropdown-menu">
			        <?php foreach($data['submenu'] as $submenu): ?>
			        <li>
			            <a href="<?php echo $submenu['link']?>"><?php echo $submenu['nama']?></a>
			        </li>
			        <?php endforeach; ?>
			    </ul>
			    <?php endif; ?>
			</li>
			<?php }?>
          </ul>
        </div>
      </div>
    </nav>
