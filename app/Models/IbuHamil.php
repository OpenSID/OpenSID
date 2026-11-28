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

use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id_ibu_hamil
 * @property int $config_id
 * @property int|null $posyandu_id
 * @property int|null $kia_id
 * @property int|null $status_kehamilan
 * @property int|null $usia_kehamilan
 * @property string|null $tanggal_melahirkan
 * @property int $pemeriksaan_kehamilan
 * @property int $konsumsi_pil_fe
 * @property int $butir_pil_fe
 * @property int $pemeriksaan_nifas
 * @property int $konseling_gizi
 * @property int $kunjungan_rumah
 * @property int $akses_air_bersih
 * @property int $kepemilikan_jamban
 * @property int $jaminan_kesehatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @property-read \App\Models\KIA|null $kia
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereAksesAirBersih($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereButirPilFe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereIdIbuHamil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereJaminanKesehatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereKepemilikanJamban($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereKiaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereKonselingGizi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereKonsumsiPilFe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereKunjunganRumah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil wherePemeriksaanKehamilan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil wherePemeriksaanNifas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil wherePosyanduId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereStatusKehamilan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereTanggalMelahirkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil whereUsiaKehamilan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IbuHamil withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class IbuHamil extends BaseModel
{
    use ConfigId;

    /**
     * Static data status kehamilan ibu
     *
     * @var array
     */
    public const STATUS_KEHAMILAN_IBU = [
        [
            'id'     => 1,
            'simbol' => 'N',
            'nama'   => 'Normal (N)',
        ],
        [
            'id'     => 2,
            'simbol' => 'Risti',
            'nama'   => 'Risiko Tinggi (Risti)',
        ],
        [
            'id'     => 3,
            'simbol' => 'KEK',
            'nama'   => 'Kekurangan Energi Kronis (KEK)',
        ],
    ];

    /**
     * The table update parameter.
     *
     * @var string
     */
    public $primaryKey = 'id_ibu_hamil';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ibu_hamil';

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
            $query->whereMonth('ibu_hamil.created_at', $filters['bulan']);
        }

        if (! empty($filters['tahun'])) {
            $query->whereYear('ibu_hamil.created_at', $filters['tahun']);
        }

        if (! empty($filters['posyandu'])) {
            $query->where('posyandu_id', $filters['posyandu']);
        }

        return $query;
    }
}
