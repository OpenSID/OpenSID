<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<style type="text/css">
	img.icon {
		width: 50px;
		margin-right: 15px;
	}
</style>
<div class="single_page_area">
	<h2 class="post_titile">SDGs Desa</h2>
	<div class="box-body">
		<?php if ($evaluasi): ?>
			<div class="panel-group" id="kemendes-sdgs" role="tablist" aria-multiselectable="true">
				<?php foreach ($evaluasi as $index => $heading) : ?>
				<div class="panel panel-default">
					<div class="panel-heading" role="tab">
						<h4 class="panel-title">
							<a role="button" data-toggle="collapse" href="#<?= 'col'.$index ?>" aria-expanded="true">
								<img class="icon" src="<?= $heading['icon'] ?>" alt="Icon SDG"/><?= $heading['uraian'] ?>
							</a>
						</h4>
					</div>
					<div id="<?= 'col'.$index ?>" class="panel-collapse collapse  " role="tabpanel" aria-labelledby="headingOne">
						<div class="panel-body">
							<table class="table ">
								<?php foreach ($heading['sub'] as $list) : ?>
								<tr>
									<td><?= $list['uraian'] ?></td>
									<td><?= $list['value'] ?></td>
								</tr>
								<?php endforeach ?>

							</table>
						</div>
					</div>
				</div>
				<?php endforeach ?>
			</div>
		<?php else: ?>
			<b>Maaf. Halaman ini tidak dapat di akses.</b>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('#col0').collapse()
});
</script>

<style type="text/css">
.panel-heading a:before {
	font-family: 'Glyphicons Halflings';
	content: "\e114";
	float: right;
	transition: all 0.5s;
}

.panel-heading.active a:before {
	-webkit-transform: rotate(180deg);
	-moz-transform: rotate(180deg);
	transform: rotate(180deg);
}

.panel-group .panel {
	margin-bottom: 30px !important;
	border-radius: 0;
}
</style>