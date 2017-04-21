		<!-- NOTIFICATION -->
		<?php  if(@$_SESSION['success']==1): ?>
		<script type="text/javascript">
			$('document').ready(function(){
			notification('success','Data Berhasil Disimpan')();
			});
		</script>
		<?php  elseif(@$_SESSION['success']==-1): ?>
			<script type="text/javascript">
				$('document').ready(function(){
				notification('error','Data Gagal Disimpan <?php echo $_SESSION["error_msg"]?>')();
				});
			</script>
		<?php  elseif(@$_SESSION['success']==-2): ?>
			<script type="text/javascript">
				$('document').ready(function(){
				notification('error','Simpan data gagal, nama id sudah ada!')();
				});
			</script>
		<?php  elseif(@$_SESSION['success']==-3): ?>
			<script type="text/javascript">
				$('document').ready(function(){
				notification('error','Simpan data gagal, nama id sudah ada!')();
				});
			</script>
		<?php  endif; ?>
		<?php  $_SESSION['success']=0; ?>
		<!-- ************ -->
		<!-- ************ -->

		<div class="module-panel">
			<div class="contentm" style="overflow: hidden;">
				<?php foreach ($modul AS $mod){?>
					<a class="cpanel <?php if($modul_ini==$mod['id']){?>selected<?php }?>" href="<?php echo site_url()?><?php echo $mod['url']?>">
						<img src="<?php echo base_url()?>assets/images/cpanel/<?php echo $mod['ikon']?>" alt=""/>
						<span><?php echo $mod['modul']?></span>
					</a>
				<?php } ?>
		  </div>
		</div>

		<div id="nav">
			<ul>
				<li <?php if($act==1){?>class="selected"<?php }?>>
					<a href="<?php echo site_url('database')?>">Export</a>
				</li>
				<li <?php if($act==2){?>class="selected"<?php }?>>
					<a href="<?php echo site_url('database/import')?>">Import Excel</a>
				</li>
				<li <?php if($act==5){?>class="selected"<?php }?>>
					<a href="<?php echo site_url('database/import_bip')?>">Import BIP</a>
				</li>
				<li <?php if($act==3){?>class="selected"<?php }?>>
					<a href="<?php echo site_url('database/backup')?>">Backup/Restore</a>
				</li>
				<li <?php if($act==4){?>class="selected"<?php }?>>
					<a href="<?php echo site_url('database/import_ppls')?>">Import PPLS</a>
				</li>
				<li <?php if($act==6){?>class="selected"<?php }?>>
					<a href="<?php echo site_url('database/migrasi_cri')?>">Migrasi DB</a>
				</li>
			</ul>
		</div>
