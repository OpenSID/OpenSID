<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	$panel = [
		'default',
		'info',
		'primary',
		'secondary',
		'warning',
		'danger',
		'success',
	];
	
?>


<style type="text/css">
	#covid_local {
		margin-right: 8px;
		margin-left: 7px;
	}

	#covid_local .panel {
		background-color: inherit;
		margin-bottom: 0px;
	}

	#covid_local .panel-body {
		background-color: white;
	}

	#covid_local .panel-body.sub {
		background-color: inherit;
		padding-top: 10px;
	}

	#covid_local .row .panel-heading {
		height: 50px;
		padding: 1px;
	}
</style>

<div id="covid_local">
  <div class="panel">
    <div class="box-header with-border">
      <h3 class="box-title">
        <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Status COVID-19 di <?= ucwords($this->setting->sebutan_desa); ?> <?=$desa['nama_desa']; ?></span>
      </h3>
    </div>
    <div class="panel-body sub">
			<div class="row">
				<?php foreach ($covid as $key => $val):
					if ($key >= 7) break;
					if($key >= 3) echo '<br/>'
				?>
					<div class="col-lg-3 col-md-3 col-sm-3"">
						<div class="panel panel-<?= $panel[$key]?>" style="border: 1px solid">
							<div style="padding:1px" class="panel-heading text-center"><h4><?= $val['nama']; ?></h4></div>
							<div style="height: 40px;padding:1px" class="panel-body text-center">
								<h4><?= number_format($val['jumlah']); ?> <small>Orang</small></h4>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="progress-group">
          <a href="<?= site_url('first/statistik/covid')?>">
          <button type="button" class="btn btn-info btn-block">Lihat info lengkap di Statistik COVID19</button>
        </a>
      </div>
    </div>
  </div>
</div>
