										<div class="row">
											<div class="col-sm-6">
												<div class="dataTables_length">
													<form id="paging" action="<?= site_url("komentar")?>" method="post" class="form-horizontal">
														<label>
														Tampilkan
														<select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
															<option value="20" <?php selected($per_page, 1); ?> >20</option>
															<option value="50" <?php selected($per_page, 50); ?> >50</option>
															<option value="100" <?php selected($per_page, 100); ?> >100</option>
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
														<li><a href="<?=site_url("$this->modul/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
													<?php endif; ?>
													<?php if ($paging->prev): ?>
														<li><a href="<?=site_url("$this->modul/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
													<?php endif; ?>
													<?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
														<li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("$this->modul/index/$i/$o")?>"><?= $i?></a></li>
													<?php endfor; ?>
													<?php if ($paging->next): ?>
														<li><a href="<?=site_url("$this->modul/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
													<?php endif; ?>
													<?php if ($paging->end_link): ?>
														<li><a href="<?=site_url("$this->modul/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
													<?php endif; ?>
													</ul>
												</div>
											</div>
										</div>