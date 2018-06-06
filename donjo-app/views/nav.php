<!-- Perubahan script coding untuk bisa menampilkan menu dan sub menu bootstrap (AdminLTE) berdasarkan daftar modul dan sub modul yang aktif  -->
<!-- Nantinya seluruh file nav.php (sub modul) yang ada di masing-masing folder modul utama akan dihapus (sudah tidak digunakan lagi)  -->
<aside class="main-sidebar">		
	<section class="sidebar">	
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?php echo LogoDesa($desa['logo']);?>" class="img-circle" alt="User Image">
			</div>        
			<div class="pull-left info">
				<p><?php echo ucwords($this->setting->sebutan_desa." ".$desa['nama_desa']); ?></p>
				<?php 
					$nam_kec = strlen(unpenetration($desa['nama_kecamatan']));
					$nam_kab = strlen(unpenetration($desa['nama_kabupaten']));
					if ($nam_kec<=12 AND $nam_kab<=12){
						echo ucwords($this->setting->sebutan_kecamatan." ".unpenetration($desa['nama_kecamatan']));
						echo "</br>";
						echo ucwords($this->setting->sebutan_kabupaten." ".unpenetration($desa['nama_kabupaten']));
					}else{
						echo ucwords(substr($this->setting->sebutan_kecamatan,0,3).". ".unpenetration($desa['nama_kecamatan']));
						echo "</br>";
						echo ucwords(substr($this->setting->sebutan_kabupaten,0,3).". ".unpenetration($desa['nama_kabupaten']));
					}
					
				?>				
				
			</div>
		</div>	
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
 
