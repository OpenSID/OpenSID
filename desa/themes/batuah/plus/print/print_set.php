<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<textarea id="printing-css" style="display:none;">
	* {box-sizing: border-box;-moz-box-sizing: border-box;}
	body{font-family:Arial;color:#000;font-size:16px;}.no-print{display:none;}.forprint{display:block !important;}.noprint{display:none !important;line-height:1.2;}
	.flexcenter{display:flex !important;justify-content: center;align-items: center;}
	.flexleft{display:flex !important;justify-content: left;align-items: center;}
	.flexright{display:flex !important;justify-content:right;align-items: center;}
	.pemb-detail .flexcenter{display:flex !important;justify-content: left;align-items: center;}
	.rowsame{display: flex;flex-flow: row wrap;justify-content: space-between center;}
	.margin-minlr-5{margin-left:-5px;margin-right:-5px;}
	a{text-decoration:none;outline:none;color:#000;}
	
	h1, h2, h3, h4, h5, h6, p{margin:5px 0;padding:0;line-height:1.2;}
	.print-header{width:100%;position:relative;overflow:hidden;padding-bottom:10px;border-bottom:#919191 1px solid;margin:0 0 20px;}
	.print-header img{width:auto;height:80px;float:left;margin:0 10px 0 0;}
	.print-header h1, .stat-title h1{font-size:22px;margin:2px 0;padding:0;line-height:1;font-weight:bold;text-transform:uppercase;}
	.print-header h2{font-size:16px;margin:0;padding:0;line-height:1.2;font-weight:500;}
	.print-headcontent{font-size:22px;margin:0;padding:0;line-height:1.2;font-weight:bold;}
	.print-content-image{vertical-align:top;width:40%;float:right;margin:0 0 0 10px;}
	.print-content-image img{width:100%;height:auto;margin:0 0 5px;}
	.print-body h1{font-size:22px;}.print-body h2{font-size:20px;}.print-body h3{font-size:18px;}.print-body h4{font-size:17px;}
	table{width:100% !important;}
	.center-head.flexleft{display:flex !important;justify-content: center;align-items: center;}
	.center-head h1{font-size:20px;}
	.headstat-lebar{margin:40px auto 25px;text-align:center !important;width:100%;}
	.headstat-lebar.flexcenter{display:flex !important;justify-content: center;align-items: center;}
	.headstat-lebar h1{font-size:16pt;margin:0 0;padding:0;line-height:1;font-weight:bold;text-transform:uppercase;}
	.headstat-lebar h2{display:none;}
	.headstat-lebar img{width:auto;height:70px;margin:0 10px 0 0;}
	.pembmap{min-width:100% !important;width:100% !important;height:300px !important;position:relative;overflow:hidden;}
	.pembtitle{padding:0 0;height:auto;font-size:14pt;margin:30px 0 10px;font-weight:bold;text-align:left;}
	.table-pemb td{vertical-align:top;padding:2px 0;font-size:16px;}
	.pemb-doc-box{position:relative;width:calc(50% - 27px)!important;margin:5px;overflow:hidden;padding:5px;border-radius:5px;border:#bdbdbd 1px solid !important;box-shadow:none !important;}
	.pemb-doc-box img{display:block;max-width:100%;height:auto;margin:0 0;}
	.pemb-doc-box p{margin:5px 0;}
	div.dataTables_wrapper div.dataTables_info, div.dataTables_wrapper div.dataTables_paginate, div.dataTables_wrapper div.dataTables_filter label, div.dataTables_wrapper div.dataTables_length label{display:none;}
	.pemb-head-cover{display:none;}
	.pemb-head h1{font-size:16pt;margin:0 10px 0 0;padding:0;line-height:1.2;text-align:center;margin:0 0 15px;}
	.imagepemb-big{width:100%%;float:left;text-align:center;}
	.imagepemb-big img{width:100% !important;height:auto !important;border-radius:5px;margin:0 auto;}
	.pemb-head{position:relative;overflow:hidden;text-align:center;}
	.pemb-ket{min-width:150px !important;}
	.table-pemb td{padding:2px 0;font-size:12pt;}
	.page {position:relative;overflow:hidden;width: 210mm;min-height: 297mm;padding: 0;margin: 0 auto;border-radius: 5px;background: white;}
	.pemb-doc{position:relative;border-radius:0;margin:30px 0 0;width:100%;border:none;}
	.pemb-print-padding{padding:0 5%;}
	.pemb-doc .pembtitle{text-align:center !important;margin:0 auto 10px;}
</textarea>