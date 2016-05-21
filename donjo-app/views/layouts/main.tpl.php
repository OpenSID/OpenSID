<html>
	<head>
		<title>Website Desa <?=unpenetration($desa['nama_desa'])?></title>
		<link rel="shortcut icon" href="<?=base_url()?>favicon.ico" />
		<link type='text/css' href="<?=base_url()?>assets/front/css/first.css" rel='Stylesheet' />
		<link type='text/css' href="<?=base_url()?>assets/css/ui-buttons.css" rel='Stylesheet' />
		<link type='text/css' href="<?=base_url()?>assets/front/css/colorbox.css" rel='Stylesheet' />
		<script src="<?=base_url()?>assets/front/js/jquery.js"></script>
		<script src="<?=base_url()?>assets/front/js/layout.js"></script>
		<script src="<?=base_url()?>assets/front/js/jquery.colorbox.js"></script>
		<script>
			$(document).ready(function(){
				$(".group2").colorbox({rel:'group2', transition:"fade"});
				$(".group3").colorbox({rel:'group3', transition:"fade"});
			});
		</script>
	</head>
	
	<body class="sub">
		<div style="display: none;" id="cboxOverlay"></div>
		<div style="position:absolute; width:9999px; visibility:hidden; display:none"></div>
		
		<div id="content">
			<div id="header">
				<div id="headleft">
<div id="sid-logo"><img src="<?=base_url()?>assets/images/logo/<?=$desa['logo']?>" alt=""/></div>
<div id="sid-judul">Desa <?=unpenetration($desa['nama_desa'])?></a></div>
<div id="sid-info">Kecamatan <?=unpenetration($desa['nama_kecamatan'])?></a></div>
<div id="sid-moto">Kabupaten <?=unpenetration($desa['nama_kabupaten'])?></a></div>
<div id="sid-alamat"><?=unpenetration($desa['alamat_kantor'])?></a></div>
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
					<? $i=0;foreach($slide AS $data){?>
					<? if($data['gambar']!='' AND $i<4){if(is_file("assets/front/artikel/kecil_".$data['gambar'])) {?>
						<img src="<?=base_url()?>/assets/front/artikel/kecil_<?=$data['gambar']?>" width="127" height="90"><?
						$i++;}elseif(is_file("assets/front/artikel/".$data['gambar'])){?>
						
						<img src="<?=base_url()?>/assets/front/artikel/<?=$data['gambar']?>" width="127" height="90"><?
						$i++;}?>
					<?}}?>
					</div>
				</div>
			</div>
			<div id="mainmenu">
				<div id="mainmenuget">
					<?php $this->load->view('partials/menu.left.php');?>
				</div>
			</div>
			<div id="sidebar" class="rtside Home">
				<div id="sidebartop" class="rtside">
					<?php $this->load->view('partials/side.right.php');?>
				</div>
			</div>
			<div id="main" class="Home" style="min-height:900px;">
				<div  overflow: hidden;" id="cycle">
					<div style=" display: block; z-index: 6; opacity: 1;" class="cycled">
						<?php $this->load->view('partials/content.php');?>
					</div>
				</div>
			</div>
			<div style="clear:left;"></div>
		</div>
		<br>
		<div id="foot">
			<div id="footer">
				<?php $this->load->view('partials/copywright.tpl.php');?>
			</div>           
		</div>
	</body>
</html>