<!-- widget Peta Lokasi Kantor Desa -->
<?php
if($data_config['lat']!= "0"){
  echo "

  <div class=\"box box-default box-solid\">
    <div class=\"box-header\">
      <h3 class=\"box-title\"><i class=\"fa fa-map-marker\"></i> Lokasi Kantor ". ucwords($this->setting->sebutan_desa) ."</h3>
    </div>
    <div class=\"box-body\">
      <div id=\"map_canvas\" style=\"height:200px;\"></div>
      <script type=\"text/javascript\" src=\"https://maps.googleapis.com/maps/api/js?key=". $this->setting->google_key ."\"></script>";
      ?>
      <script type="text/javascript">
        var map;
        var marker;
        var location;

        function initialize(){
          var myLatlng = new google.maps.LatLng(<?php echo $data_config['lat'].",".$data_config['lng']; ?>);
          var myOptions = {
            zoom: <?php echo $data_config["zoom"];?>,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            overviewMapControl: true
          }
          map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

            var marker = new google.maps.Marker({
              position: new google.maps.LatLng(<?php echo $data_config['lat'].",".$data_config['lng']; ?>),
              map: map,
              draggable:false
              });               }

        function addEvent(obj, evType, fn){
         if (obj.addEventListener){
           obj.addEventListener(evType, fn, false);
           return true;
         } else if (obj.attachEvent){
           var r = obj.attachEvent("on"+evType, fn);
           return r;
         } else {
           return false;
         }
        }
        addEvent(window, 'load',initialize);


      </script>
    <?php
    echo "
      <a href=\"//www.google.co.id/maps/@".$data_config['lat'].",".$data_config['lng']."z?hl=id\" target=\"_blank\">tampilkan dalam peta lebih besar</a><br />
    </div>
  </div>
  ";
}
?>
