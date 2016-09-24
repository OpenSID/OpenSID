
<!--widget Statis -->

<?php include("donjo-app/views/widgets/layanan_mandiri.php"); ?>
<?php include("donjo-app/views/widgets/agenda.php"); ?>
<?php include("donjo-app/views/widgets/galeri.php"); ?>
<?php include("donjo-app/views/widgets/statistik.php"); ?>
<?php include("donjo-app/views/widgets/komentar.php"); ?>
<?php include("donjo-app/views/widgets/media_sosial.php"); ?>
<?php include("donjo-app/views/widgets/peta_lokasi_kantor.php"); ?>
<?php include("donjo-app/views/widgets/statistik_pengunjung.php"); ?>
<?php include("donjo-app/views/widgets/arsip_artikel.php"); ?>


<!--widget Manual-->
<?php

if($w_cos){
	foreach($w_cos as $data){
		echo "
		<div class=\"box box-primary box-solid\">
			<div class=\"box-header\">
				<h3 class=\"box-title\">".$data["judul"]."</h3>
			</div>
			<div class=\"box-body\">
			".$data['isi']."
			</div>
		</div>
		";
	}
}

?>

