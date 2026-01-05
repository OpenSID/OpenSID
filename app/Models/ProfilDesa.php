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

namespace App\Models;

use App\Traits\Author;
use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

class ProfilDesa extends BaseModel
{
    use ConfigId;
    use Author;

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profil_desa';

    protected $guarded = ['id'];

    public static function simpanData(array $data, $config_id): void
    {
        foreach ($data as $key => $value) {
            static::updateOrCreate(
                ['config_id' => $config_id, 'key' => $key],
                [
                    'value'     => $value,
                    'config_id' => $config_id,
                    'kategori'  => static::kategoriProfil($key),
                    'judul'     => static::judulProfil($key),
                ]
            );
        }
    }

    protected static function kategoriProfil($key): string
    {
        $map = [
            'jenis_tanah'        => 'ekologi',
            'topografi'          => 'ekologi',
            'sumber_daya_alam'   => 'ekologi',
            'flora_fauna'        => 'ekologi',
            'rawan_bencana'      => 'ekologi',
            'kearifan_lokal'     => 'ekologi',
            'jenis_jaringan'     => 'internet',
            'provider_internet'  => 'internet',
            'cakupan_wilayah'    => 'internet',
            'kecepatan_internet' => 'internet',
            'akses_publik'       => 'internet',
            'status_desa'        => 'adat',
            'lembaga_adat'       => 'adat',
            'struktur_adat'      => 'adat',
            'wilayah_adat'       => 'adat',
            'peraturan_adat'     => 'adat',
        ];

        return $map[$key] ?? 'lainnya';
    }

    protected static function judulProfil($key): string
    {
        return ucwords(str_replace('_', ' ', $key));
    }
}
