<div id="nav">
<ul>
<li <?php if($act==3){?>class="selected"<?php }?>>
<a href="<?php echo site_url('plan/clear')?>">Lokasi</a>
</li>
<li <?php if($act==0){?>class="selected"<?php }?>>
<a href="<?php echo site_url('point/clear')?>">Tipe Point</a>
</li>
<li <?php if($act==1){?>class="selected"<?php }?>>
<a href="<?php echo site_url('garis/clear')?>">Garis</a>
</li>
<li <?php if($act==2){?>class="selected"<?php }?>>
<a href="<?php echo site_url('line/clear')?>">Tipe Garis</a>
</li>
<li <?php if($act==4){?>class="selected"<?php }?>>
<a href="<?php echo site_url('area/clear')?>">Area</a>
</li>
<li <?php if($act==5){?>class="selected"<?php }?>>
<a href="<?php echo site_url('polygon/clear')?>">Tipe Polygon</a>
</li>
<?php /*
<li <?php if($act==1){?>class="selected"<?php }?>>
<a href="<?php echo site_url('plan_poligon')?>">Tipe Poligon</a>
</li>
<li <?php if($act==2){?>class="selected"<?php }?>>
<a href="<?php echo site_url('plan_line')?>">Tipe Garis</a>
</li>
*/?>
</ul>
</div>
