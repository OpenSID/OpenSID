<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/css/960.css" type="text/css" media="screen">
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/css/screen.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/css/print-preview.css" type="text/css" media="screen">
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/css/print.css" type="text/css" media="print" />
<script src="<?php echo base_url()?>assets/css/css/jquery.tools.min.js"></script>
<script src="<?php echo base_url()?>assets/css/css/jquery.print-preview.js" type="text/javascript" charset="utf-8"></script>
 
<script type="text/javascript">
$(function() {
$("#feature > div").scrollable({interval: 2000}).autoscroll();
$('#aside').prepend('<a class="print-preview">Cetak </a>');
$('a.print-preview').printPreview();
var code = 80;
$.printPreview.loadPrintPreview();
});
</script>
</head>