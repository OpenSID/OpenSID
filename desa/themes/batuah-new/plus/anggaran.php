<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="hiddenrelative" style="padding:0 0 15px;">
    <div class="headmod2 bgcolor-1 flexcenter" style="border-radius:5px 5px 0 0;"><h1 class="bggrey1 color-2">Transparansi Anggaran</h1></div>
    <div class="keuangan bggrey1 bordergrey" style="position:relative;overflow:hidden;">
	    <div class="samerow3">
	        <?php foreach($data_widget as $subdata_name => $subdatas) : ?>
		        <div class="keuangan-col bgwhite bordergrey">
		            <div class="flexcenter">
		                <div class="head-anggaran flexcenter"><h2><?= ($subdatas['laporan'])?></h2></div>
		            </div>
					<div class="scroll-area" id="scrollstyle">
		            <?php foreach ($subdatas as $key => $subdata): ?>
		                <?php if (! is_array($subdata)) continue; ?> 
			            <?php if($subdata['judul'] != NULL and $key != 'laporan' and $subdata['realisasi'] != 0 or $subdata['anggaran'] != 0): ?>
				            <div class="keuangan-box">
					            <h3><?= ucwords(strtolower($subdata['judul'])) ?></h3>
					            <table class="table-anggaran" style="width:100%;">
						            <tr>
							            <td style="text-align:center;font-size:95%;">Anggaran</td><td style="text-align:center;font-size:95%;">Realisasi</td>
						            </tr>
						            <tr>
							            <td style="font-size:90%;letter-spacing:-0.3px;"><?= rupiah24($subdata['anggaran']) ?></td><td style="font-size:90%;letter-spacing:-0.3px;"><?= rupiah24($subdata['realisasi']) ?></td>
						            </tr>
					            </table>
					            <div class="progress-anggaran">	
						            <progress max="100" value="<?= $subdata['persen'] ?>" class="php">
							            <div class="progress-bar" role="progressbar" style="width: <?= $subdata['persen'] ?>%" aria-valuenow="<?= $subdata['persen'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
						            </progress>
						            <p style="width:<?= $subdata['persen'] ?>%" data-value="<?= $subdata['persen'] ?>"></p>
					            </div>
				            </div>
			            <?php endif; ?>
		            <?php endforeach; ?>
					</div>
		        </div>
        	<?php endforeach; ?>
	    </div>
    </div>
</div>
