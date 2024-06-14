<?php

namespace App\Models;

use App\Traits\Uuid;
use App\Traits\ConfigId;
use Spatie\EloquentSortable\SortableTrait;

defined('BASEPATH') || exit('No direct script access allowed');

class SinergiProgram extends BaseModel
{
    use ConfigId;
    use Uuid;
    use SortableTrait;

    public const ACTIVE = 1;
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
