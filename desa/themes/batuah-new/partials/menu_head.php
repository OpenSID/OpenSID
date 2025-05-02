<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div id="navbar">
	<ul class="nav navbar-nav">
		<?php foreach ($menu_atas as $data): ?>
		<li class="dropdown">
			<a class="dropdown-toggle" href="<?= $data['link']?>"><?= $data['nama']; jecho(count($data['submenu']) > 0, TRUE, '<span class="caret"></span>'); ?></a>
			<?php if (count($data['submenu']) > 0): ?>
			<ul class="dropdown-menu">
			<?php foreach ($data['submenu'] as $submenu): ?>
				<li>
					<a href="<?= $submenu['link']?>"><?= $submenu['nama']?></a>
				</li>
			<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>
</div>