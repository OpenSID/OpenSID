<img src="<?= $icon ?>"
     alt="<?= lang('config') ?>" title="<?= lang('config') ?>"/> <?= lang('config') ?>
    <div class="detail config">
        <div class="scroll">
            <?php
            foreach ($configuration as $config => $val) {
                if (is_array($val) || is_object($val)) {
                    $val = print_r($val, true);
                }
                echo '<p>';
                echo '<span class="left-col" style="width:60%">' . $config . ':</span>';
                echo '<span class="right-col" style="width:40%">' . htmlentities($val) . '</span>';
                echo '</p>';
            }
?>
        </div>
    </div>
