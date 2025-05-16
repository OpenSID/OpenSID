<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- Event -->
<?php 
$date = date('Y-m-d');
$start_date = date('Y-m-d', strtotime('2022-8-20'));
$end_date = date('Y-m-d', strtotime('2022-9-10')); ?>
<?php if($date >= $start_date && $date <= $end_date){
	$this->load->view("$folder_themes/event/moment_item.php"); 
}
?>
<!-- Event -->

