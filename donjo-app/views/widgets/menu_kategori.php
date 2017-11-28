<!-- widget Menu-->

<div class="box box-primary box-solid">
	<div class="box-header">
    <h3 class="box-title"><i class="fa fa-bars"></i> Kategori</h3>
	</div>
	<div class="box-body">
			<ul id="ul-menu" class="main">
				<?php foreach($menu_kiri as $data){?>
					<li><a href="<?php echo site_url()."first/kategori/".$data['id']?>"><?php echo $data['nama']; if(count($data['submenu'])>0) { echo "<span class='caret'></span>"; } ?></a>
						<?php if(count($data['submenu'])>0): ?>
							<ul class="submenu">
								<?php foreach($data['submenu'] as $submenu): ?>
									<li><a href="<?php echo site_url()."first/kategori/".$submenu['id']?>"><?php echo $submenu['nama']?></a></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</li>
				<?php }?>
			</ul>
	</div>
</div>
