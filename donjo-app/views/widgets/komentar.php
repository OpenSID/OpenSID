<!-- widget Komentar-->
<div class="box box-primary box-solid">
  <div class="box-header">
    <h3 class="box-title"><i class="fa fa-comments"></i> Komentar Terkini</h3>
  </div>
  <div class="box-body">
    <marquee onmouseover="this.stop()" onmouseout="this.start()" scrollamount="2" direction="up" width="100%" height="100" align="center" behavior=”alternate”>
      <ul class="sidebar-latest" id="li-komentar">
        <?php foreach($komen As $data){?>
          <li>
            <i class="fa fa-comment"></i> <?php echo $data['owner']?> :<?php echo $data['komentar']?><br /><small>ditulis pada <?php echo tgl_indo2($data['tgl_upload'])?></small>
            <br />
            <br />
          </li>
        <?php }?>
      </ul>
    </marquee>
  </div>
</div>