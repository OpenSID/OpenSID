<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<section class="content" id="maincontent">
  <div class="row">
    <div class="col-md-12">
      <input id="stat" type="hidden" value="<?=$lap?>">
      <div class="box box-info">
        <div class="box-header with-border" style="margin-bottom: 15px;">
          <h3 class="box-title">Daftar <?= $heading ?></h3>
        </div>
        <div style="margin-right: 1rem; margin-left: 1rem;">
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="peserta_program">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Program</th>
                  <th>Nama Peserta</th>
                  <th>Alamat</th>
                </tr>
              </thead>
              <tfoot>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
  $(document).ready(function() {

    var url = "<?= site_url($this->controller.'/ajax_peserta_program_bantuan')?>";
      table = $('#peserta_program').DataTable({
        'processing': true,
        'serverSide': true,
        "pageLength": 10,
        'order': [],
        "ajax": {
          "url": url,
          "type": "POST",
          "data": {stat: $('#stat').val()}
        },
        //Set column definition initialisation properties.
        "columnDefs": [
          {
            "targets": [ 0, 3 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
        'language': {
          'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
        },
        'drawCallback': function (){
            $('.dataTables_paginate > .pagination').addClass('pagination-sm no-margin');
        }
      });

  } );
</script>
