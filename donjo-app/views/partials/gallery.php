<?foreach($gallery AS $data){?>
<div class="themes nobig3">
	<div class='entry'>
		<? if(is_file("assets/front/gallery/sedang_".$data['gambar'])) {?>
			<a href="<?=site_url()?>first/sub_gallery/<?=$data['id']?>" title="<?=$data['nama']?>">
			<img src="<?=base_url()?>assets/front/gallery/sedang_<?=$data['gambar']?>" />
			<? }else{?>
			<img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?=base_url()?>assets/images/404-image-not-found.jpg" />
		<? }?>
		</a>
	</div>
	<div class='title'>
	<? if(!empty($data)){?><a class="group2" href="<?=base_url()?>assets/front/gallery/sedang_<?=$data['gambar']?>" title="<?=$data['nama']?>">Album : <?=$data['nama']?></a><?}?>
	</div>
</div>
<?}?>

<div class="themes nobig2">
<div class="bleft">
            <label><strong><?=$paging->num_rows?></strong></label>
            <label>Total Data</label>
</div>
        <div class="bright">
            <div class="uibutton-group">
            <? if($paging->start_link): ?>
				<a href="<?=site_url("first/gallery/$paging->start_link")?>" class="uibutton"  >Awal</a>
			<? endif; ?>
			<? if($paging->prev): ?>
				<a href="<?=site_url("first/gallery/$paging->prev")?>" class="uibutton"  >Prev</a>
			<? endif; ?>
            </div>
            <div class="uibutton-group">
                
				<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?=site_url("first/gallery/$i")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
				<? endfor; ?>
            </div>
            <div class="uibutton-group">
			<? if($paging->next): ?>
				<a href="<?=site_url("first/gallery/$paging->next")?>" class="uibutton">Next</a>
			<? endif; ?>
			<? if($paging->end_link): ?>
                <a href="<?=site_url("first/gallery/$paging->end_link")?>" class="uibutton">Akhir</a>
			<? endif; ?>
            </div>
        </div>
</div>