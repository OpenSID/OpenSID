<!-- Widget Agenda -->

<?php
if($agenda){
  ?>
<div class="box box-primary box-solid">
  <div class="box-header">
    <h3 class="box-title"><a href="<?php echo site_url();?>first/kategori/4"><i class="fa fa-calendar"></i> Agenda</a></h3>
  </div>
  <div class="box-body">
    <ul id="ul-menu" class="sidebar-latest">
      <?php
      foreach ($agenda as $l){?>
      <li><div id="small-agenda"><?php echo tgl_indo($l['tgl_upload'])?></div><a href="<?php echo site_url("first/artikel/$l[id]")?>"><?php echo $l['judul']?></a></li>
      <?php  }?>
    </ul>
  </div>
</div>
  <?php
}
?>
