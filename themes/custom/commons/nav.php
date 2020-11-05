<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<nav class="nav">
	<ul class="nav__list">
		<?php if(menu_atas) : ?>	
			<li class="nav__item nav__item--active">
				<a href="<?= site_url('first') ?>" class="nav__link"><i class="fa fa-home"></i></a>
			</li>
			<?php foreach($menu_atas as $menu) : ?>
				<li class="nav__item <?php count(menu['submenu']) > 0 and print('nav__item--has-dropdown') ?>">
					<a href="<?= $menu['link'] ?>" class="nav__link"><?= $menu['nama'] ?>
						<?php if(count($menu['submenu']) > 0) : ?>
							<i class="fa fa-chevron-down nav__icon"></i>
						<?php endif ?>
					</a>
						<?php if(count($menu['submenu']) > 0) : ?>
							<ul class="nav-dropdown">
								<?php foreach($menu['submenu'] as $submenu) : ?>
									<li class="nav-dropdown__item"><a href="<?= $submenu['link'] ?>" class="nav-dropdown__link"><?= $submenu['nama'] ?></a></li>
								<?php endforeach ?>
							</ul>
						<?php endif ?>
			<?php endforeach ?>
		<?php endif ?>
	</ul>
</nav>