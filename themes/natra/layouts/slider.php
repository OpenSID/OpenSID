<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style type="text/css">
	.slick_slider img {
		width: 100%;
	}
	.slick_slider, .cycle-slideshow {
		max-height: 350px;
		border: 5px solid #e5e5e500;
		display: block;
		position: relative;
		/*margin: 0px auto;*/
		overflow: hidden;
	}
	.textgambar{
		position: absolute;
		left: 20px;
		top: 280px;
		color: black;
		font-weight: bold;
		font-family: Oswald;
		
		background-color: #ffffff;
		border: 1px solid black;
		border-radius: 3px;
		padding: 5px;
		opacity: 0.6;
		filter: alpha(opacity=60); /* For IE8 and earlier */
	}
</style>
<div class="slick_slider" style="margin-bottom:5px;">
<?php $active = true; ?>
<?php foreach ($slider_gambar['gambar'] as $gambar) : ?>
<?php $file_gambar = $slider_gambar['lokasi'] . 'sedang_' . $gambar['gambar']; ?>
<?php if(is_file($file_gambar)) : ?>
	<div class="single_iteam <?php echo ($active == true)?"active":"" ?>" data-artikel="<?php echo $gambar['id']?>" <?php if ($slider_gambar['sumber'] != 3): ?> onclick="location.href='<?='artikel/'.buat_slug($gambar); ?>'" <?php endif; ?>>
		<img class="tlClogo" src="<?php echo base_url().$slider_gambar['lokasi'].'sedang_'.$gambar['gambar']?>">
		<div class="<?php if ($gambar['judul']): ?>textgambar <?php endif; ?>hidden-xs"><?= $gambar['judul'] ?></div>
	</div>
<?php $active = false; ?>
<?php endif; ?>
<?php endforeach; ?>
</div>
<script>
$('.tlClogo').bind('contextmenu', function(e) {
    return false;
});
</script>