<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<style type="text/css">
    <?php include(FCPATH . "/assets/css/dukcapil.css"); ?>
</style>

<page orientation="portrait" format="210x330" style="font-size: 7pt">
    <table align="right" style="padding: 5px 20px; border: solid 1px black;">
        <tr><td><strong style="font-size: 14pt;">F-1.02</strong></td></tr>
    </table>
    <p style="text-align: center; margin-top: 40px;">
        <strong style="font-size: 10pt;">FORMULIR PENDAFTARAN PERISTIWA KEPENDUDUKAN</strong>
    </p>
    <table class="disdukcapil" style="margin-top: 0px; border: 0px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td class="tengah">I.</td>
            <td colspan="47">DATA PELAPOR:</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="tengah">1.</td>
            <td colspan="18">NAMA LENGKAP</td>
            <td class="kanan"> : </td>
            <td colspan="27" class="kotak"><?= $input['nama_pelapor'] ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="tengah">2.</td>
            <td colspan="18">No. Induk Kependudukan</td>
            <td class="kanan"> : </td>
            <td colspan="27" class="kotak"><?= $input['nik_pelapor'] ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="tengah">3.</td>
            <td colspan="18">Nomor Kartu Keluarga</td>
            <td class="kanan"> : </td>
            <td colspan="27" class="kotak"><?= $input['no_kk_pelapor'] ?></td>
        </tr>

        <tr><td>&nbsp;</td></tr>
        <tr>
            <td class="tengah">II.</td>
            <td colspan="47">JENIS PERMOHONAN:</td>
        </tr>
    </table>

    <table style="border-collapse: collapse;" class="disdukcapil">
        <col style="width: 5%;">
        <col style="width: 20%;">
        <col style="width: 5%;">
        <col style="width: 20%;">
        <col style="width: 5%;">
        <col style="width: 20%;">
        <col style="width: 5%;">
        <col style="width: 20%;">

        <tr>
            <td class="judul tengah">I</td>
            <td class="judul">KARTU KELUARGA</td>
            <td class="judul tengah">II</td>
            <td class="judul">KTP-el</td>
            <td class="judul tengah">III</td>
            <td class="judul">KARTU IDENTITAS ANAK</td>
            <td class="judul tengah">IV</td>
            <td class="judul">PERUBAHAN DATA</td>
        </tr>
        <tr>
            <td class="judul tengah">A</td>
            <td class="judul">BARU</td>
            <td class="judul tengah">A</td>
            <td class="judul">BARU</td>
            <td class="judul tengah">A</td>
            <td class="judul">BARU</td>
            <td class="judul tengah">A</td>
            <td class="judul">KK</td>
        </tr>
        <tr>
            <td class="judul tengah">1</td>
            <td class="judul">Membentuk Keluarga Baru</td>
            <td class="judul">&nbsp;</td>
            <td class="judul">&nbsp;</td>
            <td class="judul">&nbsp;</td>
            <td class="judul">&nbsp;</td>
            <td class="judul">&nbsp;</td>
            <td class="judul">&nbsp;</td>
        </tr>
        <tr>
            <td class="judul tengah">2</td>
            <td class="judul">Penggantian Kepala Keluarga</td>
            <td class="judul tengah">B</td>
            <td class="judul">PINDAH DATANG</td>
            <td class="judul tengah">B</td>
            <td class="judul">HILANG / RUSAK</td>
            <td class="judul tengah">B</td>
            <td class="judul">KTP-el</td>
        </tr>
        <tr>
            <td class="judul tengah">3</td>
            <td class="judul">Pisah KK</td>
            <td class="judul tengah"></td>
            <td class="judul"></td>
            <td class="judul tengah">1</td>
            <td class="judul">Hilang</td>
            <td class="judul tengah"></td>
            <td class="judul"></td>
        </tr>
        <tr>
            <td class="judul tengah">4</td>
            <td class="judul">Pindah Datang</td>
            <td class="judul tengah">C</td>
            <td class="judul">HILANG / RUSAK</td>
            <td class="judul tengah">2</td>
            <td class="judul">RUSAK</td>
            <td class="judul tengah">C</td>
            <td class="judul">KIA</td>
        </tr>
        <tr>
            <td class="judul tengah">5</td>
            <td class="judul">WNI dan LN karena Pindah</td>
            <td class="judul tengah">1</td>
            <td class="judul">Hilang</td>
            <td class="judul tengah"></td>
            <td class="judul"></td>
            <td class="judul tengah" rowspan="9"></td>
            <td class="judul atas" rowspan="9" valign="top">
                <br />Melampirkan:<br />
                <div style="margin-left: -20px;">
                    <ol>
                        <li>Formulir Perubahan Data; dan</li>
                        <li>Bukti Perubahan Data</li>
                    </ol>
                </div>
            </td>
        </tr>
        <tr>
            <td class="judul tengah">6</td>
            <td class="judul">Rentan Adminduk</td>
            <td class="judul tengah">2</td>
            <td class="judul">Rusak</td>
            <td class="judul tengah">C</td>
            <td class="judul">Perpanjang ITAP</td>
        </tr>
        <tr>
            <td class="judul tengah">B</td>
            <td class="judul">PERUBAHAN DATA</td>
            <td class="judul tengah">&nbsp;</td>
            <td class="judul">&nbsp;</td>
            <td class="judul tengah">&nbsp;</td>
            <td class="judul">&nbsp;</td>
        </tr>
        <tr>
            <td class="judul tengah">1</td>
            <td class="judul">Menumpang dalam KK</td>
            <td class="judul tengah">D</td>
            <td class="judul">Perpanjang ITAP</td>
            <td class="judul tengah">D</td>
            <td class="judul">Lainnya</td>
        </tr>
        <tr>
            <td class="judul tengah">2</td>
            <td class="judul">Peristiwa Penting</td>
            <td class="judul tengah">&nbsp;</td>
            <td class="judul">&nbsp;</td>
            <td class="judul tengah">&nbsp;</td>
            <td class="judul">&nbsp;</td>
        </tr>
        <tr>
            <td class="judul tengah">3</td>
            <td class="judul">Perubahan elemen data yang tercantum dalam KK</td>
            <td class="judul tengah">E</td>
            <td class="judul">PERUBAHAN STATUS KEWARGANEGARAAN</td>
            <td class="judul tengah"></td>
            <td class="judul"></td>
        </tr>
        <tr>
            <td class="judul tengah">C</td>
            <td class="judul">HILANG / RUSAK</td>
            <td class="judul tengah">F</td>
            <td class="judul">LUAR DOMISILI</td>
            <td class="judul tengah"></td>
            <td class="judul"></td>
        </tr>
        <tr>
            <td class="judul tengah">1</td>
            <td class="judul">Hilang</td>
            <td class="judul tengah"></td>
            <td class="judul"></td>
            <td class="judul tengah"></td>
            <td class="judul"></td>
        </tr>
        <tr>
            <td class="judul tengah">2</td>
            <td class="judul">Rusak</td>
            <td class="judul tengah">G</td>
            <td class="judul">TRANSMIGRASI</td>
            <td class="judul tengah"></td>
            <td class="judul"></td>
        </tr>
    </table>

    <table class="disdukcapil" style="margin-top: 0px; border: 0px;">
        <col span="48" style="width: 2.0833%;">

        <tr><td>&nbsp;</td></tr>
        <tr>
            <td class="tengah">III.</td>
            <td colspan="47">PERSYARATAN YANG DILAMPIRKAN:</td>
        </tr>
    </table>

    <table style="border-collapse: collapse;" class="disdukcapil">
        <col style="width: 5%;">
        <col style="width: 35%;">
        <col style="width: 5%;">
        <col style="width: 5%;">
        <col style="width: 50%;">

        <tr>
            <td class="tengah"><span style="font-size: 14pt;">O</span></td>
            <td>KK Lama / KK Rusak</td>
            <td>&nbsp;</td>
            <td class="tengah"><span style="font-size: 14pt;">O</span></td>
            <td>Surat keterangan/bukti perubahan Peristiwa Kependudukan dan Peristiwa Penting.</td>
        </tr>
        <tr>
            <td class="tengah"><span style="font-size: 14pt;">O</span></td>
            <td>Buku nikah / kutipan akta perkawinan</td>
            <td>&nbsp;</td>
            <td class="tengah"><span style="font-size: 14pt;">O</span></td>
            <td>SPTJM perkawinan / perceraian belum tercatat</td>
        </tr>
        <tr>
            <td class="tengah"><span style="font-size: 14pt;">O</span></td>
            <td>Kutipan akta perceraian</td>
            <td>&nbsp;</td>
            <td class="tengah"><span style="font-size: 14pt;">O</span></td>
            <td>Akta Kematian</td>
        </tr>
        <tr>
            <td class="tengah"><span style="font-size: 14pt;">O</span></td>
            <td>Surat Keterangan Pindah</td>
            <td>&nbsp;</td>
            <td class="tengah"><span style="font-size: 14pt;">O</span></td>
            <td>Surat pernyataan penyebab terjadinya hilang atau rusak</td>
        </tr>
        <tr>
            <td class="tengah"><span style="font-size: 14pt;">O</span></td>
            <td>Surat Keterangan Pindah Luar Negeri</td>
            <td>&nbsp;</td>
            <td class="tengah"><span style="font-size: 14pt;">O</span></td>
            <td>Surat Keterangan Pindah dari Perwakilan RI</td>
        </tr>
        <tr>
            <td class="tengah"><span style="font-size: 14pt;">O</span></td>
            <td>Dokumen Perjalanan</td>
            <td>&nbsp;</td>
            <td class="tengah"><span style="font-size: 14pt;">O</span></td>
            <td>Surat kuasa pengasuhan anak dari orang tua / wali</td>
        </tr>
        <tr>
            <td class="tengah"><span style="font-size: 14pt;">O</span></td>
            <td>Surat keterangan hilang dari kepolisian</td>
            <td>&nbsp;</td>
            <td class="tengah"><span style="font-size: 14pt;">O</span></td>
            <td>Kartu izin tinggal tetap</td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
    </table>

    <table class="ttd" style="margin-top: 15px">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 30%; text-align: center; height: 18px;">Mengetahui :</td>
                <td style="width: 40%; height: 18px;"> </td>
                <td style="width: 30%; text-align: center; height: 18px;">[NAma_desa], [TgL_surat]</td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 30%; text-align: center; height: 18px;">Petugas,</td>
                <td style="width: 40%; height: 18px;"> </td>
                <td style="width: 30%; text-align: center; height: 18px;">Pelapor,</td>
            </tr>
            <tr style="height: 72px;">
                <td style="width: 30%; text-align: center; height: 72px;"> </td>
                <td style="width: 40%; height: 72px;"><br><br><br><br></td>
                <td style="width: 30%; height: 72px;"> </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 30%; text-align: center; height: 18px;"><strong>(<?= str_pad('.', 50, '.', STR_PAD_LEFT); ?>)</strong></td>
                <td style="width: 40%; height: 18px;"> </td>
                <td style="width: 30%; text-align: center; height: 18px;"><strong>(<?= padded_string_center(strtoupper($input['nama_pelapor']), 30) ?>)</strong></td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 30%; height: 18px;"> </td>
                <td style="width: 40%; height: 18px;"> </td>
                <td style="width: 30%; text-align: center; height: 18px;"> </td>
            </tr>
        </tbody>
    </table>
</page>
