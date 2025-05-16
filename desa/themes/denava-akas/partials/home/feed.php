<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if ($feed['items']): ?>
	<div class="articlehome">
		<div class="relative-row ptb-10">
			<div class="container-page">
				<div class="articlehome-inner bg-gradient-hor pd-lr-20 p-tb-10">
					<div class="articlehome-head bg-gradient-black flexleft">
						<h1>RSS FEED</h1>
					</div>
					<div class="article-inner">
						<div class="flexrow">
							<?php foreach ($feed['items'] as $data): ?>
								<?php $deskripsi = potong_teks($data['DESCRIPTION'], 250) ?>
								<div class="flex-column2f bg-grey-medium">
									<div class="latest-article relative-hid">
										<div class="latest-date"><?= gmdate("d-M-Y H:i:s", $data['PUBDATE']) ?> | <i class="fa fa-heart"></i> <?= $data['CATEGORY'] ?> | <i class="fa fa-user"></i> <?= $data["DC:CREATOR"] ?></div>
										<a href="<?= $data['LINK'] ?>" rel="noopener noreferrer" target="_blank" ><h2><?= $data["TITLE"] ?></h2></a>
									</div>
									<div class="latest-box-bottom bg-white border-grey-soft flexcenter" style="border-radius:0 0 5px 5px;">
										<div class="flexcenter">
											<div class="latest-info flexcenter border-grey-soft small">
												<a title="Detail" data-remote="true" data-toggle="modal" data-target="#<?= str_replace(':', '', gmdate("H:i:s", $data['PUBDATE'])); ?>">
													<div class="article-link flexleft">
														<svg viewBox="0 0 128 128">
															<path d="M109,55c0-29.8-24.2-54-54-54C25.2,1,1,25.2,1,55s24.2,54,54,54c13.5,0,25.8-5,35.2-13.1l25.4,25.4l5.7-5.7L95.9,90.2   C104,80.8,109,68.5,109,55z M55,101C29.6,101,9,80.4,9,55S29.6,9,55,9s46,20.6,46,46S80.4,101,55,101z"/><path d="M25.6,30.9l6.2,5.1C37.5,29,46,25,55,25v-8C43.6,17,32.9,22.1,25.6,30.9z"/><path d="M17,55h8c0-2.1,0.2-4.1,0.6-6.1l-7.8-1.6C17.3,49.8,17,52.4,17,55z"/>
														</svg>
														Baca
													</div>
												</a>
											</div>
											<div class="latest-info flexcenter" style="border:none;">
												<a href="<?= $data['LINK'] ?>" rel="noopener noreferrer" target="_blank" >
													<div class="article-link flexleft">
														<svg viewBox="0 0 128 128">
															<polygon points="37,9 119,9 119,119 37,119 37,127 127,127 127,1 37,1  "/><rect height="8" width="8" x="1" y="60"/><polygon points="46.2,91.2 51.8,96.8 84.7,64 51.8,31.2 46.2,36.8 69.3,60 19,60 19,68 69.3,68  "/>
														</svg>
														Selengkapnya
													</div>
												</a>
											</div>
										</div>
									</div>	
								</div>								
								<div class="modal fade" id="<?= str_replace(':', '', gmdate("H:i:s", $data['PUBDATE'])); ?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="true">
									<div class="modal-dialog" style="margin:0 !important;">
										<div class="modal-container-big">
											<div class="modal-article">
												<div class="topmodal bg-grey-dark2 flexleft" data-dismiss="modal" aria-hidden="true">Detail Feed<div class="close-button"></div></div>
												<div class="modal-article-inner">
													<div class="modalscroll">
														<div class="relative-hid2 modal-article-content bgwhite">
															<div class="article-info flexleft">
																<div class="article-info-inner flexleft"><i class="fa fa-calendar flexleft"></i><p><?= gmdate("d-M-Y H:i:s", $data['PUBDATE']) ?></p></div>
																<div class="article-info-inner flexleft"><i class="fa fa-heart flexleft"></i><p><?= $data['CATEGORY'] ?></p></div>
																<div class="article-info-inner flexleft"><i class="fa fa-user flexleft"></i><p><?= $data["DC:CREATOR"] ?></p></div>
															</div>
															<?= $deskripsi ?> ...
															<a href="<?= $data['LINK'] ?>" rel="noopener noreferrer" target="_blank" >
																<div class="article-link flexleft" style="margin:15px 0;">
																	<svg viewBox="0 0 128 128">
																		<polygon points="37,9 119,9 119,119 37,119 37,127 127,127 127,1 37,1  "/><rect height="8" width="8" x="1" y="60"/><polygon points="46.2,91.2 51.8,96.8 84.7,64 51.8,31.2 46.2,36.8 69.3,60 19,60 19,68 69.3,68  "/>
																	</svg>
																	<b>Selengkapnya</b>
																</div>
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</div>
<?php endif; ?>
