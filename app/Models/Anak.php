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

namespace App\Models;

use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

class Anak extends BaseModel
{
    use ConfigId;

    /**
     * Static data status gizi anak
     *
     * @var array
     */
    public const STATUS_GIZI_ANAK = [
        [
            'id'     => 1,
            'simbol' => 'N',
            'nama'   => 'Sehat / Normal (N)',
        ],
        [
            'id'     => 2,
            'simbol' => 'GK',
            'nama'   => 'Gizi Kurang (GK)',
        ],
        [
            'id'     => 3,
            'simbol' => 'GB',
            'nama'   => 'Gizi Buruk (GB)',
        ],
        [
            'id'     => 4,
            'simbol' => 'S',
            'nama'   => 'Stunting (S)',
        ],
    ];

    /**
     * Static data status tikar anak
     *
     * @var array
     */
    public const STATUS_TIKAR_ANAK = [
        [
            'id'     => 1,
            'simbol' => 'TD',
            'nama'   => 'Tidak Diukur (TD)',
        ],
        [
            'id'     => 2,
            'simbol' => 'M',
            'nama'   => 'Merah (M)',
        ],
        [
            'id'     => 3,
            'simbol' => 'K',
            'nama'   => 'Kuning (K)',
        ],
        [
            'id'     => 4,
            'simbol' => 'H',
            'nama'   => 'Hijau (H)',
        ],
    ];

    /**
     * Static data status imunisasi campak
     *
     * @var array
     */
    public const STATUS_IMUNISASI_CAMPAK = [
        1 => 'Belum',
        2 => 'Sudah',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bulanan_anak';

    /**
     * The table update parameter.
     *
     * @var string
     */
    public $primaryKey = 'id_bulanan_anak';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    public function kia()
    {
        return $this->belongsTo(KIA::class, 'kia_id');
    }

    public function scopeFilter($query, array $filters)
    {
        if (! empty($filters['bulan'])) {
            $query->whereMonth('bulanan_anak.created_at', $filters['bulan']);
        }

        if (! empty($filters['tahun'])) {
            $query->whereYear('bulanan_anak.created_at', $filters['tahun']);
        }

        if (! empty($filters['posyandu'])) {
            $query->where('posyandu_id', $filters['posyandu']);
        }

        return $query;
    }
}
