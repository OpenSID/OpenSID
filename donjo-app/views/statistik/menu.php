<div class="module-panel">
<div class="contentm" style="overflow: hidden;">

<?php if($_SESSION['grup']==1 OR $_SESSION['grup']==2){?>
<a class="cpanel" href="<?php echo site_url()?>hom_desa/about">
<img src="<?php echo base_url()?>assets/images/cpanel/go-home-5.png" alt=""/>
<span>SID Home</span>
</a>

<a class="cpanel" href="<?php echo site_url()?>sid_core/clear">
<img src="<?php echo base_url()?>assets/images/cpanel/preferences-contact-list.png" alt=""/>
<span>Penduduk</span>
</a>

<a class="cpanelx" href="<?php echo site_url()?>statistik/clear">
<img src="<?php echo base_url()?>assets/images/cpanel/statistik.png" alt=""/>
<span>Statistik</span>
</a>

<a class="cpanel" href="<?php echo site_url()?>surat">
<img src="<?php echo base_url()?>assets/images/cpanel/applications-office-5.png" alt=""/>
<span>Cetak Surat</span>
</a>

<a class="cpanel" href="<?php echo site_url()?>analisis">
<img src="<?php echo base_url()?>assets/images/cpanel/analysis.png" alt=""/>
<span>Analisis</span>
</a>

<a class="cpanel" href="<?php echo site_url()?>plan">
<img src="<?php echo base_url()?>assets/images/cpanel/planmap.png" alt=""/>
<span>Plan Maps</span>
</a>

<a class="cpanel" href="<?php echo site_url()?>gis/clear">
<img src="<?php echo base_url()?>assets/images/cpanel/gis.png" alt=""/>
<span>SID GIS</span>
</a>

<?php if($_SESSION['grup']==1){?>
<a class="cpanel" href="<?php echo site_url()?>man_user/clear">
<img src="<?php echo base_url()?>assets/images/cpanel/system-users.png" alt=""/>
<span>Pengguna</span>
</a>
<?php }?>
<?php }?>

<a class="cpanel" href="<?php echo site_url()?>sms">
<img src="<?php echo base_url()?>assets/images/cpanel/mail-send-receive.png" alt=""/>
<span>SMS</span>
</a>
<a class="cpanel" href="<?php echo site_url()?>web">
<img src="<?php echo base_url()?>assets/images/cpanel/message-news.png" alt=""/>
<span>Admin Web</span>
</a>

</div>
</div>

