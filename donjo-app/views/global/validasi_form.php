<script src="<?= base_url('assets/js/jquery.validate.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/validasi.js'); ?>"></script>
<script src="<?= base_url('assets/js/localization/messages_id.js'); ?>"></script>

<script src="<?= base_url('assets/js/script.js'); ?>"></script>

<?php IF($_SESSION['UI'] === 'ADMIN'): ?>
<script src="<?= base_url('assets/js/script-admin.js'); ?>"></script>
<?php ENDIF ?>
