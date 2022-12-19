<?php  defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php 
  $social_media = [
    'facebook' => [
      'color' => 'bg-blue-700',
      'icon' => 'fa-facebook-f',
      'link' => 'https://facebook.com/sharer.php?u='
    ],
    'twitter' => [
      'color' => 'bg-blue-400',
      'icon' => 'fa-twitter',
      'link' => 'https://twitter.com/share?url='
    ],
    'whatsapp' => [
      'color' => 'bg-green-500',
      'icon' => 'fa-whatsapp',
      'link' => 'https://api.whatsapp.com/send?text='
    ],
    'telegram' => [
      'color' => 'bg-blue-600',
      'icon' => 'fa-telegram',
      'link' => 'https://telegram.me/share/url?url='
    ],
    'pinterest' => [
      'color' => 'bg-red-500',
      'icon' => 'fa-pinterest',
      'link' => 'https://pinterest.com/pin/create/link/?url='
    ],
    'messenger' => [
      'color' => 'bg-blue-500',
      'icon' => 'fa-facebook-messenger',
      'link' => 'https://facebook.com/dialog/send?link='
    ]
  ];
?>
<section 
  class="fixed bottom-0 lg:top-1/2 left-0 transform transition-all duration-300 right-0 lg:right-auto z-30 lg:-translate-y-1/2"
  x-data="{
    stickyShare: false,
    onScroll() { 
      this.stickyShare = window.scrollY > document.querySelector('.content').getBoundingClientRect().top + 150 && window.scrollY + 200 < document.querySelector('footer').getBoundingClientRect().bottom * 3 }
  }"
  @scroll.window="onScroll()"
  :class="{'invisible opacity-0 translate-y-full lg:-translate-y-1/2 lg:-translate-x-full': !stickyShare, 'visible opacity-100 translate-y-0 lg:translate-x-0 lg:-translate-y-1/2 z-50': stickyShare}">
  <ul class="bg-white lg:bg-transparent py-3 px-3 lg:pl-0 rounded-tr-lg rounded-br-lg text-center lg:text-left">
    <?php foreach($social_media as $key => $data) : ?>
      <li class="inline-block lg:block"><a href="<?= $data['link'] . current_url() ?>" target="_blank" rel="noreferrer noopener" class="w-10 hover:relative hover:w-16 transition-all duration-300 h-10 text-white text-lg inline-flex items-center justify-center <?= $data['color'] ?>" aria-label="Bagikan ke <?= ucfirst($key) ?>" title="Bagikan ke <?= ucfirst($key) ?>"><i class="fab <?= $data['icon'] ?>"></i></a></li>
    <?php endforeach ?>
  </ul>
</section>