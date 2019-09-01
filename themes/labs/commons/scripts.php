<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
 <script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/core/jquery.min.js"); ?>"></script>
 <script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/core/bootstrap.bundle.min.js"); ?>">
 </script>
 <script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/core/jquery.slimscroll.min.js"); ?>">
 </script>
 <script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/core/jquery.scrollLock.min.js"); ?>">
 </script>
 <script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/core/jquery.appear.min.js"); ?>">
 </script>
 <script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/core/jquery.countTo.min.js"); ?>">
 </script>
 <script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/core/js.cookie.min.js"); ?>"></script>
 <script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/codebase.js"); ?>"></script>
 <script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/plugins/slick/slick.min.js"); ?>">
 </script>
 <script>
jQuery(function() {
    // Init page helpers (Slick Slider plugin)
    Codebase.helpers('slick');
});
 </script>


 
<script type="text/javascript">
window.setTimeout("renderDate()", 1);
days = new Array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu");
months = new Array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober",
    "November", "Desember");

function renderDate() {
    var mydate = new Date();
    var year = mydate.getYear();
    if (year < 2000) {
        if (document.all)
            year = "19" + year;
        else
            year += 1900;
    }
    var day = mydate.getDay();
    var month = mydate.getMonth();
    var daym = mydate.getDate();
    if (daym < 10)
        daym = "0" + daym;
    var hours = mydate.getHours();
    var minutes = mydate.getMinutes();
    var seconds = mydate.getSeconds();
    if (hours <= 9)
        hours = "0" + hours;
    if (minutes <= 9)
        minutes = "0" + minutes;
    if (seconds <= 9)
        seconds = "0" + seconds;
    document.getElementById("jam").innerHTML = days[day] + ", " + daym + " " + months[month] + " " + year + " / " +
        hours + " : " + minutes + " : " + seconds;
    setTimeout("renderDate()", 1000)
}
 </script>
 </head>
<body>