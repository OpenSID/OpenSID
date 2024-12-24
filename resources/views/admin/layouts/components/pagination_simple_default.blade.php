<?php if ($paginator->hasPages()) : ?>
<nav>
    <ul class="pagination">
        <!-- Previous Page Link -->
        <?php if ($paginator->onFirstPage()) : ?>
        <li class="disabled" aria-disabled="true"><span><?= lang('Pager.previous') ?></span></li>
        <?php else : ?>
        <li><a href="<?= $paginator->previousPageUrl() ?>" rel="prev"><?= lang('Pager.previous') ?></a></li>
        <?php endif ?>

        <!-- Next Page Link -->
        <?php if ($paginator->hasMorePages()) : ?>
        <li><a href="<?= $paginator->nextPageUrl() ?>" rel="next"><?= lang('Pager.next') ?></a></li>
        <?php else : ?>
        <li class="disabled" aria-disabled="true"><span><?= lang('Pager.next') ?></span></li>
        <?php endif ?>
    </ul>
</nav>
<?php endif ?>
