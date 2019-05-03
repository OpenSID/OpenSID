<div id="penduduk" class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Program Bantuan</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if ($lap=='1'): ?>class="active"<?php endif; ?>><a href="<?=site_url('program_bantuan/create')?>"><i class="fa fa-pencil"></i> Tambah Program Bantuan</a></li>
      <li <?php if ($lap=='0'): ?>class="active"<?php endif; ?>><a href="<?=site_url('program_bantuan')?>"><i class="fa fa-list"></i> Daftar Program Bantuan</a></li>
			<li <?php if ($lap=='2'): ?>class="active"<?php endif; ?>><a href="<?=site_url('program_bantuan/panduan')?>"><i class="fa fa-question-circle"></i> Panduan</a></li>
		</ul>
	</div>
</div>
