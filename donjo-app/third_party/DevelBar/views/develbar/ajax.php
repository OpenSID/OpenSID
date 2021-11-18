<script type="text/javascript">
    var cidvl_siteurl = '<?= site_url(); ?>';
</script>
<img src="<?= $icon ?>" alt="<?= lang('ajax_requests') ?>"
     title="<?= lang('ajax_requests') ?>"/> <span class="ci-toolbar-ajax-requests"></span>
<div class="detail ajax ci-toolbar-block-ajax">
    <div class="scroll">
        <p class="ci-toolbar-ajax-info"></p>
        <table cellspacing="0" width="100%" class="ci-toolbar-ajax-table">
            <thead class="ci-toolbar-ajax-head">
            <tr>
                <td><?= lang('method') ?></td>
                <td><?= lang('status') ?></td>
                <td><?= lang('url') ?></td>
                <td><?= lang('time') ?></td>
                <td><?= lang('profiler') ?></td>
            </tr>
            </thead>
            <tbody class="ci-toolbar-ajax-request-list"></tbody>
        </table>
    </div>
</div>
