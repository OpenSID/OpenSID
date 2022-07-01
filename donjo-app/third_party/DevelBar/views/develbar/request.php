<img src="<?= $icon ?>" title="<?= lang('request') ?>"
     alt="<?= lang('request') ?>"/> <?= lang('request') . ' : ' . $controller . '/' . $action ?>
<div class="detail request">
    <p>
        <span class="left-col"><?= lang('method') ?> :</span>
        <span class="right-col"><?= strtoupper($method) ?></span>
    </p>

    <?php if (count($parameters) > 0): ?>
        <p>
            <span class="left-col"><?= lang('parameters') ?> :</span>
        </p>
        <p>
            <span class="right-col" style="float:none"><pre style="color:#FFF"><?=   print_r($parameters, true) ?></pre></span>
        </p>
    <?php endif ?>
</div>
