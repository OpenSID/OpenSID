<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Menu Pendataan C-Desa</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<?php if ($this->CI->cek_hak_akses('u')): ?>
				<li class='<?= jecho($this->tab_ini, 10, 'active') ?>'><a href="<?= site_url('cdesa/create')?>"><i class='fa fa-plus'></i> Tambah C-Desa</a></li>
			<?php endif; ?>
			<li class='<?= jecho($this->tab_ini, 12, 'active') ?>'><a href="<?= site_url('cdesa/clear')?>"><i class='fa fa-list'></i>Daftar C-DESA</a></li>
			<li class='<?= jecho($this->tab_ini, 13, 'active') ?>'><a href="<?= site_url('data_persil/clear')?>"><i class='fa fa-list'></i>Daftar Persil</a></li>
			<!-- <li class='<?= jecho($this->tab_ini, 14, 'active') ?>'><a data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Impor Data Persil"><i class='fa fa-upload'></i>Impor Data C-Desa</a></li> -->
			<li class='<?= jecho($this->tab_ini, 15, 'active') ?>'><a href="<?= site_url('cdesa/panduan')?>"><i class='fa fa-question-circle'></i>Panduan C-Desa</a></li>
		</ul>
	</div>
</div>
