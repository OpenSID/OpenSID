<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view($folder_themes.'/layouts/header.php');?>
			<div id="contentwrapper">
				<div id="contentcolumn">
					<div class="innertube">
						<?php if ($tipe == 2): ?>
						<?php elseif ($tipe == 3): ?>
							<?php $this->load->view(Web_Controller::fallback_default($this->theme, '/partials/wilayah.php')); ?>
						<?php elseif ($tipe == 4): ?>
							<?php $this->load->view('statistik/dpt.php'); ?>
						<?php else: ?>
							<?php if (isset($list_dusun)): ?>
							<form method="post" action="<?= site_url("first/statistik/$st/$tipe") ?>" class="form-inline" style="margin-bottom:10px;">
								<select class="form-control input-sm" name="dusun" onchange="this.form.submit()">
									<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun) ?></option>
									<?php foreach ($list_dusun as $data_dusun): ?>
										<option value="<?= $data_dusun['dusun'] ?>" <?= selected($dusun, $data_dusun['dusun']) ?>><?= set_ucwords($data_dusun['dusun']) ?></option>
									<?php endforeach; ?>
								</select>
								<?php if ($dusun && isset($list_rw)): ?>
									<select class="form-control input-sm" name="rw" onchange="this.form.submit()">
										<option value="">Pilih RW</option>
										<?php foreach ($list_rw as $data_rw): ?>
											<option value="<?= $data_rw['rw'] ?>" <?= selected($rw, $data_rw['rw']) ?>><?= $data_rw['rw'] ?></option>
										<?php endforeach; ?>
									</select>
								<?php endif; ?>
								<?php if ($rw && isset($list_rt)): ?>
									<select class="form-control input-sm" name="rt" onchange="this.form.submit()">
										<option value="">Pilih RT</option>
										<?php foreach ($list_rt as $data_rt): ?>
											<option value="<?= $data_rt['rt'] ?>" <?= selected($rt, $data_rt['rt']) ?>><?= $data_rt['rt'] ?></option>
										<?php endforeach; ?>
									</select>
								<?php endif; ?>
							</form>
							<?php endif; ?>
							<?php $this->load->view('statistik/penduduk_grafik_web.php'); ?>
							<?php if (in_array($st, array('bantuan_keluarga', 'bantuan_penduduk'))):?>
								<?php if ($this->setting->daftar_penerima_bantuan):?>
									<?php $this->load->view('statistik/peserta_bantuan', array('lap' => $st)); ?>
								<?php endif;?>
							<?php endif;?>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div id="rightcolumn">
				<div class="innertube">
					<?php $this->load->view(Web_Controller::fallback_default($this->theme, '/partials/side.right.php'));?>
				</div>
			</div>

			<div id="footer">
				<?php
				$this->load->view($folder_themes.'/partials/copywright.tpl.php');
				?>
			</div>
		</div>
	</body>
</html>
