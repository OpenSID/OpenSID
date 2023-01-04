<img src="<?= $icon ?>" title="<?= lang('models') ?>"
     alt="<?= lang('models') ?>"/> <?= count($models) ? lang('models') . ' (' . count($models) . ')' : 'N/A' ?>
<?php if (count($models)): ?>
<div class="detail models">
    <div class="scroll">
    <?php
    foreach ($models as $model) {
        echo '
            <p>
                <span class="left-col"><strong>' . $model . '</strong></span>';
        echo '</p>';
    }
         ?>
    </div>
</div>
<?php endif; ?>
