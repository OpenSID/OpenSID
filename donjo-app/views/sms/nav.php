<div id="nav">
	<ul>
		<li <?php if($act==0){?>class="selected"<?php }?>>
			<a href="<?php echo site_url('sms/clear')?>">SMS</a>
		</li>
		<li <?php if($act==1){?>class="selected"<?php }?>>
			<a href="<?php echo site_url('sms/setting')?>">Pengaturan SMS</a>
		</li>
		<li <?php if($act==2){?>class="selected"<?php }?>>
			<a href="<?php echo site_url('sms/kontak')?>">Kontak</a>
		</li>
		
	</ul>
</div>
