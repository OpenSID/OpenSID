<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if(menu_tema()) : ?>
<?php
function showChildrens($items) {
    $html = '<ul style="background:rgba(0,0,0,0.2);margin-top:5px;margin-bottom:5px;padding:15px 10px 10px;font-size:16px !important;">';
    foreach ($items as $item) {
        $html .= '<li class="dropdown" style="font-size:16px !important;">';
        $html .= '<a style="font-size:16px !important;" href="' . $item["link_url"] . '">' . $item['nama'];
        if (!empty($item['childrens'])) {
            $html .= '</a>';
            $html .= showChildrens($item['childrens']);
        } else {
            $html .= '</a>';
        }
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}
?>
<?php foreach(menu_tema() as $menu) : ?>
    <?php $has_dropdown = count($menu['childrens'] ?? []) > 0 ?>
    <li class="dropdown" style="font-size:16px !important;">
    <!-- utama -->
    <a style="font-size:16px !important;" href="<?= $has_dropdown ? 'javascript:void(0);' : $menu['link_url'] ?>" class="dropdown-toggle" data-toggle="<?= $has_dropdown ? 'dropdown' : '' ?>" role="button" aria-haspopup="true" aria-expanded="true"><span><?= $menu['nama'] ?></span>
    <?php if($has_dropdown) : ?>
        <span class='caret'></span>
    <?php endif ?>
    </a>
    <?php if($has_dropdown) : ?>
        <ul class="dropdown-menu" style="margin-top:10px;font-size:16px !important;">
        <?php foreach($menu['childrens'] as $submenu) : ?>
            <?php $has_dropdown = count($submenu['childrens'] ?? []) > 0 ?>
            <ul style="padding:0px 0px 0px;font-size:16px !important;">
                <li class="dropdown" style="font-size:16px !important;">
                    <!-- submenu -->
                    <a href="<?= $has_dropdown ? 'javascript:void(0);' : $submenu['link_url'] ?>">
                        <p style="font-size:16px !important;"><?= $submenu['nama'] ?></p>
                    </a>
                    <?php if($has_dropdown) : ?>
                        <?= showChildrens($submenu['childrens']) ?>
                    <?php endif ?>
                </li>
            </ul>
        <?php endforeach ?>
        </ul>
    <?php endif ?>
    </li>
<?php endforeach ?>
<?php endif ?>
