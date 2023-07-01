<?php  defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<nav
  class="bg-primary-100 text-white lg:hidden block"
  x-data="{menuOpen: false}"
  role="navigation">
  <button
    type="button"
    class="w-full block text-center uppercase p-3"
    @click="menuOpen = !menuOpen" >
    <i
      class="fas mr-1"
      :class="{'fa-bars':!menuOpen, 'fa-times': menuOpen}"></i> 
      Menu
  </button>
  <ul 
    x-show="menuOpen"
    x-transition
    class="divide-y divide-primary-200">
    <?php if($menu_atas) : ?>
      <?php foreach($menu_atas as $menu) : ?>
        <?php $has_dropdown = count($menu['submenu']) > 0 ?>
        <li class="block relative" <?php $has_dropdown and print('x-data="{dropdown: false}"') ?>>

          <?php $menu_link = $has_dropdown ? '#!' : $menu['link'] ?>

          <a href="<?= $menu_link ?>"
            class="p-3 block hover:bg-secondary-100"
            @click="dropdown = !dropdown">
            <?= $menu['nama'] ?>
            
            <?php if($has_dropdown) : ?>
              <i class="fas fa-chevron-down text-xs ml-1 inline-block transition duration-300" :class="{'transform rotate-180': dropdown}"></i>
            <?php endif ?>
          </a>

          <?php if($has_dropdown) : ?>
            <ul
              class="divide-y divide-primary-200"
              :class="{'opacity-0 invisible z-[-10] scale-y-75 h-0': !dropdown, 'opacity-100 visible z-30 scale-y-100 h-auto': dropdown}"
              x-transition.opacity
              @click="dropdown = !dropdown">

              <?php foreach($menu['submenu'] as $submenu) : ?>
                <li @click="dropdown = false"><a href="<?= $submenu['link'] ?>" class="block py-3 pl-5 pr-4 hover:bg-primary-200 hover:text-white"><?= $submenu['nama'] ?></a></li>
              <?php endforeach ?>
              
            </ul>
          <?php endif ?>
        </li>
      <?php endforeach ?>
    <?php endif ?>
  </ul>
</nav>