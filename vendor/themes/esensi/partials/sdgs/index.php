<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<nav role="navigation" aria-label="navigation" class="breadcrumb">
    <ol>
        <li><a href="<?= site_url() ?>">Beranda</a></li>
        <li aria-current="page">SDGs <?= ucwords($this->setting->sebutan_desa) ?></li>
    </ol>
</nav>

<h1 class="text-h2">SDGs <?= ucwords($this->setting->sebutan_desa) ?></h1>
<div class="space-y-12 text-center">
    <span class="text-h2" id="total"></span>
    </br>
    <span class="text-h6">Skor SDGs Desa</span>
</div>
<?php if ($evaluasi = sdgs()): ?>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 py-5">
        
        <?php $bagi = 0;
            foreach ($evaluasi as $key => $value): ?>
                    <?php
                    $total += $value->data->capaian;

                if (is_numeric($value->data->capaian)) {
                    $bagi++;
                }
                ?>
        <div class="space-y-3">
            <img class="h-44 w-full object-cover object-center bg-gray-300 dark:bg-gray-600" src="https://sid.kemendesa.go.id/images/<?=$value->name?>.webp" alt="sdgs-logo" />

            <div class="space-y-1 text-sm text-center z-10">
                <span class="text-h6">NILAI</span>
                <span class="block"><?= $value->data->capaian ?></span>
            </div>
        </div>
        <?php endforeach ?>
        <?php $hasil = ($bagi > 0) ? round($total / $bagi, 2) : 'N/A' ?>
    </div>
<?php else: ?>
    <p class="py-3">SDGs <?= ucwords($this->setting->sebutan_desa) ?> tidak tersedia.</p>
<?php endif ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#total').prepend('<?= $hasil ?>')
});
</script>