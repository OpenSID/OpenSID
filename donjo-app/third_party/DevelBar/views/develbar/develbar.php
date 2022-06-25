<!-- START CodeIgniter Developer Toolbar -->
<style type="text/css">
    <?= $css ?>
</style>
<script type="text/javascript">
    <?= $js ?>
</script>
<div class="develbar" id="develbar-container">
    <ul class="develbar-nav">
        <li class="none" onclick="HideDevelBar()"><img src="<?= $logo ?>" alt="CodeIgniter" />
            <div class="detail">
                <p><?= sprintf(lang('ci_version'), $ci_version) ?></p>
                <?php if ($ci_new_version !== false): ?>
                    <p>
                    <span class="label warning"><?= lang('info') ?></span>
                        <?= sprintf(lang('update_message'), anchor($config['ci_download_link'], $ci_new_version, 'target="_blank"')) ?>
                    </p>
                <?php endif ?>
                <p><?= anchor($config['documentation_link'], 'CodeIgniter documentation', 'target="_blank"') ?></p>
                <p><?= sprintf(lang('develbar_version'), $develBar_version) ?></p>
                <?php if ($develbar_new_version !== false): ?>
                    <p>
                    <span class="label warning"><?= lang('info') ?></span>
                        <?= sprintf(lang('update_message'), anchor($config['develbar_download_link'], $develbar_new_version, 'target="_blank"')) ?>
                    </p>
                <?php endif ?>
                <p><?= sprintf(lang('php_version'), PHP_VERSION) ?></p>
                <p><?= sprintf(lang('default_language'), ucfirst(config_item('language'))) ?></p>
            </div>
        </li>
        <?php if (count($views)): ?>
            <?php foreach ($views as $view): ?>
                <li onmouseover="HideDevelBarSection()"><?= $view ?></li>
            <?php endforeach ?>
        <?php endif ?>
    </ul>
</div>
<div onclick="ShowDevelBar()" id="develbar-off"><img src="<?= $logo ?>" alt="CodeIgniter" />

<!-- END CodeIgniter Developer Toolbar -->
