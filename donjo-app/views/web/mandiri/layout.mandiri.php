<?php $this->load->view('web/mandiri/header_mandiri.php') ?>

<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-3">
        <div class="box box-info">
          <div class="box-header with-border">
          </div>
          <div class="box-body no-padding">
            <?php include('donjo-app/views/web/mandiri/layanan_mandiri.php'); ?>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="box box-info">
          <div class="box-body">
            <div class="row">
              <div class="col-sm-12">
                <?php
                $views_partial_layout = '';
                switch ($m) {
                  case 1:
                    $views_partial_layout = 'web/mandiri/mandiri.php';
                    break;
                  case 2:
                    $views_partial_layout = 'web/mandiri/layanan.php';
                    break;
                  case 4:
                    $views_partial_layout = 'web/mandiri/bantuan.php';
                    break;
                  default:
                    $views_partial_layout = 'web/mandiri/layanan.php';
                }
                $this->load->view($views_partial_layout);
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php $this->load->view('web/mandiri/footer_mandiri.php') ?>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header  bg-primary ">
        <h5 class="modal-title" id="exampleModalLabel">Lapor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="validasi">
          <p>Silakan laporkan perubahan data kependudukan anda.</p>

          <div class="form-group row">
            <label for="pengirim" class="col-sm-2 col-form-label">Pengirim</label>
            <div class="col-sm-10">
              <input type="text" id="pengirim" class="form-control" readonly="readonly" name="owner" value="<?= $_SESSION['nama'] ?>">
            </div>
          </div>

          <div class="form-group row">
            <label for="nik" class="col-sm-2 col-form-label">NIK</label>
            <div class="col-sm-10">
              <input type="text" id="nik" class="form-control" readonly="readonly" name="email" value="<?= $_SESSION['nik'] ?>">
            </div>
          </div>

          <div class="form-group row" id="cek">
            <label for="Komentar" class="col-sm-2 col-form-label">Laporan</label>
            <div class="col-sm-10">
              <textarea id="komentar" class="form-control is-invalid" name="komentar" placeholder="Isi laporan anda"></textarea>
              <span id="error" class="help-block"></span>
            </div>


          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="kirim">Kirim</button>
        <button type="button" class="btn btn-secondary" id="reset">Reset</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
      </div>
      </form>
    </div>
  </div>

  <script>
    <?php if ($m == 3) { ?>
      $('#exampleModal').modal('show')
    <?php }; ?>

    $('#exampleModal').on('hide.bs.modal', function() {
      $('#komentar').val('');
      $('#error').html('');
      $('#cek').removeClass('has-error');
    });

    $('#reset').on('click', function() {
      $('#komentar').val('');
      $('#error').html('');
      $('#cek').removeClass('has-error');
    });

    $('#komentar').on('keyup', function() {
      $('#error').html('');
      $('#cek').removeClass('has-error');
    })

    $('#kirim').on('click', function() {
      $.ajax({
        type: "post",
        url: "<?= site_url() ?>lapor_web/insert",
        dataType: "JSON",
        data: $('#validasi').serialize(),
        success: function(hasil) {
          if (hasil['sukses'] == 1) {
            $('#exampleModal').modal('hide')
          } else {
            $('#cek').addClass('has-error');
            $('#error').html(hasil['message']);
          }

        }
      })
    })
  </script>
