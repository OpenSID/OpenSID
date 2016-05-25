
<html>
	<head>
		<title><?php echo $single_artikel['judul']?></title>
		<link rel="shortcut icon" href="<?php echo base_url()?>favicon.ico" />
		<link type='text/css' href="<?php echo base_url()?>assets/front/css/first.css" rel='Stylesheet' />
		<link type='text/css' href="<?php echo base_url()?>assets/front/css/colorbox.css" rel='Stylesheet' />
		<link type='text/css' href="<?php echo base_url()?>assets/css/ui-buttons.css" rel='Stylesheet' />
		<script src="<?php echo base_url()?>assets/front/js/jquery.js"></script>
		<script src="<?php echo base_url()?>assets/front/js/layout.js"></script>
		<script src="<?php echo base_url()?>assets/front/js/jquery.colorbox.js"></script>
		<script>
			$(document).ready(function(){
				$(".group2").colorbox({rel:'group2', transition:"fade"});
			});
		</script>
	</head>
	
	<body class="sub">
		<div id="content">
			<div id="header">
				<div id="headleft">
					<div id="sid-logo"><img src="<?php echo base_url()?>assets/images/logo/<?php echo $desa['logo']?>" alt=""/></div>
					<div id="sid-judul">Desa <?php echo unpenetration($desa['nama_desa'])?></a></div>
					<div id="sid-info">Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?></a></div>
					<div id="sid-moto">Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?></a></div>
					<div id="sid-alamat"><?php echo unpenetration($desa['alamat_kantor'])?></a></div>
				</div>
				<div id="headright">
					<div id="menu_vert">
						<div id="menuwrapper">
							<?php $this->load->view('partials/menu.tpl.php');?>
						</div>
					</div>
				</div>
				<div id="headright">
					<div id="menu_vert2" style="height:90px;">
					<?php  $i=0;foreach($slide AS $data){?>
					<?php  if($data['gambar']!='' AND $i<4){if(is_file("assets/front/artikel/kecil_".$data['gambar'])) {?>
						<img src="<?php echo base_url()?>/assets/front/artikel/kecil_<?php echo $data['gambar']?>" width="127" height="90"><?php 
						$i++;}elseif(is_file("assets/front/artikel/".$data['gambar'])){?>
						
						<img src="<?php echo base_url()?>/assets/front/artikel/<?php echo $data['gambar']?>" width="127" height="90"><?php 
						$i++;}?>
					<?php }}?>
					</div>
				</div>
			</div>
			
			<div id="mainmenu">
				<div id="mainmenuget">
					<?php $this->load->view('partials/menu.left.php');?>
				</div>
			</div>
			<?php /*<div id="sidebar">
				<div id="sidebartop" class="rtside">
					<?php $this->load->view('partials/side.right.php');?>
				</div>
			</div>
			*/?>
			<div id="main" class="Home">
				<!-- ini bagian c -->
					<div id="cycle">
						<div style=" display: block; z-index: 6; opacity: 1;" class="cycled">
							<!-- content isi -->
							<?php $this->load->view('partials/artikel.php');?>
						</div>
					</div>
			</div>
		</div>
			<br>
		<div id="foot">
		<div id="footer">
		<?php $this->load->view('partials/copywright.tpl.php');?>
		</div>           
		</div>
	</body>
</html>
