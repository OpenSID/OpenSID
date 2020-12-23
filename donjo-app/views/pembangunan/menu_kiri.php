<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Menu Pembangunan</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li class="<?php ($this->tab_ini == 1) and print('active')?>"><a href="<?=site_url('pembangunan')?>"><i class="fa fa-tags"></i> Daftar Pembangunan</a></li>
			<li class="<?php ($this->tab_ini == 2) and print('active')?>"><a href="<?=site_url('pembangunan_dokumentasi')?>"><i class="fa fa-tags"></i> Daftar Dokumentasi</a></li>
			<li class="<?php ($this->tab_ini == 3) and print('active')?>"><a href="<?=site_url('pembangunan_jenis')?>"><i class="fa fa-tags"></i> Daftar Jenis</a></li>
			<li class="<?php ($this->tab_ini == 4) and print('active')?>"><a href="<?=site_url('pembangunan_sumber_dana')?>"><i class="fa fa-tags"></i> Daftar Sumber Dana</a></li>
		</ul>
	</div>
</div>

