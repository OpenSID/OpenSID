<div class="form-group group-komentar" id="kolom-komentar">
  <?php if ($single_artikel['boleh_komentar'] == 1) : ?>
    <div class="box box-default">
      <div class="box-header">
        <h2 class="box-title">Kirim Komentar</h2>
      </div>
      <hr />
      <?php
      $notif = $this->session->flashdata('notif');
      $label = ($notif['status'] == -1) ? 'label-danger' : 'label-info';
      ?>
      <?php if ($notif) : ?>
        <div class="box-header <?= $label ?>"><?= $notif['pesan'] ?></div>
      <?php endif ?>
      <div class="contact_bottom">
        <form class="contact_form form-komentar" id="validasi" name="form" action="<?= site_url("add_comment/{$single_artikel['id']}") ?>" method="POST" onSubmit="return validasi(this);">
          <table width="100%">
            <tr class="komentar nama">
              <td width="20%">Nama</td>
              <td>
                <input class="form-group required" type="text" name="owner" maxlength="50" placeholder="ketik di sini" value="<?= $notif['data']['owner'] ?>">
              </td>
            </tr>
            <tr class="komentar alamat">
              <td>No. Hp</td>
              <td>
                <input class="form-group number required" type="text" name="no_hp" maxlength="15" placeholder="ketik di sini" value="<?= $notif['data']['no_hp'] ?>">
              </td>
            </tr>
            <tr class="komentar alamat">
              <td>E-mail</td>
              <td>
                <input class="form-group email" type="text" name="email" maxlength="50" placeholder="email@gmail.com" value="<?= $notif['data']['email'] ?>">
              </td>
            </tr>
            <tr class="komentar pesan">
              <td valign="top">Isi Pesan</td>
              <td>
                <textarea class="required" name="komentar"><?= $notif['data']['komentar'] ?></textarea>
              </td>
            </tr>
            <tr class="captcha">
              <td>&nbsp;</td>
              <td>
                <a href="#" onclick="document.getElementById('captcha').src = '<?= site_url('captcha') ?>'; return false" style="color: #000000;">
                  <img id="captcha" src="<?= site_url('captcha') ?>" alt="CAPTCHA Image" />
                </a>
                &nbsp;
                <input type="text" name="captcha_code" class="required" maxlength="6" placeholder="Masukkan kode diatas"/>
              </td>
            </tr>
            <tr class="submit">
              <td>&nbsp;</td>
              <td><input type="submit" value="Kirim"></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  <?php else : ?>
    <span class='info'></span>
  <?php endif ?>
</div>