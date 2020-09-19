<?php if ($this->config->config['csrf_protection']): ?>
	<script type="text/javascript">
		var csrfParam = '<?=$this->security->get_csrf_token_name()?>';
		var getCsrfToken = () => document.cookie.match(new RegExp(csrfParam +'=(\\w+)'))[1]
	</script>
	<script src="<?= base_url()?>assets/js/anti-csrf.js"></script>
<?php endif ?>
