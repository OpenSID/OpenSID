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
use Illuminate\Database\Eloquent\Relations\HasOne;

defined('BASEPATH') || exit('No direct script access allowed');

class Garis extends BaseModel
{
    use ConfigId;

    public const LOCK   = 1;
    public const UNLOCK = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'garis';

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
        'ref_line',
        'foto',
        'desk',
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
        'foto_garis',
    ];

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
            $fotoSedang = LOKASI_FOTO_GARIS . 'sedang_' . $model->getOriginal($file);
            $fotoKecil  = LOKASI_FOTO_GARIS . 'kecil_' . $model->getOriginal($file);
            if (file_exists($fotoSedang)) {
                unlink($fotoSedang);
            }
            if (file_exists($fotoKecil)) {
                unlink($fotoKecil);
            }
        }
    }

    /**
     * Getter untuk foto kecil.
     */
    public function getFotoKecilAttribute(): ?string
    {
        $foto = LOKASI_FOTO_GARIS . 'kecil_' . $this->attributes['foto'];

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
        $foto = LOKASI_FOTO_GARIS . 'sedang_' . $this->attributes['foto'];

        if (file_exists(FCPATH . $foto)) {
            return $foto;
        }

        return null;
    }

    /**
     * Getter untuk foto sedang.
     */
    public function getFotoGarisAttribute(): ?string
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
        return $query->whereEnabled(1);
    }

    public function isLock(): bool
    {
        return $this->enabled == self::LOCK;
    }

    /**
     * Get the line associated with the Garis
     */
    public function line(): HasOne
    {
        return $this->hasOne(Line::class, 'id', 'ref_line');
    }

    public static function activeGarisMap()
    {
        return self::active()->with(['line' => static fn ($q) => $q->select(['id', 'nama', 'parrent', 'simbol', 'color', 'tebal', 'jenis'])->with(['parent' => static fn ($r) => $r->select(['id', 'nama', 'parrent', 'simbol', 'color', 'tebal', 'jenis'])]),
        ])->get()->map(function ($item) {
            $item->jenis       = $item->line->parent->nama ?? '';
            $item->kategori    = $item->line->nama ?? '';
            $item->simbol      = $item->line->simbol ?? '';
            $item->color       = $item->line->color ?? '';
            $item->tebal       = $item->line->tebal ?? '';
            $item->jenis_garis = $item->line->jenis ?? '';

            return $item;
        })->toArray();
    }
}
