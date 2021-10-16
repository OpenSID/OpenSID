<img src="<?php echo $icon ?>" title="<?php echo lang('models') ?>"
     alt="<?php echo lang('models') ?>"/> <?php echo (count($models) ? lang('models') . ' (' . count($models) . ')' : 'N/A') ?>
<?php if(count($models)): ?>
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
