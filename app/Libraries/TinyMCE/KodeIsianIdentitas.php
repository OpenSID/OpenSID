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
    public static function get(): array
    {
        return (new self())->kodeIsian();
    }

    public function kodeIsian(): array
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
                'isian' => 'nama_desa',
                'data'  => $config->nama_desa,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Kode Desa',
                'isian'         => 'kode_desa',
                'data'          => $config->kode_desa,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Kode POS',
                'isian'         => 'kode_pos',
                'data'          => $config->kode_pos,
            ],
            [
                'judul' => 'Sebutan Desa',
                'isian' => 'sebutan_desa',
                'data'  => $sebutan_desa,
            ],
            [
                'judul' => 'Sebutan Kepala Desa',
                'isian' => 'sebutan_kepala_desa',
                'data'  => $sebutan_kepala_desa,
            ],
            [
                'judul' => 'Nama Kepala Desa',
                'isian' => 'nama_kepala_desa',
                'data'  => $config->nama_kepala_desa,
            ],
            [
                'judul' => 'Sebutan NIP Desa',
                'isian' => 'sebutan_nip_desa',
                'data'  => $sebutan_nip_desa,
            ],
            [
                'judul' => 'NIP Kepala Desa',
                'isian' => 'nip_kepala_desa',
                'data'  => $config->nip_kepala_desa,
            ],
            [
                'judul' => 'Nama Kecamatan',
                'isian' => 'nama_kecamataN',
                'data'  => $config->nama_kecamatan,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Kode Kecamatan',
                'isian'         => 'kode_kecamataN',
                'data'          => $config->kode_kecamatan,
            ],
            [
                'judul' => 'Sebutan Kecamatan',
                'isian' => 'sebutan_kecamataN',
                'data'  => $sebutan_kecamatan,
            ],
            [
                'judul' => 'Sebutan Kecamatan (Singkat)',
                'isian' => 'sebutan_keC',
                'data'  => $sebutan_kec,
            ],
            [
                'judul' => 'Sebutan Camat',
                'isian' => 'sebutan_camaT',
                'data'  => $sebutan_camat,
            ],
            [
                'judul' => 'Nama Kepala Camat',
                'isian' => 'nama_kepala_camaT',
                'data'  => $config->nama_kepala_camat,
            ],
            [
                'judul' => 'NIP Kepala Camat',
                'isian' => 'nip_kepala_camaT',
                'data'  => $config->nip_kepala_camat,
            ],
            [
                'judul' => 'Nama Kabupaten',
                'isian' => 'nama_kabupateN',
                'data'  => $config->nama_kabupaten,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Kode Kabupaten',
                'isian'         => 'kode_kabupateN',
                'data'          => $config->kode_kabupaten,
            ],
            [
                'judul' => 'Sebutan Kabupaten',
                'isian' => 'sebutan_kabupateN',
                'data'  => $sebutan_kabupaten,
            ],
            [
                'judul' => 'Sebutan Kabupaten (Singkat)',
                'isian' => 'sebutan_kaB',
                'data'  => $sebutan_kab,
            ],
            [
                'judul' => 'Nama Provinsi',
                'isian' => 'nama_provinsI',
                'data'  => $config->nama_propinsi,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Kode Provinsi',
                'isian'         => 'kode_provinsI',
                'data'          => $config->kode_propinsi,
            ],
            [
                'judul' => 'Alamat Desa',
                'isian' => 'alamat_desa',
                'data'  => $alamat_desa,
            ],
            [
                'judul' => 'Alamat Surat Desa',
                'isian' => 'alamat_suraT',
                'data'  => $alamat_surat,
            ],
            [
                'judul' => 'Alamat Kantor Desa',
                'isian' => 'alamat_kantor',
                'data'  => $config->alamat_kantor,
            ],
            [
                'judul' => 'Email Desa',
                'isian' => 'email_desa',
                'data'  => $config->email_desa,
            ],
            [
                'case_sentence' => true,
                'judul'         => 'Telepon Desa',
                'isian'         => 'telepon_desa',
                'data'          => $config->telepon,
            ],
            [
                'judul' => 'Website Desa',
                'isian' => 'website_desa',
                'data'  => $config->website,
            ],
            [
                'judul' => 'Sebutan Dusun',
                'isian' => 'sebutan_dusun',
                'data'  => $sebutan_dusun,
            ],
        ];
    }
}
