<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @copyright	  Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	  Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 *
 * @see 	https://github.com/OpenSID/OpenSID
 */
?>

<div class="box box-solid">
	<div class="box-header with-border bg-blue">
		<h4 class="box-title">DOKUMEN</h4>
	</div>
	<div class="box-body box-line">
		<div class="form-group">
			<a href="<?= site_url('layanan-mandiri/dokumen'); ?>" class="btn bg-aqua btn-social"><i class="fa fa-arrow-circle-left "></i>Kembali ke Dokumen</a>
		</div>
	</div>
	<div class="box-body box-line">
		<h4><b><?= $aksi ?> DOKUMEN</b></h4>
	</div>

	<div class="box-body">
    <?php $this->load->view('fmandiri/notifikasi') ?>
		<form id="validasi" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data">
      <input type="number" class="hidden" name="id_pend" value="<?= $this->is_login->id_pend ?>" />
      <div class="form-group">
        <label for="nama_dokumen">Nama Dokumen</label>
        <input id="nama_dokumen" name="nama" class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvtext'); ?>" type="text" placeholder="Nama Dokumen" value="<?= $dokumen['nama']?>"/>
      </div>
      <div class="form-group">
        <label for="jenis">Jenis Dokumen</label>
        <select class="form-control select2 required" name="id_syarat" id="id_syarat">
          <option value=""> -- Pilih Jenis Dokumen -- </option>
          <?php foreach ($jenis_syarat_surat as $data) : ?>
            <option value="<?= $data['ref_syarat_id'] ?>" <?= selected($data['ref_syarat_id'], $dokumen['id_syarat']) ?>><?= $data['ref_syarat_nama']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="file">File Dokumen</label>
        <div class="input-group">
          <input type="text" class="form-control <?= jecho($dokumen['id'], false, 'required') ?>" id="file_path" name="satuan" readonly/>
          <input type="file" class="hidden" id="file" name="satuan"/>
          <input type="hidden" name="old_satuan" value="<?= $dokumen['satuan'] ?>"/>
          <span class="input-group-btn">
            <button type="button" class="btn btn-danger btn-flat" onclick="kamera();"><i class="fa fa-camera"></i> Kamera</button>
            <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
          </span>
        </div>
      </div>
      <span class="help-block"><code>Kosongkan jika tidak ingin mengubah dokumen. Ukuran maksimal <strong><?= max_upload() ?> MB</strong>.</code></span>
      </hr>
      <?php if (! empty($kk)) : ?>
        <p><strong>Centang jika dokumen yang diupload berlaku juga untuk anggota keluarga di bawah ini. </strong></p>
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-data">
            <thead>
              <tr>
                <th>#</th>
                <th>NIK</th>
                <th>Nama</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($kk as $item) : ?>
                <?php if ($item['nik'] != $this->is_login->nik) : ?>
                  <tr>
                    <td class="padat"><input type='checkbox' name='anggota_kk[]' value="<?= $item['id'] ?>" <?= jecho(in_array($item['id'], $anggota), true, 'checked') ?>></td>
                    <td><?= $item['nik'] ?></td>
                    <td><?= $item['nama'] ?></td>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        </hr>
      <?php endif ?>
      <button type="submit" class="btn btn-social btn-info"><i class="fa fa-check"></i> Simpan</button>
		</form>
	</div>
</div>

<?php $this->load->view('global/capture') ?>
