<img src="<?php echo $icon ?>" title="<?php echo lang('libraries') ?>"
     alt="<?php echo lang('libraries') ?>"/> <?php echo lang('libraries') . ' (' . count($loaded_libraries) . ')' ?>
<?php if(count($loaded_libraries)): ?>
<div class="detail">
    <div class="scroll">
    <?php
    foreach ($loaded_libraries as $library) {
        echo '
            <p>
                <span class="left-col"><strong>' . $library . '</strong></span>';
        echo '</p>';
    }
    ?>
    </div>
</div>
<?php endif; ?>
