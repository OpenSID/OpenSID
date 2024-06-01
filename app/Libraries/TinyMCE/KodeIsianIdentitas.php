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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries\TinyMCE;

class KodeIsianIdentitas
{
    public function __construct()
    {
    }

    public static function get()
    {
        return (new self())->kodeIsian();
    }

    public function kodeIsian()
    {
        $config              = identitas();
        $sebutan_dusun       = setting('sebutan_dusun');
        $sebutan_desa        = setting('sebutan_desa');
        $sebutan_kecamatan   = setting('sebutan_kecamatan');
        $sebutan_kec         = setting('sebutan_kecamatan_singkat');
        $sebutan_kabupaten   = setting('sebutan_kabupaten');
        $sebutan_kab         = setting('sebutan_kabupaten_singkat');
        $sebutan_kepala_desa = setting('sebutan_kepala_desa');
        $sebutan_camat       = setting('sebutan_camat');

        if (! empty($config->email_desa)) {
            $alamat_desa  = "{$config->alamat_kantor} Email: {$config->email_desa} Kode Pos: {$config->kode_pos}";
            $alamat_surat = "{$config->alamat_kantor} Telp. {$config->telepon} Kode Pos: {$config->kode_pos} <br> Website: {$config->website} Email: {$config->email_desa}";
        } else {
            $alamat_desa  = "{$config->alamat_kantor} Kode Pos: {$config->kode_pos}";
            $alamat_surat = "{$config->alamat_kantor} Telp. {$config->telepon} Kode Pos: {$config->kode_pos}";
        }

        if (null === $config->pamong()->pamong_nip && (! empty($config->pamong()->pamong_niap))) {
            $sebutan_nip_desa = setting('sebutan_nip_desa');
        } else {
            $sebutan_nip_desa = 'NIP';
        }

        return [
            [
                'judul' => 'Nama Desa',
                'isian' => 'Nama_desA',
                'data'  => $config->nama_desa,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Kode Desa',
                'isian'         => 'Kode_desA',
                'data'          => $config->kode_desa,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Kode POS',
                'isian'         => 'Kode_poS',
                'data'          => $config->kode_pos,
            ],
            [
                'judul' => 'Sebutan Desa',
                'isian' => 'Sebutan_desA',
                'data'  => $sebutan_desa,
            ],
            [
                'judul' => 'Sebutan Kepala Desa',
                'isian' => 'Sebutan_kepala_desA',
                'data'  => $sebutan_kepala_desa,
            ],
            [
                'judul' => 'Nama Kepala Desa',
                'isian' => 'Nama_kepala_desA',
                'data'  => $config->pamong_nama,
            ],
            [
                'judul' => 'Sebutan NIP Desa',
                'isian' => 'Sebutan_nip_desA',
                'data'  => $sebutan_nip_desa,
            ],
            [
                'judul' => 'NIP Kepala Desa',
                'isian' => 'Nip_kepala_desA',
                'data'  => $config->pamong_nip,
            ],
            [
                'judul' => 'Nama Kecamatan',
                'isian' => 'Nama_kecamataN',
                'data'  => $config->nama_kecamatan,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Kode Kecamatan',
                'isian'         => 'Kode_kecamataN',
                'data'          => $config->kode_kecamatan,
            ],
            [
                'judul' => 'Sebutan Kecamatan',
                'isian' => 'Sebutan_kecamataN',
                'data'  => $sebutan_kecamatan,
            ],
            [
                'judul' => 'Sebutan Kecamatan (Singkat)',
                'isian' => 'Sebutan_keC',
                'data'  => $sebutan_kec,
            ],
            [
                'judul' => 'Sebutan Camat',
                'isian' => 'Sebutan_camaT',
                'data'  => $sebutan_camat,
            ],
            [
                'judul' => 'Nama Kepala Camat',
                'isian' => 'Nama_kepala_camaT',
                'data'  => $config->nama_kepala_camat,
            ],
            [
                'judul' => 'NIP Kepala Camat',
                'isian' => 'Nip_kepala_camaT',
                'data'  => $config->nip_kepala_camat,
            ],
            [
                'judul' => 'Nama Kabupaten',
                'isian' => 'Nama_kabupateN',
                'data'  => $config->nama_kabupaten,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Kode Kabupaten',
                'isian'         => 'Kode_kabupateN',
                'data'          => $config->kode_kabupaten,
            ],
            [
                'judul' => 'Sebutan Kabupaten',
                'isian' => 'Sebutan_kabupateN',
                'data'  => $sebutan_kabupaten,
            ],
            [
                'judul' => 'Sebutan Kabupaten (Singkat)',
                'isian' => 'Sebutan_kaB',
                'data'  => $sebutan_kab,
            ],
            [
                'judul' => 'Nama Provinsi',
                'isian' => 'Nama_provinsI',
                'data'  => $config->nama_propinsi,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Kode Provinsi',
                'isian'         => 'Kode_provinsI',
                'data'          => $config->kode_propinsi,
            ],
            [
                'judul' => 'Alamat Desa',
                'isian' => 'Alamat_desA',
                'data'  => $alamat_desa,
            ],
            [
                'judul' => 'Alamat Surat Desa',
                'isian' => 'Alamat_suraT',
                'data'  => $alamat_surat,
            ],
            [
                'judul' => 'Alamat Kantor Desa',
                'isian' => 'Alamat_kantor',
                'data'  => $config->alamat_kantor,
            ],
            [
                'judul' => 'Email Desa',
                'isian' => 'Email_desA',
                'data'  => $config->email_desa,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Telepon Desa',
                'isian'         => 'Telepon_desA',
                'data'          => $config->telepon,
            ],
            [
                'judul' => 'Website Desa',
                'isian' => 'Website_desA',
                'data'  => $config->website,
            ],
            [
                'judul' => 'Sebutan Dusun',
                'isian' => 'Sebutan_dusuN',
                'data'  => $sebutan_dusun,
            ],
        ];
    }
}
