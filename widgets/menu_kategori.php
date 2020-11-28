<!-- widget Kategori-->

<div class="single_bottom_rightbar">
	<h2> Kategori</h2>
	<ul id="ul-menu" class="sidebar-latest">
		<?php foreach($menu_kiri as $data):?>
			<li>
				<a href="<?= site_url("artikel/kategori/$data[slug]"); ?>">
					<?= $data['kategori']; ?><?php (count($data['submenu'])>0) and print('<span class="caret"></span>');?>
				</a>
				<?php if(count($data['submenu'])>0): ?>
					<ul class="nav submenu">
						<?php foreach($data['submenu'] as $submenu):?>
							<li><a href="<?= site_url("artikel/kategori/$submenu[slug]"); ?>"><?= $submenu['kategori']?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</li>
		<?php endforeach;?>
	</ul>
</div>
