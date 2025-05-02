<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (empty(html_escape($cari)) && count($slider_gambar ?? []) > 0) : ?>
<div class="sliderstyle">
	<div class="sliderstyle-top bg-grey-medium"></div>
	<div class="relative-row">
		<div class="container-page mb-10">
			<?php if($headline) : ?>
				<?php if($slider_gambar['gambar']) : ?>
					<div class="flexrow bg-gradient-vert" style="margin:0;border-radius:5px;">
						<div class="flex-slide">
							<?php $this->load->view($folder_themes . "/commons/slider"); ?>
						</div>
						<div class="flex-righttop bg-gradient-vert">
							<?php if($headline) : ?>
								<?php $this->load->view($folder_themes . "/commons/headline"); ?>
							<?php endif; ?>	
						</div>
					</div>
				<?php else: ?>
					<?php if($headline) : ?>
						<div class="headline-single bg-gradient-vert">
							<?php $this->load->view($folder_themes . "/commons/headline"); ?>
						</div>
					<?php endif; ?>	
				<?php endif; ?>	
			<?php else: ?>
				<?php $this->load->view($folder_themes . "/commons/slider"); ?>
			<?php endif; ?>		
		</div>
	</div>
</div>
<?php endif ?>
