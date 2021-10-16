<img src="<?php echo $icon ?>" title="<?php echo lang('helpers') ?>"
     alt="<?php echo lang('helpers') ?>"/> <?php echo lang('helpers') . ' (' . count($helpers) . ')' ?>
<?php if(count($helpers)): ?>
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
