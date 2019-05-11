          <div class="single_bottom_rightbar">
          
        <h2><i class="fa fa-user-circle-o"></i> Aparatur Desa</h2>
	<div class="content_middle_middle"><div class="slick_slider2">
	
		<?php foreach($aparatur_desa as $data) : ?>
		  	<?php if(AmbilFoto($data['foto'],"besar") AND is_file(LOKASI_USER_PICT.$data['foto'])) : ?>
            <div class="single_featured_slide">
                <a><img src="<?php echo AmbilFoto($data['foto'],"besar") ?>"></a>
                <h2><a href="#"><?php echo $data['nama'] ?></a></h2>
                <h5><a href="#"><?php echo $data['jabatan'] ?></a></h5>
            </div>
				<?php endif; ?>
			<?php endforeach; ?>
    </div></div>
</div> 
 
