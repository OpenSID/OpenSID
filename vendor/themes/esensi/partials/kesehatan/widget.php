<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 container px-3 lg:px-5"> 
    <?php foreach($widgets as $item): ?>
    <div class="shadow  <?= $item['bg-color'] ?>-300 rounded-lg py-2">
        <div class="p-4 flex flex-row">
            <div class="text-5xl">
                <i class="ion <?= $item['icon'] ?>"></i>
            </div>
            <div class="flex-grow pl-6">
                <p class="text-gray-400"><?= $item['title'] ?></p>
                <p class="text-3xl"><?= $item['total'] ?></p>                
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>
