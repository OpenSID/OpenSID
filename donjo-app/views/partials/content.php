

<?php if($headline){?>
<div class="themes nobig2">
<div class='title'>
<h2><a href="<?php echo site_url("first/artikel/$headline[id]")?>"><?php echo $headline['judul']?></a></h2>
<label class="owner"><?php echo $headline['owner']?>, </label><label><?php echo tgl_indo2($headline['tgl_upload'])?></label>
</div>
<div class='entry'>
<p>
<?php  if($headline['gambar']!=''){?>
		<?php  if(is_file("assets/front/artikel/sedang_".$headline['gambar'])) {?>
			<a class="group2" href="<?php echo base_url()?>assets/front/artikel/sedang_<?php echo $headline['gambar']?>" title="">
<img width="200" class="head" src="<?php echo base_url()?>assets/front/artikel/sedang_<?php echo $headline['gambar']?>" /></a>
			<?php  }else{?>
			<img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?php echo base_url()?>assets/images/404-image-not-found.jpg" width="300" height="180"/>
		<?php  }?>

<?php  }?><?php echo $headline['isi']?></p>
</div>
</div>
<?php }?>
<div class="new-artikel">
<h1>Artikel Terkini</h1>
</div>
<?php foreach($artikel AS $data){?>
<div class="themes nobig">
<div class='title'>
<h2><a href="<?php echo site_url("first/artikel/$data[id]")?>"><?php echo $data['judul']?></a></h2>
<label class="owner"><?php echo $data['owner']?>, </label><label><?php echo tgl_indo2($data['tgl_upload'])?></label>
</div>

<div class='entry'>
<?php  if($data['gambar']!=''){?>
	<?php  if(is_file("assets/front/artikel/kecil_".$data['gambar'])) {?>
			<a class="group2" href="<?php echo base_url()?>assets/front/artikel/sedang_<?php echo $data['gambar']?>" title=""><img  style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?php echo base_url()?>assets/front/artikel/kecil_<?php echo $data['gambar']?>" /></a>
			<?php  }else{?>
			<img style="margin-right: 10px; margin-bottom: 5px; float: left;" src="<?php echo base_url()?>assets/images/404-image-not-found.jpg" width="120" height="90"/>
		<?php  }?>
<?php }?>
<p style="text-align: justify;"><?php echo $data['isi']?></p>
</div>
</div>
<?php }?>
<div class="themes nobig2">
<div class="bleft">
            <label>Jumlah Total Artikel:</label>
			<label><strong><?php echo $paging->num_rows?></strong></label>
</div>
        <div class="bright">
            <div class="uibutton-group">
            <?php  if($paging->start_link): ?>
				<a href="<?php echo site_url("first/index/$paging->start_link")?>" class="uibutton"  >Awal</a>
			<?php  endif; ?>
			<?php  if($paging->prev): ?>
				<a href="<?php echo site_url("first/index/$paging->prev")?>" class="uibutton"  >Prev</a>
			<?php  endif; ?>
            </div>
            <div class="uibutton-group">
                
				<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("first/index/$i")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php  endfor; ?>
            </div>
            <div class="uibutton-group">
			<?php  if($paging->next): ?>
				<a href="<?php echo site_url("first/index/$paging->next")?>" class="uibutton">Next</a>
			<?php  endif; ?>
			<?php  if($paging->end_link): ?>
                <a href="<?php echo site_url("first/index/$paging->end_link")?>" class="uibutton">Akhir</a>
			<?php  endif; ?>
            </div>
        </div>
</div>
