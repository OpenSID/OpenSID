<?php foreach($gallery AS $data){?>
<div class="themes nobig3">
	<div class='entry'>
		<?php  if(is_file("assets/front/gallery/sedang_".$data['gambar'])) {?>
			<a class="group2" href="<?php echo base_url()?>assets/front/gallery/sedang_<?php echo $data['gambar']?>" title="<?php echo $data['nama']?>">
			<img src="<?php echo base_url()?>assets/front/gallery/sedang_<?php echo $data['gambar']?>" /></a>
			<?php  }else{?>
			<img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?php echo base_url()?>assets/images/404-image-not-found.jpg" />
		<?php  }?>
	</div>
	<div class='title'>
	<?php  if(!empty($data)){?><?php echo $data['nama']?><?php }?>
	</div>
</div>
<?php }?>

<div class="themes nobig2">
<div class="bleft">
<a class="uibutton confirm" href="<?php echo site_url()?>first/gallery/">Kembali</a>
            <label>Jumlah Total Album: </label>
			<label><strong><?php echo $paging->num_rows?></strong></label>           
</div>
        <div class="bright">
            <div class="uibutton-group">
            <?php  if($paging->start_link): ?>
				<a href="<?php echo site_url("first/sub_gallery/$gal/$paging->start_link")?>" class="uibutton"  >Awal</a>
			<?php  endif; ?>
			<?php  if($paging->prev): ?>
				<a href="<?php echo site_url("first/sub_gallery/$gal/$paging->prev")?>" class="uibutton"  >Prev</a>
			<?php  endif; ?>
            </div>
            <div class="uibutton-group">
                
				<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("first/sub_gallery/$gal/$i")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php  endfor; ?>
            </div>
            <div class="uibutton-group">
			<?php  if($paging->next): ?>
				<a href="<?php echo site_url("first/sub_gallery/$gal/$paging->next")?>" class="uibutton">Next</a>
			<?php  endif; ?>
			<?php  if($paging->end_link): ?>
                <a href="<?php echo site_url("first/sub_gallery/$gal/$paging->end_link")?>" class="uibutton">Akhir</a>
			<?php  endif; ?>
            </div>
        </div>
</div>
