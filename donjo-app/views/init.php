<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Sistem Informasi Desa (SID)</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<link rel="shortcut icon" href="<?php echo base_url()?>favicon.ico" />
		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo base_url()?>rss.xml" />
		<link href="<?php echo base_url()?>assets/css/screen.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/style2.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/noJS.css" />
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-1.5.2.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-ui-1.8.16.custom.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.formtips.1.2.2.packed.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.tipsy.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.elastic.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.flexbox.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.easing-1.3.pack.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/donjoscript/donjoscript2.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/donjoscript/donjo.ui.dialog.js"></script>
	<style>
	body{
	 background: url(<?php echo base_url()?>assets/files/bg.jpg) no-repeat center center fixed; 
	 -webkit-background-size: cover;
	 -moz-background-size: cover;
	 -o-background-size: cover;
	 background-size: cover;
		margin:0px;
		padding:0px;
	}
	#full{
		background: #ccccff repeat-x; 
		text-align:center;
		margin:150px 0px 0px 0px;
		padding:10px;
box-shadow:0px 0px 15px #777;
-moz-box-shadow:0px 0px 15px #777;
-webkit-box-shadow:0px 0px 15px #777;
	}
	</style>
	</head>
<body>
<script type="text/javascript">
	$(function(){
	<?php if($pass != NULL){ ?>
		modalpin('pin','PENTING!!! Informasi Username dan Password.','Berikut adalah password Administrator SID yang baru saja di hasilkan, silahkan dicatat dan di ingat dengan baik, Password ini sangat rahasia terkait dengan keamanan Data. Informasi ini hanya akan tampil sekali saja pada tahapan Instalasi SID.<br>Setelah berhasil masuk aplikasi harap untuk segera mengganti Password yang sekiranya mudah diingat.<br>Harap Diperhatikan dan dimaklumi.<table class="list"><td width="150">Username</td><td width="5"> : </td><td>admin</td></tr><tr><td>Pssword</td><td width="5"> : </td><td><?php echo $pass; ?></td></tr></table>');
	<?php }?>
		
	function modalpin(id,title,message,width,height){
	 if (width==null || height==null){
		width='500';
		height='auto';
	 }
	 $('#'+id+'').remove();
	 $('body').append('<div id="'+id+'" title="'+title+'" style="display:none;">'+message+'</div>');
			$('#'+id+'').dialog({
				resizable: false,
				draggable: true,
		 width:width,
		 height:height,
		 autoOpen: true,
			modal: true,
		 dragStart: function(event, ui) { 
			$(this).parent().addClass('drag');
		 },
		 dragStop: function(event, ui) { 
			$(this).parent().removeClass('drag');
		 }
		});
	 $('#'+id+'').dialog('open');
	 }
	});
</script>
<div id="full">
<h1>SUKSES!!!<h1>
<h2>Anda baru saja menginstall Aplikasi SID dengan Lancar.<h2>
<a href ="<?php echo site_url();?>siteman" class="uibutton special">Mulai SID </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
</body>
</html>
<?php exec('c:\sid-combine\ncmd.exe speak text "Installation Successed" -5 100');?>