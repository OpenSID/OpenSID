<img src="<?php echo $icon ?>" alt="<?php echo lang('benchmarks') ?>"
     title="<?php echo lang('benchmarks') ?>"/> <?php echo $benchmarks['total_time']['elapsed_time'] . ' ' . lang('sec') ?>
<div class="detail benchmarks">
    <?php foreach ($benchmarks['profiles'] as $profile): ?>
        <p>
            <span class="left-col"><?php echo $profile['profile'] ?> :</span>
            <span class="right-col"><?php echo $profile['elapsed_time'] . ' ' . lang('sec') ?></span>
        </p>
    <?php endforeach ?>
</div>
