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

		<?php $this->load->view($modul_nav, $nav); ?>
