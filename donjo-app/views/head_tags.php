<?php if ($this->config->config['csrf_protection']): ?>
	<script type="text/javascript">
		var csrfParam = '<?= $this->security->get_csrf_token_name() ?>';
		var csrfVal      = '<?= $this->security->get_csrf_hash() ?>';
		function getCsrfToken() {
			return csrfVal;
		}
	</script>
	<script src="<?= asset('js/anti-csrf.js') ?>"></script>
<?php endif ?>
