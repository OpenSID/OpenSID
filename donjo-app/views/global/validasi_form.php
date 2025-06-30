<?= view('admin.layouts.components.token') ?>

<script src="<?= asset('js/jquery.validate.min.js') ?>"></script>
<script src="<?= asset('js/validasi.js') ?>"></script>
<script src="<?= asset('js/localization/messages_id.js') ?>"></script>
<script src="<?= asset('js/script.js') ?>"></script>
<?php if (empty($web_ui) || $web_ui == false): ?>
	<script src="<?= asset('js/custom-select2.js') ?>"></script>
	<script src="<?= asset('js/custom-datetimepicker.js') ?>"></script>
<?php endif; ?>
