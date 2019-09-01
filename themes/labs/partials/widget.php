<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if($w_cos){
	foreach($w_cos as $data){
		if($data["jenis_widget"] == 1){
			include("$this->theme_folder/$this->theme/widgets/".trim($data['isi']));
		} elseif($data["jenis_widget"] == 2){
			include(LOKASI_WIDGET.trim($data['isi']));
		} else {
			echo "
				".html_entity_decode($data['isi'])."
			";
		}
	}
}
?>