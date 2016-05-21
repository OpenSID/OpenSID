<div id="nav">
	<ul>
		<li <?if($act==0){?>class="selected"<?}?>>
			<a href="<?=site_url('sms/clear')?>">SMS</a>
		</li>
		<li <?if($act==1){?>class="selected"<?}?>>
			<a href="<?=site_url('sms/setting')?>">Pengaturan SMS</a>
		</li>
		<li <?if($act==2){?>class="selected"<?}?>>
			<a href="<?=site_url('sms/kontak')?>">Kontak</a>
		</li>
		
	</ul>
</div>
