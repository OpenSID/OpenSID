<script type="text/javascript">
    var cidvl_siteurl = '<?php echo site_url(); ?>';
</script>
<img src="<?php echo $icon ?>" alt="<?php echo lang('ajax_requests') ?>"
     title="<?php echo lang('ajax_requests') ?>"/> <span class="ci-toolbar-ajax-requests"></span>
<div class="detail ajax ci-toolbar-block-ajax">
    <div class="scroll">
        <p class="ci-toolbar-ajax-info"></p>
        <table cellspacing="0" width="100%" class="ci-toolbar-ajax-table">
            <thead class="ci-toolbar-ajax-head">
            <tr>
                <td><?php echo lang('method') ?></td>
                <td><?php echo lang('status') ?></td>
                <td><?php echo lang('url') ?></td>
                <td><?php echo lang('time') ?></td>
                <td><?php echo lang('profiler') ?></td>
            </tr>
            </thead>
            <tbody class="ci-toolbar-ajax-request-list"></tbody>
        </table>
    </div>
</div>
