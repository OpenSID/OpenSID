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

namespace App\Enums;

defined('BASEPATH') || exit('No direct script access allowed');

class PerubahanDataPiEnum extends BaseEnum
{
    public const NAMA               = 'nama';
    public const GOLONGAN_DARAH     = 'golongan_darah_id';
    public const ALAMAT             = 'alamat_sekarang';
    public const NAMA_AYAH          = 'nama_ayah';
    public const NAMA_IBU           = 'nama_ibu';
    public const JENIS_KELAMIN      = 'sex';
    public const TANGGAL_LAHIR      = 'tanggallahir';
    public const STATUS_PERKAWINAN  = 'status_kawin';
    public const TANGGAL_PERKAWINAN = 'tanggalperkawinan';
    public const KEWARGANEGARAAN    = 'warganegara_id';
    public const DOKUMEN_IMIGRASI   = 'dokumen_pasport';

    public static function all(): array
    {
        return [
            self::NAMA               => 'NAMA',
            self::GOLONGAN_DARAH     => 'GOLONGAN DARAH',
            self::ALAMAT             => 'ALAMAT',
            self::NAMA_AYAH          => 'NAMA ORANG TUA (AYAH)',
            self::NAMA_IBU           => 'NAMA ORANG TUA (IBU)',
            self::JENIS_KELAMIN      => 'JENIS KELAMIN',
            self::TANGGAL_LAHIR      => 'TANGGAL LAHIR',
            self::STATUS_PERKAWINAN  => 'STATUS PERKAWINAN',
            self::TANGGAL_PERKAWINAN => 'TANGGAL PERKAWINAN',
            self::KEWARGANEGARAAN    => 'KEWARGANEGARAAN',
            self::DOKUMEN_IMIGRASI   => 'DOKUMEN IMIGRASI',
        ];
    }
}
