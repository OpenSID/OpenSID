<img src="<?= $icon ?>" title="<?= lang('helpers') ?>"
     alt="<?= lang('helpers') ?>"/> <?= lang('helpers') . ' (' . count($helpers) . ')' ?>
<?php if (count($helpers)): ?>
<div class="detail">
    <div class="scroll">
    <?php
    foreach ($helpers as $helper) {
        echo '
            <p>
                <span class="left-col"><strong>' . ucfirst($helper) . '</strong></span>';
        echo '</p>';
    }
         ?>
    </div>
</div>
<?php endif; ?>
