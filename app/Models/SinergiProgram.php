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
use App\Traits\Uuid;
use Spatie\EloquentSortable\SortableTrait;

defined('BASEPATH') || exit('No direct script access allowed');

class SinergiProgram extends BaseModel
{
    use ConfigId;
    use Uuid;
    use SortableTrait;

    public const ACTIVE   = 1;
    public const INACTIVE = 0;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sinergi_program';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'judul',
        'gambar',
        'tautan',
        'urut',
        'status',
    ];

    protected $appends = [
        'gambar_url',
    ];

    /**
     * {@inheritDoc}
     */
    public $sortable = [
        'order_column_name'  => 'urut',
        'sort_when_creating' => true,
    ];

    public function scopeStatus($query, $status = null)
    {
        if ($status === null) {
            return $query;
        }

        return $query->where('status', $status);
    }

    public function getGambarUrlAttribute()
    {
        if (file_exists(FCPATH . LOKASI_SINERGI_PROGRAM . $this->gambar)) {
            return base_url(LOKASI_SINERGI_PROGRAM . $this->gambar);
        }

        return asset('images/404-image-not-found.jpg');
    }

    protected static function boot()
    {
        parent::boot();

        cache()->forget('sinergi_program');

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
            $gambar = LOKASI_SINERGI_PROGRAM . $model->getOriginal($file);
            if (file_exists($gambar)) {
                unlink($gambar);
            }
        }
    }
}
