<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<nav class="main-nav stick-fixed">
	<div class="full-wrapper relative clearfix">
		<div class="headertop transition-height">
			<div class="menu-left flexcenter bg-grey-dark2" onclick="openmenu()">
				<div class="menu-left-inner flexcenter">
					<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/menu.svg") ?>" alt=""/>
				</div>
			</div>
			<div id="openmenu" class="leftpanel">
				<div class="panel-cover bg-gradient-hor"></div>
				<div class="leftpanel-inner bg-grey-medium slideInDown">
					<div class="logo-menu flexleft bg-gradient-hor">
						<div class="logo-menu-image">
							<img src="<?= gambar_desa($desa['logo']);?>" alt=""/>
						</div>
						<div class="logo-menu-title">
							<h1><?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></h1> 
							<h2><?= ucwords($this->setting->sebutan_kecamatan_singkat." ".$desa['nama_kecamatan'])?><br><?= ucwords($this->setting->sebutan_kabupaten_singkat." ".$desa['nama_kabupaten'])?><br>Prov. <?= ucwords(($desa['nama_propinsi']) ? ' ' . $desa['nama_propinsi'] : ''); ?></h2>
						</div>
					</div>
					<div class="menu-icon">
						<div class="margin-min5">
							<a href="<?= setting('tampilkan_kehadiran') ? site_url('kehadiran') : site_url() ?>" target="_blank">
								<div class="mobile-menu-box flexleft">
									<div class="pd-lr-5">
										<div class="flexleft">
											<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/monitor.svg") ?>" alt=""/>
											<h3>Halaman <?= setting('tampilkan_kehadiran') ? 'Kehadiran' : 'Utama' ?></h3>
										</div>
									</div>
								</div>
							</a>
							<a href="<?= site_url('siteman') ?>" target="_blank">
								<div class="mobile-menu-box flexleft">
									<div class="pd-lr-5">
										<div class="flexleft">
											<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/login.svg") ?>" alt=""/>
											<h3><?= $this->session->siteman == 1 ? 'Halaman Admin' : 'Login Admin' ?></h3>
										</div>
									</div>
								</div>
							</a>
							<a href="<?= site_url('layanan-mandiri/masuk') ?>" target="_blank">
								<div class="mobile-menu-box flexleft">
									<div class="pd-lr-5">
										<div class="flexleft">
											<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/mandiri.svg") ?>" alt=""/>
											<h3>Layanan Mandiri</h3>
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>
					<div class="menu-left-container">
						<div class="withscroll">
							<div class="mlr-20">
								<div class="warna-container flexcenter">
									<div class="warna flexcenter">
										<div class="colors"><a class="c-tourism" data-val="tourism" href="javascript:void(0);">
											<div class="warna-style tourism"></div>
										</a></div>
									</div>
									<div class="warna flexcenter">
										<div class="colors"><a class="c-gogreen" data-val="gogreen" href="javascript:void(0);">
											<div class="warna-style gogreen"></div>
										</a></div>
									</div>
									<div class="warna flexcenter">
										<div class="colors">
											<a class="c-classic" data-val="classic" href="javascript:void(0);">
												<div class="warna-style classic"></div>
											</a>
										</div>
									</div>
									<?php if (cekKondisiColor()): ?>
									<div class="warna flexcenter">
										<div class="colors">
											<a class="c-sunrise" data-val="sunrise" href="javascript:void(0);">
												<div class="warna-style sunrise"></div>
											</a>
										</div>
									</div>
									<?php endif; ?>
								</div>
								<div class="warna-container flexcenter">
									<p style="margin:0 0 20px 0;padding:0;line-height:1.1;"><b>OpenSID <?= AmbilVersi()?></b></p>
								</div>
								<div class="searchtool-mobile">
									<form method=get action="<?= site_url(); ?>">
										<input type="text" name="cari" autocomplete="off" maxlength="50" class="form-control border-grey-soft flexleft" value="<?= htmlspecialchars_decode(htmlspecialchars_decode(html_escape($cari))) ?>" placeholder="Cari Artikel">
										<button type="submit" class="go-search flexcenter">
											<svg viewBox="0 0 56.966 56.966">
												<path d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23 s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92 c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17 s-17-7.626-17-17S14.61,6,23.984,6z"/>
											</svg>
										</button>
									</form>
								</div>
								<div class="mobile-menu">
									<nav class="navbar" style="font-size:16px !important;" >
										<ul style="font-size:16px !important;" >
                                          	<li class="dropdown" style="font-size:16px !important;">
                                              <a style="font-size:16px !important;" href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><span>Menu Kategori</span> <span class="caret"></span></a>
                                              <ul class="dropdown-menu" style="background:rgba(0,0,0,0.2);margin-top:10px;padding:15px 20px 8px;font-size:16px !important;">
                                                <?php foreach($menu_kiri as $data): ?>
                                                <a style="font-size:16px !important;" href="<?= site_url("artikel/kategori/$data[slug]"); ?>">
													<?php if(count($data['submenu'] ?? []) > 0): ?><span class='caret'></span><?php endif; ?>
													<p style="font-size:16px !important;"><?= $data['kategori']; ?></p>
                                                </a>
                                                <?php if(count($data['submenu'] ?? []) > 0): ?>
                                                <ul style="padding:8px 20px 8px;font-size:16px !important;">
                                                    <?php foreach($data['submenu'] as $submenu): ?>
                                                    <li class="dropdown" style="font-size:16px !important;">
                                                        <a style="font-size:16px !important;" href="<?= site_url("artikel/kategori/$submenu[slug]"); ?>"><p style="font-size:16px !important;"><?= $submenu['kategori']; ?></p></a>
                                                    </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                                <?php endif; ?>
                                                <?php endforeach; ?>
                                              </ul>
                                            </li>
											<?php if (IS_PREMIUM || (!IS_PREMIUM && IS_240810)) : ?>
											<?php $this->load->view($folder_themes . "/partials/home/menu_sidebar"); ?>
											<?php else : ?>
											<?php foreach($menu_atas as $data): ?>
												<?php if(count($data['submenu'] ?? []) > 0): ?>
													<li class="dropdown" style="font-size:16px !important;">
														<a style="font-size:16px !important;" href="<?= count($data['submenu'] ?? []) > 0 ? 'javascript:void(0);' : $data['link'] ?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><?= $data['nama']; if(count($data['submenu'] ?? []) > 0) { echo "<span class='caret'></span>"; } ?></a>
														<ul class="dropdown-menu" style="background:rgba(0,0,0,0.2);margin-top:10px;padding:15px 20px 8px;font-size:16px !important;">
															<?php foreach($data['submenu'] as $submenu): ?>
																<a style="font-size:16px !important;" href="<?= $submenu['link']?>"><p style="font-size:16px !important;"><?= $submenu['nama']?></p></a>
															<?php endforeach; ?>
														</ul>
													</li>
												<?php else: ?>
													<li><a href="<?= $data['link']?>"><?= $data['nama']?></a></li>
												<?php endif; ?>
											<?php endforeach; ?>
											<?php endif ?>
										</ul>
									</nav>
								</div>	
							</div>	
						</div>	
					</div>
				</div>
				<a href="javascript:void(0)" onclick="closemenu()">
					<div class="close-area flexcenter">
						<div class="close-button bg-color5"></div>
					</div>
				</a>
			</div>
			<a href="<?= site_url(); ?>">
				<div class="headertop-logo flexleft">
					<img src="<?= gambar_desa($desa['logo']);?>" alt=""/>
					<h1><b><?= ucwords($this->setting->website_title); ?> <?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></b><br/>
						<div class="big-screen"><?= ucwords($this->setting->sebutan_kecamatan_singkat." ".$desa['nama_kecamatan'])?> <?= ucwords($this->setting->sebutan_kabupaten_singkat." ".$desa['nama_kabupaten'])?></div>
					</h1>
				</div>
			</a>
			<div class="righttop flexright">
				<div class="big-screen">
					<div class="warna-container flexcenter">
						<div class="warna flexcenter">
							<div class="colors">
								<a class="c-tourism" data-val="tourism" href="javascript:void(0);">
									<div class="warna-style tourism"></div>
								</a>
							</div>
						</div>
						<div class="warna flexcenter">
							<div class="colors">
								<a class="c-gogreen" data-val="gogreen" href="javascript:void(0);">
									<div class="warna-style gogreen"></div>
								</a>
							</div>
						</div>
						<div class="warna flexcenter">
							<div class="colors">
								<a class="c-classic" data-val="classic" href="javascript:void(0);">
									<div class="warna-style classic"></div>
								</a>
							</div>
						</div>
						<?php if (cekKondisiColor()): ?>
						<div class="warna flexcenter">
							<div class="colors">
								<a class="c-sunrise" data-val="sunrise" href="javascript:void(0);">
									<div class="warna-style sunrise"></div>
								</a>
							</div>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="big-screen">
					<div class="searchtool flexleft">
						<form method=get action="<?= site_url(); ?>">
							<input type="text" name="cari" maxlength="50" autocomplete="off" class="form-control flexleft" value="<?= htmlspecialchars_decode(htmlspecialchars_decode(html_escape($cari))) ?>" placeholder="Cari Artikel">
							<button type="submit" class="go-search flexcenter">
								<svg viewBox="0 0 56.966 56.966">
									<path d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23 s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92 c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17 s-17-7.626-17-17S14.61,6,23.984,6z"/>
								</svg>
							</button>
						</form>
					</div>
				</div>
				<div class="big-screen">
					<?php if ($this->session->siteman == 1): ?>
						<div class="dropdown" style="float:right;">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-target="#online" role="button" aria-haspopup="true" aria-expanded="true"><div class="righttop-item flexcenter" style="width:auto;"><p style="margin-right:1px;">Anda Online</p><img style="margin-left:5px;" class="popin" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/login.svg") ?>" alt=""/></div></a>
							<div id="online">
								<div class="dropdown-menu">
									<a href="<?= site_url('siteman'); ?>" target="_blank">
										<div class="admin-online flexleft">
											<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/monitor.svg") ?>" alt=""/>
											<p style="font-size:90%;">Halaman Admin</p>
										</div>
									</a>
									<a href="<?= site_url('siteman/logout'); ?>">
										<div class="admin-online flexleft">
											<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/logout.svg") ?>" alt=""/>
											<p style="font-size:90%;">Logout</p>
										</div>
									</a>
								</div>
							</div>
						</div>
					<?php else: ?>
						<a href="<?= site_url('siteman') ?>" target="_blank">
							<div class="righttop-item flexcenter tip-bottom" data-toggle="tooltip" data-original-title="Login">
								<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/login.svg") ?>" alt=""/>
							</div>
						</a>
					<?php endif; ?>
				</div>
				<div class="righttop-item flexcenter tip-bottom" data-toggle="tooltip" data-original-title="Pengunjung" onclick="openvisitor()">
					<img style="width:auto;" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/statistik.svg") ?>" alt=""/>
				</div>
				<div id="visitor" class="rightpanel">
					<div class="panel-cover bg-gradient-hor"></div>
					<div class="rightpanel-container">
						<div class="m-20">
                            <a href="http://www.facebook.com/sharer.php?u=<?= "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" rel="noopener noreferrer" target="_blank">
                              <div class="mandiri-top-inner bg-color4 flexcenter">
                                Bagikan Web ke Facebook
                              </div>
                            </a>
						</div>
                      	<div class="m-20">
                        	<div class="relative-hid visitor-head bg-grey-medium border-grey-soft flexcenter">
								<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/monitor.svg") ?>" alt=""/>Statistik Pengunjung
							</div>
							<?php $this->load->view($folder_themes . "/widgets/statistik_pengunjung"); ?>
						</div>
					</div>
					<a href="javascript:void(0)" onclick="closevisitor()">
						<div class="close-area flexcenter">
							<div class="close-button bg-white"></div>
						</div>
					</a>
				</div>
				<div class="righttop-item flexcenter tip-bottom bell" data-toggle="tooltip" data-original-title="Info" onclick="openinfo()">
					<img style="height:28px;width:auto;" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/bell.svg") ?>" alt=""/>
				</div>
				<div id="openinfo" class="rightpanel">
					<div class="panel-cover bg-gradient-hor"></div>
					<div class="rightpanel-container">
						<?php $this->load->view($folder_themes . "/partials/home/info"); ?>
					</div>
					<a href="javascript:void(0)" onclick="closeinfo()">
						<div class="close-area flexcenter">
							<div class="close-button bg-white"></div>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
</nav>
<script>
	function openmenu() {
		document.getElementById("openmenu").style.height = "100%";
	}
	function closemenu() {
		document.getElementById("openmenu").style.height = "0";
	}  
	function openinfo() {
		document.getElementById("openinfo").style.height = "100%";
	}
	function closeinfo() {
		document.getElementById("openinfo").style.height = "0";
	}
	function openaparatur() {
		document.getElementById("aparatur").style.height = "100%";
	}
	function closeaparatur() {
		document.getElementById("aparatur").style.height = "0";
	}
	function openvisitor() {
		document.getElementById("visitor").style.height = "100%";
	}
	function closevisitor() {
		document.getElementById("visitor").style.height = "0";
	}
</script>
