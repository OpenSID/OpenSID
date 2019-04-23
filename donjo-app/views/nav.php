<aside class="main-sidebar">
	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?= LogoDesa($desa['logo']);?>" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<strong><?= ucwords($this->setting->sebutan_desa." ".$desa['nama_desa']);?></strong>
				</br>
				<?php
					$nam_kec = strlen($desa['nama_kecamatan']);
					$nam_kab = strlen($desa['nama_kabupaten']);
				?>
				<?php	if ($nam_kec<=12 AND $nam_kab<=12): ?>
					<?=ucwords($this->setting->sebutan_kecamatan." ".$desa['nama_kecamatan']);?>
					</br>
					<?=ucwords($this->setting->sebutan_kabupaten." ".$desa['nama_kabupaten']);?>
				<?php	else: ?>
					<?=ucwords(substr($this->setting->sebutan_kecamatan,0,3).". ".$desa['nama_kecamatan']);?>
					</br>
					<?=ucwords(substr($this->setting->sebutan_kabupaten,0,3).". ".$desa['nama_kabupaten']);?>
				<?php	endif; ?>
			</div>
		</div>
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header">MENU UTAMA</li>
			<?php foreach ($modul AS $mod): ?>
				<?php if (count($mod['submodul'])==0): ?>
					<li class="<?php ($this->modul_ini==$mod['id']) and print('active')?>">
						<a href="<?= site_url()?><?=$mod['url']?>">
							<i class="fa <?= $mod['ikon']?> <?php ($this->modul_ini==$mod['id']) and print('text-aqua')?>"></i> <span><?=$mod['modul']?></span>
							<span class="pull-right-container"></span>
						</a>
					</li>
				<?php else : ?>
					<li class="treeview <?php ($this->modul_ini==$mod['id']) and print('active')?>">
						<a href="<?= site_url()?><?=$mod['url']?>">
							<i class="fa <?= $mod['ikon']?> <?php ($this->modul_ini==$mod['id']) and print('text-aqua')?>"></i> <span><?=$mod['modul']?></span>
							<span class="pull-right-container"><i class='fa fa-angle-left pull-right'></i></span>
						</a>
						<ul class="treeview-menu <?php ($this->modul_ini==$mod['id']) and print('active')?>">
							<?php foreach ($mod['submodul'] as $submod): ?>
								<li class="<?php ($act_sub==$submod['id']) and print('active')?>">
									<a href="<?= site_url()?><?=$submod['url']?>">
										<i class="fa <?= ($submod['ikon'] != NULL) ? $submod['ikon'] : 'fa-circle-o'?> <?php ($act_sub==$submod['id']) and print('text-red')?>"></i>
										<?=$submod['modul']?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</section>
 </aside>

