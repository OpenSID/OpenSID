<div id="nav">
<ul>
<?if($_SESSION['grup']==1){?>
<li <?if($act==0){?>class="selected"<?}?>>
<a href="<?=site_url('hom_desa')?>">Identitas Desa</a>
</li>
<?}?>
<li <?if($act==1){?>class="selected"<?}?>>
<a href="<?=site_url('pengurus')?>">Pemerintah Desa</a>
</li>
<li <?if($act==2){?>class="selected"<?}?>>
<a href="<?=site_url('hom_desa/about')?>">SID</a>
</li>
</ul>
</div>
