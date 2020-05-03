<script>
  $(function()
  {
    var keyword = <?= $keyword['in']?> ;
    $( "#cari" ).autocomplete(
    {
      source: keyword,
      maxShowItems: 10,
    });
  });
  $('document').ready(function()
  {
    $('select[name=pamong_ttd]').change(function(e)
    {
      $('select[name=jabatan_ttd]').val($(this).find(':selected').data('jabatan'));
    });
    $('select[name=pamong_ketahui]').change(function(e)
    {
      $('select[name=jabatan_ketahui]').val($(this).find(':selected').data('jabatan'));
    });
  });
</script>


<div class="box-header with-border">
  <a href="<?= site_url("{$this->controller}/dialog_cetak/$o")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Agenda Surat Masuk" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Agenda Surat Masuk"><i class="fa fa-print "></i> Cetak</a>
  <a href="<?= site_url("{$this->controller}/dialog_unduh/$o")?>" title="Unduh Agenda Surat Keluar" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Agenda Surat Masuk" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Agenda Surat Masuk"><i class="fa fa-download"></i> Unduh</a>
</div>

<div class="box-body">
  <div class="row">
    <div class="col-sm-12">
      <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
        <form id="mainform" name="mainform" action="" method="post">
          <div class="row">
            <div class="col-sm-6">
              <select class="form-control input-sm " name="filter" onchange="formAction('mainform','<?= site_url($this->controller.'/filter')?>')">
                <option value="">Tahun Penerimaan</option>
                <?php foreach ($tahun_penerimaan as $tahun): ?>
                  <option value="<?= $tahun['tahun']?>" <?php selected($filter, $tahun['tahun']) ?>><?= $tahun['tahun']?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-6">
              <div class="box-tools">
                <div class="input-group input-group-sm pull-right">
                  <input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url("surat_masuk/search")?>');$('#'+'mainform').submit();}">
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("surat_masuk/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="table-responsive">
                <table class="table table-bordered dataTable table-hover">
                  <thead class="bg-gray disabled color-palette">
                    <tr>
                      <?php if ($o==2): ?>
                        <th class="nostretch"><a href="<?= site_url("surat_masuk/index/$p/1")?>">No. Urut <i class='fa fa-sort-asc fa-sm'></i></a></th>
                      <?php elseif ($o==1): ?>
                        <th class="nostretch"><a href="<?= site_url("surat_masuk/index/$p/2")?>">No. Urut <i class='fa fa-sort-desc fa-sm'></i></a></th>
                      <?php else: ?>
                        <th class="nostretch"><a href="<?= site_url("surat_masuk/index/$p/1")?>">No. Urut <i class='fa fa-sort fa-sm'></i></a></th>
                      <?php endif; ?>
                      <?php if ($o==4): ?>
                        <th><a href="<?= site_url("surat_masuk/index/$p/3")?>">Tanggal Penerimaan <i class='fa fa-sort-asc fa-sm'></i></a></th>
                      <?php elseif ($o==3): ?>
                        <th><a href="<?= site_url("surat_masuk/index/$p/4")?>">Tanggal Penerimaan <i class='fa fa-sort-desc fa-sm'></i></a></th>
                      <?php else: ?>
                        <th><a href="<?= site_url("surat_masuk/index/$p/3")?>">Tanggal Penerimaan <i class='fa fa-sort fa-sm'></i></a></th>
                      <?php endif; ?>
                      <th>Nomor Surat</th>
                      <th>Tanggal Surat</th>
                      <?php if ($o==6): ?>
                        <th nowrap><a href="<?= site_url("surat_masuk/index/$p/5")?>">Pengirim <i class='fa fa-sort-asc fa-sm'></i></a></th>
                      <?php elseif ($o==5): ?>
                        <th nowrap><a href="<?= site_url("surat_masuk/index/$p/6")?>">Pengirim <i class='fa fa-sort-desc fa-sm'></i></a></th>
                      <?php else: ?>
                        <th nowrap><a href="<?= site_url("surat_masuk/index/$p/5")?>">Pengirim <i class='fa fa-sort fa-sm'></i></a></th>
                      <?php endif; ?>
                      <th width="30%">Isi Singkat</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($main['in'] as $data): ?>
                      <tr>
                        <td><?= $data['nomor_urut']?></td>
                        <td nowrap><?= tgl_indo_out($data['tanggal_penerimaan'])?></td>
                        <td nowrap><?= $data['nomor_surat']?></td>
                        <td nowrap><?= tgl_indo_out($data['tanggal_surat'])?></td>
                        <td nowrap><?= $data['pengirim']?></td>
                        <td><?= $data['isi_singkat']?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6">
            <div class="dataTables_length">
              <form id="paging" action="<?= site_url("surat_masuk")?>" method="post" class="form-horizontal">
                <label>
                  Tampilkan
                  <select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
                    <option value="20" <?php selected($per_page, 20); ?> >20</option>
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
                  <li><a href="<?=site_url("surat_masuk/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
                <?php endif; ?>
                <?php if ($paging->prev): ?>
                  <li><a href="<?=site_url("surat_masuk/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                <?php endif; ?>
                <?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
                  <li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("surat_masuk/index/$i/$o")?>"><?= $i?></a></li>
                <?php endfor; ?>
                <?php if ($paging->next): ?>
                  <li><a href="<?=site_url("surat_masuk/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                <?php endif; ?>
                <?php if ($paging->end_link): ?>
                  <li><a href="<?=site_url("surat_masuk/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>  
  </div>
</div>
