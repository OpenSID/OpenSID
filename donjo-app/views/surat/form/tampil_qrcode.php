<div class="form-group">
  <label class="col-sm-3 control-label" for="tampil_qrcode">Tampilkan QRCode di Surat</label>
  <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
    <label id="m1" class="tipe btn btn-info btn-flat btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label <?= jecho($tampil_qrcode, 1, 'active'); ?>">
      <input id="qrcode1" type="radio" name="tampil_qrcode" class="form-check-input" type="radio" value="1" <?= jecho($tampil_qrcode, 1, 'checked'); ?> autocomplete="off">Ya
    </label>
    <label id="m2" class="tipe btn btn-info btn-flat btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label <?= jecho($tampil_qrcode != 1, TRUE, 'active'); ?>">
      <input id="qrcode2" type="radio" name="tampil_qrcode" class="form-check-input" type="radio" value="0" <?= jecho($tampil_qrcode != 1, TRUE, 'checked'); ?> autocomplete="off">Tidak
    </label>
  </div>
</div>
<!-- <div id="qrcontent" <?= jecho($tampil_qrcode != 1, TRUE, 'style="display:none;"'); ?>>
  <div class="form-group" id='isi_qrcode'>
    <label class="col-sm-3 control-label" for="isi_qrcode">Pilih Jenis Isi QRCode</label>
    <div class="col-sm-6 col-lg-4">
      <select class="form-control input-sm" id="isiqr" name="isiqr" onchange="show_hide_isiqr($(this).find(':selected').val())">
        <?php foreach ($isi_qr as $kode => $jenis): ?>
          <option value="<?= $kode ?>" <?= selected($kode, $this->input->post('isiqr')) ?>><?= ucwords($jenis) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="form-group" id='isi_qrcode_manual' style="display:none;">
    <label class="col-sm-3 control-label" for="isi_qrcode_manual">Ketik Manual Isi QRCode</label>
    <div class="col-sm-6 col-lg-4">
      <textarea class="form-control input-sm tetap" rows="1" id="isiqr_manual" name="isiqr_manual" maxlength="300"></textarea>
    </div>
  </div>

  <div class="form-group" id='isi_qrcode_pilih' style="display:none;">
    <label for="isi_qrcode_pilih" class="col-sm-3 control-label">Pilih Data QRCode</label>
      <div class="dataTables_wrapper dt-bootstrap">
          <div class="row">
            <div class="col-sm-4">
              <div class="table-responsive">
                <table class="table table-bordered table-hover ">
                  <thead class="bg-gray disabled color-palette">
                    <tr>
                      <th><input type="checkbox" id="checkall"></th>
                      <th>Nama Data</th>
                      <th>Isi Data QRCode</th>
                    </tr>
                  </thead>
                  <tbody>
                      <tr>
                        <td><input type="checkbox" name="id_cb[]" value="<?= $individu['nik'] ?>" ></td>
                        <td>NIK</td>
                        <td><?= $individu['nik'] ?></td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" name="id_cb[]" value="<?= $individu['nama'] ?>" ></td>
                        <td>Nama</td>
                        <td><?= $individu['nama'] ?></td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" name="id_cb[]" value="<?= $format_nomor_surat ?>" ></td>
                        <td>No. Surat</td>
                        <td><?= $format_nomor_surat ?></td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" name="id_cb[]" value="<?= 'Surat '.$surat['nama'] ?>" ></td>
                        <td>Nama Surat</td>
                        <td><?= 'Surat '.$surat['nama'] ?></td>
                      </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
      </div>
  </div>

  <div class="form-group" id='warna_depan'>
    <label class="col-sm-3 control-label" for="warna_depan">Pilih Warna QRCode</label>
    <div class="col-sm-6 col-lg-4">
      <div class="input-group my-colorpicker2">
        <div class="input-group-addon input-sm">
          <i></i>
        </div>
        <input type="text" id="foreqr" name="foreqr" class="form-control input-sm" value="<?= $qrcode['foreqr'] ?>">
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	$(document).ready(function()
	{
    qrcontent($('input[name="tampil_qrcode"]:checked').val());
		$('input[name="tampil_qrcode"]').change(function() {
			qrcontent($(this).val());
		});
	});

  function qrcontent(tipe) {
		(tipe == '1' || tipe == null) ? $('#qrcontent').show() : $('#qrcontent').hide();
	}

	function show_hide_isiqr(isiqr)
	{
    switch (isiqr)
		{
      case '':
      $('#isi_qrcode_manual').hide();
      $('#isi_qrcode_pilih').hide();
      break;
			case '1':
      $('#isi_qrcode_manual').hide();
      $('#isi_qrcode_pilih').hide();
      break;
			case '2':
      $('#isi_qrcode_manual').hide();
      $('#isi_qrcode_pilih').show();
      break;
      case '3':
      $('#isi_qrcode_manual').show();
      $('#isi_qrcode_pilih').hide();
      break;
		}
	}
</script>
 -->