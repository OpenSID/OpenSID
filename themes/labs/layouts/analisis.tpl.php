<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<!--[if lte IE 9]>     <html lang="en" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en" class="no-focus"> <!--<![endif]-->

<head>
<?php $this->load->view("$folder_themes/commons/meta.php"); ?>
<?php $this->load->view("$folder_themes/commons/style.php"); ?>
<?php $this->load->view("$folder_themes/commons/scripts.php"); ?>
    <div id="page-container"
        class="sidebar-inverse side-overlay-hover side-scroll page-header-fixed page-header-inverse main-content-boxed side-trans-enabled">

        <!-- header Mobile -->
        <?php $this->load->view("$folder_themes/partials/header_mobile.php"); ?>
        <!-- header mobile -->

        <!-- header -->
        <?php $this->load->view("$folder_themes/partials/header.php"); ?>
        <!-- header -->
        <main id="main-container">
            <div class="bg-white">
                <div class="bg-pattern" style="background-image: url('assets/img/various/bg-pattern-inverse.png');">
                    <div class="content text-center">
                        <div class="pt-50 pb-20">
                            <a href="<?= site_url(); ?>first">
                                <h1 class="font-w700 mb-10">
                                    <?= $this->setting->website_title. ' ' . ucwords($this->setting->sebutan_desa). (($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?>
                                </h1>
                            </a>
                            <h2 class="h5 font-w400 text-muted">
                                <?= ucwords($this->setting->sebutan_kecamatan_singkat." ".$desa['nama_kecamatan'])?>,
                                <?= ucwords($this->setting->sebutan_kabupaten_singkat." ".$desa['nama_kabupaten'])?>,
                                <?= ucwords("Prov. ".$desa['nama_propinsi'])?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content content-full">
                <!-- Dummy content -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php $this->load->view("$folder_themes/partials/all_statistik.php"); ?>
                            </div>
                            <?php
					    if($list_jawab){
                            echo"
                            <div class=\"col-sm-12\">
                                <div class=\block\">";
                                     $this->load->view("$folder_themes/partials/statistik.php");
                              
                            echo "</div>
                            </div>";
                        }else{ ?>
                            <div class="">
							<div class="single_page_area">
								<h2 class="post_titile">DAFTAR AGREGASI DATA ANALISIS DESA</h2>
									<div class="single_bottom_rightbar wow fadeInDown animated"> 
										<h2>Klik untuk melihat lebih detail</h2>
									</div>
							</div>
							<?php foreach($list_indikator AS $data){?>
								<div class="box-header">
									<a href="<?= site_url()?>first/data_analisis/<?= $data['id']?>/<?= $data['subjek_tipe']?>/<?= $data['id_periode']?>">
									<h4><?= $data['indikator']?></h4>
									</a>
								</div>
								<div class="box-body" style="font-size:12px;">
									<table>
										<tr>
											<td width="100">Pendataan </td>
											<td width="20"> :</td>
											<td> <?= $data['master']?></td>
										</tr>
										<tr>
											<td>Subjek </td>
											<td> : </td>
											<td> <?= $data['subjek']?></td>
										</tr>
										<tr>
											<td>Tahun </td>
											<td> :</td>
											<td> <?= $data['tahun']?></td>
										</tr>
									</table>
								</div>
							<?php
							}
						} ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="block">
                                    <div class="block-content">
                                        <?php $this->load->view("$folder_themes/partials/widget.php"); ?>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Dummy content -->
            </div>
        </main>

    </div>
</body>

</html>