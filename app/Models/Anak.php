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
 * @property int $id_bulanan_anak
 * @property int $config_id
 * @property int|null $posyandu_id
 * @property int|null $kia_id
 * @property int $status_gizi
 * @property int $umur_bulan
 * @property int $status_tikar
 * @property int $pemberian_imunisasi_dasar
 * @property int|null $pemberian_imunisasi_campak
 * @property int $pengukuran_berat_badan
 * @property float|null $berat_badan
 * @property int $pengukuran_tinggi_badan
 * @property float|null $tinggi_badan
 * @property int $konseling_gizi_ayah
 * @property int $konseling_gizi_ibu
 * @property int $kunjungan_rumah
 * @property int $air_bersih
 * @property int $kepemilikan_jamban
 * @property int $akta_lahir
 * @property int $jaminan_kesehatan
 * @property int $pengasuhan_paud
 * @property string|null $keterangan
 * @property string|null $tanggal_periksa
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @property-read \App\Models\KIA|null $kia
 * @property-read \App\Models\Posyandu|null $posyandu
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak normal()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak resikoStunting()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak stunting()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak stuntingPendek()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereAirBersih($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereAktaLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereBeratBadan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereIdBulananAnak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereJaminanKesehatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereKepemilikanJamban($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereKiaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereKonselingGiziAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereKonselingGiziIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereKunjunganRumah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak wherePemberianImunisasiCampak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak wherePemberianImunisasiDasar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak wherePengasuhanPaud($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak wherePengukuranBeratBadan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak wherePengukuranTinggiBadan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak wherePosyanduId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereStatusGizi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereStatusTikar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereTanggalPeriksa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereTinggiBadan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereUmurBulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Anak withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Anak extends BaseModel
{
    use ConfigId;

    public const NORMAL           = 1;
    public const GIZI_KURANG      = 2;
    public const GIZI_BURUK       = 3;
    public const STUNTING         = 4;
    public const TB_SANGAT_PENDEK = 2;
    public const TB_PENDEK        = 3;

    /**
     * Static data status gizi anak
     *
     * @var array
     */
    public const STATUS_GIZI_ANAK = [
        [
            'id'     => self::NORMAL,
            'simbol' => 'N',
            'nama'   => 'Sehat / Normal (N)',
        ],
        [
            'id'     => self::GIZI_KURANG,
            'simbol' => 'GK',
            'nama'   => 'Gizi Kurang (GK)',
        ],
        [
            'id'     => self::GIZI_BURUK,
            'simbol' => 'GB',
            'nama'   => 'Gizi Buruk (GB)',
        ],
        [
            'id'     => self::STUNTING,
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
     * The table update parameter.
     *
     * @var string
     */
    public $primaryKey = 'id_bulanan_anak';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bulanan_anak';

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

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class, 'posyandu_id');
    }

    public function scopeFilter($query, array $filters)
    {
        if (! empty($filters['bulan'])) {
            $query->whereMonth('bulanan_anak.tanggal_periksa', $filters['bulan']);
        }

        if (! empty($filters['tahun'])) {
            $query->whereYear('bulanan_anak.tanggal_periksa', $filters['tahun']);
        }

        if (! empty($filters['posyandu'])) {
            $query->where('posyandu_id', $filters['posyandu']);
        }

        return $query;
    }

    public function isNormal()
    {
        return $this->attributes['status_gizi'] == self::NORMAL;
    }

    public function isResikoStunting()
    {
        return in_array($this->attributes['status_gizi'], [self::GIZI_BURUK, self::GIZI_KURANG]);
    }

    public function isStunting()
    {
        return $this->attributes['status_gizi'] == self::STUNTING;
    }

    protected function scopeNormal($query)
    {
        return $query->where('status_gizi', self::NORMAL);
    }

    protected function scopeResikoStunting($query)
    {
        return $query->whereIn('status_gizi', [self::GIZI_BURUK, self::GIZI_KURANG]);
    }

    protected function scopeStunting($query)
    {
        return $query->where('status_gizi', self::STUNTING);
    }

    protected function scopeStuntingPendek($query)
    {
        return $query->stunting()->whereIn('status_tikar', [self::TB_PENDEK, self::TB_SANGAT_PENDEK]);
    }
}
