<div class="col-md-3">
	<div class="panel-group card card-outline card-info">
		<div class="card-header">
			<h3 class="card-title">Kategori</h3>
			<div class="card-tools">
				<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#collapsibleNavbar"><i class="plus-minus fa fa-minus"></i></button>
			</div>
		</div>
		<div class="card-body collapse show navbar-collapse" id="collapsibleNavbar">
			<ul class="navbar-nav nav-pills nav-stacked sidebar-menu text-sm">
				<?php foreach($submenu as $id => $nama_menu) : ?>
					<li class="<?php ($_SESSION['submenu'] == $id) and print('nav-item active') ?>">
						<a class="nav-link" href="<?= site_url("mailbox/clear/$id") ?>"><?= $nama_menu ?></a>
					</li>
				<?php endforeach ?>
			</ul>
		</div>
	</div>
</div>
<script>
	$(function() {
	  function toggleIcon(e) {
	      $(e.target)
	          .prev('.card-header')
	          .find(".plus-minus")
	          .toggleClass('fa-plus fa-minus');
	  }
	  $('.panel-group').on('hidden.bs.collapse', toggleIcon);
	  $('.panel-group').on('shown.bs.collapse', toggleIcon);
	});
</script>
