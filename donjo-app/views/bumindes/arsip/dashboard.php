<div class="content-wrapper">
    <section class='content-header'>
		<h1>Dokumen Arsip Desa</h1>
		<ol class='breadcrumb'>
			<li><a href='<?=site_url()?>'><i class='fa fa-home'></i> Home</a></li>
			<li class='active'>Arsip Desa</li>
		</ol>
	</section>
    <section class="content" id="maincontent">
        <div class="row">
            <div class="col-md-8">
                <div class="box box-info">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-6 col-xs-6">
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3><?=$dokumen_desa['total']?></h3>
                                        <p><?=$dokumen_desa['title']?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-document"></i>
                                    </div>
                                    <a href="<?=site_url($dokumen_desa['uri'])?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xs-6">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3><?=$surat_desa['total']?></h3>
                                        <p><?=$surat_desa['title']?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-email"></i>
                                    </div>
                                    <a href="<?=site_url($surat_desa['uri'])?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xs-6">
                                <div class="small-box bg-purple">
                                    <div class="inner">
                                        <h3><?=$kependudukan['total']?></h3>
                                        <p><?=$kependudukan['title']?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-person"></i>
                                    </div>
                                    <a href="<?=site_url($kependudukan['uri'])?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xs-6">
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3><?=$layanan_surat['total']?></h3>
                                        <p><?=$layanan_surat['title']?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-document-text"></i>
                                    </div>
                                    <a href="<?=site_url($layanan_surat['uri'])?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>