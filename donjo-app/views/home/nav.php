<div id="nav">
<ul>
<?php if($_SESSION['grup']==1){?>
<li <?php if($act==0){?>class="selected"<?php }?>>
<a href="<?php echo site_url('hom_desa')?>">Identitas <?php echo ucwords(config_item('sebutan_desa'))?></a>
</li>
<?php }?>
<li <?php if($act==1){?>class="selected"<?php }?>>
<a href="<?php echo site_url('pengurus')?>">Pemerintah <?php echo ucwords(config_item('sebutan_desa'))?></a>
</li>
<li <?php if($act==2){?>class="selected"<?php }?>>
<a href="<?php echo site_url('hom_desa/about')?>">SID</a>
</li>
</ul>
</div>
