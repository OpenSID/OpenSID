<style>
    .stunting-area{position:relative;overflow:hidden;padding:0 10px;}
    .stunting-col{position:relative;width:calc(33.33333333% - 10px);margin:5px;}
    .icon-stunting{min-width:50px;min-height:50px;max-width:50px;max-height:50px;border-radius:50px;background:rgba(0,0,0,0.2);margin:0 5px 0 0;}
    .stunting-col p{font-size:95%;margin:0padding:0;line-height:1;}
    @media (max-width: 992px) {
        .stunting-col{width:calc(100% - 10px);}
    }
</style>
<div class="stunting-area" style="margin-bottom:20px;">
<div class="rowsame margin-minlr-5">
    <?php 
        $listIcon = ['fa-female','fa-child', 'fa-female','fa-child','fa-child','fa-child','fa-child'];
    ?>
    <?php foreach($widgets as $index => $item): ?>
    <div class="stunting-col">
        <div class="bordered <?= $item['bg-color'] == 'bg-gray' ? 'bg-danger' : $item['bg-color'] ?>"  style="border-radius:5px;padding:5px">
            <div class="hiddenrelative flexleft" style="padding:10px 5px;">
                <div class="icon-stunting flexcenter"><i class="fa fa-2x <?= $listIcon[$index] ?? 'fa-female' ?>"></i></div>
                <p style="margin-right:10px;"><?= $item['title'] ?></p>
                <h2 style="float:right;margin:0 0 0 auto;"><?= $item['total'] ?></h2>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>
</div>
