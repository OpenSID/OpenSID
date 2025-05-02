<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<nav
  class="bg-primary-100 text-white lg:hidden block"
  x-data="{menuOpen: false}"
  role="navigation">
  <button
    type="button"
    class="w-full block text-center uppercase p-3"
    @click="menuOpen = !menuOpen">
    <i
      class="fas mr-1"
      :class="{'fa-bars':!menuOpen, 'fa-times': menuOpen}"></i>
    Menu
  </button>
  <ul
    x-show="menuOpen"
    x-transition
    class="divide-y divide-primary-200">
    <?php $menu_atas = menu_tema() ?>
    <?php if ($menu_atas) : ?>
      <?php foreach ($menu_atas as $menu) : ?>
        <?php $has_dropdown = count($menu['childrens'] ?? []) > 0 ?>
        <li class="block relative" <?php $has_dropdown && print 'x-data="{dropdownMain: false}"' ?>>

          <?php $menu_link = $has_dropdown ? '#!' : $menu['link_url'] ?>

          <a href="<?= $menu_link ?>"
            class="p-3 block hover:bg-secondary-100"
            @click="dropdownMain = !dropdownMain">
            <?= $menu['nama'] ?>

            <?php if ($has_dropdown) : ?>
              <i class="fas fa-chevron-down text-xs ml-1 inline-block transition duration-300" 
                :class="{'transform rotate-180': dropdownMain}"></i>
            <?php endif ?>
          </a>

          <?php if ($has_dropdown) : ?>
            <ul
              class="divide-y divide-primary-200"
              :class="{'opacity-0 invisible z-[-10] scale-y-75 h-0': !dropdownMain, 'opacity-100 visible z-30 scale-y-100 h-auto': dropdownMain}"
              x-transition.opacity>

              <?php foreach ($menu['childrens'] as $childrens) : ?>
                <?php $has_dropdown2 = count($childrens['childrens'] ?? []) > 0 ?>

                <li <?php $has_dropdown2 && print 'x-data="{dropdownSub: false}"' ?>>
                  <?php $menu_link2 = $has_dropdown2 ? '#!' : $childrens['link_url'] ?>
                  <a href="<?= $menu_link2 ?>" class="block py-3 pl-5 pr-4 hover:bg-primary-200 hover:text-white" 
                    @click="dropdownSub = !dropdownSub">
                    <?= $childrens['nama'] ?>
                    <?php if ($has_dropdown2) : ?>
                      <i class="fas fa-chevron-down text-xs ml-1 inline-block transition duration-300" 
                        :class="{'transform rotate-180': dropdownSub}"></i>
                    <?php endif ?>
                  </a>

                  <?php if ($has_dropdown2) : ?>
                    <ul
                      class="divide-y divide-primary-200"
                      :class="{'opacity-0 invisible z-[-10] scale-y-75 h-0': !dropdownSub, 'opacity-100 visible z-30 scale-y-100 h-auto': dropdownSub}"
                      x-transition.opacity>
                      <?php foreach ($childrens['childrens'] as $children) : ?>
                        <li @click="dropdownSub = false">
                          <a href="<?= $children['link_url'] ?>" style="padding-left: 2.3rem" class="block py-3 pl-5 pr-4 hover:bg-primary-200 hover:text-white">
                            <?= $children['nama'] ?>
                          </a>
                        </li>
                      <?php endforeach ?>
                    </ul>
                  <?php endif ?>

                </li>

              <?php endforeach ?>

            </ul>
          <?php endif ?>
        </li>
      <?php endforeach ?>
    <?php endif ?>
  </ul>
</nav>