<!-- widget Komentar-->
<div class="box box-primary box-solid">
  <div class="box-header">
    <h3 class="box-title"><i class="fa fa-comments"></i> Komentar Terkini</h3>
  </div>
  <div class="box-body">
    <ul class="sidebar-latest">
    <?php  foreach($komen As $data){?>
      <li><i class="fa fa-comment"></i> <?php echo $data['owner']?> :
      <?php echo $data['komentar']?><br />
      <small>ditulis pada <?php echo tgl_indo2($data['tgl_upload'])?></small>
      <br />
      <br />
      </li>
    <?php  }?>
    </ul>
  </div>
</div>
