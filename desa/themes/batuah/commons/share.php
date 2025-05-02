<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>


	<div class="globalshare flexcenter">
		<a class="share-icon facebook flexcenter" name="fb_share" href="http://www.facebook.com/sharer.php?u=<?= $link; ?>" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='noopener noreferrer' target='_blank' title='Facebook'><img src="<?= base_url("$this->theme_folder/$this->theme/images/svgfile/social/facebook.svg") ?>"/></a>
		
		<a class="share-icon twitter flexcenter" href="http://twitter.com/share?source=sharethiscom&text=<?= $judul; ?>%0A&url=<?= $link .'&via=batuah'?>" class="twitter-share-button" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='noopener noreferrer' target='_blank' title='Twitter'><img src="<?= base_url("$this->theme_folder/$this->theme/images/svgfile/social/twitter.svg") ?>"/></a>
		
		<a class="share-icon whatsapp flexcenter" href="https://api.whatsapp.com/send?text=<?= $judul; ?>%0A<?= $link; ?>" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='noopener noreferrer' target='_blank' title='Whatsapp'><img src="<?= base_url("$this->theme_folder/$this->theme/images/svgfile/social/whatsapp.svg") ?>"/></a>
		
		 <a class="share-icon email flexcenter" href="mailto:?subject=<?= $judul; ?>&body=<?= potong_teks($single_artikel["isi"], 1000); ?> ... Selengkapnya di <?= $link; ?>" title='Email'><img src="<?= base_url("$this->theme_folder/$this->theme/images/svgfile/social/email.svg") ?>"/></a>
		 
		<a class="share-icon telegram flexcenter" href="https://telegram.me/share/url?url=<?= $link; ?>&text=<?= $judul; ?>%0A" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='noopener noreferrer' target='_blank' title='Telegram'><img src="<?= base_url("$this->theme_folder/$this->theme/images/svgfile/social/telegram.svg") ?>"/></a>
		
		<a class="share-icon print flexcenter" href="javascript:printDiv('printthis');" title='Cetak'><img src="<?= base_url("$this->theme_folder/$this->theme/images/svgfile/social/print.svg") ?>"/></a>
	
	</div>
