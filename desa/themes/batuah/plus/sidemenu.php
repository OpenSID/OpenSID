<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="sidebar" role="navigation" style="background:transparent !important;">
	<div class="sidebar-nav navbar-collapse">
		<ul class="nav" id="side-menu">
			<div class="menu-list">
			<?php if(IS_PREMIUM) : ?>
				<?php if (menu_tema()) : ?>
					<?php foreach (menu_tema() as $menu) { ?>
						<?php $has_dropdown = count($menu['childrens'] ?? []) > 0 ?>
						<?php if ($has_dropdown) : ?>
							<li class="bgwhite-trans1">
								<?php $menu_link = $has_dropdown ? '#!' : $menu['link_url'] ?>

								<a href="<?= $menu_link ?>">
									<?= $menu['nama'];
									if ($has_dropdown) {
										echo "<span class='caret'></span>";
									} ?>
								</a>
								<?php if ($has_dropdown) : ?>
									<ul class="nav nav-second-level">
										<?php foreach ($menu['childrens'] as $childrens) : ?>
											<?php if ($childrens['childrens']) : ?>
												<li>
													<a href="<?= $childrens['link_url'] ?>">
														<?= $childrens['nama'];
														if ($has_dropdown) {
															echo "<span class='caret'></span>";
														} ?>
													</a>
												</li>

												<?php foreach ($childrens['childrens'] as $bmenu) : ?>
													<?php $bhas_dropdown = count($bmenu['childrens'] ?? []) > 0 ?>
													<li>
														<?php $bmenu_link = $bhas_dropdown ? '#!' : $bmenu['link_url'] ?>

														<a style="white-space: nowrap; margin-left: 25px;" href="<?= $bmenu_link ?>">
															<?= $bmenu['nama'];
															if ($bhas_dropdown) {
																echo "<span class='caret'></span>";
															} ?>
														</a>

														<?php if ($bhas_dropdown) : ?>
															<ul>
																<?php foreach ($bmenu['childrens'] as $bchildrens) : ?>
																	<li>
																		<a style="margin-left: 50px;" href="<?= $bchildrens['link_url'] ?>">
																			<?= $bchildrens['nama'] ?>
																		</a>
																	</li>
																<?php endforeach ?>
															</ul>
														<?php endif ?>
													</li>
												<?php endforeach ?>
											<?php else : ?>
												<li>
													<a href="<?= $childrens['link_url'] ?>">
														<?= $childrens['nama'] ?>
													</a>
												</li>
											<?php endif ?>
										<?php endforeach; ?>
									</ul>
								<?php endif ?>
							</li>
						<?php else : ?>
							<li class="bgwhite-trans1"><a href="<?= $menu['link_url'] ?>"><?= $menu['nama'] ?></a></li>
						<?php endif; ?>
					<?php } ?>
				<?php endif ?>
			<?php else : ?>
				<?php foreach($menu_atas as $data) { ?>
					<?php if(count($data['submenu'])>0): ?>
					<li class="bgwhite-trans1"><a href="#"><?= $data['nama']; if(count($data['submenu'])>0) { echo "<span class='caret'></span>"; } ?></a>
						<ul class="nav nav-second-level">
							<?php foreach($data['submenu'] as $submenu): ?>
							<li><a href="<?= $submenu['link']?>" style="background:transparent !important;"><?= $submenu['nama']?></a></li>
							<?php endforeach; ?>
						</ul>
					</li>
					<?php else: ?>
					<li class="bgwhite-trans1"><a href="<?= $data['link']?>"><?= $data['nama']?></a></li>
					<?php endif; ?>
				<?php } ?>	
			<?php endif ?>	
			</div>
		</ul>
	</div>
</div>