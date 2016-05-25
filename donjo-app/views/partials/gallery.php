<?php foreach($gallery AS $data){?>
<div class="themes nobig3">
	<div class='entry'>
		<?php  if(is_file("assets/front/gallery/sedang_".$data['gambar'])) {?>
			<a href="<?php echo site_url()?>first/sub_gallery/<?php echo $data['id']?>" title="<?php echo $data['nama']?>">
			<img src="<?php echo base_url()?>assets/front/gallery/sedang_<?php echo $data['gambar']?>" />
			<?php  }else{?>
			<img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?php echo base_url()?>assets/images/404-image-not-found.jpg" />
		<?php  }?>
		</a>
	</div>
	<div class='title'>
	<?php  if(!empty($data)){?><a class="group2" href="<?php echo base_url()?>assets/front/gallery/sedang_<?php echo $data['gambar']?>" title="<?php echo $data['nama']?>">Album : <?php echo $data['nama']?></a><?php }?>
	</div>
</div>
<?php }?>

<div class="themes nobig2">
<div class="bleft">
            <label><strong><?php echo $paging->num_rows?></strong></label>
            <label>Total Data</label>
</div>
        <div class="bright">
            <div class="uibutton-group">
            <?php  if($paging->start_link): ?>
				<a href="<?php echo site_url("first/gallery/$paging->start_link")?>" class="uibutton"  >Awal</a>
			<?php  endif; ?>
			<?php  if($paging->prev): ?>
				<a href="<?php echo site_url("first/gallery/$paging->prev")?>" class="uibutton"  >Prev</a>
			<?php  endif; ?>
            </div>
            <div class="uibutton-group">
                
				<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("first/gallery/$i")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php  endfor; ?>
            </div>
            <div class="uibutton-group">
			<?php  if($paging->next): ?>
				<a href="<?php echo site_url("first/gallery/$paging->next")?>" class="uibutton">Next</a>
			<?php  endif; ?>
			<?php  if($paging->end_link): ?>
                <a href="<?php echo site_url("first/gallery/$paging->end_link")?>" class="uibutton">Akhir</a>
			<?php  endif; ?>
            </div>
        </div>
</div>
