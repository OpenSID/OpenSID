<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php $has_dropdown = count($data['childrens'] ?? []) > 0 ?>
<li>
    <a class="menu-down" href="<?= $has_dropdown ? 'javascript:void(0);' : $data['link_url'] ?>">
        <?= $data['nama']; ?>
      	<?php if($has_dropdown) : ?><span class='caret'></span><?php endif; ?>
    </a>
    <?php if($has_dropdown) : ?>
        <ul class="nav-sub bg-navsub">
            <?php foreach($data['childrens'] as $submenu) : ?>
                <?php $this->load->view($folder_themes . "/partials/home/menu_header", ['data' => $submenu]); ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</li>
