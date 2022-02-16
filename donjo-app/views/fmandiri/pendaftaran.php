<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View modul Layanan Mandiri > Pendaftaran akun Layanan Mandiri
 *
 * donjo-app/views/fmandiri/pendaftaran.php
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

<div>
    <h2><strong>PENDAFTARAN AKUN LAYANAN MANDIRI</strong></h2>
    <div class="row">
        <div class="col-12">
            <div class="col-md-6">
                <div class="form-group form-daftar">
                    <input type="text" autocomplete="off" class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber'); ?>" name="daftar_nama" placeholder="Nama">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="row date form-daftar">
                        <input placeholder="Tanggal Lahir" type="text" class="form-control pull-right required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber'); ?>" id="daftar_tgl_lahir" name="daftar_tgl_lahir" style="border-top-right-radius: 0px; border-bottom-right-radius: 0px;" onchange="myFunction()" autocomplete="off">
                        <div class="input-group-addon" style="height: 35px; width:40px; display: flex; align-items: center;">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-top-fmandiri">
        <div class="col-12">
            <div class="col-md-6">
                <div class="form-group form-daftar" style="margin-right: -10px">
                    <input type="text" autocomplete="off" class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber'); ?>" name="daftar_nik" placeholder="NIK" minlength="16" maxlength="16">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="row form-daftar">
                        <input type="password" class="form-control input-md bilangan pin required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber'); ?>" name="daftar_pin1" id="daftar_pin1" placeholder="PIN" minlength="6" maxlength="6" style="border-top-right-radius: 0px; border-bottom-right-radius: 0px;">
                        <span class="input-group-addon" style="height: 35px; width:40px; display: flex; align-items: center;">
                            <i class="fa fa-eye-slash" id="baru1" onclick="show(this);" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-top-fmandiri">
        <div class="col-12">
            <div class="col-md-6">
                <div class="form-group form-daftar">
                    <input type="text" autocomplete="off" class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber'); ?>" name="daftar_kk" placeholder="KK" minlength="16" maxlength="16">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="row form-daftar">
                        <input type="password" class="form-control input-md bilangan pin required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber'); ?>" name="daftar_pin2" id="daftar_pin2" placeholder="Konfirmasi PIN" minlength="6" maxlength="6" style="border-top-right-radius: 0px; border-bottom-right-radius: 0px;">
                        <span class="input-group-addon" style="height: 35px; width:40px; display: flex; align-items: center;">
                            <i class="fa fa-eye-slash" id="baru2" onclick="show(this);" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: -10px;">
        <div class="col-md-12">
            <div class="form-group">
                <div class="row input-group-sm form-daftar">
                    <input type="text" class="form-control required" id="file_path1" placeholder="Unggah Scan KTP" style="border-top-right-radius: 0px; border-bottom-right-radius: 0px;">
                    <input id="file1" type="file" class="hidden required" name="scan_1">
                    <span class="">
                        <button type="button" class="btn btn-info btn-flat" id="file_browser1" style="height: 35px; border-radius: 0px; display: flex; align-items: center;"><i class="fa fa-search"></i>&nbsp;</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: -10px;">
        <div class="col-md-12">
            <div class="form-group">
                <div class="row input-group-sm form-daftar">
                    <input type="text" class="form-control required" id="file_path2" placeholder="Unggah Scan KK" style="border-top-right-radius: 0px; border-bottom-right-radius: 0px;">
                    <input id="file2" type="file" class="hidden required form-daftar" name="scan_2">
                    <span class="">
                        <button type="button" class="btn btn-info btn-flat" id="file_browser2" style="height: 35px; border-radius: 0px; display: flex; align-items: center;"><i class="fa fa-search"></i>&nbsp;</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: -10px;">
        <div class="col-md-12">
            <div class="form-group">
                <div class="row input-group-sm form-daftar">
                    <input type="text" class="form-control required" id="file_path3" placeholder="Unggah Foto Selfie dan Membawa KTP" style="border-top-right-radius: 0px; border-bottom-right-radius: 0px;">
                    <input id="file3" type="file" class="hidden required form-daftar" name="scan_3">
                    <span class="">
                        <button type="button" class="btn btn-info btn-flat" id="file_browser3" style="height: 35px; border-radius: 0px; display: flex; align-items: center;"><i class="fa fa-search"></i>&nbsp;</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <small style="color: crimson; font-size: 12px;">Gambar ukuran maksimal : 1024kb, Tipe gambar : gif,jpg,jpeg,png </small>
    <div class="form-group">
        <div class="form-group">
            <button type="submit" class="btn btn-block bg-green"><b>BUAT AKUN</b></button>
        </div>
    </div>
    <div class="form-group">
        <a href="<?= site_url('layanan-mandiri/masuk') ?>">
            <button type="button" class="btn btn-block bg-green"><b>SUDAH PUNYA AKUN</b></button>
        </a>
    </div>
</div>

<!-- Pesan Dialog-->
<?php $info = $this->session->flashdata('info_pendaftaran'); ?>
<div class="modal fade" id="informasi" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Informasi</h4>
            </div>
            <div class="modal-body">
                <?php if ($info['status'] == 1) : ?>
                    <p>Daftar Akun menggunakan NIK : <b><?= $info['nik']; ?></b></p>
                    <p>Silahkan dicatat pendaftaran di Layanan Mandiri dengan kode PIN : <b><?= $info['pin']; ?></b></p>
                    <p><?= $info['pesan']; ?></p>
                    <a href="<?= $info['aksi'] ?>">
                        <button type="button" class="btn btn-block bg-green"><b>VERIFIKASI</b></button>
                    </a>
                <?php elseif ($info['status'] == 0) : ?>
                    <p><?= $info['pesan']; ?></p>
                    <a href="<?= $info['aksi'] ?>">
                        <button type="button" class="btn btn-block bg-green"><b>MASUK</b></button>
                    </a>
                <?php elseif ($info['status'] == -1) : ?>
                    <p><?= $info['pesan']; ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    $('#file_browser1').click(function(e) {
        e.preventDefault();
        $('#file1').click();
    });
    $('#file1').change(function() {
        $('#file_path1').val($(this).val());
    });
    $('#file_path1').click(function() {
        $('#file_browser1').click();
    });

    $('#file_browser2').click(function(e) {
        e.preventDefault();
        $('#file2').click();
    });
    $('#file2').change(function() {
        $('#file_path2').val($(this).val());
    });
    $('#file_path2').click(function() {
        $('#file_browser2').click();
    });

    $('#file_browser3').click(function(e) {
        e.preventDefault();
        $('#file3').click();
    });
    $('#file3').change(function() {
        $('#file_path3').val($(this).val());
    });
    $('#file_path3').click(function() {
        $('#file_browser3').click();
    });
</script>