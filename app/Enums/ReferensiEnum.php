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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Enums;

defined('BASEPATH') || exit('No direct script access allowed');

class ReferensiEnum extends BaseEnum
{
    public const JENIS_KELAMIN                      = 'Jenis Kelamin';
    public const STATUS_HUBUNGAN_DALAM_KELUARGA     = 'Status Hubungan Dalam Keluarga';
    public const STATUS_HUBUNGAN_DALAM_RUMAH_TANGGA = 'Status Hubungan Dalam Rumah Tangga';
    public const AGAMA                              = 'Agama';
    public const PENDIDIKAN_DALAM_KK                = 'Pendidikan Dalam KK';
    public const PENDIDIKAN_SEDANG_DITEMPUH         = 'Pendidikan Sedang Ditempuh';
    public const PEKERJAAN                          = 'Pekerjaan';
    public const STATUS_PERKAWINAN                  = 'Status Perkawinan';
    public const WARGA_NEGARA                       = 'Warga Negara';
    public const GOLONGAN_DARAH                     = 'Golongan Darah';
    public const STATUS_PENDUDUK                    = 'Status Penduduk';
    public const STATUS_DASAR                       = 'Status Dasar';
    public const CACAT                              = 'Cacat';
    public const SAKIT_MENAHUN                      = 'Sakit Menahun';
    public const CARA_KB                            = 'Cara KB';
    public const ASURANSI                           = 'Asuransi';
    public const DUSUN                              = 'Dusun';

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::JENIS_KELAMIN                      => 'tweb_penduduk_sex',
            self::STATUS_HUBUNGAN_DALAM_KELUARGA     => 'tweb_penduduk_hubungan',
            self::STATUS_HUBUNGAN_DALAM_RUMAH_TANGGA => 'tweb_rtm_hubungan',
            self::AGAMA                              => 'tweb_penduduk_agama',
            self::PENDIDIKAN_DALAM_KK                => 'tweb_penduduk_pendidikan_kk',
            self::PENDIDIKAN_SEDANG_DITEMPUH         => 'tweb_penduduk_pendidikan',
            self::PEKERJAAN                          => 'tweb_penduduk_pekerjaan',
            self::STATUS_PERKAWINAN                  => 'tweb_penduduk_kawin',
            self::WARGA_NEGARA                       => 'tweb_penduduk_warganegara',
            self::GOLONGAN_DARAH                     => 'tweb_golongan_darah',
            self::STATUS_PENDUDUK                    => 'tweb_penduduk_status',
            self::STATUS_DASAR                       => 'tweb_status_dasar',
            self::CACAT                              => 'tweb_cacat',
            self::SAKIT_MENAHUN                      => 'tweb_sakit_menahun',
            self::CARA_KB                            => 'tweb_cara_kb',
            self::ASURANSI                           => 'tweb_penduduk_asuransi',
            self::DUSUN                              => 'tweb_wil_clusterdesa',
        ];
    }
}
