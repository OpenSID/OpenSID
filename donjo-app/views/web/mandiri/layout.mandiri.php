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
<div class="modal fade" id="lapor_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header  bg-primary ">
        <h3 class="text-center"><i class="fa fa-user"></i> Form Pelaporan</h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <form id="validasi" class="form-horizontal">
            <div class="row">
              <div class="form-group">
                <label for="pengirim" class="col-sm-3 control-label" style="text-align:right;">Pengirim : </label>
                <div class="col-sm-6">
                  <input type="text" id="pengirim" class="form-control" readonly="readonly" name="owner" value="<?= $_SESSION['nama'] ?>">
                </div>
              </div>
              <div class="form-group">
                <label for="nik" class="col-sm-3 control-label" style="text-align:right;">NIK :</label>
                <div class="col-sm-6">
                  <input type="text" id="nik" class="form-control" readonly="readonly" name="email" value="<?= $_SESSION['nik'] ?>">
                </div>
              </div>
              <hr style="height:1.5px;border:none;color:#d2d6de;background-color:#d2d6de;" />
            </div>
            <div class="form-group" id="cek">
              <label for="Komentar" class="col-form-label">
                <span class="lead">Isi Laporan</span> Penjelasan dan Isi Laporan
              </label>
              <textarea id="komentar" class="form-control is-invalid" rows="10" name="komentar" placeholder="Isi laporan anda"></textarea>
              <span id="error" class="help-block"></span>
            </div>
          </form>
          <div class="modal-footer">
            <div class="row">
              <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-times"></i>Batal</button>
              <button type="button" class="btn btn-info pull-left" id="reset"><i class="fa fa-undo"></i>Reset</button>
              <button type="button" class="btn btn-primary pull-right" id="kirim"><i class="fa fa-sign-in"></i>Kirim</button>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script>
    var alamat = "<?= site_url('lapor_web/insert') ?>";
    var m = <?= $m;  ?>

    if (m == 3) {
      $('#lapor_modal').modal('show')
    }

    $('#lapor_modal').on('hide.bs.modal', function() {
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
        url: alamat,
        dataType: "JSON",
        data: $('#validasi').serialize(),
        success: function(hasil) {
          if (hasil['sukses'] == 1) {
            $('#lapor_modal').modal('hide');
            alert(hasil['pesan'])
          } else {
            $('#cek').addClass('has-error');
            $('#error').html(hasil['pesan']);
          }

        }
      })
    })
  </script>
