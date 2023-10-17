<style type="text/css">
  #info_publik_wrapper .row .col-sm-6 {
    width: 50% !important;
  }

  .swal-lg {
    width: 1000px !important;
  }

  @media (max-width: 1000px) {
    .swal-lg {
      width: 100%;
    }
  }
</style>
<div class="content py-1">
  <div class="box box-danger" style="padding-bottom: 2rem;">
    <div class="box-header with-border" style="margin-bottom: 15px;">
      <h3 class="box-title"><?= $heading ?></h3>
    </div>
    <div style="margin-right: 1rem; margin-left: 1rem;">
      <div class="table-responsive">
        <table class="table table-striped table-bordered" id="info_publik">
          <thead>
            <tr>
              <th>No</th>
              <th>Judul Informasi</th>
              <th>Tahun</th>
              <th>Kategori</th>
              <th>Tanggal Upload</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tfoot>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
<script src="<?= asset('js/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<link rel="stylesheet" href="<?= asset('js/sweetalert2/sweetalert2.min.css') ?>">
<script type="text/javascript">
  $(document).ready(function() {
    var url = "<?= site_url('informasi-publik/data') ?>";
    table = $('#info_publik').DataTable({
      'processing': true,
      'serverSide': true,
      "pageLength": 10,
      'order': [],
      "ajax": {
        "url": url,
        "type": "POST"
      },
      //Set column definition initialisation properties.
      "columnDefs": [{
        "targets": [0], //first column / numbering column
        "orderable": false, //set not orderable
      }, ],
      'language': {
        'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
      },
      'drawCallback': function(event) {
        $('.dataTables_paginate > .pagination').addClass('pagination-sm no-margin');
        if (event.iDraw == 1) {
          $('#info_publik_wrapper').on('click', 'table tr a.pdf', function(e) {
            e.preventDefault();
            var attr = $(this).attr('href');
            Swal.fire({
              customClass: {
                popup: 'swal-lg',
              },
              title: 'Lihat',
              html: `<object data="${attr}" style="width: 100%; min-height: 400px;" ></object>`,
              showCancelButton: true,
              cancelButtonText: 'Tutup',
              showConfirmButton: false,
            })
          })
        }
      }
    });
  });
</script>