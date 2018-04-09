<!-- widget SocMed -->

<div class="box box-default">
  <div class="box-header">
    <h3 class="box-title"><i class="fa fa-globe"></i> Info Media Sosial</h3>
  </div>
  <div class="box-body">
<?php
foreach($sosmed As $data){
  if ($data["link"] != ""):
    echo "<a href=\"".$data["link"]."\" target=\"_blank\"><img src=\"".base_url()."assets/front/".$data["gambar"]."\" alt=\"".$data["nama"]."\" style=\"width:50px;height:50px;\"/></a>";
  endif;
}
?>
  </div>
</div>
