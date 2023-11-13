<?php
$nama    = '';
$nomor   = '';
$asubjek = '';
if (isset($_SESSION['subjek_tipe'])) {
    $subjek = $_SESSION['subjek_tipe'];

    switch ($subjek) {
        case 1: $nama = 'Nama';
            $nomor    = 'NIK';
            $asubjek  = 'Penduduk';
            break;

        case 2: $nama = 'Kepala Keluarga';
            $nomor    = 'Nomor KK';
            $asubjek  = 'Keluarga';
            break;

        case 3: $nama = 'Kepala Rumahtangga';
            $nomor    = 'Nomor Rumahtangga';
            $asubjek  = 'Rumahtangga';
            break;

        case 4: $nama = 'Nama Kelompok';
            $nomor    = 'ID Kelompok';
            $asubjek  = 'Kelompok';
            break;

        default: return null;
    }
}
?>
<div id="nav">
	<ul>
		<li <?php if (! isset($_SESSION['analisis_master'])) {?>class="selected"<?php } ?>>
			<a href="<?= site_url('analisis_master/clear')?>">Analisis</a>
		</li>
		<?php if (isset($_SESSION['analisis_master'])) {?>
			<li <?php if (! isset($_SESSION['submenu'])) {?>class="selected"<?php }?>>
				<a href="<?= site_url("analisis_master/menu/{$_SESSION['analisis_master']}")?>"><?= $_SESSION['analisis_nama']?> [ <?= $asubjek?> ]</a>
			</li>
		<?php } ?>
		<?php if (isset($_SESSION['submenu'])) {?>
			<li class="selected">
				<a href="<?= site_url("{$_SESSION['asubmenu']}/clear")?>><?= $_SESSION['submenu']?></a>
			</li>
		<?php } ?>
	</ul>
</div>