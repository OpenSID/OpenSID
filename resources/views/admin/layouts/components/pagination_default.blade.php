<?php if ($paginator->hasPages()) : ?>
  <nav>
    <ul class="pagination">
      <!-- Previous Page Link -->
      <?php if ($paginator->onFirstPage()) : ?>
        <li class="disabled" aria-disabled="true" aria-label="<?= lang('Pager.previous') ?>">
          <span aria-hidden="true">&lsaquo;</span>
        </li>
      <?php else : ?>
        <li>
          <a href="<?= $paginator->previousPageUrl() ?>" rel="prev" aria-label="<?= lang('Pager.previous') ?>">&lsaquo;</a>
        </li>
      <?php endif ?>

      <!-- Pagination Elements -->
      <?php foreach ($elements as $element) : ?>
        <!-- "Three Dots" Separator -->
        <?php if (is_string($element)) : ?>
          <li class="disabled" aria-disabled="true"><span><?= $element ?></span></li>
        <?php endif ?>

        <!-- Array Of Links -->
        <?php if (is_array($element)) : ?>
          <?php foreach ($element as $page => $url) : ?>
            <?php if ($page == $paginator->currentPage()) : ?>
              <li class="active" aria-current="page"><span><?= $page ?></span></li>
            <?php else : ?>
              <li><a href="<?= $url ?>"><?= $page ?></a></li>
            <?php endif ?>
          <?php endforeach ?>
        <?php endif ?>
      <?php endforeach ?>

      <!-- Next Page Link -->
      <?php if ($paginator->hasMorePages()) : ?>
        <li>
          <a href="<?= $paginator->nextPageUrl() ?>" rel="next" aria-label="<?= lang('Pager.next') ?>">&rsaquo;</a>
        </li>
      <?php else : ?>
        <li class="disabled" aria-disabled="true" aria-label="<?= lang('Pager.next') ?>">
          <span aria-hidden="true">&rsaquo;</span>
        </li>
      <?php endif ?>
    </ul>
  </nav>
<?php endif ?>