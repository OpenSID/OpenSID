<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!--
 Feed dari situs lain
-->
<?php if ($feed['items']): ?>
	<div id="feed" class="box box-primary" style="margin-left:.2	5em;">
		<div class="box-header with-border">
			<h3 class="box-title"><a href="<?= $feed['url']?>" target='_blank'><?= $feed['title'] ?></a></h3>
		</div>
		<div class="box-body">
			<div>
				<ul class="artikel-list artikel-list-in-box">
					<?php foreach ($feed['items'] as $data): ?>
						<li class="artikel feed">
							<h3 class="judul">
								<a href="<?= $data['LINK'] ?>" target="_blank"><?= $data["TITLE"] ?></a>
							</h3>
							<div class="teks_feed">
								<div class="kecil">
									<i class="fa fa-clock-o"></i> <?= gmdate("d-M-Y H:i:s", $data['PUBDATE']) ?> | 
									<i class="fa fa-user"></i> <?= $data['DC:CREATOR'] ?> | 
									<i class='fa fa-tag'></i> <?= $data['CATEGORY'] ?>
								</div>
								<?= str_split($data['DESCRIPTION'], 300)[0] ?>
								<a href="<?= $data['LINK'] ?>"> ..selengkapnya</a>
							</div>
							<br class="clearboth gb"/>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>	
	</div>
<?php endif; ?>
