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

namespace App\Models;

use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

defined('BASEPATH') || exit('No direct script access allowed');

class Area extends BaseModel
{
    use ConfigId;

    public const LOCK   = 1;
    public const UNLOCK = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'area';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'path',
        'enabled',
        'ref_polygon',
        'foto',
        'id_cluster',
        'desk',
    ];

    /**
     * The appends with the model.
     *
     * @var array
     */
    protected $appends = [
        'foto_kecil',
        'foto_sedang',
        'foto_area',
    ];

    /**
     * Getter untuk foto kecil.
     */
    public function getFotoKecilAttribute(): ?string
    {
        $foto = LOKASI_FOTO_AREA . 'kecil_' . $this->attributes['foto'];

        if (file_exists(FCPATH . $foto)) {
            return $foto;
        }

        return null;
    }

    /**
     * Getter untuk foto sedang.
     */
    public function getFotoSedangAttribute(): ?string
    {
        $foto = LOKASI_FOTO_AREA . 'sedang_' . $this->attributes['foto'];

        if (file_exists(FCPATH . $foto)) {
            return $foto;
        }

        return null;
    }

    /**
     * Getter untuk foto sedang.
     */
    public function getFotoAreaAttribute(): ?string
    {
        if ($kecil = $this->getFotoKecilAttribute()) {
            return to_base64($kecil);
        }

        if ($sedang = $this->getFotoSedangAttribute()) {
            return to_base64($sedang);
        }

        return null;
    }

    protected function scopeActive($query)
    {
        return $query->whereEnabled(self::UNLOCK);
    }

    /**
     * Get the polygon that owns the Area
     */
    public function polygon(): BelongsTo
    {
        return $this->belongsTo(Polygon::class, 'ref_polygon', 'id');
    }

    public function isLock(): bool
    {
        return $this->enabled == self::LOCK;
    }

    public static function activeAreaMap()
    {
        return self::active()->with(['polygon' => static fn ($q) => $q->select(['id', 'nama', 'parrent', 'simbol', 'color'])->with(['parent' => static fn ($r) => $r->select(['id', 'nama', 'parrent', 'simbol', 'color'])]),
        ])->get()->map(function ($item) {
            $item->jenis    = $item->polygon->parent->nama ?? '';
            $item->kategori = $item->polygon->nama ?? '';
            $item->simbol   = $item->polygon->simbol ?? '';
            $item->color    = $item->polygon->color ?? '';

            return $item;
        })->toArray();
    }

    public static function areaMap()
    {
        return self::with(['polygon' => static fn ($q) => $q->select(['id', 'nama', 'parrent', 'simbol', 'color'])->with(['parent' => static fn ($r) => $r->select(['id', 'nama', 'parrent', 'simbol', 'color'])]),
        ])->get()->map(function ($item) {
            $item->jenis    = $item->polygon->parent->nama ?? '';
            $item->kategori = $item->polygon->nama ?? '';
            $item->simbol   = $item->polygon->simbol ?? '';
            $item->color    = $item->polygon->color ?? '';

            return $item;
        })->toArray();
    }

    /**
     * The "booted" method of the model.
     */
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
            $fotoSedang = LOKASI_FOTO_AREA . 'sedang_' . $model->getOriginal($file);
            $fotoKecil  = LOKASI_FOTO_AREA . 'kecil_' . $model->getOriginal($file);
            if (file_exists($fotoSedang)) {
                unlink($fotoSedang);
            }
            if (file_exists($fotoKecil)) {
                unlink($fotoKecil);
            }
        }
    }
}
