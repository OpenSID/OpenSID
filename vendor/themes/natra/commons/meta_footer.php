<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<script src="<?= base_url("{$this->theme_folder}/{$this->theme}/assets/js/wow.min.js") ?>"></script>
<script src="<?= base_url("{$this->theme_folder}/{$this->theme}/assets/js/slick.min.js") ?>"></script>
<script src="<?= base_url("{$this->theme_folder}/{$this->theme}/assets/js/custom.js") ?>"></script>
<script>
    $.extend($.fn.dataTable.defaults, {
        lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "Semua"]
        ],
        pageLength: 10,
        language: {
        url: "<?= asset('bootstrap/js/dataTables.indonesian.lang') ?>",
        }
    });
</script>
<?php if (! setting('inspect_element')): ?>
    <script src="<?= asset('js/disabled.min.js') ?>"></script>
<?php endif ?>