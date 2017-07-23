<!-- widget Menu-->
<div class="box box-default">
	<div class="box-header">
    <h3 class="box-title"><i class="fa fa-bars"></i> Kategori</h3>
	</div>
	<div class="box-body">
		<ul id="ul-menu" class="main">
		<?php foreach($menu_kiri as $data){?>
			<?php echo $data['menu']?>
		<?php }?>
		</ul>
	</div>
</div>
