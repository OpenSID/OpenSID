<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Traits;

trait GenerateRtf
{
    private function buat_berkas_kk($data = [], $format = null): ?string
    {
        $path_arsip = LOKASI_ARSIP;
        $file       = DEFAULT_LOKASI_EKSPOR . ($format === 'F1.09' ? 'kk-f1.09.rtf' : 'kk.rtf');
        if (! is_file($file)) {
            return null;
        }

        $nama   = '';
        $handle = fopen($file, 'rb');
        $buffer = stream_get_contents($handle);
        $i      = 0;

        foreach ($data['main'] as $ranggota) {
            $i++;
            $nama              .= $ranggota['nama'] . '\\line ';
            $no                .= $i . '\\line ';
            $hubungan          .= $ranggota['penduduk_hubungan'] . '\\line ';
            $nik               .= $ranggota['nik'] . '\\line ';
            $sex               .= ($ranggota['jenis_kelamin']) . '\\line ';
            $tempatlahir       .= $ranggota['tempatlahir'] . '\\line ';
            $tanggallahir      .= tgl_indo(tgl: $ranggota['tanggallahir'], format: 'd-m-Y') . '\\line ';
            $agama             .= ($ranggota['agama']) . '\\line ';
            $pendidikan        .= ($ranggota['pendidikan_kk'] ?? '') . '\\line ';
            $pekerjaan         .= ($ranggota['pekerjaan'] ?? '') . '\\line ';
            $status_kawin      .= ($ranggota['status_perkawinan'] ?? '') . '\\line ';
            $warganegara       .= ($ranggota['warganegara']) . '\\line ';
            $dokumen_pasport   .= $ranggota['dokumen_pasport'] . '\\line ';
            $dokumen_kitas     .= $ranggota['dokumen_kitas'] . '\\line ';
            $nama_ayah         .= $ranggota['nama_ayah'] . '\\line ';
            $nama_ibu          .= $ranggota['nama_ibu'] . '\\line ';
            $golongan_darah    .= ($ranggota['golongan_darah']) . '\\line ';
            $tanggalperkawinan .= isset($ranggota['tanggalperkawinan']) ? tgl_indo(tgl: $ranggota['tanggalperkawinan'], format: 'd-m-Y') . '\\line ' : '- \\line ';
            $tanggalperceraian .= isset($ranggota['tanggalperceraian']) ? tgl_indo(tgl: $ranggota['tanggalperceraian'], format: 'd-m-Y') . '\\line ' : '- \\line ';
        }

        $buffer = str_replace('[no]', "{$no}", $buffer);
        $buffer = str_replace('[nama]', "\\caps {$nama}", $buffer);
        $buffer = str_replace('[hubungan]', "{$hubungan}", $buffer);
        $buffer = str_replace('[nik]', "{$nik}", $buffer);
        $buffer = str_replace('[sex]', "{$sex}", $buffer);
        $buffer = str_replace('[agama]', "{$agama}", $buffer);
        $buffer = str_replace('[pendidikan]', "{$pendidikan}", $buffer);
        $buffer = str_replace('[pekerjaan]', "{$pekerjaan}", $buffer);
        $buffer = str_replace('[tempatlahir]', "\\caps {$tempatlahir}", $buffer);
        $buffer = str_replace('[tanggallahir]', "\\caps {$tanggallahir}", $buffer);
        $buffer = str_replace('[kawin]', "{$status_kawin}", $buffer);
        $buffer = str_replace('[warganegara]', "{$warganegara}", $buffer);
        $buffer = str_replace('[pasport]', "{$dokumen_pasport}", $buffer);
        $buffer = str_replace('[kitas]', "{$dokumen_kitas}", $buffer);
        $buffer = str_replace('[ayah]', "\\caps {$nama_ayah}", $buffer);
        $buffer = str_replace('[ibu]', "\\caps {$nama_ibu}", $buffer);
        $buffer = str_replace('[darah]', "\\caps {$golongan_darah}", $buffer);
        $buffer = str_replace('[tanggalperkawinan]', "\\caps {$tanggalperkawinan}", $buffer);
        $buffer = str_replace('[tanggalperceraian]', "\\caps {$tanggalperceraian}", $buffer);

        $h              = $data['desa'];
        $k              = $data['kepala_kk'];
        $sebutan_kepala = setting('sebutan_kepala_desa');
        $tertanda       = tgl_indo(date('Y m d'));
        $tertanda       = $h['nama_desa'] . ', ' . $tertanda;
        $buffer         = str_replace('[sebutan_kepala_desa]', "\\caps {$sebutan_kepala}", $buffer);
        $buffer         = str_replace('desa', "\\caps {$h['nama_desa']}", $buffer);
        $buffer         = str_replace('alamat_plus_dusun', "\\caps {$k['alamat_wilayah_kartu_keluarga']}", $buffer);
        $buffer         = str_replace('prop', "\\caps {$h['nama_propinsi']}", $buffer);
        $buffer         = str_replace('kab', "\\caps {$h['nama_kabupaten']}", $buffer);
        $buffer         = str_replace('kec', "\\caps {$h['nama_kecamatan']}", $buffer);
        $buffer         = str_replace('*camat', "\\caps {$h['nama_kepala_camat']}", $buffer);
        $buffer         = str_replace('*kades', "\\caps {$h['nama_kepala_desa']}", $buffer);
        $buffer         = str_replace('*rt', "{$k['keluarga']['wilayah']['rt']}", $buffer);
        $buffer         = str_replace('*rw', "{$k['keluarga']['wilayah']['rw']}", $buffer);
        $buffer         = str_replace('*kk', "\\caps {$k['nama']}", $buffer);
        $buffer         = str_replace('no_kk', "{$k['keluarga']['no_kk']}", $buffer);
        $buffer         = str_replace('pos', "{$h['kode_pos']}", $buffer);
        $buffer         = str_replace('*tertanda', "\\caps {$tertanda}", $buffer);
        $buffer         = str_replace('*nip_camat', "{$h['nip_kepala_camat']}", $buffer);

        $berkas_arsip = $path_arsip . "kk_{$k['keluarga']['no_kk']}.rtf";
        $handle       = fopen($berkas_arsip, 'w+b');
        fwrite($handle, $buffer);
        fclose($handle);

        return $berkas_arsip;
    }
}
