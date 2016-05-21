<div id="nav">
<ul>
<li <?if($act==0){?>class="selected"<?}?>>
<a href="<?=site_url('sid_core/clear')?>">Wilayah Administrasi</a>
</li>
<li <?if($act==1){?>class="selected"<?}?>>
<a href="<?=site_url('keluarga/clear')?>">Keluarga</a>
</li>
<li <?if($act==2){?>class="selected"<?}?>>
<a href="<?=site_url('penduduk/clear')?>">Penduduk</a>
</li>
<li <?if($act==3){?>class="selected"<?}?>>
<a href="<?=site_url('rtm/clear')?>">Rumah Tangga</a>
</li>

<li <?if($act==4){?>class="selected"<?}?>>
<a href="<?=site_url('kelompok/clear')?>">Kelompok</a>
</li>



</ul>
</div>
