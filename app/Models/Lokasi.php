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

use App\Enums\AktifEnum;
use App\Traits\ConfigId;
use App\Traits\StatusTrait;
use Illuminate\Database\Eloquent\Relations\HasOne;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property string $desk
 * @property string $nama
 * @property int $enabled
 * @property string|null $lat
 * @property string|null $lng
 * @property int|null $ref_point
 * @property string|null $foto
 * @property int|null $id_cluster
 * @property-read string|null $foto_kecil
 * @property-read string|null $foto_lokasi
 * @property-read string|null $foto_sedang
 * @property-read string $status_label
 * @property-read string|null $url_foto
 * @property-read \App\Models\Point|null $point
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi status(?int $status = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereDesk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereIdCluster($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereRefPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Lokasi extends BaseModel
{
    use ConfigId;
    use StatusTrait;

    public $timestamps      = false;
    public $statusColumName = 'enabled';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lokasi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'desk',
        'nama',
        'enabled',
        'lat',
        'lng',
        'ref_point',
        'foto',
        'id_cluster',
    ];

    /**
     * The appends with the model.
     *
     * @var array
     */
    protected $appends = [
        'foto_kecil',
        'foto_sedang',
        'foto_lokasi',
        'url_foto',
    ];

    public static function activeLocationMap()
    {
        return self::active()->with(['point' => static fn ($q) => $q->select(['id', 'nama', 'parrent', 'simbol'])->with(['parent' => static fn ($r) => $r->select(['id', 'nama', 'parrent', 'simbol'])]),
        ])->get()->map(function ($item) {
            $item->jenis    = $item->point->parent->nama ?? '';
            $item->kategori = $item->point->nama ?? '';
            $item->simbol   = $item->point->simbol ?? '';
            unset($item->point);

            return $item;
        })->toArray();
    }

    public static function boot(): void
    {
        parent::boot();

        static::updating(static function ($model): void {
            static::deleteFile($model, 'foto');
        });

        static::deleting(static function ($model): void {
            static::deleteFile($model, 'foto', true);
        });
    }

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $original = LOKASI_FOTO_LOKASI . $model->getOriginal($file);
            $kecil    = LOKASI_FOTO_LOKASI . 'kecil_' . $model->getOriginal($file);
            $sedang   = LOKASI_FOTO_LOKASI . 'sedang_' . $model->getOriginal($file);

            if (file_exists($original)) {
                unlink($original);
            }

            if (file_exists($kecil)) {
                unlink($kecil);
            }
            if (file_exists($sedang)) {
                unlink($sedang);
            }
        }
    }

    /**
     * Getter untuk foto kecil.
     */
    public function getFotoKecilAttribute(): ?string
    {
        $foto = LOKASI_FOTO_LOKASI . 'kecil_' . $this->attributes['foto'];

        if ($this->attributes['foto'] !== null && file_exists(FCPATH . $foto)) {
            return $foto;
        }

        return null;
    }

    /**
     * Getter untuk foto sedang.
     */
    public function getFotoSedangAttribute(): ?string
    {
        $foto = LOKASI_FOTO_LOKASI . 'sedang_' . $this->attributes['foto'];

        if ($this->attributes['foto'] !== null && file_exists(FCPATH . $foto)) {
            return $foto;
        }

        return null;
    }

    public function getFotoLokasiAttribute(): ?string
    {
        if ($kecil = $this->getFotoKecilAttribute()) {
            return base_url($kecil);
        }

        if ($sedang = $this->getFotoSedangAttribute()) {
            return base_url($sedang);
        }

        $foto = LOKASI_FOTO_LOKASI . $this->attributes['foto'];
        if (! empty($this->attributes['foto']) && file_exists(FCPATH . $foto)) {
            return base_url($foto);
        }

        return base_url('assets/images/404-image-not-found.jpg');
    }

    public function getUrlFotoAttribute(): ?string
    {
        $foto = $this->getFotoLokasiAttribute();

        if ($foto) {
            return $foto;
        }

        return base_url('assets/images/404-image-not-found.jpg');
    }

    /**
     * Get the point associated with the Lokasi
     */
    public function point(): HasOne
    {
        return $this->hasOne(Point::class, 'id', 'ref_point');
    }

    public function isLock(): bool
    {
        return $this->enabled == AktifEnum::TIDAK_AKTIF;
    }

    protected function scopeActive($query)
    {
        return $query->whereEnabled(AktifEnum::AKTIF);
    }
}
