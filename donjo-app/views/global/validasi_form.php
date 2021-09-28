<script src="<?= base_url('assets/js/jquery.validate.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/validasi.js'); ?>"></script>
<script src="<?= base_url('assets/js/localization/messages_id.js'); ?>"></script>
<script src="<?= base_url('assets/js/script.js'); ?>"></script>
<?php if (empty($web_ui) || $web_ui == false): ?>
	<script src="<?= base_url('assets/js/custom-select2.js'); ?>"></script>
	<script src="<?= base_url('assets/js/custom-datetimepicker.js'); ?>"></script>
<?php endif; ?>
