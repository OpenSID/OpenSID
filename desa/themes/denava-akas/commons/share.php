<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php $judul = htmlspecialchars_decode($judul) ?>
<div class="share flexcenter">
    <?php
    $socialItems = [
        [
            'class' => 'facebook',
            'name' => 'fb_share',
            'href' => "http://www.facebook.com/sharer.php?u=$link",
            'imgSrc' => base_url("$this->theme_folder/$this->theme/assets/images/icon/social/facebook.svg"),
            'alt' => 'Facebook',
            'text' => 'Facebook'
        ],
        [
            'class' => 'twitter',
            'href' => "http://twitter.com/share?source=sharethiscom&text=$judul%0A&url=$link&via=ariandii",
            'imgSrc' => base_url("$this->theme_folder/$this->theme/assets/images/icon/social/x-twitter.svg"),
            'alt' => 'Twitter',
            'text' => 'Twitter'
        ],
        [
            'class' => 'whatsapp',
            'href' => "https://api.whatsapp.com/send?text=$judul%0A$link",
            'imgSrc' => base_url("$this->theme_folder/$this->theme/assets/images/icon/social/whatsapp.svg"),
            'alt' => 'Whatsapp',
            'text' => 'Whatsapp'
        ],
        [
            'class' => 'telegram',
            'href' => "https://telegram.me/share/url?url=$link&text=$judul%0A",
            'imgSrc' => base_url("$this->theme_folder/$this->theme/assets/images/icon/social/telegram.svg"),
            'alt' => 'Telegram',
            'text' => 'Telegram'
        ],
        [
            'class' => 'email',
            'href' => "mailto:?subject=$judul&body=" . potong_teks($single_artikel["isi"], 1000) . " ... Selengkapnya di $link",
            'imgSrc' => base_url("$this->theme_folder/$this->theme/assets/images/icon/social/email.svg"),
            'alt' => 'Email',
            'text' => 'Email'
        ],
        [
            'class' => 'print',
            'href' => 'javascript:void(0);',
            'imgSrc' => base_url("$this->theme_folder/$this->theme/assets/images/icon/social/print.svg"),
            'alt' => 'Print',
            'text' => 'Print',
            'onclick' => "printDiv('printableArea')"
        ]
    ];
    ?>

    <?php foreach ($socialItems as $item): ?>
        <a class="social-icon <?= $item['class'] ?> flexcenter"
           href="<?= $item['href'] ?>"
           <?php if (isset($item['onclick'])): ?>
               onclick="<?= $item['onclick'] ?>"
           <?php endif; ?>
           rel="noopener noreferrer" target="_blank" title="<?= $item['alt'] ?>">
            <img src="<?= $item['imgSrc'] ?>" alt="<?= $item['alt'] ?>"/><p><?= $item['text'] ?></p>
        </a>
    <?php endforeach; ?>
</div>
