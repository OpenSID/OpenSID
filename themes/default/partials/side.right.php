<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>


<div class="box box-primary box-solid">
	<div class="box-body">
	<form method=post action="<?php echo site_url('first');?>" class="form-inline">
		<input type="text" name="cari" class="form-control" value="<?= html_escape($_POST['cari']); ?>" placeholder="Cari artikel...">
		<button type="submit" class="btn btn-primary">Cari</button>
	</form>
	</div>
</div>

<!-- Tampilkan Widget -->
<?php

if($w_cos){
	foreach($w_cos as $data){
		if($data["jenis_widget"] == 1){
			include("donjo-app/views/widgets/".trim($data['isi']));
		} elseif($data["jenis_widget"] == 2){
			include(LOKASI_WIDGET.trim($data['isi']));
		} else {
			echo "
			<div class=\"box box-primary box-solid\">
				<div class=\"box-header\">
					<h3 class=\"box-title\">".$data["judul"]."</h3>
				</div>
				<div class=\"box-body\">
				".html_entity_decode($data['isi'])."
				</div>
			</div>
			";
		}
	}
}

?>
