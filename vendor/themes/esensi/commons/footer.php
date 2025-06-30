<?php  defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
    <?php if($transparansi) $this->load->view($folder_themes .'/partials/apbdesa', $transparansi) ?>
</div>

<?php 
    $social_media = [
        'facebook' => [
            'color' => 'bg-blue-600',
            'icon' => 'fa-facebook-f'
        ],
        'twitter' => [
            'color' => 'bg-blue-400',
            'icon' => 'fa-twitter'
        ],
        'instagram' => [
            'color' => 'bg-pink-500',
            'icon' => 'fa-instagram'
        ],
        'telegram' => [
            'color' => 'bg-blue-500',
            'icon' => 'fa-telegram'
        ],
        'whatsapp' => [
            'color' => 'bg-green-500',
            'icon' => 'fa-whatsapp'
        ],
        'youtube' => [
            'color' => 'bg-red-500',
            'icon' => 'fa-youtube'
        ]
    ];
?>

<?php foreach($sosmed as $social) : ?>
    <?php if($social['link']) : ?>  
        <?php $social_media[strtolower($social['nama'])]['link'] = $social['link']; ?>
    <?php endif ?>
<?php endforeach ?>

<?php $this->load->view($folder_themes .'/commons/back_to_top') ?>

<footer class="container mx-auto lg:px-5 px-3 pt-5 footer">
    <div class="bg-zinc-700 text-white py-5 px-5 rounded-t-xl text-sm flex flex-col gap-3 lg:flex-row justify-between items-center text-center lg:text-left">
        <span class="space-y-2">
            <p>Hak cipta situs &copy; <?= date('Y') ?> - <?= NAMA_DESA ?></p>
            <p><a href="https://www.trivusi.web.id" class="underline decoration-pink-500 underline-offset-1 decoration-2" target="_blank" rel="noopener">Esensi <?= THEME_VERSION ?></a> - <a href="https://opensid.my.id" class="underline decoration-green-500 underline-offset-1 decoration-2" target="_blank" rel="noopener">OpenSID <?= ambilVersi() ?></a> -
            <?php if (file_exists('mitra')): ?>
                Hosting didukung <a href="https://my.idcloudhost.com/aff.php?aff=3172" rel="noopener noreferrer" target="_blank">
                <img src="<?= base_url('/assets/images/Logo-IDcloudhost.png')?>" class="h-4 inline-block" alt="Logo-IDCloudHost" title="Logo-IDCloudHost"></a>
            <?php endif; ?>
            </p>
        </span>
        <?php if (setting('tte')): ?>
            <div class="space-x-1">
                <img src="<?=asset('assets/images/bsre.png?v', false); ?>" alt="Bsre" class="img-responsive" style="width: 185px;" />
            </div>
        <?php endif ?>
        
        <ul class="space-x-1">
            <?php foreach($social_media as $sm) : ?>
                <?php if($link = $sm['link']) : ?>
                <li class="inline-block"><a href="<?= $link ?>" class="inline-flex items-center justify-center <?= $sm['color'] ?> h-8 w-8 rounded-full"><i class="fab fa-lg <?= $sm['icon'] ?>"></i></a></li>
                <?php endif ?>
            <?php endforeach ?>
        </ul>
    </div>
</footer>