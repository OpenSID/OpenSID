<img src="<?= $icon ?>" alt="<?= lang('benchmarks') ?>"
     title="<?= lang('benchmarks') ?>"/> <?= $benchmarks['total_time']['elapsed_time'] . ' ' . lang('sec') ?>
<div class="detail benchmarks">
    <?php foreach ($benchmarks['profiles'] as $profile): ?>
        <p>
            <span class="left-col"><?= $profile['profile'] ?> :</span>
            <span class="right-col"><?= $profile['elapsed_time'] . ' ' . lang('sec') ?></span>
        </p>
    <?php endforeach ?>
</div>
