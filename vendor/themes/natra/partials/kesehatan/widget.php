<div class="container row"> 
    <?php 
        $listIcon = ['fa-female','fa-child', 'fa-female','fa-child','fa-child','fa-child','fa-child'];
    ?>
    <?php foreach($widgets as $index => $item): ?>
    <div class="col-lg-4 col-sm-6 col-xs-12" style="padding:15px">
        <div class="small-box bordered <?= $item['bg-color'] == 'bg-gray' ? 'bg-danger' : $item['bg-color'] ?>"  style="border-radius:5px;padding:5px">
            <div class="row" style="padding:5px">
                <div class="col-md-2 col-sm-4">
                    <div class="icon">
                        <i class="fa fa-4x <?= $listIcon[$index] ?? 'fa-female' ?>"></i>
                    </div>
                </div>
                <div class="col-md-10 col-sm-8">                    
                    <p><?= $item['title'] ?></p>
                    <p style="font-size:40px;margin-top:-20px "><?= $item['total'] ?></p>
                </div>
            </div>                    
            
        </div>
    </div>
    <?php endforeach ?>
</div>
