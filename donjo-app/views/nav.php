<aside class="main-sidebar">
	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?=LogoDesa($desa['logo']);?>" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p><?=ucwords($this->setting->sebutan_desa." ".$desa['nama_desa']);?></p>
				<?php
					$nam_kec = strlen(unpenetration($desa['nama_kecamatan']));
					$nam_kab = strlen(unpenetration($desa['nama_kabupaten']));
				?>
				<?php	if($nam_kec<=12 AND $nam_kab<=12):?>
					<?=ucwords($this->setting->sebutan_kecamatan." ".unpenetration($desa['nama_kecamatan']));?>
					</br>
					<?=ucwords($this->setting->sebutan_kabupaten." ".unpenetration($desa['nama_kabupaten']));?>
				<?php	else:?>
					<?=ucwords(substr($this->setting->sebutan_kecamatan,0,3).". ".unpenetration($desa['nama_kecamatan']));?>
					</br>
					<?=ucwords(substr($this->setting->sebutan_kabupaten,0,3).". ".unpenetration($desa['nama_kabupaten']));?>
				<?php	endif;?>
			</div>
		</div>
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header">MENU UTAMA</li>
			<?php foreach ($modul AS $mod):?>
				<li class="<?php if(count($mod['submodul'])>0): ?>treeview<?php endif ?> <?php if($this->modul_ini==$mod['id']):?>active<?php endif?>">
					<a href="<?=site_url()?><?=$mod['url']?>">
						<i class="fa <?=$mod['ikon']?> <?php if($this->modul_ini==$mod['id']):?>text-aqua<?php endif?>"></i> <span><?=$mod['modul']?></span>
						<span class="pull-right-container"><?php if(count($mod['submodul'])>0):?><i class='fa fa-angle-left pull-right'></i><?php endif ?> </span>
					</a>
					<?php if(count($mod['submodul'])>0): ?>
						<ul class="treeview-menu <?php if($this->modul_ini==$mod['id']):?>active<?php endif?>">
							<?php foreach($mod['submodul'] as $submod): ?>
								<li class="<?php if($act_sub==$submod['id']):?>active<?php endif?>">
									<a href="<?=site_url()?><?=$submod['url']?>">
										<i class="fa <?php if($submod['ikon']!=NULL):?><?=$submod['ikon']?><?php else: ?> fa-circle-o<?php endif?> <?php if($act_sub==$submod['id']):?>text-red<?php endif?>"></i>
										<?=$submod['modul']?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
 </aside>

