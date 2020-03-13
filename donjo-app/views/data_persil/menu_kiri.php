<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Menu Pendataan Persil</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if ($this->tab_ini == 10): ?>class="active"<?php endif; ?>><a href="<?= site_url('data_persil/create')?>"><i class='fa fa-plus'></i> Tambah Data C-Desa Baru</a></li>
			<li <?php if ($this->tab_ini == 11): ?>class="active"<?php endif; ?>><a href="<?= site_url('data_persil/create_ext')?>"><i class='fa fa-edit'></i>Tambah Data C-Desa (Manual)</a></li>
			<li <?php if ($this->tab_ini == 12): ?>class="active"<?php endif; ?>><a href="<?= site_url('data_persil/clear')?>"><i class='fa fa-list'></i>Daftar C-DESA</a></li>
			<li <?php if ($this->tab_ini == 13): ?>class="active"<?php endif; ?>><a href="<?= site_url('data_persil/persil_clear')?>"><i class='fa fa-list'></i>Daftar Persil</a></li>
			<li <?php if ($this->tab_ini == 14): ?>class="active"<?php endif; ?>><a href="<?= site_url('data_persil/import')?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Impor Data Persil"><i class='fa fa-upload'></i>Impor Data C-Desa</a></li>
			<li <?php if ($this->tab_ini == 15): ?>class="active"<?php endif; ?>><a href="<?= site_url('data_persil/panduan')?>"><i class='fa fa-question-circle'></i>Panduan C-Desa</a></li>
		</ul>
	</div>
</div>
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Jenis Tanah</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<?php if ($persil_jenis): ?>
				<?php foreach ($persil_jenis as $key => $item): ?>
					<li <?php if ($this->tab_ini == 2..$key): ?>class="active"<?php endif; ?> ><a href="<?= site_url("data_persil/persil/jenis/$key/")?>" title="<?=$item['nama']?>"><i class='fa fa-list'></i> <?=$item['nama']?></a></li>
				<?php endforeach;?>
			<?php endif; ?>
			<li <?php if ($this->tab_ini == 20): ?>class="active"<?php endif; ?>><a href="<?= site_url('data_persil/persil_jenis')?>"><i class='fa fa-plus'></i> Tambah Jenis Tanah</a></li>
		</ul>
	</div>
</div>
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Peruntukan Tanah</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
		<?php if ($persil_peruntukan): ?>
			<?php foreach ($persil_peruntukan as $key=>$item): ?>
				<li <?php if ($this->tab_ini == 3..$key): ?>class="active"<?php endif; ?>><a href="<?= site_url("data_persil/persil/peruntukan/$key/")?>" title="<?=$item[1]?>"><i class='fa fa-list'></i> <?=$item[0]?></a></li>
			<?php endforeach;?>
		<?php endif; ?>
		<li <?php if ($this->tab_ini == 30): ?>class="active"<?php endif; ?>><a href="<?= site_url('data_persil/persil_peruntukan')?>"><i class='fa fa-plus'></i> Tambah Data Peruntukan</a></li>
	</div>
</div>