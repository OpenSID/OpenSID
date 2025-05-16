<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php $this->load->view("$folder_themes/plus/header_side"); ?>
<div class="mainpage">
	<div class="margin-side-10">
	<div class="stunting"> 
	<div class="mainbodyarea">
		<div class="bigarea hiddenrelative">
		<div class="bigarea-margin">
			<div class="col-default margin-top-10">
				<div class="bodypage bgwhite bordergrey">
					<div class="center-head bggrad-color2 flexcenter">
						<div class="icon-titlepage bgcolor-1 flexcenter"><svg viewBox="0 0 24 24"><path d="M7.5,2A2,2 0 0,1 9.5,4A2,2 0 0,1 7.5,6A2,2 0 0,1 5.5,4A2,2 0 0,1 7.5,2M6,22V16H3L5.6,8.4C5.9,7.6 6.6,7 7.5,7C8.4,7 9.2,7.6 9.4,8.4L12,16H9V22H6M14.5,12A2,2 0 0,1 16.5,10A2,2 0 0,1 18.5,12A2,2 0 0,1 16.5,14A2,2 0 0,1 14.5,12M15,15H18L19.5,19H18V22H15V19H13.5L15,15Z" /></svg></div>
						<h1>Stunting</h1>
					</div>
					<form class="form form-inline flexcenter" action="" method="get" style="margin:15px 5px 0;">                           
							<div class="form-group" style="margin:0 2px;">
								<select name="kuartal" id="kuartal" required class="form-control input-sm" title="Pilih salah satu">
									<?php foreach (kuartal2() as $item): ?>
									<option value="<?= $item['ke'] ?>" <?= $item['ke'] == $kuartal ? 'selected' : '' ?>>
										Kuartal ke <?= $item['ke'] ?>
										(<?= $item['bulan'] ?>)
									</option>
									<?php endforeach ?>
								</select>
							</div>
						
							<div class="form-group" style="margin:0 2px;">
								<select name="tahun" id="tahun" class="form-control input-sm" title="Pilih salah satu">
									<?php foreach ($dataTahun as $item): ?>
									<option value="<?= $item->tahun ?>"><?= $item->tahun ?></option>
									<?php endforeach ?>
								</select>
							</div>
						
							<div class="form-group" style="margin:0 2px;">
								<select name="id_posyandu" class="form-control input-sm" title="Pilih salah satu">
									<option value="">Semua</option>
									<?php foreach ($posyandu as $item): ?>
									<option value="<?= $item->id ?>" <?= $item->id == $idPosyandu ? 'selected' : '' ?>>
										<?= $item->nama ?></option>
									<?php endforeach ?>
								</select>
							</div>
						
						<div class="form-group" style="margin:0 2px;">
							<button type="submit" class="btn btn-info btn-sm" id="cari">
								<i class="fa fa-search"></i> Cari
							</button>
						</div>
						
					</form>
					<div class="hiddenrelative" style="margin:15px 0;">
					<?php $this->load->view($folder_themes . '/partials/kesehatan/widget'); ?>
					<?php $this->load->view($folder_themes . '/partials/kesehatan/chart_stunting_umur'); ?>
					<?php $this->load->view($folder_themes . '/partials/kesehatan/chart_stunting_posyandu'); ?>
					<?php $this->load->view($folder_themes . '/partials/kesehatan/scorecard', $scorecard); ?>
					</div>
				</div>
			</div>
			<?php $this->load->view("$folder_themes/plus/middle_page"); ?>
		</div>
		</div>
		<div class="rightsidearea">
			<?php $this->load->view("$folder_themes/plus/side"); ?>			
		</div>
	</div>
	</div>
	</div>
</div>