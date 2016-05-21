<div id="nav">
<ul>
<li <?if($act==3){?>class="selected"<?}?>>
<a href="<?=site_url('plan/clear')?>">Lokasi</a>
</li>
<li <?if($act==0){?>class="selected"<?}?>>
<a href="<?=site_url('point/clear')?>">Tipe Point</a>
</li>
<li <?if($act==1){?>class="selected"<?}?>>
<a href="<?=site_url('garis/clear')?>">Garis</a>
</li>
<li <?if($act==2){?>class="selected"<?}?>>
<a href="<?=site_url('line/clear')?>">Tipe Garis</a>
</li>
<li <?if($act==4){?>class="selected"<?}?>>
<a href="<?=site_url('area/clear')?>">Area</a>
</li>
<li <?if($act==5){?>class="selected"<?}?>>
<a href="<?=site_url('polygon/clear')?>">Tipe Polygon</a>
</li>
<?/*
<li <?if($act==1){?>class="selected"<?}?>>
<a href="<?=site_url('plan_poligon')?>">Tipe Poligon</a>
</li>
<li <?if($act==2){?>class="selected"<?}?>>
<a href="<?=site_url('plan_line')?>">Tipe Garis</a>
</li>
*/?>
</ul>
</div>
