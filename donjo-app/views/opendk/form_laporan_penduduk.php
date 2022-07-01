<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modal form di modul Keuangan > Laporan APBDes
 *
 * donjo-app/views/opendk/modal_form_laporan_apbdes.php
 */

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

<?php $this->load->view('global/validasi_form'); ?>
<form id="validasi" action="<?= $form_action; ?>" method="POST" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="form-group">
            <label for="judul">Judul</label>
            <input type="text" class="form-control input-sm required" id="judul" name="judul" value="<?= $main->judul; ?>" placeholder="Judul"  <?= jecho($main->kirim, true, 'disabled')?>/>
        </div>

        <div class="form-group">
            <label for="tahun">Tahun</label>
            <input type="text" class="form-control input-sm required" id="tahun" name="tahun" value="<?= $main->tahun; ?>" placeholder="Tahun"  <?= jecho($main->kirim, true, 'disabled')?>/>
        </div>

        <div class="form-group">
            <label for="bulan">Bulan</label>
            <select class="form-control input-sm select2 required" id="bulan" name="bulan" <?= jecho($main->kirim, true, 'disabled')?>>
                <?php foreach (bulan() as $key => $nama_bulan): ?>
                    <option value="<?= $key; ?>" <?= selected($bulan, $key); ?>><?= $nama_bulan; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="file" >File : <code>(.pdf)</code></label>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control" id="file_path" name="satuan">
                <input type="file" class="hidden <?= jecho($main, false, 'required'); ?>" id="file" name="nama_file">
                <?php if ($main): ?>
                    <input type="hidden" name="old_file" id="old_file" value="<?= $main->nama_file; ?>">
                <?php endif; ?>
                <span class="input-group-btn">
                    <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                </span>
            </div>
            <span class="help-block"><code>Kosongkan jika tidak ingin mengubah dokumen. Ukuran maksimal <strong><?= max_upload() ?> MB</strong>.</code></span>
        </div>
    </div>

    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
        <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="aksi"><i class="fa fa-check"></i> Simpan</button>
    </div>
</form>

