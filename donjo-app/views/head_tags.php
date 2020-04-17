		<meta content="<?=$this->security->get_csrf_token_name()?>" name="csrf-param"/>
		<meta content="<?=$this->security->get_csrf_hash()?>" name="csrf-token"/>
		<script src="<?= base_url()?>assets/js/anti-csrf.js"></script>
