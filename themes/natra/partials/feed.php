<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
	<div id="feed" class="single_category wow fadeInDown">
		<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text"><a href="<?= $feed['url']?>" rel="noopener noreferrer" target="_blank" ><?= $feed['title'] ?></a></span> </h2>
		<div class="archive_style_1">
			<?php foreach ($feed['items'] as $data): ?>
				<div class="business_category_left wow fadeInDown">
					<ul class="fashion_catgnav">
						<li>
							<div class="catgimg2_container2">
								<h5 class="catg_titile">
									<a href="<?= $data['LINK'] ?>" rel="noopener noreferrer" target="_blank" ><?= $data["TITLE"] ?></a>
								</h5>
								<div class="post_commentbox">
									<span class="meta_date"><?= gmdate("d-M-Y H:i:s", $data['PUBDATE']) ?>&nbsp;
										<i class="fa fa-user"></i> <?= $data['DC:CREATOR'] ?>&nbsp;
										<i class='fa fa-tag'></i> <?= $data['CATEGORY'] ?>
									</span>
								</div>
								<div class="single_page_content">
									<div class="post">
										<div style="text-align: justify;">
											<?php $deskripsi = substr($data['DESCRIPTION'], 0, 450); ?>
											<?= $deskripsi ?> ...
											<a href="<?= $data['LINK'] ?>" rel="noopener noreferrer" target="_blank" >
												<button type="button" class="btn btn-info btn-block">Baca Selengkapnya <i class="fa fa-arrow-right"></i></button>
											</a>
										</div>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	