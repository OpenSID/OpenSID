<div id="nav">
<ul>
<li <?if($act==0){?>class="selected"<?}?>>
<a href="<?=site_url('web/index/1')?>">Artikel</a>
</li>
<li <?if($act==1){?>class="selected"<?}?>>
<a href="<?=site_url('menu/index/1')?>">Menu</a>
</li>
<li <?if($act==2){?>class="selected"<?}?>>
<a href="<?=site_url('komentar')?>">Komentar</a>
</li>
<li <?if($act==3){?>class="selected"<?}?>>
<a href="<?=site_url('gallery')?>">Gallery</a>
</li>
<li <?if($act==4){?>class="selected"<?}?>>
<a href="<?=site_url('dokumen')?>">Dokumen</a>
</li>
<?/*
<li <?if($act==5){?>class="selected"<?}?>>
<a href="<?=site_url('web/widget')?>">Wigdet</a>
</li>
*/?>
<li <?if($act==6){?>class="selected"<?}?>>
<a href="<?=site_url('sosmed')?>">Media Sosial</a>
</li>
</ul>
</div>
