<img src="<?= $icon ?>"
     alt="<?= lang('session') ?>" title="<?= lang('session') ?>"/> <?= count($session) > 0 ? lang('session') : 'N/A' ?>
<?php if (count($session) > 0): ?>
    <div class="detail config">
        <div class="scroll">
            <?php
            foreach ($session as $key => $val) {
                if (is_array($val) || is_object($val)) {
                    $val = print_r($val, true);
                }
                echo '<p>';
                echo '<span class="left-col" style="width:50%">' . $key . ':</span>';
                echo '<span class="right-col" style="width:50%">' . htmlspecialchars($val) . '</span>';
                echo '</p>';
            }
         ?>
        </div>
    </div>
<?php endif ?>