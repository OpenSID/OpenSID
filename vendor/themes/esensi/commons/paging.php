<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if($paging->num_rows > $paging->per_page) : ?>
  <p class="text-xs lg:text-sm py-3">Halaman <?= $paging->page ?> dari <?= $paging->end_link ?></p>
  <ul class="pagination flex gap-2 flex-wrap">
    <?php if($paging->start_link) : ?>
      <li class="page-item">
        <a href="<?= site_url($paging_page.'/'.$paging->start_link)?>" class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200"><i class="fas fa-arrow-left"></i></a>
      </li>
    <?php endif ?>
    <?php if($paging->prev) : ?>
      <li class="page-item">
        <a href="<?= site_url($paging_page.'/'.$paging->prev.$paging->suffix)?>" class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200"><i data-feather="chevron-left" class="fas fa-chevron-left inline-block"></i></a>
      </li>
    <?php endif ?>
    <?php for($page=$paging->start_link; $page<=$paging->end_link; $page++) : ?>
      <li class="page-item">
        <a href="<?= site_url($paging_page.'/'.$page.$paging->suffix)?>" class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 <?= ($p == $page || $paging->page == $page) ? 'bg-primary-100 text-white hover:text-white hover:bg-primary-200' : 'bg-white hover:text-primary-200' ?>"><?= $page ?></a>
      </li>
    <?php endfor ?>
    <?php if($paging->next) : ?>
      <li class="page-item">
        <a href="<?= site_url($paging_page.'/'.$paging->next.$paging->suffix)?>" class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200"><i class="fas fa-chevron-right inline-block"></i></a>
      </li>
    <?php endif ?>
    <?php if($paging->end_link) : ?>
      <li class="page-item">
        <a href="<?= site_url($paging_page.'/'.$paging->end_link)?>" class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200"><i class="fas fa-arrow-right"></i></a>
      </li>
    <?php endif ?>
  </ul>
<?php endif ?>
