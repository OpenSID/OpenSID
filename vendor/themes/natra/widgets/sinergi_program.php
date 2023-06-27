<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<!-- TODO: Pindahkan ke external css -->
<style>
	#sinergi_program
	{
		text-align: center;
	}

	#sinergi_program table
	{
		margin: auto;
	}

	#sinergi_program img
	{
		max-width: 100%;
		max-height: 100%;
		transition: all 0.5s;
		-o-transition: all 0.5s;
		-moz-transition: all 0.5s;
		-webkit-transition: all 0.5s;
	}

	#sinergi_program img:hover
	{
		transition: all 0.3s;
		-o-transition: all 0.3s;
		-moz-transition: all 0.3s;
		-webkit-transition: all 0.3s;
		transform: scale(1.5);
		-moz-transform: scale(1.5);
		-o-transform: scale(1.5);
		-webkit-transform: scale(1.5);
		box-shadow: 2px 2px 6px rgba(0,0,0,0.5);
	}
</style>
<div class="single_bottom_rightbar">
	<h2 class="box-title"><i class="fa fa-external-link"></i>&ensp;<?= $judul_widget ?></h2>
	<div id="sinergi_program" class="box-body">
		<table>
			<?php foreach($sinergi_program as $key => $program) :
				$baris[$program['baris']][$program['kolom']] = $program;
			endforeach; ?>

			<?php foreach($baris as $baris_program) : ?>
				<tr>
					<td >
						<?php $width = 100/count($baris_program)-count($baris_program)?>
						<?php foreach($baris_program as $key => $program) : ?>
							<span style="display: inline-block; width: <?= $width.'%'?>">
								<a href="<?= $program['tautan']?>" rel="noopener noreferrer" target="_blank"><img src="<?= base_url().LOKASI_GAMBAR_WIDGET.$program['gambar']?>" style="float:left; margin:0px 0px 0px 0px;" alt="<?= $program['judul']?>" /></a>
							</span>
						<?php endforeach; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>
