<!-- START CodeIgniter Developer Toolbar -->
<style type="text/css">
    <?php echo $css ?>
</style>
<script type="text/javascript">
    <?php echo $js ?>
</script>
<div class="develbar" id="develbar-container">
    <ul class="develbar-nav">
        <li class="none" onclick="HideDevelBar()"><img src="<?php echo $logo ?>" alt="CodeIgniter" />
            <div class="detail">
                <p><?php echo sprintf(lang('ci_version'), $ci_version) ?></p>
                <?php if($ci_new_version !== FALSE): ?>
                    <p>
                    <span class="label warning"><?php echo lang('info') ?></span>
                        <?php echo sprintf(lang('update_message'), anchor($config['ci_download_link'], $ci_new_version, 'target="_blank"')) ?>
                    </p>
                <?php endif ?>
                <p><?php echo anchor($config['documentation_link'], 'CodeIgniter documentation', 'target="_blank"') ?></p>
                <p><?php echo sprintf(lang('develbar_version'), $develBar_version) ?></p>
                <?php if($develbar_new_version !== FALSE): ?>
                    <p>
                    <span class="label warning"><?php echo lang('info') ?></span>
                        <?php echo sprintf(lang('update_message'), anchor($config['develbar_download_link'], $develbar_new_version, 'target="_blank"')) ?>
                    </p>
                <?php endif ?>
                <p><?php echo sprintf(lang('php_version'), PHP_VERSION) ?></p>
                <p><?php echo sprintf(lang('default_language'), ucfirst(config_item('language'))) ?></p>
            </div>
        </li>
        <?php if(count($views)): ?>
            <?php foreach($views as $view): ?>
                <li onmouseover="HideDevelBarSection()"><?php echo $view ?></li>
            <?php endforeach ?>
        <?php endif ?>
    </ul>
</div>
<div onclick="ShowDevelBar()" id="develbar-off"><img src="<?php echo $logo ?>" alt="CodeIgniter" />

<!-- END CodeIgniter Developer Toolbar -->
