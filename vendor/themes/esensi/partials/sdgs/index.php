<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<nav role="navigation" aria-label="navigation" class="breadcrumb">
    <ol>
        <li><a href="<?= site_url() ?>">Beranda</a></li>
        <li aria-current="page">SDGs <?= ucwords($this->setting->sebutan_desa) ?></li>
    </ol>
</nav>

<h1 class="text-h2">SDGs <?= ucwords($this->setting->sebutan_desa) ?></h1>
<?php $evaluasi = sdgs() ?>
<?php if ($error_msg = $evaluasi->error_msg): ?>
    <div class="alert alert-danger">
        <p class="py-3"><?= $error_msg ?></p>
    </div>
<?php else: ?>
    <div class="space-y-12 text-center">
        <span class="text-h2"><?= $evaluasi->average ?></span>
        </br>
        <span class="text-h6">Skor SDGs Desa</span>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 py-5">
        <?php foreach ($evaluasi->data as $key => $value): ?>
        <div class="space-y-3">
            <img class="w-full object-cover object-center" src="<?= asset("images/sdgs/{$value->image}") ?>" alt="<?= $value->images ?>" />

            <div class="space-y-1 text-sm text-center z-10">
                <span class="text-h6">NILAI</span>
                <span class="block"><?= $value->score ?></span>
            </div>
        </div>
        <?php endforeach ?>
    </div>
<?php endif ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#total').prepend('<?= $hasil ?>')
});
</script>