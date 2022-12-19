<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if(is_array($komentar) && $single_artikel['boleh_komentar']) : ?>
  <?php
    $comments = array();
    foreach ($komentar as $comment ) {
      if($comment['is_archived'] != 1) array_push($comments, $comment);
    }
    $comments = array_reverse($comments);
    $forms = array(
      'owner' => 'Nama',
      'email' => 'Alamat Email',
      'no_hp' => 'No. HP'
    )
  ?>

  <?php $notif = $this->session->flashdata('notif'); ?>
  
  <?php if(count($comments) > 0) : ?>
    <div class="accordion" id="semuaKomentar">
      <div class="accordion-item border-t-0 border-l-0 border-r-0 rounded-none bg-white border border-gray-200">
        <h2 class="accordion-header mb-0" id="komentarLabel">
          <button class="accordion-button relative flex items-center w-full py-4 text-h5 text-left bg-white border-0 rounded-none transition focus:outline-none" type="button" data-bs-toggle="collapse" data-bs-target="#listKomentar" aria-expanded="false" aria-controls="listKomentar">
            Semua Komentar
          </button>
        </h2>
        <div id="listKomentar" class="accordion-collapse border-0 collapse show" aria-labelledby="komentarLabel" data-bs-parent="#semuaKomentar">
          <div class="accordion-body py-4 px-3 lg:px-5 divide-y">
            <!-- List Komentar -->
            <?php foreach($comments as $comment) : ?>
              <div class="flex gap-x-3 py-4">
                <div class="h-10 lg:h-12 lg:w-12 w-10 inline-flex items-center justify-center bg-gray-200 rounded-full flex-shrink-0 text-secondary-100"><i class="fa fa-comments text-lg"></i></div>
                <div class="space-y-2">
                  <blockquote class="italic">"<?= $comment['komentar'] ?></blockquote>
                  <div class="space-y-1 space-x-3 text-xs lg:text-sm">
                    <span><i class="fa fa-user mr-1 text-accent-100"></i> <?= $comment['owner'] ?></span>
                    <span><i class="fa fa-calendar-alt mr-1 text-accent-100"></i> <?= tgl_indo($comment['tgl_upload']) ?></span>
                  </div>
                </div>
              </div>
            <?php endforeach ?>
          </div>
        </div>
      </div>
    </div>
  <?php endif ?>

  <?php if($single_artikel['boleh_komentar'] == 1) : ?>
    <form action="<?= site_url('/add_comment/'.$single_artikel['id'])?>" method="POST"class="space-y-4 pt-5 pb-4"  id="kolom-komentar">
      <h5 class="text-h5">Beri Komentar</h5>
      <div class="alert alert-info text-sm text-primary-100">Komentar baru terbit setelah disetujui oleh admin</div>

      <?php $alert = ($notif['status'] == -1) ? 'error' : 'success'; ?>
      <?php if ($flash_message = $notif['pesan']): ?>
        <div class="alert alert-<?= $alert ?> text-sm"><i class="<?php $notif['status'] != -1 and print('fas fa-check mr-2')  ?>"></i><?= $flash_message?></div>
      <?php endif; ?>

      <div class="space-y-2">
        <label for="komentar" class="text-xs lg:text-sm">Komentar <span style="color:red">*</span></label>
        <textarea class="form-textarea" name="komentar" id="komentar" cols="30" rows="4" required><?= $notif['data']['komentar'] ?></textarea>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        <?php foreach($forms as $name => $label) : ?>
          <div class="space-y-2">
            <label for="<?= $name ?>" class="text-xs lg:text-sm"><?= $label ?> <?php if($name !== 'email') : ?><span style="color:red">*</span><?php endif ?></label>
            <input type="text" class="form-input" id="<?= $name ?>" name="<?= $name ?>" value="<?= $name === 'owner' && !empty($notif['data']['nama']) ? $notif['data']['nama'] : $notif['data'][$name] ?>" <?php $name !== 'email' and print('required') ?>>
          </div>
        <?php endforeach ?>
      </div>
      <div class="flex flex-col lg:flex-row gap-3">
        <div class="group">
          <img id="captcha" src="<?= base_url('securimage/securimage_show.php') ?>" alt="CAPTCHA Image" class="max-w-full h-auto">
          <button type="button" class="hover:text-link text-xs lg:text-sm" onclick="document.getElementById('captcha').src = '<?= base_url("securimage/securimage_show.php?")?>'+Math.random(); return false">[Ganti Gambar]</button>
        </div>
        <input type="text" name="captcha_code" class="form-input" placeholder="Isikan jawaban">
      </div>
      <button type="submit" class="btn btn-secondary">Kirim Komentar <i class="fas fa-paper-plane ml-2"></i></button>
    </form>
  <?php endif ?>
<?php endif; ?>