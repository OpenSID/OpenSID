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

use App\Enums\StatusEnum;
use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EloquentSortable\SortableTrait;

defined('BASEPATH') || exit('No direct script access allowed');

class Galery extends BaseModel
{
    use ConfigId;
    use SortableTrait;

    public const PARRENT = 0;

    /**
     * {@inheritDoc}
     */
    protected $table = 'gambar_gallery';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $appends = ['url_gambar'];

    /**
     * {@inheritDoc}
     */

    /**
     * {@inheritDoc}
     */
    public $sortable = [
        'order_column_name'  => 'urut',
        'sort_when_creating' => false,
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model): void {
            $urutTerakhir = Galery::select(['urut'])->whereParrent($model->parrent)->orderBy('urut', 'desc')->first();
            $model->urut  = $urutTerakhir ? (int) ($urutTerakhir->urut) + 1 : 1;
        });

        static::updating(static function ($model): void {
            static::deleteFile($model, 'gambar');
        });

        static::deleting(static function ($model): void {
            static::deleteFile($model, 'gambar', true);
        });
    }

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $prefix = ['kecil_', 'sedang_'];

            foreach ($prefix as $pref) {
                $gambar = LOKASI_GALERI . $pref . $model->getOriginal($file);
                if (file_exists($gambar)) {
                    unlink($gambar);
                }
            }
        }
    }

    protected function scopeChild($query, int $parent)
    {
        return $query->whereParrent($parent);
    }

    protected function scopeActive($query)
    {
        return $query->whereEnabled(StatusEnum::YA);
    }

    public function isActive(): bool
    {
        return $this->enabled == StatusEnum::YA;
    }

    public function isSlider(): bool
    {
        return $this->slider == StatusEnum::YA;
    }

    /**
     * Get the parent that owns the Polygon
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Galery::class, 'parrent', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Galery::class, 'parrent', 'id');
    }

    public function getUrlGambarAttribute(): void
    {
        // try {
        //     return Storage::disk('ftp')->exists("desa/upload/galeri/kecil_{$this->gambar}")
        //         ? Storage::disk('ftp')->url("desa/upload/galeri/kecil_{$this->gambar}")
        //         : null;
        // } catch (Exception $e) {
        //     Log::error($e);
        // }
    }
}
