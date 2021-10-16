<img src="<?php echo $icon ?>" title="<?php echo lang('request') ?>"
     alt="<?php echo lang('request') ?>"/> <?php echo lang('request') . ' : ' . $controller . '/' . $action ?>
<div class="detail request">
    <p>
        <span class="left-col"><?php echo lang('method') ?> :</span>
        <span class="right-col"><?php echo strtoupper($method) ?></span>
    </p>

    <?php if (count($parameters) > 0): ?>
        <p>
            <span class="left-col"><?php echo lang('parameters') ?> :</span>
        </p>
        <p>
            <span class="right-col" style="float:none"><pre style="color:#FFF"><?php echo   print_r($parameters, true) ?></pre></span>
        </p>
    <?php endif ?>
</div>
