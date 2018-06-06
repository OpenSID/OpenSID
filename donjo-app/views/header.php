<!-- Perubahan script coding untuk bisa menampilkan header dalam bentuk tampilan bootstrap (AdminLTE)  -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<meta charset="utf-8">
  		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>
			<?php
				echo $this->setting->admin_title
				. ' ' . ucwords($this->setting->sebutan_desa)
				. (($desa['nama_desa']) ? ' ' . unpenetration($desa['nama_desa']) : '')
				. get_dynamic_title_page_from_path();
			?>
		</title>	
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">	
		<?php if(is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
			<link rel="shortcut icon" href="<?php echo base_url()?><?php echo LOKASI_LOGO_DESA?>favicon.ico" />
		<?php else: ?>
			<link rel="shortcut icon" href="<?php echo base_url()?>favicon.ico" />
		<?php endif; ?>
		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo base_url()?>rss.xml" />		
			
		<!-- Bootstrap 3.3.7 -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/bootstrap/dist/css/bootstrap.min.css">				
		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/Ionicons/css/ionicons.min.css">
		<!-- DataTables -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/datatables.net-bs/css/dataTables.bootstrap.min.css">	
		<!-- bootstrap wysihtml5 - text editor -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
		<!-- Select2 -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/dist/css/select2.min.css">
		<!-- Daterange picker -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/bootstrap-daterangepicker/daterangepicker.css">
		<!-- Bootstrap Color Picker -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
		<!-- Date Picker -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
		<!-- Bootstrap time Picker -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/timepicker/bootstrap-timepicker.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/skins/_all-skins.min.css">
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/pace/pace.min.css">	
		
	</head>
	<body class="skin-purple sidebar-mini  <?php if($hidmenu==1){?>sidebar-collapse<?php }?>">
		<div class="wrapper">
			<header class="main-header">
				<a href="<?php echo site_url()?>first"  target="_blank" class="logo">										
					<span class="logo-mini"><b>SID</b></span>										
					<span class="logo-lg"><b>OpenSID</b></span>
				</a>								
				<nav class="navbar navbar-static-top">										
					<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">												
							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<?php if($foto){?>
										<img src="<?php echo AmbilFoto($foto)?>" class="user-image" alt="User Image"/>
									<?php }else{?>
										<img src="<?php echo base_url()?>assets/files/user_pict/kuser.png" class="user-image" alt="User Image"/>
									<?php }?>	
									<span class="hidden-xs"><?php echo $nama?> </span>
								</a>
								<ul class="dropdown-menu">
									<li class="user-header">
										<?php if($foto){?>
											<img src="<?php echo AmbilFoto($foto)?>" class="img-circle" alt="User Image"/>
										<?php }else{?>
											<img src="<?php echo base_url()?>assets/files/user_pict/kuser.png" class="img-circle" alt="User Image"/>
										<?php }?>									
										<p>Anda Login Sebagai</p>
										<p><strong><?php echo $nama?></strong></p>
									</li>            
									<li class="user-footer">
										<div class="pull-left">
											<a href="<?php echo site_url()?>user_setting/"												
												data-toggle="modal" data-target="#modalBox">
												<button  data-toggle="modal"  class="btn bg-maroon btn-flat btn-sm" >Profile</button>
											</a>																											
										</div>																								
										<div class="pull-right">
											<a href="<?php echo site_url()?>siteman" class="btn bg-maroon btn-flat btn-sm">Logout</a>
										</div>
									</li>
								</ul>
							</li>							
						</ul>
					</div>
				</nav>					
			</header>
			<input id="success-code" type="hidden" value="<?php echo $_SESSION['success']?>">
			<!-- Untuk menampilkan modal bootstrap info pengguna login  -->
			<div  class="modal fade" id="modalBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class='modal-dialog'>
					<div class='modal-content'>            
						<div class='modal-header'>
							<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
							<h4 class='modal-title' id='myModalLabel'><i class='fa fa-text-width text-yellow'></i> Ubah Password</h4>
						</div>
						<div class="fetched-data"></div>
					</div>
				</div>
			</div>	
			<!-- Untuk menampilkan modal / pemberitahuan perubahan password default  -->
			<div  class="modal fade" id="massageBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class='modal-dialog'>
					<div class='modal-content'> 
						<div class='modal-header btn-info'>
							<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
							<h4 class='modal-title' id='myModalLabel'><i class='fa fa-text-width text-white'></i> <?php echo $_SESSION['admin_warning'][0]; ?></h4>
						</div>
						<div class='modal-body'>
							<?php echo $_SESSION['admin_warning'][1]; ?>
						</div>						
						<div class='modal-footer'>
							<button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-arrow-circle-o-left'></i> Lain Kali</button>
							<a href="<?php echo site_url()?>user_setting/" data-toggle="modal" data-target="#modalBox" id="ok">
								<button type="button" class="btn btn-social btn-flat btn-success btn-sm"><i class='fa fa-edit'></i> Ubah</button>
							</a>
						</div>
					</div>
				</div>
			</div>
