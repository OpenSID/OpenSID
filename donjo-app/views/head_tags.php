<script type="text/javascript">
	var csrfParam = '<?=$this->security->get_csrf_token_name()?>';
	var csrfToken = '<?=$this->security->get_csrf_hash()?>';
</script>
<script src="<?= base_url()?>assets/js/anti-csrf.js"></script>
