<?php defined('BASEPATH') || exit('No direct script access allowed') ?>

<?php
    // Didefinisikan di ulang, karena variabel $modul_ini, $submodul_ini tidak sesuai di view ini
    $modul_ini = $this->modul_ini;
$sub_modul_ini = $this->sub_modul_ini;

include RESOURCESPATH . 'views/admin/layouts/partials/sidebar.blade.php';

?>