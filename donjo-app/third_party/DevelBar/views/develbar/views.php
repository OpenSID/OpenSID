<img src="<?php echo $icon ?>" title="<?php echo lang('views') ?>"
     alt="<?php echo lang('views') ?>"/> <?php echo lang('views') . ' (' . count($views) . ')' ?>
<?php if (count($views)): ?>
    <div class="detail views">
        <div class="scroll">
            <?php
            foreach ($views as $path => $data) {
                $path = explode('/', $path);
                $path[count($path) - 1] = '<span style="color:#FFF">' . end($path) . '</span>';
                echo '
            <p>
                <span class="left-col">
                <a href="#" onclick="return ShowViewVars(this);" title="Show variables">
                    <strong><span class="develbar-open-icon">+</span> ' . implode('<span style="color:#FFF">/</span>',
                        $path) . '</strong>
                </a>
                </span>';
                echo '</p>';

                echo '<div class="develbar-detail-vars" style="display:none">';
                if (count($data)) {
                    if (is_array($data) || is_object($data)) {
                        $data = print_r($data, true);
                    }
                    echo '<p>';
                    echo '<span class="right-col" style="width:100%"><pre>' . htmlspecialchars($data) . '</pre></span>';
                    echo '</p>';
                }
                echo '</div>';
            }
            ?>
        </div>
    </div>
<?php endif; ?>
