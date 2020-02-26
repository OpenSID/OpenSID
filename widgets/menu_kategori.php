<!-- widget Kategori-->

<div class="single_bottom_rightbar">
    <h2> Kategori</h2>
			<ul id="ul-menu" class="sidebar-latest">
				<?php foreach($menu_kiri as $data){?>
					<li><a href="<?= site_url()."first/kategori/".$data['id']?>"><?= $data['nama']; if(count($data['submenu'])>0) { echo "<span class='caret'></span>"; } ?></a>
						<?php if(count($data['submenu'])>0): ?>
							<ul class="submenu">
								<?php foreach($data['submenu'] as $submenu): ?>
									<li><a href="<?= site_url()."first/kategori/".$submenu['id']?>"><?= $submenu['nama']?></a></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</li>
				<?php }?>
			</ul>
</div>