<div class="row">
	<div class="col-sm-6">
		<div class="dataTables_length">
			<form id="paging" action="<?= site_url("$this->controller/$func");?>" method="post" class="form-horizontal">
				<label>
					Tampilkan
					<select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
						<?php foreach ($set_page as $set): ?>
							<option value="<?=$set?>" <?php selected($per_page, $set); ?> ><?=$set?></option>
						<?php endforeach; ?>
					</select>
					Dari
					<strong><?= $paging->num_rows?></strong>
					Total Data
				</label>
			</form>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="dataTables_paginate paging_simple_numbers">
			<ul class="pagination">
				<?php if ($paging->start_link): ?>
					<li><a href="<?=site_url("$this->controller"); ($func !== 'index') and print("/$func");?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
				<?php endif; ?>
				<?php if ($paging->prev): ?>
					<li><a href="<?=site_url("$this->controller/$func/$paging->prev");?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
				<?php endif; ?>
				<?php for ($i=$paging->start_link; $i<=$paging->end_link; $i++): ?>
					<li <?=jecho($p, $i, "class='active'");?>><a href="<?= site_url("$this->controller/$func/$i");?>"><?= $i?></a></li>
				<?php endfor; ?>
				<?php if ($paging->next): ?>
					<li><a href="<?=site_url("$this->controller/$func/$paging->next");?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
				<?php endif; ?>
				<?php if ($paging->end_link): ?>
					<li><a href="<?=site_url("$this->controller/$func/$paging->end_link");?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>
