<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<style type="text/css">
    <?php include FCPATH . '/assets/css/lampiran-surat.css'; ?>
</style>

<?php
if (($input['status_kawin'] == 'Duda' || $input['status_kawin'] == 'Janda') && ($input['tanggal_meninggal_pasangan_terdahulu'] != "") ) {
    include_once('view_individu.php');
}

if (($input['status_kawin_calon_pasangan'] == 'Duda' || $input['status_kawin_calon_pasangan'] == 'Janda') && ($input['tanggal_meninggal_pasangan_terdahulu_calon_pasangan'] != "") ) {
    include_once('view_calon_pasangan.php');
}

include_once('.php');
?>