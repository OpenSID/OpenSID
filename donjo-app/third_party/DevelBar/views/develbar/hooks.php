<img src="<?= $icon ?>" title="<?= lang('hooks') ?>"
     alt="<?= lang('hooks') ?>"/> <?= $total_hooks > 0 ? lang('hooks') . ' (' . $total_hooks . ')' : 'N/A' ?>
<?php if (count($loaded_hooks)): ?>
<div class="detail hooks">
    <div class="scroll">
    <?php
    foreach ($loaded_hooks as $name => $hooks) {
        echo '
            <p>
                <span class="left-col"><strong>' . $name . ' [' . count($hooks) . ']' . '</strong></span>';

        foreach ($hooks as $key => $hook) {
            $border = $key == count($hooks) - 1 ? '' : 'border-bottom:1px solid #57595E';
            echo '<span class="right-col" style="margin-left:20px;' . $border . '">';
            if ($hook == 'Closure') {
                echo '<span class="left-col" style="width:30%">&nbsp;</span>';
                echo '<span class="right-col" style="width:70%">' . $hook . '</span>';
            } else {
                foreach ($hook as $key => $value) {
                    echo '<span class="left-col" style="width:30%">' . $key . ':</span>';
                    echo '<span class="right-col" style="width:70%">' . (! is_array($value) ? $value : var_dump($value)) . '</span>';
                }
            }

            echo '</span>';
        }

        echo '</p>';
    }
?>
    </div>
</div>
<?php endif; ?>
