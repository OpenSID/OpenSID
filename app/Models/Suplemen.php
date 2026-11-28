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

use App\Traits\ConfigIdNull;
use Cviebrock\EloquentSluggable\Sluggable;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int|null $config_id
 * @property string|null $nama
 * @property string|null $slug
 * @property int|null $sasaran
 * @property string|null $keterangan
 * @property int $status 1 = Aktif, 0 = Nonaktif
 * @property string $sumber
 * @property array<array-key, mixed>|null $form_isian Menyimpan data formulir dinamis tambahan sebagai JSON atau teks
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SuplemenTerdata> $terdata
 * @property-read int|null $terdata_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suplemen filter($sasaran)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suplemen findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suplemen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suplemen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suplemen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suplemen whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suplemen whereFormIsian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suplemen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suplemen whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suplemen whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suplemen whereSasaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suplemen whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suplemen whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suplemen whereSumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suplemen withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suplemen withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Suplemen extends BaseModel
{
    use ConfigIdNull;
    use Sluggable;

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suplemen';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'slug',
        'sasaran',
        'keterangan',
    ];

    protected $casts = [
        'form_isian' => 'array',
    ];

    /**
     * Relasi terdata yang otomatis filter sesuai sasaran.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terdata()
    {
        return $this->hasMany(SuplemenTerdata::class, 'id_suplemen')
            ->where(static function ($query): void {
                $query->where(static function ($query): void {
                    $query->whereNotNull('penduduk_id');
                })->orWhere(static function ($query): void {
                    $query->whereNotNull('keluarga_id');
                });
            });
    }

    public function scopeFilter($query, $sasaran)
    {
        if (! empty($sasaran)) {
            $query->where('sasaran', $sasaran);
        }

        return $query;
    }

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nama',
            ],
        ];
    }
}
