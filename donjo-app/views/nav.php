<aside class="main-sidebar">		
	<section class="sidebar">	
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header">MENU UTAMA</li>
			<?php foreach ($modul AS $mod){?>
				

			<li class="<?php if($this->modul_ini==$mod['id']){?>active<?php }?> <?php if(count($mod['submodul'])>0) {?>treeview<?php } ?>">
				<a href="<?php echo site_url()?><?php echo $mod['url']?>">
					<i class="fa <?php echo $mod['ikon']?> <?php if($this->modul_ini==$mod['id']){?>text-aqua<?php }?>"></i> <span><?php echo $mod['modul']?></span>	
					<span class="pull-right-container"><?php if(count($mod['submodul'])>0) { echo "<i class='fa fa-angle-left pull-right'></i>"; } ?> </span>					
				</a>
				<?php if(count($mod['submodul'])>0): ?>
					<ul class="<?php if($this->modul_ini==$mod['id']){?>active<?php }?> treeview-menu">	
						<?php foreach($mod['submodul'] as $submod): ?>
							<li class="<?php if($act_sub==$submod['id']){?>active<?php }?>">
								<a href="<?php echo site_url()?><?php echo $submod['url']?>">
									<i class="fa <?php if($submod['ikon']!==''){ echo $submod['ikon']; }else{ echo 'fa-circle-o'; }?> <?php if($act_sub==$submod['id']){?>text-red<?php }?>"></i> 
									<?php echo $submod['modul']?>
								</a>
							</li>									
						<?php endforeach; ?>
					</ul>							
				<?php endif; ?>	
			</li>
			<?php } ?>		
		</ul>
	</section>
 </aside>
 
